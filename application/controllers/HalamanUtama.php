<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Config $config
 * @property CI_Input $input
 * @property CI_Encryption $encryption
 * @property CI_Session $session
 * @property CI_Form_validation $form_validation
 */

class HalamanUtama extends MY_Controller
{
    public function index()
    {
        #die(var_dump($this->session->all_userdata()));
        $data['page'] = 'dashboard';
        $data['peran'] = $this->session->userdata('peran');

        $this->load->view('layout', $data);
    }

    public function page($halaman)
    {
        // Amanin nama file view agar tidak sembarang file bisa diload
        $allowed = [
            'dashboard',
            'laporan_harian',
            'laporan_satker',
            'laporan_apel',
            'laporan_kegiatan',
            'kegiatan'
        ];

        if (in_array($halaman, $allowed)) {
            $data['peran'] = $this->session->userdata('peran');
            $data['page'] = $halaman;

            if ($halaman == 'laporan_harian') {
                $data['presensi'] = $this->model->all_pres_pengguna($this->session->userdata("userid"));
                $this->session->set_userdata("tanggal", date("Y-m"));
            } elseif ($halaman == 'laporan_satker') {
                $data['hakim'] = $this->model->pres_hakim_now();
                $data['cakim'] = $this->model->pres_cakim_now();
                $data['pns'] = $this->model->pres_pns_now();
                $data['pppk'] = $this->model->pres_pppk_now();
                $data['honor'] = $this->model->pres_honor_now();
                $this->session->set_userdata("tanggal", date("Y-m-d"));
            } elseif ($halaman == 'laporan_apel') {
                $data['apel'] = $this->model->register_apel();
            } elseif ($halaman == 'laporan_kegiatan') {
                $data['kegiatan'] = $this->model->register_kegiatan();
            } elseif ($halaman == 'kegiatan') {
                $data['kegiatan'] = $this->model->all_kegiatan();
            }

            $this->load->view($halaman, $data);
        } else {
            show_404();
        }
    }

    public function cek_token_sso()
    {
        $token = $this->input->cookie('sso_token');
        $cookie_domain = $this->config->item('sso_server');
        $sso_api = $cookie_domain . "api/cek_token?sso_token={$token}";
        $response = file_get_contents($sso_api);
        $data = json_decode($response, true);

        if ($data['status'] == 'success') {
            echo json_encode(['valid' => true]);
        } else {
            echo json_encode(['valid' => false, 'message' => 'Token Expired, Silakan login ulang', 'url' => $cookie_domain . 'login']);
        }
    }

