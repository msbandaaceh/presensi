<?php

class MY_Controller extends CI_Controller
{
    private $jwt_key;
    private $url_sso = 'http://sso.ms-bandaaceh.local/';

    public function __construct()
    {
        parent::__construct();
        $this->jwt_key = $this->config->item('jwt_key'); // inisialisasi di sini
        $cookie_domain = $this->config->item('cookie_domain');

        $this->load->model('ModelPresensi', 'model');

        if (!$this->session->userdata('logged_in')) {
            $token = $this->input->cookie('sso_token');
            if ($token) {
                $this->cek_token($token);
            } else {
                $redirect_url = current_url();
                setcookie('redirect_to', urlencode($redirect_url), time() + 300, "/", $cookie_domain);
                redirect($this->url_sso . 'login');
            }
        }

        $queryRapat = $this->model->get_seleksi('register_rapat', 'tanggal', date('Y-m-d'));
        if ($queryRapat->num_rows() > 0) {
            $this->session->set_userdata('agenda_rapat', '1');
        }

        # Periksa apakah hari ini ada kegiatan
        $queryKegiatan = $this->model->get_seleksi('ref_kegiatan', 'tanggal', date('Y-m-d'));
        if ($queryKegiatan->num_rows() > 0) {
            $this->session->set_userdata('agenda_lainnya', '1');
        }

        $queryApp = $this->model->get_seleksi('ref_client_app', 'id', '1');
        if ($queryApp->num_rows() > 0) {
            $this->session->set_userdata('nama_app', $queryApp->row()->nama_app);
            $this->session->set_userdata('deskripsi', $queryApp->row()->deskripsi);
        }

        $token = "";
        # Periksa apakah user login sebagai plh atau bukan
        if ($this->session->userdata('status_plh') != '1') {
            $cekUser = $this->model->get_seleksi('v_users', 'userid', $this->session->userdata("userid"));
            if ($cekUser->num_rows() > 0) {
                $token = $cekUser->row()->token;
                $this->session->set_userdata("token_now", $token);
            }
        }

        $this->session->set_userdata('ip_satker', $this->model->get_konfigurasi('34')->row()->value);
        $this->session->set_userdata('mulai_apel_senin', $this->model->get_konfigurasi('30')->row()->value);
        $this->session->set_userdata('selesai_apel_senin', $this->model->get_konfigurasi('31')->row()->value);
        $this->session->set_userdata('mulai_apel_jumat', $this->model->get_konfigurasi('32')->row()->value);
        $this->session->set_userdata('selesai_apel_jumat', $this->model->get_konfigurasi('33')->row()->value);
    }

    private function cek_token($token)
    {
        $sso_api = $this->url_sso . "api/cek_token?sso_token={$token}";
        $response = file_get_contents($sso_api);
        $data = json_decode($response, true);

        if ($data['status'] == 'success') {
            $this->session->set_userdata([
                'logged_in' => TRUE,
                'userid' => $data['user']['userid'],
                'status_plh' => $data['user']['status_plh']
            ]);
            redirect(current_url());
        } else {
            redirect($this->url_sso . 'halamanlogin');
        }
    }
}