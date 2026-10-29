<?php

/**
 * @property CI_Config $config
 * @property CI_Input $input
 * @property ModelPresensi $model
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
        if (!$this->session->userdata('agenda_checked')) {
            $queryKegiatan = $this->model->get_seleksi('ref_kegiatan', 'tanggal', date('Y-m-d'));
            $this->session->set_userdata('agenda_lainnya', $queryKegiatan->num_rows() > 0 ? '1' : '0');
            $this->session->set_userdata('agenda_checked', time());
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
                    $this->session->set_userdata('pegawai_id', $user_data['pegawai_id']);
                }
            }
        }

        // 2. Ambil user & config CUKUP 1X, jangan 10x
        if (!$this->session->userdata('sys_config_loaded')) {
            // a. Ambil v_users sekali
            if (!$this->session->userdata('status_plh') && !$this->session->userdata('status_plt')) {
                $params = [
                    'tabel' => 'v_users',
                    'kolom_seleksi' => 'userid',
                    'seleksi' => $this->session->userdata("userid")
                ];
                $result = $this->apihelper->get('apiclient/get_data_seleksi', $params);
                if ($result['status_code'] === 200 && $result['response']['status'] === 'success') {
                    $user_data = $result['response']['data'][0];
                    $this->session->set_userdata('jabatan', $user_data['jabatan']);
                    $this->session->set_userdata('pegawai_id', $user_data['pegawai_id']);
                    $this->session->set_userdata('token_now', $user_data['token']);
                }
            } else {
                $this->session->set_userdata('token_now', $token_presensi);
            }

            // b. Ambil SEMUA sys_config SEKALI JALAN, bukan 9x
            $params = ['tabel' => 'sys_config'];
            $resConfig = $this->apihelper->get('apiclient/get_data_tabel', $params);
            if ($resConfig['status_code'] === 200 && $resConfig['response']['status'] === 'success') {
                $map = array_column($resConfig['response']['data'], 'value', 'id');
                
                $this->session->set_userdata([
                    'ip_satker' => $map['34'],
                    'mulai_apel_senin' => $map['30'],
                    'selesai_apel_senin' => $map['31'],
                    'mulai_apel_jumat' => $map['32'],
                    'selesai_apel_jumat' => $map['33'],
                    'mulai_istirahat' => $map['35'],
                    'selesai_istirahat' => $map['36'],
                    'mulai_istirahat_jumat' => $map['37'],
                    'selesai_istirahat_jumat' => $map['38'],
                ]);
            }
            $this->session->set_userdata('sys_config_loaded', true);
        }

        // Peran, cek kalau belum ada aja
        if (!$this->session->userdata('peran')) {
            if (in_array($this->session->userdata('role'), ['super', 'validator_kepeg_satker', 'admin_satker'])) {
                $this->session->set_userdata('peran', 'admin');
            } else {
                $query = $this->model->get_seleksi2('role_presensi', 'userid', $this->session->userdata('userid'), 'hapus', '0');
                $this->session->set_userdata('peran', $query->num_rows() > 0 ? $query->row()->role : '');
            }
        }
    }

    private function cek_token($token)
    {
        $cookie_domain = $this->config->item('sso_server');
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