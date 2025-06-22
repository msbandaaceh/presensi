<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HalamanLaporan extends MY_Controller
{
    ########################################
    #   LAPORAN PRESENSI HARIAN PRIBADI    #
    ########################################

    public function index()
    {
        if ($this->session->userdata('status_plt') == '1') {
            $ip = null;
            $token = null;
        } else {
            $cekUser = $this->model->get_seleksi_pengguna($this->session->userdata("userid"));
            $ip = $cekUser->row()->ip;
            $token = $cekUser->row()->token;
        }

        if ($ip && $token) {
            $this->session->set_userdata("ip_now", $ip);
            $this->session->set_userdata("token_now", $token);
        }

        $data['presensi'] = $this->absen->all_pres_pengguna($this->session->userdata("userid"));

        $this->session->set_userdata("tanggal", date("Y-m"));

        $this->load->view('halamanabsen/header');
        $this->load->view('halamanabsen/laporan/sidebar');
        $this->load->view('halamanabsen/laporan/lis_report', $data);
        $this->load->view('halamanabsen/footer');

    }

    #############################################################
    #   LAPORAN PRESENSI HARIAN PRIBADI SESUAI BULAN PILIHAN    #
    #############################################################

    public function laporan_bulan()
    {
        $this->form_validation->set_rules('bulan', 'Bulan', 'trim|required');

        $this->form_validation->set_message('required', '%s Tidak Boleh Kosong');

        if ($this->form_validation->run() == FALSE) {
            //echo json_encode(array('st' => 0, 'msg' => 'Tidak Berhasil:<br/>'.validation_errors()));
            $this->session->set_flashdata('info', '0');
            $this->session->set_flashdata('pesan_gagal', form_error('bulan'));
            redirect('laporan', validation_errors());
            return;
        }

        $month = $this->input->post('bulan');
        $bulan = DateTime::createFromFormat('F Y', $month);
        $konversi_bulan = $bulan->format('Y-m');
        $bulan_fix = $konversi_bulan . '-00';
        //die(var_dump($bulan_fix));
        $this->session->set_userdata("tanggal", $konversi_bulan);

        $data['presensi'] = $this->absen->all_pres_pengguna_bulan($this->session->userdata("userid"), $bulan_fix);
        //die(var_dump($data));
        $this->load->view('halamanabsen/header');
        $this->load->view('halamanabsen/laporan/sidebar');
        $this->load->view('halamanabsen/laporan/lis_report', $data);
        $this->load->view('halamanabsen/footer');
    }

    #############################################
    #   LAPORAN PRESENSI HARIAN SATUAN KERJA    #
    #############################################
    public function laporan_satker()
    {
        $data['hakim'] = $this->absen->pres_hakim_now();
        $data['cakim'] = $this->absen->pres_cakim_now();
        $data['pns'] = $this->absen->pres_pns_now();
        $data['pppk'] = $this->absen->pres_pppk_now();
        $data['honor'] = $this->absen->pres_honor_now();
        //die(var_dump($data));

        $this->session->set_userdata("tanggal", date("Y-m-d"));

        $this->load->view('halamanabsen/header');
        $this->load->view('halamanabsen/laporansatker/sidebar');
        $this->load->view('halamanabsen/laporansatker/lis_report', $data);
        $this->load->view('halamanabsen/footer');
    }

    ####################################################################
    #   LAPORAN PRESENSI HARIAN SATUAN KERJA SESUAI TANGGAL PILIHAN    #
    ####################################################################

    public function laporan_satker_bulan()
    {
        $this->form_validation->set_rules('bulan', 'Tanggal', 'trim|required');

        $this->form_validation->set_message('required', '%s Tidak Boleh Kosong');

        if ($this->form_validation->run() == FALSE) {
            //echo json_encode(array('st' => 0, 'msg' => 'Tidak Berhasil:<br/>'.validation_errors()));
            $this->session->set_flashdata('info', '0');
            $this->session->set_flashdata('pesan_gagal', form_error('bulan'));
            redirect('laporan_satker', validation_errors());
            return;
        }

        $month = $this->input->post('bulan');
        $bulan = DateTime::createFromFormat('d F Y', $month);
        $konversi_bulan = $bulan->format('Y-m-d');
        //die(var_dump($konversi_bulan));
        $this->session->set_userdata("tanggal", $konversi_bulan);

        $data['hakim'] = $this->absen->pres_hakim_bulan($konversi_bulan);
        $data['cakim'] = $this->absen->pres_cakim_bulan($konversi_bulan);
        //die(var_dump($data));
        $data['pns'] = $this->absen->pres_pns_bulan($konversi_bulan);
        $data['pppk'] = $this->absen->pres_pppk_bulan($konversi_bulan);
        //die(var_dump($data));
        $data['honor'] = $this->absen->pres_honor_bulan($konversi_bulan);
        //die(var_dump($data));
        $this->load->view('halamanabsen/header');
        $this->load->view('halamanabsen/laporansatker/sidebar');
        $this->load->view('halamanabsen/laporansatker/lis_report', $data);
        $this->load->view('halamanabsen/footer');
    }

    ############################################################################
    #   MENAMPILKAN DATA PRESENSI PADA MODAL KETIKA MEMBUKA DETAIL PRESENSI    #
    ############################################################################

    public function edit()
    {
        $id = $this->encrypt->decode(base64_decode($this->input->post('id')));
        $userid = $this->encrypt->decode(base64_decode($this->input->post('userid')));

        $user = $this->model->get_seleksi_pengguna($userid);
        $nama = $user->row()->fullname;

        $ket = array('' => "-- Keterangan --", 'Manual Presensi' => 'Manual Presensi', 'Tidak Presensi' => 'Tidak Presensi', 'Izin Terlambat Masuk' => 'Izin Terlambat Masuk', 'Izin Tidak Masuk *Hakim' => 'Izin Tidak Masuk *Hakim', 'Piket Malam' => 'Piket Malam', 'Work From Home' => 'Work From Home', 'Tanpa Keterangan' => 'Tanpa Keterangan', 'Dinas Luar' => 'Dinas Luar', 'Cuti Tahunan' => 'Cuti Tahunan', 'Cuti Sakit' => 'Cuti Sakit', 'Cuti Melahirkan' => 'Cuti Melahirkan', 'Cuti Alasan Penting' => 'Cuti Alasan Penting', 'Cuti Besar' => 'Cuti Besar', 'Libur' => 'Libur');

        if ($id == "-1") {
            $id = "";
            $masuk = "";
            $pulang = "";
            $ktrngn = form_dropdown('ket', $ket, '', 'class="form-control show-tick"  id="ket"');
        } else {
            $cariPresensi = $this->absen->get_presensi_data($id);
            if ($cariPresensi->masuk) {
                $masuk = $this->tanggalhelper->konversiJam($cariPresensi->masuk);
            } else {
                $masuk = "";
            }
            if ($cariPresensi->pulang) {
                $pulang = $this->tanggalhelper->konversiJam($cariPresensi->pulang);
            } else {
                $pulang = "";
            }
            $ketCari = $cariPresensi->ket;
            $ktrngn = form_dropdown('ket', $ket, $ketCari, 'class="form-control show-tick"  id="ket"');
        }

        echo json_encode(
            array(
                'st' => 1,
                'id' => $id,
                'userid' => $userid,
                'judul' => $nama,
                'masuk' => $masuk,
                'pulang' => $pulang,
                'ket' => $ktrngn
            )
        );
        return;
    }

    ##################################################
    #   MENAMPILKAN DATA PRESENSI SELURUH PEGAWAI    #
    ##################################################

    public function show_all()
    {
        $pegawai = $this->model->list_pengguna_aktif();
        $nama = array();
        $nama[''] = "-- Tampilkan Seluruh Pegawai --";
        foreach ($pegawai->result() as $row) {
            $nama[$row->userid] = $row->fullname;
        }
        //die(var_dump($nama));

        $list = form_dropdown('pegawai', $nama, '', 'class="form-control show-tick" id="pegawai"');
        echo json_encode(
            array(
                'st' => 1,
                'pegawai' => $list,
            )
        );
        return;
    }

    ##############################################
    #   MENYIMPAN HASIL EDIT PRESENSI PEGAWAI    #
    ##############################################
    public function simpan_edit_presensi()
    {
        $id = html_purify($this->input->post('id'));
        $userid = html_purify($this->input->post('userid'));
        $tgl = html_purify($this->input->post('tgl'));
        if (html_purify($this->input->post('masuk'))) {
            $masuk = html_purify($this->input->post('masuk'));
        } else {
            $masuk = NULL;
        }

        if (html_purify($this->input->post('pulang'))) {
            $pulang = html_purify($this->input->post('pulang'));
        } else {
            $pulang = NULL;
        }

        if (html_purify($this->input->post('ket'))) {
            $ket = html_purify($this->input->post('ket'));
        } else {
            $ket = NULL;
        }

        if ($id) {
            $dataPengguna = array(
                'tgl' => $tgl,
                'userid' => $userid,
                'masuk' => $masuk,
                'pulang' => $pulang,
                'ket' => $ket,
                'modified_by' => $this->session->userdata('fullname'),
                'modified_on' => date('Y-m-d H:i:s')
            );

            $querySimpan = $this->absen->update_presensi($dataPengguna, $id);

        } else {
            $dataPengguna = array(
                'tgl' => $tgl,
                'userid' => $userid,
                'masuk' => $masuk,
                'pulang' => $pulang,
                'ket' => $ket,
                'created_on' => date('Y-m-d H:i:s')
            );

            $querySimpan = $this->absen->simpan_presensi($dataPengguna);
        }

        if ($querySimpan == 1) {

            $this->session->set_flashdata('info', '1');
            $this->session->set_flashdata('pesan_sukses', 'Presensi Berhasil Di Simpan');
            redirect('laporan_satker');
        } else {
            $this->session->set_flashdata('info', '0');
            $this->session->set_flashdata('pesan_gagal', 'Presensi Gagal Simpan, Silakan Ulangi Lagi');
            redirect('laporan_satker');
        }
    }

    ################################################
    #   MENCETAK LAPORAN PRESENSI MASUK PEGAWAI    #
    ################################################
    public function cetak_laporan_masuk()
    {
        $jenis = html_purify($this->input->post('jenis'));
        $tgl = html_purify($this->input->post('tgl'));

        //die(var_dump($jenis.' dan '.$tgl));
        switch ($jenis) {
            case 1:
                $data['jenis'] = "HAKIM";
                break;
            case 2:
                $data['jenis'] = "ASN";
                break;
            case 3:
                $data['jenis'] = "HONORER";
                break;
            case 4:
                $data['jenis'] = "CALON HAKIM";
                break;
        }

        //die(var_dump($data['jenis']));

        $data['presensi'] = $this->absen->pres_harian($jenis, $tgl);

        $this->load->view('halamanabsen/laporan_masuk', $data);
    }

    #################################################
    #   MENCETAK LAPORAN PRESENSI PULANG PEGAWAI    #
    #################################################
    public function cetak_laporan_pulang()
    {
        $jenis = html_purify($this->input->post('jenis'));
        $tgl = html_purify($this->input->post('tgl'));

        switch ($jenis) {
            case 1:
                $data['jenis'] = "HAKIM";
                break;
            case 2:
                $data['jenis'] = "ASN";
                break;
            case 3:
                $data['jenis'] = "HONORER";
                break;
            case 4:
                $data['jenis'] = "CALON HAKIM";
                break;
        }

        $data['presensi'] = $this->absen->pres_harian($jenis, $tgl);

        $this->load->view('halamanabsen/laporan_pulang', $data);
    }

    #########################################################################
    #   MENCETAK LAPORAN PRESENSI SELURUH PEGAWAI SESUAI TANGGAL PILIHAN    #
    #########################################################################
    public function cetak_laporan_semua()
    {
        $start_date = $this->input->post('tgl_awal');
        $end_date = $this->input->post('tgl_akhir');
        $peg = $this->input->post('pegawai');
        //die(var_dump($start_date.' ,'.$end_date));
        if ($peg != '') {
            $data['presensi'] = $this->absen->semua_presensi_peg($start_date, $end_date, $peg);
        } else {
            $data['presensi'] = $this->absen->semua_presensi($start_date, $end_date);
        }
        //die(var_dump($data));
        $this->load->view('halamanabsen/laporan_pegawai', $data);
        return;
    }

    ##############################################
    #                                            #
    #       FUNGSI-FUNGSI LAPORAN APEL           #
    #                                            #
    ##############################################


    public function laporan_apel()
    {
        $data['apel'] = $this->absen->register_apel();

        $this->load->view('halamanabsen/header');
        $this->load->view('halamanabsen/laporanapel/sidebar');
        $this->load->view('halamanabsen/laporanapel/lis_report', $data);
        $this->load->view('halamanabsen/footer');
    }

    public function detail_laporan_apel()
    {
        $tgl = $this->input->post('tgl');
        $data['tanggal'] = $tgl;
        $data['peserta'] = $this->absen->get_detail_presensi($tgl);

        $this->load->view('halamanabsen/header');
        $this->load->view('halamanabsen/laporanapel/sidebar');
        $this->load->view('halamanabsen/laporanapel/detail_report', $data);
        $this->load->view('halamanabsen/footer');
    }

    public function cetak_detail_laporan_apel()
    {
        $tgl = $this->input->post('tgl');
        $data['tanggal'] = $tgl;
        $data['peserta'] = $this->absen->get_detail_presensi($tgl);

        $this->load->view('halamanabsen/laporan_apel', $data);
    }

    ##############################################
    #                                            #
    #       FUNGSI-FUNGSI LAPORAN RAPAT          #
    #                                            #
    ##############################################

    public function show_pegawai()
    {
        $pegawai = $this->model->list_pengguna_aktif();
        $nama = array();
        $nama[''] = "-- Pilih Pegawai Penandatangan --";
        foreach ($pegawai->result() as $row) {
            $nama[$row->userid] = $row->fullname;
        }
        //die(var_dump($nama));

        $list = form_dropdown('pegawai', $nama, '', 'class="form-control show-tick" id="pegawai"');
        echo json_encode(
            array(
                'st' => 1,
                'pegawai' => $list,
            )
        );
        return;
    }

    public function laporan_rapat()
    {
        $data['rapat'] = $this->absen->register_rapat();

        $this->load->view('halamanabsen/header');
        $this->load->view('halamanabsen/laporanrapat/sidebar');
        $this->load->view('halamanabsen/laporanrapat/lis_report', $data);
        $this->load->view('halamanabsen/footer');
    }

    public function detail_laporan_rapat()
    {
        if ($this->session->flashdata('id')) {
            $id = $this->session->flashdata('id');
        } else {
            $id = $this->input->post('id');
        }
        $query = $this->absen->get_seleksi('register_rapat', 'id', $id);
        $data['id'] = $query->row()->id;
        $data['tanggal'] = $query->row()->tanggal;
        $data['agenda'] = $query->row()->agenda;
        $data['peserta'] = $this->absen->get_detail_presensi_rapat($id);

        $this->load->view('halamanabsen/header');
        $this->load->view('halamanabsen/laporanrapat/sidebar');
        $this->load->view('halamanabsen/laporanrapat/detail_report', $data);
        $this->load->view('halamanabsen/footer');
    }

    public function cetak_detail_laporan_rapat()
    {
        $this->form_validation->set_rules('pegawai', 'Pegawai Penandatangan', 'trim|required');
        $this->form_validation->set_message('required', '%s Belum Dipilih');

        if ($this->form_validation->run() == FALSE) {
            //echo json_encode(array('st' => 0, 'msg' => 'Tidak Berhasil:<br/>'.validation_errors()));
            $this->session->set_flashdata('info', '2');
            $this->session->set_flashdata('pesan_gagal', form_error('pegawai'));
            $this->session->set_flashdata('id', $this->input->post('id'));
            redirect('detail_rapat', validation_errors());
            return;
        }

        $id = $this->input->post('id');
        $pegawai = $this->input->post('pegawai');
        $query = $this->absen->get_seleksi('register_rapat', 'id', $id);
        $data['agenda'] = $query->row()->agenda;
        $data['tanggal'] = $query->row()->tanggal;
        $data['mulai'] = $query->row()->mulai;
        $data['selesai'] = $query->row()->selesai;
        $data['tempat'] = $query->row()->tempat;
        $data['peserta'] = $this->absen->get_detail_presensi_rapat($id);

        # Data Penandatangan
        $queryTTD = $this->absen->get_seleksi('v_users', 'userid', $pegawai);
        $data['nama_ttd'] = $queryTTD->row()->fullname;
        $data['jabatan'] = $queryTTD->row()->jabatan;
        $data['ttd'] = $queryTTD->row()->ttd;

        $this->load->view('halamanabsen/laporan_rapat', $data);
    }

    ##############################################
    #                                            #
    #       FUNGSI-FUNGSI LAPORAN KEGIATAN       #
    #                                            #
    ##############################################

    public function laporan_kegiatan()
    {
        $data['page'] = 'laporan_kegiatan';
        $data['kegiatan'] = $this->model->register_kegiatan();

        $this->load->view('halamanutama/header', $data);
        $this->load->view('halamanutama/sidebar');
        $this->load->view('halamanutama/kegiatan/laporan/lis_report');
        $this->load->view('halamanutama/footer');
    }

    public function laporan_detail_kegiatan()
    {
        $id = $this->encryption->decrypt(base64_decode($this->input->post('id')));
        $query = $this->model->get_seleksi('ref_kegiatan', 'id', $id);
        $data['id'] = $query->row()->id;
        $data['tanggal'] = $query->row()->tanggal;
        $data['kegiatan'] = $query->row()->nama_kegiatan;
        $data['peserta'] = $this->model->get_detail_presensi_kegiatan($id);
        $data['page'] = 'laporan_kegiatan';

        $this->load->view('halamanutama/header', $data);
        $this->load->view('halamanutama/sidebar');
        $this->load->view('halamanutama/kegiatan/laporan/detail_report');
        $this->load->view('halamanutama/footer');
    }

    public function cetak_detail_laporan_kegiatan()
    {
        $id = $this->encryption->decrypt(base64_decode($this->input->post('id')));
        $query = $this->model->get_seleksi('ref_kegiatan', 'id', $id);
        $data['nama_kegiatan'] = $query->row()->nama_kegiatan;
        $data['tanggal'] = $query->row()->tanggal;
        $data['peserta'] = $this->model->get_detail_presensi_kegiatan($id);

        $this->load->view('halamanutama/cetak_laporan_kegiatan', $data);
    }
}