    # MENAMPILKAN ISI MODAL PRESENSI MASUK DAN PULANG
    public function show()
    {
        $hari = $this->tanggalhelper->convertDayDate(date('Y-m-d', time()));
        $jam = date('H:i:s');
        $userid = $this->session->userdata('userid');
        $fullname = $this->session->userdata('fullname');
        $id_jabatan = $this->session->userdata('jab_id');
        $jamSekarang = strtotime($jam);
        $jamTengahHari = strtotime('12:00:00');
        $batasKemarin = strtotime("16:30:00");
        //die(var_dump($jam));

        $cekPresensi = $this->model->get_presensi_pengguna_hari_ini($userid);
        $cekPresensiKemarin = $this->model->get_presensi_pengguna_kemarin($userid);
        //die(var_dump($cekPresensiKemarin));

        if ($id_jabatan == '31') { //untuk satpam
            if ($jamSekarang > $jamTengahHari) { //waktu siang
                if ($cekPresensi) {
                    if (strtotime($cekPresensi->masuk) > $jamTengahHari) {
                        echo json_encode(
                            array(
                                'st' => 1,
                                'hari' => $hari,
                                'jam' => $this->tanggalhelper->konversiJam($jam),
                                'jam_masuk' => $this->tanggalhelper->konversiJam($cekPresensi->masuk),
                                'jam_pulang' => $this->tanggalhelper->konversiJam($cekPresensi->pulang)
                            )
                        );
                    } else {
                        echo json_encode(
                            array(
                                'st' => 1,
                                'hari' => $hari,
                                'jam' => $this->tanggalhelper->konversiJam($jam),
                                'jam_masuk' => $this->tanggalhelper->konversiJam($cekPresensi->masuk),
                                'jam_pulang' => $this->tanggalhelper->konversiJam($cekPresensi->pulang)
                            )
                        );
                    }
                } else {
                    echo json_encode(
                        array(
                            'st' => 1,
                            'hari' => $hari,
                            'jam' => $this->tanggalhelper->konversiJam($jam),
                            'jam_masuk' => '',
                            'jam_pulang' => ''
                        )
                    );
                }
            } else { //waktu pagi
                if ($cekPresensi == null) {
                    if ($cekPresensiKemarin == NULL) {
                        echo json_encode(
                            array(
                                'st' => 1,
                                'hari' => $hari,
                                'jam' => $this->tanggalhelper->konversiJam($jam),
                                'jam_masuk' => '',
                                'jam_pulang' => ''
                            )
                        );
                    } else {
                        if (strtotime($cekPresensiKemarin->masuk) < $jamTengahHari) {
                            echo json_encode(
                                array(
                                    'st' => 1,
                                    'hari' => $hari,
                                    'jam' => $this->tanggalhelper->konversiJam($jam),
                                    'jam_masuk' => '',
                                    'jam_pulang' => ''
                                )
                            );
                        } else {
                            echo json_encode(
                                array(
                                    'st' => 1,
                                    'hari' => $hari,
                                    'jam' => $this->tanggalhelper->konversiJam($jam),
                                    'jam_masuk' => $this->tanggalhelper->konversiJam($cekPresensiKemarin->masuk),
                                    'jam_pulang' => $this->tanggalhelper->konversiJam($cekPresensiKemarin->pulang)
                                )
                            );
                        }
                    }
                } else {
                    echo json_encode(
                        array(
                            'st' => 1,
                            'hari' => $hari,
                            'jam' => $this->tanggalhelper->konversiJam($jam),
                            'jam_masuk' => $this->tanggalhelper->konversiJam($cekPresensi->masuk),
                            'jam_pulang' => $this->tanggalhelper->konversiJam($cekPresensi->pulang)
                        )
                    );
                }
            }
        } else { //untuk selain satpam
            if ($cekPresensi == null) {
                echo json_encode(
                    array(
                        'st' => 1,
                        'hari' => $hari,
                        'jam' => $this->tanggalhelper->konversiJam($jam),
                        'jam_masuk' => '',
                        'jam_pulang' => ''
                    )
                );
            } else {
                echo json_encode(
                    array(
                        'st' => 1,
                        'hari' => $hari,
                        'jam' => $this->tanggalhelper->konversiJam($jam),
                        'jam_masuk' => $this->tanggalhelper->konversiJam($cekPresensi->masuk),
                        'jam_pulang' => $this->tanggalhelper->konversiJam($cekPresensi->pulang)
                    )
                );
            }
        }
        return;
    }

    public function show_istirahat()
    {
        $hari = $this->tanggalhelper->convertDayDate(date('Y-m-d', time()));
        $jam = date('H:i:s');
        $userid = $this->session->userdata("userid");

        $cekPresensi = $this->model->get_presensi_pengguna_hari_ini($userid);

        if ($cekPresensi == null) {
            echo json_encode(
                array(
                    'st' => 2,
                    'pesan' => "Anda tidak presensi kehadiran, tidak diizinkan presensi istirahat"
                )
            );
        } else {
            echo json_encode(
                array(
                    'st' => 1,
                    'hari' => $hari,
                    'jam' => $this->tanggalhelper->konversiJam($jam),
                    'istirahat_mulai' => $this->tanggalhelper->konversiJam($cekPresensi->istirahat_mulai),
                    'istirahat_selesai' => $this->tanggalhelper->konversiJam($cekPresensi->istirahat_selesai)
                )
            );
        }

        return;
    }

