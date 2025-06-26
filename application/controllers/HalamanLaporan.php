<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Config $config
 * @property CI_Input $input
 * @property CI_Model $model
 * @property CI_Encryption $encryption
 * @property CI_Session $session
 * @property CI_Form_validation $form_validation
 */

class HalamanLaporan extends MY_Controller
{
    ########################################
    #   LAPORAN PRESENSI HARIAN PRIBADI    #
    ########################################

    public function index()
    {
        $data['presensi'] = $this->model->all_pres_pengguna($this->session->userdata("userid"));
        $data['page'] = 'laporan_harian';
        $this->session->set_userdata("tanggal", date("Y-m"));
        if ($this->session->userdata('super') || in_array($this->session->userdata('peran'), ['validator', 'petugas'])) {
            $data['peran'] = 'admin';
        } else {
            $data['peran'] = '';
        }

        $this->load->view('halamanlaporan/header', $data);
        $this->load->view('halamanlaporan/sidebar');
        $this->load->view('halamanlaporan/harian/lis_report');
        $this->load->view('halamanlaporan/footer');
    }

    #############################################################
    #   LAPORAN PRESENSI HARIAN PRIBADI SESUAI BULAN PILIHAN    #
    #############################################################

    public function laporan_bulan()
    {
        $this->form_validation->set_rules('bulan', 'Bulan', 'trim|required');
        $this->form_validation->set_message(['required' => '%s Tidak Boleh Kosong']);

        if ($this->form_validation->run() == FALSE) {
            //echo json_encode(array('st' => 0, 'msg' => 'Tidak Berhasil:<br/>'.validation_errors()));
            $this->session->set_flashdata('info', '2');
            $this->session->set_flashdata('pesan_gagal', form_error('bulan'));
            redirect('laporan', validation_errors());
            return;
        }

        $month = $this->input->post('bulan');
        $bulan = DateTime::createFromFormat('F Y', $month);
        $konversi_bulan = $bulan->format('Y-m');
        $bulan_fix = $konversi_bulan . '-00';
        $this->session->set_userdata("tanggal", $konversi_bulan);

        $data['presensi'] = $this->model->all_pres_pengguna_bulan($this->session->userdata("userid"), $bulan_fix);
        $data['page'] = 'laporan_harian';
        if ($this->session->userdata('super') || in_array($this->session->userdata('peran'), ['validator', 'petugas'])) {
            $data['peran'] = 'admin';
        } else {
            $data['peran'] = '';
        }

        $this->session->set_flashdata('info', '1');
        $this->session->set_flashdata('pesan_sukses', "Data ditemukan");

        $this->load->view('halamanlaporan/header', $data);
        $this->load->view('halamanlaporan/sidebar');
        $this->load->view('halamanlaporan/harian/lis_report');
        $this->load->view('halamanlaporan/footer');
    }

    #############################################
    #   LAPORAN PRESENSI HARIAN SATUAN KERJA    #
    #############################################
    public function laporan_satker()
    {
        $data['hakim'] = $this->model->pres_hakim_now();
        $data['cakim'] = $this->model->pres_cakim_now();
        $data['pns'] = $this->model->pres_pns_now();
        $data['pppk'] = $this->model->pres_pppk_now();
        $data['honor'] = $this->model->pres_honor_now();
        $data['page'] = 'laporan_satker';
        //die(var_dump($data));

        if ($this->session->userdata('super') || in_array($this->session->userdata('peran'), ['validator'])) {
            $data['peran'] = 'admin';
        } elseif ($this->session->userdata('peran') == 'petugas') {
            $data['peran'] = 'petugas';
        } else {
            $this->session->set_flashdata('info', '2');
            $this->session->set_flashdata('pesan_gagal', 'Anda tidak memiliki akses');
            redirect('laporan');
        }

        $this->session->set_userdata("tanggal", date("Y-m-d"));

        $this->load->view('halamanlaporan/header', $data);
        $this->load->view('halamanlaporan/sidebar');
        $this->load->view('halamanlaporan/satker/lis_report');
        $this->load->view('halamanlaporan/footer');
    }

    ####################################################################
    #   LAPORAN PRESENSI HARIAN SATUAN KERJA SESUAI TANGGAL PILIHAN    #
    ####################################################################

