<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Config $config
 * @property CI_Session $session
 */

class HalamanPanduan extends MY_Controller
{
    public function index()
    {
        $peran = $this->session->userdata('peran');
        
        // Redirect berdasarkan peran
        if ($peran == 'admin') {
            redirect('panduan/admin');
        } elseif ($peran == 'operator') {
            redirect('panduan/operator');
        } else {
            redirect('panduan/user');
        }
    }

    public function admin()
    {
        // Hanya admin yang bisa akses
        $peran = $this->session->userdata('peran');
        if ($peran != 'admin') {
            show_404();
            return;
        }

        $data['page'] = 'panduan_admin';
        $data['peran'] = $peran;
        $this->load->view('panduan_admin', $data);
    }

    public function operator()
    {
        // Admin dan operator bisa akses
        $peran = $this->session->userdata('peran');
        if (!in_array($peran, ['admin', 'operator'])) {
            show_404();
            return;
        }

        $data['page'] = 'panduan_operator';
        $data['peran'] = $peran;
        $this->load->view('panduan_operator', $data);
    }

    public function user()
    {
        // Semua user bisa akses
        $data['page'] = 'panduan_user';
        $data['peran'] = $this->session->userdata('peran');
        $this->load->view('panduan_user', $data);
    }

    public function dokumentasi_teknis()
    {
        // Hanya admin yang bisa akses
        $peran = $this->session->userdata('peran');
        if ($peran != 'admin') {
            show_404();
            return;
        }

        $data['page'] = 'dokumentasi_teknis';
        $data['peran'] = $peran;
        $this->load->view('dokumentasi_teknis', $data);
    }
}