    public function show_kegiatan()
    {
        $hari = $this->tanggalhelper->convertDayDate(date('Y-m-d', time()));
        $jam = date('H:i:s');

        $query = $this->model->get_list_kegiatan_lain(date('Y-m-d'));
        //die(var_dump($query->row()->agenda));
        $kegiatan = array();
        $kegiatan[''] = "-- Pilih Agenda Kegiatan Lainnya --";
        foreach ($query->result() as $row) {
            $kegiatan[$row->id] = $row->nama_kegiatan;
        }

        $acara = form_dropdown('kegiatan', $kegiatan, '', 'class="form-control bg-orange show-tick"  id="kegiatan"');

        echo json_encode(
            array(
                'st' => 1,
                'hari' => $hari,
                'jam' => $jam,
                'rapat' => $acara
            )
        );
        return;
    }

    public function show_role()
    {
        $id = $this->input->post('id');
        $data = [
            "tabel" => "v_users",
            "kolom_seleksi" => "status_pegawai",
            "seleksi" => "1"
        ];

        $users = $this->apihelper->get('apiclient/get_data_seleksi', $data);

        $pegawai = array();
        if ($users['status_code'] === '200') {
            foreach ($users['response']['data'] as $item) {
                $pegawai[$item['userid']] = $item['fullname'];
            }
        }

        if ($id != '-1') {
            $query = $this->model->get_seleksi('role_presensi', 'id', $id);

            echo json_encode(
                array(
                    'pegawai' => $users['response']['data'],
                    'role' => $pegawai,
                    'id' => $query->row()->id,
                    'editPegawai' => $query->row()->userid,
                    'editPeran' => $query->row()->role
                )
            );
        } else {
            $dataPeran = $this->model->get_data_peran();
            #die(var_dump($dataPeran));

            echo json_encode(
                array(
                    'pegawai' => $users['response']['data'],
                    'role' => $pegawai,
                    'data_peran' => $dataPeran
                )
            );
        }

        return;

        #die(var_dump($users["response"]["data"]));
        #echo $users["response"]["data"];

    }

    public function simpan_peran()
    {
        $id = $this->input->post('id');
        $pegawai = $this->input->post('pegawai');
        $peran = $this->input->post('peran');

        if ($id) {
            $data = array(
                'userid' => $pegawai,
                'role' => $peran,
                'modified_by' => $this->session->userdata('fullname'),
                'modified_on' => date('Y-m-d H:i:s')
            );

            $query = $this->model->pembaharuan_data('role_presensi', $data, 'id', $id);
        } else {
            $query = $this->model->get_seleksi('role_presensi', 'userid', $pegawai);
            if ($query->num_rows() > 0) {
                $this->session->set_flashdata('info', '2');
                $this->session->set_flashdata('pesan_gagal', 'Pegawai tersebut sudah memiliki peran');
                redirect('');
                return;
            }

            $data = array(
                'userid' => $pegawai,
                'role' => $peran,
                'created_by' => $this->session->userdata('fullname'),
                'created_on' => date('Y-m-d H:i:s')
            );
            $query = $this->model->simpan_data('role_presensi', $data);
        }

        if ($query == '1') {
            $this->session->set_flashdata('info', '1');
            $this->session->set_flashdata('pesan_sukses', 'Peran Pegawai telah disimpan');
        } else {
            $this->session->set_flashdata('info', '3');
            $this->session->set_flashdata('pesan_gagal', 'Peran Pegawai gagal disimpan');
        }

        redirect('/');
    }

    public function aktif_peran()
    {
        $id = $this->input->post('id');

        $data = array(
            'hapus' => '0',
            'modified_by' => $this->session->userdata('username'),
            'modified_on' => date('Y-m-d H:i:s')
        );

        $query = $this->model->pembaharuan_data('role_presensi', $data, 'id', $id);
        if ($query == '1') {
            echo json_encode(
                array(
                    'st' => '1'
                )
            );
        } else {
            echo json_encode(
                array(
                    'st' => '0'
                )
            );
        }
    }