    public function laporan_satker_bulan()
    {
        $this->form_validation->set_rules('bulan', 'Tanggal', 'trim|required');
        $this->form_validation->set_message(['required' => '%s Tidak Boleh Kosong']);

        if ($this->form_validation->run() == FALSE) {
            //echo json_encode(array('st' => 0, 'msg' => 'Tidak Berhasil:<br/>'.validation_errors()));
            $this->session->set_flashdata('info', '2');
            $this->session->set_flashdata('pesan_gagal', form_error('bulan'));
            redirect('laporan_satker', validation_errors());
            return;
        }

        if ($this->session->userdata('super') || in_array($this->session->userdata('peran'), ['validator', 'petugas'])) {
            $data['peran'] = 'admin';
        } else {
            $this->session->set_flashdata('info', '2');
            $this->session->set_flashdata('pesan_gagal', 'Anda tidak memiliki akses');
            redirect('laporan');
        }

        $month = $this->input->post('bulan');
        $bulan = DateTime::createFromFormat('d F Y', $month);
        $konversi_bulan = $bulan->format('Y-m-d');
        //die(var_dump($konversi_bulan));
        $this->session->set_userdata("tanggal", $konversi_bulan);

        $data['hakim'] = $this->model->pres_hakim_bulan($konversi_bulan);
        $data['cakim'] = $this->model->pres_cakim_bulan($konversi_bulan);
        $data['pns'] = $this->model->pres_pns_bulan($konversi_bulan);
        $data['pppk'] = $this->model->pres_pppk_bulan($konversi_bulan);
        $data['honor'] = $this->model->pres_honor_bulan($konversi_bulan);
        $data['page'] = 'laporan_satker';

        $this->session->set_flashdata('info', '1');
        $this->session->set_flashdata('pesan_sukses', "Data ditemukan");

        $this->load->view('halamanlaporan/header', $data);
        $this->load->view('halamanlaporan/sidebar');
        $this->load->view('halamanlaporan/satker/lis_report');
        $this->load->view('halamanlaporan/footer');
    }

    ############################################################################
    #   MENAMPILKAN DATA PRESENSI PADA MODAL KETIKA MEMBUKA DETAIL PRESENSI    #
    ############################################################################

