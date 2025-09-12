<?php

/**
 * @property CI_Config $config
 * @property CI_Input $input
 * @property CI_Model $model
 * @property CI_Model $api
 * @property CI_Encryption $encryption
 * @property CI_Session $session
 * @property CI_Form_validation $form_validation
 */

class MY_Controller extends CI_Controller
{
    private $jwt_key;

    public function __construct()
    {
        parent::__construct();
        $this->jwt_key = $this->config->item('jwt_key'); // inisialisasi di sini
        $cookie_domain = $this->config->item('cookie_domain');
        $sso_server = $this->config->item('sso_server');

        $this->load->model('ModelPresensi', 'model');

        if (!$this->session->userdata('logged_in')) {
            $token = $this->input->cookie('sso_token');
            if ($token) {
                $this->cek_token($token);
            } else {
                $this->session->sess_destroy();
                $redirect_url = current_url();
                setcookie('redirect_to', urlencode($redirect_url), time() + 300, "/", $cookie_domain);
                redirect($sso_server . 'login');
            }
        }

        # Cek kegiatan hari ini
        $queryKegiatan = $this->model->get_seleksi('ref_kegiatan', 'tanggal', date('Y-m-d'));
        if ($queryKegiatan->num_rows() > 0) {
            $this->session->set_userdata('agenda_lainnya', '1');
        }

        # Cek Data Aplikasi Ini
        $this->model->cek_aplikasi($this->config->item('id_app'));

        # Periksa apakah user login sebagai plh/plt atau bukan
        $token_presensi = "plh/plt";
        if (!$this->session->userdata('status_plh')) {
            if (!$this->session->userdata('status_plt')) {
                $params = [
                    'tabel' => 'v_users',
                    'kolom_seleksi' => 'userid',
                    'seleksi' => $this->session->userdata("userid")
                ];

                $result = $this->apihelper->get('apiclient/get_data_seleksi', $params);

                if ($result['status_code'] === 200 && $result['response']['status'] === 'success') {
                    $user_data = $result['response']['data'][0];
                    $token_presensi = $user_data['token'];
                    $this->session->set_userdata('jabatan', $user_data['jabatan']);
                }
            }
        }

        #Cek peran pegawai
        if (in_array($this->session->userdata('role'), ['super', 'validator_kepeg_satker', 'admin_satker'])) {
            $this->session->set_userdata('peran', 'admin');
        } else {
            $query = $this->model->get_seleksi2('role_presensi', 'userid', $this->session->userdata('userid'), 'hapus', '0');
            if ($query->num_rows() > 0) {
                $this->session->set_userdata('peran', $query->row()->role);
            } else {
                $this->session->set_userdata('peran', '');
            }
        }

        $this->session->set_userdata('token_now', $token_presensi);
        $this->session->set_userdata('ip_satker', $this->get_config_value('34'));
        $this->session->set_userdata('mulai_apel_senin', $this->get_config_value('30'));
        $this->session->set_userdata('selesai_apel_senin', $this->get_config_value('31'));
        $this->session->set_userdata('mulai_apel_jumat', $this->get_config_value('32'));
        $this->session->set_userdata('selesai_apel_jumat', $this->get_config_value('33'));
        $this->session->set_userdata('mulai_istirahat', $this->get_config_value('35'));
        $this->session->set_userdata('selesai_istirahat', $this->get_config_value('36'));
        $this->session->set_userdata('mulai_istirahat_jumat', $this->get_config_value('37'));
        $this->session->set_userdata('selesai_istirahat_jumat', $this->get_config_value('38'));
        $this->session->set_userdata('logged_in', TRUE);
    }

    private function cek_token($token)
    {
        $cookie_domain = $this->session->userdata('sso_server');
        $sso_api = $cookie_domain . "api/cek_token?sso_token={$token}";
        $response = file_get_contents($sso_api);
        $data = json_decode($response, true);

        if ($data['status'] == 'success') {
            $this->session->set_userdata([
                'logged_in' => TRUE,
                'userid' => $data['user']['userid'],
                'status_plh' => $data['user']['status_plh'],
                'status_plt' => $data['user']['status_plt']
            ]);
            redirect(current_url());
        } else {
            redirect($cookie_domain . 'login');
        }
    }

    private function get_config_value($seleksi)
    {
        $params = [
            'tabel' => 'sys_config',
            'kolom_seleksi' => 'id',
            'seleksi' => $seleksi
        ];

        $result = $this->apihelper->get('apiclient/get_data_seleksi', $params);

        if ($result['status_code'] === 200 && $result['response']['status'] === 'success') {
            $user_data = $result['response']['data'][0];
            return $user_data['value'];
        }
    }
}