    public function blok_peran()
    {
        $id = $this->input->post('id');

        $data = array(
            'hapus' => '1',
            'modified_by' => $this->session->userdata('username'),
            'modified_on' => date('Y-m-d H:i:s')
        );

        $query = $this->model->pembaharuan_data('role_presensi', $data, 'id', $id);
        if ($query == '1') {
            echo json_encode(
                array(
                    'st' => '1'
                )
            );
        } else {
            echo json_encode(
                array(
                    'st' => '0'
                )
            );
        }
    }

    public function simpan_perangkat()
    {
        $id = $this->session->userdata('userid');
        $token = md5(uniqid());

        $payload = [
            'tabel' => 'sys_users',
            'kunci' => 'userid',
            'id' => $id,
            'data' => [
                'token_pres' => $token,
                'modified_by' => $this->session->userdata('fullname'),
                'modified_on' => date('Y-m-d H:i:s')
            ]
        ];

        $result = $this->apihelper->patch('api_update', $payload);

        if ($result['status_code'] === 200 && !empty($result['response']['status'])) {
            $cookie_domain = $this->config->item('cookie_domain');
            setcookie(
                'presensi_token',
                $token,
                [
                    'expires' => time() + (86500 * 30 * 12),
                    'path' => '/',
                    'domain' => $cookie_domain, // pastikan subdomain
                    'secure' => false, // hanya jika HTTPS
                    'httponly' => true,
                    'samesite' => 'Lax', // atau 'Strict'
                ]
            );
            $this->session->set_userdata("token_now", $token);

            echo json_encode(
                array(
                    'st' => 1
                )
            );
        } else {
            echo json_encode(
                array(
                    'st' => 0
                )
            );
        }

        return;
    }

