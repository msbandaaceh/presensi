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
        $this->session->set_userdata('sso_db', $this->config->item('sso_db'));

        $this->load->model('ModelPresensi', 'model');
        if ($this->session->userdata('status_plh') == '1' || $this->session->userdata('status_plt') == '1') {
            $token = null;
            $this->session->set_userdata("token_now", $token);
        }

        if (!$this->session->userdata('logged_in')) {
            $token = $this->input->cookie('sso_token');
            if ($token) {
                $this->cek_token($token);
            } else {
                $redirect_url = current_url();
                setcookie('redirect_to', urlencode($redirect_url), time() + 300, "/", $cookie_domain);
                redirect($cookie_domain . 'login');
            }
        }

        # Cek data rapat hari ini
        #$queryRapat = $this->model->get_seleksi('register_rapat', 'tanggal', date('Y-m-d'));
        #if ($queryRapat->num_rows() > 0) {
        #    $this->session->set_userdata('agenda_rapat', '1');
        #}

        # Cek kegiatan hari ini
        $queryKegiatan = $this->model->get_seleksi('ref_kegiatan', 'tanggal', date('Y-m-d'));
        if ($queryKegiatan->num_rows() > 0) {
            $this->session->set_userdata('agenda_lainnya', '1');
        }

        # Cek Data Aplikasi Ini
        $params = [
            'tabel' => 'ref_client_app',
            'kolom_seleksi' => 'id',
            'seleksi' => '1'
        ];

        $result = $this->apihelper->get('apiclient/get_data_seleksi', $params);

        if ($result['status_code'] === 200 && $result['response']['status'] === 'success') {
            $user_data = $result['response']['data'][0];
            $this->session->set_userdata('nama_client_app', $user_data['nama_app']);
            $this->session->set_userdata('deskripsi_client_app', $user_data['deskripsi']);
        }

        # Periksa apakah user login sebagai plh/plt atau bukan
        $token = "plh/plt";
        if ($this->session->userdata('status_plh') == '0') {
            if ($this->session->userdata('status_plt') == '0') {
                $params = [
                    'tabel' => 'v_users',
                    'kolom_seleksi' => 'userid',
                    'seleksi' => $this->session->userdata("userid")
                ];

                $result = $this->apihelper->get('apiclient/get_data_seleksi', $params);

                if ($result['status_code'] === 200 && $result['response']['status'] === 'success') {
                    $user_data = $result['response']['data'][0];
                    $token = $user_data['token'];
                }
            }
        }

        #Cek peran pegawai
        if ($this->session->userdata('role') == 'validator_kepeg_satker') {
            $this->session->set_userdata('peran', 'validator');
        }
        $query = $this->model->get_seleksi2('role_presensi', 'userid', $this->session->userdata('userid'), 'hapus', '0');
        if ($query->num_rows() > 0) {
            $this->session->set_userdata('peran', $query->row()->role);
        }

        $this->session->set_userdata('token_now', $token);
        $this->session->set_userdata('ip_satker', $this->get_config_value('34'));
        $this->session->set_userdata('mulai_apel_senin', $this->get_config_value('30'));
        $this->session->set_userdata('selesai_apel_senin', $this->get_config_value('31'));
        $this->session->set_userdata('mulai_apel_jumat', $this->get_config_value('32'));
        $this->session->set_userdata('selesai_apel_jumat', $this->get_config_value('33'));
    }

    private function cek_token($token)
    {
        $cookie_domain = $this->config->item('cookie_domain');
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
            redirect($cookie_domain . 'halamanlogin');
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