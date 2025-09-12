<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HalamanKegiatan extends MY_Controller
{
    public function index()
    {
        $data['kegiatan'] = $this->model->all_kegiatan();
        $data['page'] = 'kegiatan';
        $data['peran'] = $this->session->userdata('peran');

        $this->load->view('halamanutama/header', $data);
        $this->load->view('halamanutama/sidebar');
        $this->load->view('halamanutama/kegiatan/lis_kegiatan');
        $this->load->view('halamanutama/footer');
    }

    public function show()
    {
        $id = $this->encryption->decrypt(base64_decode($this->input->post('id')));

        $query = $this->model->get_lis_kegiatan();
        $kegiatan = array();
        $kegiatan[''] = "-- Pilih Kegiatan --";
        foreach ($query->result() as $row) {
            $kegiatan[$row->id] = $row->nama_kegiatan;
        }

        $nama_kegiatan = "";
        $tanggal = "";

        if ($id == '-1') {
            $judul = "TAMBAH DATA KEGIATAN";
            $bulan = DateTime::createFromFormat('Y-m-d', date("Y-m-d"));
            //die(var_dump($bulan));
            $konversi_tgl = $bulan->format('d F Y');
        } else {
            $judul = "EDIT DATA KEGIATAN";
            $queryKegiatan = $this->model->get_seleksi('ref_kegiatan', 'id', $id);
            $nama_kegiatan = $queryKegiatan->row()->nama_kegiatan;
            $bulan = DateTime::createFromFormat('Y-m-d', $queryKegiatan->row()->tanggal);
            //die(var_dump($bulan));
            $konversi_tgl = $bulan->format('d F Y');
        }

        echo json_encode(
            array(
                'st' => 1,
                'id' => $id,
                'tgl' => $konversi_tgl,
                'judul' => $judul,
                'nama_kegiatan' => $nama_kegiatan
            )
        );
        return;
    }

    public function simpan_kegiatan()
    {
        $this->form_validation->set_rules('nama_kegiatan', 'Nama Kegiatan', 'trim|required');
        $this->form_validation->set_rules('tgl', 'Nama Kegiatan', 'trim|required');
        $this->form_validation->set_message(['required' => '%s Harus Diisi']);

        if ($this->form_validation->run() == FALSE) {
            //echo json_encode(array('st' => 0, 'msg' => 'Tidak Berhasil:<br/>'.validation_errors()));
            $this->session->set_flashdata('info', '3');
            $this->session->set_flashdata('pesan_gagal', 'Gagal Simpan Kegiatan, ' . form_error('nama_kegiatan') . ' ' . form_error('tgl'));
            redirect('kegiatan', validation_errors());
            return;
        }

        $id = $this->input->post('id');
        $nama_kegiatan = $this->input->post('nama_kegiatan');
        $tgl = $this->input->post('tgl');
        $bulan = DateTime::createFromFormat('d F Y', $tgl);
        $konversi_tgl = $bulan->format('Y-m-d');

        if ($id == "-1") {
            //buat agenda rapat baru
            $dataKegiatan = array(
                "tanggal" => $konversi_tgl,
                "nama_kegiatan" => $nama_kegiatan,
                "created_on" => date('Y-m-d H:i:s')
            );

            $queryKegiatan = $this->model->simpan_data('ref_kegiatan', $dataKegiatan);
        } else {
            $dataKegiatan = array(
                "tanggal" => $konversi_tgl,
                "nama_kegiatan" => $nama_kegiatan
            );

            $queryKegiatan = $this->model->pembaharuan_data('ref_kegiatan', $dataKegiatan, 'id', $id);
        }

        if ($queryKegiatan == 1) {
            $this->session->set_flashdata('info', '1');
            if ($id == '-1') {
                $this->session->set_flashdata('pesan_sukses', 'Kegiatan Berhasil di Tambahkan');
            } else {
                $this->session->set_flashdata('pesan_sukses', 'Kegiatan Berhasil di Perbarui');
            }
        } else {
            $this->session->set_flashdata('info', '3');
            $this->session->set_flashdata('pesan_gagal', 'Gagal Simpan Kegiatan, ' . $queryKegiatan);
        }

        redirect('kegiatan');
    }

    public function hapus_kegiatan()
    {
        $id = $this->encryption->decrypt(base64_decode($this->input->post('id')));
        $data = array(
            'modified_by' => $this->session->userdata('fullname'),
            'modified_on' => date('Y-m-d H:i:s'),
            'hapus' => '1'
        );

        $query = $this->model->pembaharuan_data('ref_kegiatan', $data, 'id', $id);

        if ($query == 1) {
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
}