    public function simpan_presensi()
    {
        $jam = $this->input->post('jam_absen');
        $userid = $this->session->userdata("userid");
        $fullname = $this->session->userdata('fullname');
        $id_jabatan = $this->session->userdata('id_jabatan');
        $jamSekarang = strtotime($jam);
        $jamTengahHari = strtotime('12:00:00');
        $batasKemarin = strtotime("16:30:00");

        $cekPresensi = $this->model->get_presensi_pengguna_hari_ini($userid);
        $cekPresensiKemarin = $this->model->get_presensi_pengguna_kemarin($userid);

        if ($cekPresensi) { // If already checked in today
            if ($id_jabatan == '31') {
                if (strtotime($cekPresensi->masuk) >= $batasKemarin) { // After 12:00 PM
                    $querySimpan = 2;
                } else {
                    if ($jamSekarang <= $jamTengahHari) { // Before 12:00 PM
                        $querySimpan = 2;
                    } else { // After 12:00 PM, check out
                        $id = $cekPresensi->id;
                        $dataPengguna = array(
                            'pulang' => $jam,
                            'modified_by' => $fullname,
                            'modified_on' => date('Y-m-d H:i:s')
                        );
                        $querySimpan = $this->model->simpan_pulang($dataPengguna, $id);
                    }
                }
            } else {
                if ($jamSekarang <= $jamTengahHari) { // Before 12:00 PM
                    $querySimpan = 2;
                } else { // After 12:00 PM, check out
                    $id = $cekPresensi->id;
                    $dataPengguna = array(
                        'pulang' => $jam,
                        'modified_by' => $fullname,
                        'modified_on' => date('Y-m-d H:i:s')
                    );
                    $querySimpan = $this->model->simpan_pulang($dataPengguna, $id);
                }
            }
        } else { // If not checked in today
            if ($id_jabatan == '31') { //untuk satpam
                if ($jamSekarang < $jamTengahHari) {
                    //jam pagi
                    if ($cekPresensiKemarin != 0 && strtotime($cekPresensiKemarin->masuk) > $batasKemarin) {
                        if ($cekPresensiKemarin != 0 && $cekPresensiKemarin->pulang == NULL) {
                            // Security guard and checked in after 4:30 PM yesterday
                            $id = $cekPresensiKemarin->id;
                            $dataPengguna = array(
                                'pulang' => $jam,
                                'modified_by' => $fullname,
                                'ket' => 'Piket Malam',
                                'modified_on' => date('Y-m-d H:i:s')
                            );
                            $querySimpan = $this->model->simpan_pulang($dataPengguna, $id);
                        } else {
                            $dataPengguna = array(
                                'userid' => $userid,
                                'masuk' => $jam,
                                'tgl' => date('Y-m-d'),
                                'created_by' => $this->session->userdata('fullname'),
                                'created_on' => date('Y-m-d H:i:s')
                            );
                            $querySimpan = $this->model->simpan_masuk($dataPengguna, $userid);
                        }
                    } else {
                        $dataPengguna = array(
                            'userid' => $userid,
                            'masuk' => $jam,
                            'tgl' => date('Y-m-d'),
                            'created_by' => $this->session->userdata('fullname'),
                            'created_on' => date('Y-m-d H:i:s')
                        );
                        $querySimpan = $this->model->simpan_masuk($dataPengguna, $userid);
                    }
                } else {
                    //jam siang
                    $dataPengguna = array(
                        'userid' => $userid,
                        'masuk' => $jam,
                        'tgl' => date('Y-m-d'),
                        'created_by' => $this->session->userdata('fullname'),
                        'created_on' => date('Y-m-d H:i:s')
                    );
                    $querySimpan = $this->model->simpan_masuk($dataPengguna, $userid);
                }

            } else {
                if ($jamSekarang <= $jamTengahHari) { // Before 12:00 PM
                    $dataPengguna = array(
                        'userid' => $userid,
                        'masuk' => $jam,
                        'tgl' => date('Y-m-d'),
                        'created_by' => $this->session->userdata('fullname'),
                        'created_on' => date('Y-m-d H:i:s')
                    );
                } else { // After 12:00 PM
                    $dataPengguna = array(
                        'userid' => $userid,
                        'pulang' => $jam,
                        'tgl' => date('Y-m-d'),
                        'created_by' => $this->session->userdata('fullname'),
                        'created_on' => date('Y-m-d H:i:s')
                    );
                }
                $querySimpan = $this->model->simpan_masuk($dataPengguna, $userid);
            }
        }

        if ($querySimpan == 1) {
            $cookie_domain = $this->config->item('cookie_domain');
            setcookie(
                'presensi_token',
                $this->session->userdata("token_now"),
                [
                    'expires' => time() + (86500 * 30 * 12),
                    'path' => '/',
                    'domain' => $cookie_domain, // pastikan subdomain
                    'secure' => false, // hanya jika HTTPS
                    'httponly' => true,
                    'samesite' => 'Lax', // atau 'Strict'
                ]
            );

            $this->session->set_flashdata('info', '1');
            $this->session->set_flashdata('pesan_sukses', 'Presensi Berhasil Di Simpan');
        } elseif ($querySimpan == 2) {
            $this->session->set_flashdata('info', '2');
            $this->session->set_flashdata('pesan_gagal', 'Anda Sudah Presensi Kedatangan Hari ini</br>Ngapain Presensi Lagi ?');
        } else {
            $this->session->set_flashdata('info', '3');
            $this->session->set_flashdata('pesan_gagal', 'Presensi Gagal Simpan, Silakan Ulangi Lagi');
        }
        redirect('/');
    }