    public function edit()
    {
        $id = $this->encryption->decrypt(base64_decode($this->input->post('id')));
        $userid = $this->encryption->decrypt(base64_decode($this->input->post('userid')));

        $params = [
            'tabel' => 'v_users',
            'kolom_seleksi' => 'userid',
            'seleksi' => $userid
        ];

        $result = $this->apihelper->get('apiclient/get_data_seleksi', $params);

        if ($result['status_code'] === 200 && $result['response']['status'] === 'success') {
            $user_data = $result['response']['data'][0];
            $nama = $user_data['fullname'];
        }

        $ket = array('' => "-- Keterangan --", 'Manual Presensi' => 'Manual Presensi', 'Tidak Presensi' => 'Tidak Presensi', 'Izin Terlambat Masuk' => 'Izin Terlambat Masuk', 'Izin Tidak Masuk *Hakim' => 'Izin Tidak Masuk *Hakim', 'Piket Malam' => 'Piket Malam', 'Work From Home' => 'Work From Home', 'Tanpa Keterangan' => 'Tanpa Keterangan', 'Dinas Luar' => 'Dinas Luar', 'Cuti Tahunan' => 'Cuti Tahunan', 'Cuti Sakit' => 'Cuti Sakit', 'Cuti Melahirkan' => 'Cuti Melahirkan', 'Cuti Alasan Penting' => 'Cuti Alasan Penting', 'Cuti Besar' => 'Cuti Besar', 'Libur' => 'Libur');

        if ($id == "-1") {
            $id = "";
            $masuk = "";
            $pulang = "";
            $ktrngn = form_dropdown('ket', $ket, '', 'class="form-control show-tick"  id="ket"');
        } else {
            $cariPresensi = $this->model->get_presensi_data($id);
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

    public function show_all_pegawai()
    {
        $params = [
            'tabel' => 'v_users',
            'kolom_seleksi' => 'status_pegawai',
            'seleksi' => '1'
        ];

        $result = $this->apihelper->get('apiclient/get_data_seleksi', $params);

        if ($result['status_code'] === 200 && $result['response']['status'] === 'success') {
            $user_data = $result['response']['data'];
            $nama = array();
            $nama[''] = "-- Tampilkan Seluruh Pegawai --";
            foreach ($user_data as $row) {
                $nama[$row['userid']] = $row['fullname'];
            }
        }

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
        $id = $this->input->post('id');
        $userid = $this->input->post('userid');
        $tgl = $this->input->post('tgl');
        if ($this->input->post('masuk')) {
            $masuk = $this->input->post('masuk');
        } else {
            $masuk = NULL;
        }

        if ($this->input->post('pulang')) {
            $pulang = $this->input->post('pulang');
        } else {
            $pulang = NULL;
        }

        if ($this->input->post('ket')) {
            $ket = $this->input->post('ket');
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

            $querySimpan = $this->model->update_presensi($dataPengguna, $id);

        } else {
            $dataPengguna = array(
                'tgl' => $tgl,
                'userid' => $userid,
                'masuk' => $masuk,
                'pulang' => $pulang,
                'ket' => $ket,
                'created_on' => date('Y-m-d H:i:s')
            );

            $querySimpan = $this->model->simpan_presensi($dataPengguna);
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
        $jenis = $this->input->post('jenis');
        $tgl = $this->input->post('tgl');

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

        $data['presensi'] = $this->model->presensi_harian($jenis, $tgl);

        $this->load->view('halamanlaporan/laporan_masuk', $data);
    }

    #################################################
    #   MENCETAK LAPORAN PRESENSI PULANG PEGAWAI    #
    #################################################
    public function cetak_laporan_pulang()
    {
        $jenis = $this->input->post('jenis');
        $tgl = $this->input->post('tgl');

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

        $data['presensi'] = $this->model->presensi_harian($jenis, $tgl);

        $this->load->view('halamanlaporan/laporan_pulang', $data);
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
            $data['presensi'] = $this->model->semua_presensi_peg($start_date, $end_date, $peg);
        } else {
            $data['presensi'] = $this->model->semua_presensi($start_date, $end_date);
        }
        //die(var_dump($data));
        $this->load->view('halamanlaporan/laporan_pegawai', $data);
        return;
    }

    ##############################################
    #                                            #
    #       FUNGSI-FUNGSI LAPORAN APEL           #
    #                                            #
    ##############################################


    public function laporan_apel()
    {
        $data['apel'] = $this->model->register_apel();
        $data['page'] = 'laporan_apel';

        if ($this->session->userdata('super') || in_array($this->session->userdata('peran'), ['validator', 'petugas'])) {
            $data['peran'] = 'admin';
        } else {
            $this->session->set_flashdata('info', '2');
            $this->session->set_flashdata('pesan_gagal', 'Anda tidak memiliki akses');
            redirect('laporan');
        }

        $this->load->view('halamanlaporan/header', $data);
        $this->load->view('halamanlaporan/sidebar');
        $this->load->view('halamanlaporan/apel/lis_report');
        $this->load->view('halamanlaporan/footer');
    }

    public function laporan_apel_detail()
    {
        $tgl = $this->input->post('tgl');
        $data['tanggal'] = $tgl;
        $data['peserta'] = $this->model->get_detail_presensi($tgl);
        $data['page'] = 'laporan_apel';

        if ($this->session->userdata('super') || in_array($this->session->userdata('peran'), ['validator', 'petugas'])) {
            $data['peran'] = 'admin';
        } else {
            $this->session->set_flashdata('info', '2');
            $this->session->set_flashdata('pesan_gagal', 'Anda tidak memiliki akses');
            redirect('laporan');
        }

        $this->load->view('halamanlaporan/header', $data);
        $this->load->view('halamanlaporan/sidebar');
        $this->load->view('halamanlaporan/apel/detail_report');
        $this->load->view('halamanlaporan/footer');
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

        if ($this->session->userdata('super') || in_array($this->session->userdata('peran'), ['validator', 'petugas'])) {
            $data['peran'] = 'admin';
        } else {
            $this->session->set_flashdata('info', '2');
            $this->session->set_flashdata('pesan_gagal', 'Anda tidak memiliki akses');
            redirect('laporan');
        }

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

        if ($this->session->userdata('super') || in_array($this->session->userdata('peran'), ['validator', 'petugas'])) {
            $data['peran'] = 'admin';
        } else {
            $this->session->set_flashdata('info', '2');
            $this->session->set_flashdata('pesan_gagal', 'Anda tidak memiliki akses');
            redirect('laporan');
        }

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

        if ($this->session->userdata('super') || in_array($this->session->userdata('peran'), ['validator', 'petugas'])) {
            $data['peran'] = 'admin';
        } else {
            $this->session->set_flashdata('info', '2');
            $this->session->set_flashdata('pesan_gagal', 'Anda tidak memiliki akses');
            redirect('laporan');
        }

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

        if ($this->session->userdata('super') || in_array($this->session->userdata('peran'), ['validator', 'petugas'])) {
            $data['peran'] = 'admin';
        } else {
            $this->session->set_flashdata('info', '2');
            $this->session->set_flashdata('pesan_gagal', 'Anda tidak memiliki akses');
            redirect('laporan');
        }

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