    public function simpan_istirahat()
    {
        $jam = $this->input->post('jam_istirahat');
        $userid = $this->session->userdata("userid");
        $fullname = $this->session->userdata('fullname');
        $hari = $this->tanggalhelper->convertDayDate(date("Y-m-d"));
        $mulaiIstirahat = strtotime($this->session->userdata("mulai_istirahat"));
        $selesaiIstirahat = strtotime($this->session->userdata("selesai_istirahat"));
        $mulaiIstirahatJumat = strtotime($this->session->userdata("mulai_istirahat_jumat"));
        $selesaiIstirahatJumat = strtotime($this->session->userdata("selesai_istirahat_jumat"));

        $cekPresensi = $this->model->get_seleksi2('register_presensi', 'userid', $userid, 'tgl', date('Y-m-d'));
        if ($cekPresensi->row()->istirahat_mulai) {
            if (strpos($hari, "umat")) {
                // Rentang waktu tombol presensi istirahat hari Jumat
                if (strtotime($jam) >= $mulaiIstirahatJumat && strtotime($jam) <= ($mulaiIstirahatJumat + 600)) {
                    // 10 menit pertama: tombol mulai istirahat
                    $this->session->set_flashdata('info', '2');
                    $this->session->set_flashdata('pesan_gagal', 'Anda sudah presensi Mulai Istirahat, silakan presensi lagi nanti ketika Istirahat akan selesai');
                    redirect('');
                } else {
                    // 10 menit terakhir: tombol selesai istirahat
                    $dataPengguna = array(
                        'istirahat_selesai' => $jam,
                        'modified_by' => $fullname,
                        'modified_on' => date('Y-m-d H:i:s')
                    );
                }
            } else {
                if (strtotime($jam) >= $mulaiIstirahat && strtotime($jam) <= ($mulaiIstirahat + 600)) {
                    // 10 menit pertama: tombol mulai istirahat
                    $this->session->set_flashdata('info', '2');
                    $this->session->set_flashdata('pesan_gagal', 'Anda sudah presensi Mulai Istirahat, silakan presensi lagi nanti ketika Istirahat akan selesai');
                    redirect('');
                } else {
                    // 10 menit terakhir: tombol selesai istirahat
                    $dataPengguna = array(
                        'istirahat_selesai' => $jam,
                        'modified_by' => $fullname,
                        'modified_on' => date('Y-m-d H:i:s')
                    );
                }
            }

            $dataPengguna = array(
                'istirahat_selesai' => $jam,
                'modified_by' => $fullname,
                'modified_on' => date('Y-m-d H:i:s')
            );
        } else {
            if (strpos($hari, "umat")) {
                // Rentang waktu tombol presensi istirahat hari Jumat
                if (strtotime($jam) >= ($selesaiIstirahatJumat - 600) && strtotime($jam) <= $selesaiIstirahatJumat) {
                    // 10 menit pertama: tombol mulai istirahat
                    $dataPengguna = array(
                        'istirahat_selesai' => $jam,
                        'modified_by' => $fullname,
                        'modified_on' => date('Y-m-d H:i:s')
                    );
                } else {
                    // 10 menit terakhir: tombol selesai istirahat
                    $dataPengguna = array(
                        'istirahat_mulai' => $jam,
                        'modified_by' => $fullname,
                        'modified_on' => date('Y-m-d H:i:s')
                    );
                }
            } else {
                if (strtotime($jam) >= ($selesaiIstirahat - 600) && strtotime($jam) <= $selesaiIstirahat) {
                    $dataPengguna = array(
                        'istirahat_selesai' => $jam,
                        'modified_by' => $fullname,
                        'modified_on' => date('Y-m-d H:i:s')
                    );
                } else {
                    $dataPengguna = array(
                        'istirahat_mulai' => $jam,
                        'modified_by' => $fullname,
                        'modified_on' => date('Y-m-d H:i:s')
                    );
                }
            }
        }

        $querySimpan = $this->model->pembaharuan_data('register_presensi', $dataPengguna, 'id', $cekPresensi->row()->id);

        if ($querySimpan == 1) {
            $this->session->set_flashdata('info', '1');
            $this->session->set_flashdata('pesan_sukses', 'Presensi Istirahat Berhasil Di Simpan');
        } else {
            $this->session->set_flashdata('info', '2');
            $this->session->set_flashdata('pesan_gagal', 'Presensi Istirahat Gagal Simpan, Silakan Ulangi Lagi');
        }
        redirect('/');
    }

    public function simpan_presensi_apel()
    {
        $userid = $this->session->userdata("userid");

        $cekPresensiApel = $this->model->get_presensi_apel($userid);

        if ($cekPresensiApel) { // If already checked in today
            $querySimpan = 2;
        } else { // If not checked in today
            $dataPengguna = array(
                'userid' => $userid,
                'created_on' => date('Y-m-d H:i:s')
            );
            $querySimpan = $this->model->simpan_apel($dataPengguna, $userid);
        }

        if ($querySimpan == 1) {
            echo json_encode(
                array(
                    'st' => 1
                )
            );
        } elseif ($querySimpan == 2) {
            echo json_encode(
                array(
                    'st' => 2
                )
            );
        } else {
            echo json_encode(
                array(
                    'st' => 0
                )
            );
        }

        return;
    }

    public function simpan_presensi_kegiatan()
    {
        $this->form_validation->set_rules('kegiatan', 'Kegiatan', 'trim|required');
        $this->form_validation->set_message('required', '%s Belum Dipilih');

        if ($this->form_validation->run() == FALSE) {
            //echo json_encode(array('st' => 0, 'msg' => 'Tidak Berhasil:<br/>'.validation_errors()));
            $this->session->set_flashdata('info', '3');
            $this->session->set_flashdata('pesan_gagal', 'Gagal Simpan Presensi, ' . form_error('kegiatan'));
            redirect('/', validation_errors());
            return;
        }

        $userid = $this->session->userdata('userid');
        $idkegiatan = $this->input->post('kegiatan');

        $cekPresensi = $this->model->get_presensi_kegiatan($userid, $idkegiatan);
        //die(var_dump($cekPresensi));
        if ($cekPresensi != 0) { // If already checked in today
            $querySimpan = 2;
        } else { // If not checked in today
            $dataPengguna = array(
                'userid' => $userid,
                'idkegiatan' => $idkegiatan,
                'created_on' => date('Y-m-d H:i:s')
            );

            $querySimpan = $this->model->simpan_kegiatan($dataPengguna, $userid);
        }

        if ($querySimpan == 1) {
            $this->session->set_flashdata('info', '1');
            $this->session->set_flashdata('pesan_sukses', 'Presensi Berhasil Di Simpan');
        } elseif ($querySimpan == 2) {
            $this->session->set_flashdata('info', '2');
            $this->session->set_flashdata('pesan_gagal', 'Anda Sudah Presensi Untuk Kegiatan ini !');
        } else {
            $this->session->set_flashdata('info', '0');
            $this->session->set_flashdata('pesan_gagal', 'Presensi Gagal Simpan, Silakan Ulangi Lagi');
        }
        redirect('/');
    }

    public function keluar()
    {
        $sso_server = $this->config->item('sso_server');
        $this->session->sess_destroy();
        redirect($sso_server . '/keluar');
    }

    public function get_status_pegawai()
    {
        $params = [
            "tabel" => "ref_mpp",
            "kolom_seleksi" => "pegawai_id",
            "seleksi" => $this->session->userdata('pegawai_id')
        ];

        $users = $this->apihelper->get('apiclient/get_data_seleksi', $params);
        if ($users['status_code'] == '200' && $users['response']['status'] == 'success') {
            $data['status'] = '1';
        } else {
            $data['status'] = '2';
        }

        echo json_encode($data);
    }

    public function get_lokasi()
    {
        $result = $this->apihelper->get('apiclient/get_lokasi_mpp');
        if ($result['status_code'] === 200 && $result['response']['status'] === 'success') {
            $lokasi = $result['response']['data'][0];
            if ($lokasi) {
                $lokasi['koordinat'] = json_decode($lokasi['polygon_json']); // decode dulu biar rapi
            }
        }

        echo json_encode($lokasi);
    }
}
