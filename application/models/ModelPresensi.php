<?php defined('BASEPATH') or exit('No direct script access allowed');

class ModelPresensi extends CI_Model
{
    private function add_audittrail($action, $title, $table, $descrip)
    {
        try {
            $data = array(
                'datetime' => date("Y-m-d H:i:s"),
                'ipaddress' => $this->input->ip_address(),
                'username' => $this->session->userdata('username'),
                'tablename' => $table,
                'action' => $action,
                'title' => $title,
                'description' => $descrip
            );
            $this->db->insert('sys_audittrail', $data);
        } catch (Exception $e) {

        }
    }

    private function fetch_description($title, $data)
    {
        $descrip = '<br><table style="vertical-align:top" cellspacing="0" cellpadding="1" border="1">';
        $descrip .= '<tr><th>Nama Kolom</th><th>Nilai</th></tr>';
        foreach ($data as $key => $value) {
            $descrip .= '<tr>';
            $descrip .= '<td>' . $key . '</td>';
            $descrip .= '<td>' . $value . '</td>';
            $descrip .= '</tr>';
        }
        $descrip .= '</table>';
        return $descrip;
    }

    public function all_kegiatan()
    {
        $this->db->select('id, nama_kegiatan, tanggal')
            ->from('ref_kegiatan')
            ->where('hapus', '0')
            ->order_by('tanggal', 'DESC');

        return $this->db->get()->result();
    }

    public function get_seleksi($tabel, $kolom_seleksi, $seleksi)
    {
        try {
            $this->db->where($kolom_seleksi, $seleksi);
            return $this->db->get($tabel);
        } catch (Exception $e) {
            return 0;
        }
    }

    public function get_list_rapat($tgl)
    {
        try {
            $this->db->order_by('tanggal', 'DESC');
            $this->db->where('tanggal', $tgl);
            return $this->db->get('register_rapat');
        } catch (Exception $e) {
            return $e;
        }
    }

    public function get_lis_kegiatan()
    {
        try {
            $this->db->order_by('tanggal', 'DESC');
            return $this->db->get('ref_kegiatan');
        } catch (Exception $e) {
            return $e;
        }
    }

    public function get_list_kegiatan_lain($tgl)
    {
        try {
            $this->db->order_by('tanggal', 'DESC');
            $this->db->where('tanggal >=', $tgl);
            return $this->db->get('ref_kegiatan');
        } catch (Exception $e) {
            return $e;
        }
    }

    public function get_presensi_pengguna_hari_ini($id)
    {
        try {
            $this->db->where('tgl', date('Y-m-d'));
            $this->db->where('userid', $id);
            return $this->db->get('reg_presensi')->row();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function get_presensi_pengguna_kemarin($id)
    {
        try {
            $this->db->where('tgl', date('Y-m-d', strtotime("-1 days")));
            $this->db->where('userid', $id);
            return $this->db->get('reg_presensi')->row();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function get_presensi_apel($id)
    {
        try {
            $this->db->where('DATE(created_on)', date('Y-m-d'));
            $this->db->where('userid', $id);
            return $this->db->get('reg_presensi_apel')->row();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function get_presensi_rapat($userid, $idrapat)
    {
        try {
            $this->db->where('idrapat', $idrapat);
            $this->db->where('userid', $userid);
            return $this->db->get('reg_presensi_rapat')->row();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function get_presensi_kegiatan($userid, $idkegiatan)
    {
        try {
            $this->db->where('idkegiatan', $idkegiatan);
            $this->db->where('userid', $userid);
            return $this->db->get('reg_presensi_lainnya')->row();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function register_kegiatan()
    {
        $this->db->select('k.id AS id_kegiatan, k.nama_kegiatan, k.tanggal, COUNT(l.userid) AS total');
        $this->db->from('ref_kegiatan k');
        $this->db->join('reg_presensi_lainnya l', 'k.id = l.idkegiatan', 'left');
        $this->db->group_by('k.id');
        $this->db->order_by('k.tanggal', 'DESC');
        $query = $this->db->get();

        return $query->result();
    }

     public function get_detail_presensi($tgl)
    {
        $this->db->select('u.nip AS nip, u.fullname AS nama, u.jabatan AS jabatan, TIME(p.created_on) AS waktu');
        $this->db->where('u.userid = p.userid AND DATE(p.created_on) = "' . $tgl . '"');
        $this->db->order_by('TIME(p.created_on)', 'ASC');
        $query = $this->db->get('v_users u, reg_presensi_apel p');

        return $query->result();
    }

    public function get_detail_presensi_rapat($id)
    {
        $this->db->select('u.nip AS nip, u.fullname AS nama, u.jabatan AS jabatan, TIME(p.created_on) AS waktu');
        $this->db->where('u.userid = p.userid AND p.idrapat = "' . $id . '"');
        $this->db->order_by('u.id_grup, u.jab_id', 'ASC');
        $query = $this->db->get('v_users u, reg_presensi_rapat p');

        return $query->result();
    }

    public function get_detail_presensi_kegiatan($id)
    {
        $this->db->select('u.nip AS nip, u.fullname AS nama, u.jabatan AS jabatan, TIME(l.created_on) AS waktu');
        $this->db->where('u.userid = l.userid AND l.idkegiatan = "' . $id . '"');
        $this->db->order_by('TIME(l.created_on)', 'ASC');
        $query = $this->db->get('v_users u, reg_presensi_lainnya l');

        return $query->result();
    }

    #################################
    #    SIMPAN PRESENSI (START)    #
    #################################

    public function simpan_masuk($data, $id)
    {
        $table = 'reg_presensi';
        try {
            $this->db->insert($table, $data);
            $title = "Update Jam Masuk Presensi Pegawai [No Id=<b>" . $id . "</b>]<br />Update jam masuk <b>reg_presensi</b>]";
            $descrip = null;
            $this->add_audittrail("UPDATE", $title, $table, $descrip);
            return 1;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function simpan_pulang($data, $id)
    {
        $table = 'reg_presensi';
        try {
            $this->db->where('id', $id);
            $this->db->update($table, $data);
            $title = "Update Jam Pulang Presensi Pegawai [No Id=<b>" . $id . "</b>]<br />Update jam pulang <b>reg_presensi</b>]";
            $descrip = null;
            $this->add_audittrail("UPDATE", $title, $table, $descrip);
            return 1;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function simpan_apel($data, $id)
    {
        $table = 'reg_presensi_apel';
        try {
            $this->db->insert($table, $data);
            $title = "Update Presensi Apel Pegawai [No Id=<b>" . $id . "</b>]<br />Update apel <b>reg_presensi_apel</b>]";
            $descrip = null;
            $this->add_audittrail("UPDATE", $title, $table, $descrip);
            return 1;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function simpan_rapat($data, $id)
    {
        $table = 'reg_presensi_rapat';
        try {
            $this->db->insert($table, $data);
            $title = "Update Presensi Rapat Pegawai [User id=<b>" . $id . "</b>]<br />Update rapat <b>reg_presensi_rapat</b>]";
            $descrip = null;
            $this->add_audittrail("UPDATE", $title, $table, $descrip);
            return 1;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function simpan_kegiatan($data, $id)
    {
        $table = 'reg_presensi_lainnya';
        try {
            $this->db->insert($table, $data);
            $title = "Update Presensi Kegiatan Lain Pegawai [User id=<b>" . $id . "</b>]<br />Update kegiatan lain <b>reg_presensi_lainnya</b>]";
            $descrip = null;
            $this->add_audittrail("UPDATE", $title, $table, $descrip);
            return 1;
        } catch (Exception $e) {
            return $e;
        }
    }

    #################################
    #     SIMPAN PRESENSI (END)     #
    #################################

    public function update_perangkat_user($data, $id)
    {
        $table = 'sys_users';
        try {
            $this->db->where('userid', $id);
            $this->db->update($table, $data);
            $title = "Update Perangkat Presensi Pegawai [No Id=<b>" . $id . "</b>]<br />Update tabel <b>sys_users</b>]";
            $descrip = null;
            $this->add_audittrail("UPDATE", $title, $table, $descrip);
            return 1;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function get_konfigurasi($id)
    {
        try {
            $this->db->where('id', $id);
            return $this->db->get('sys_config');
        } catch (Exception $e) {
            return 0;
        }
    }

    public function simpan_data($tabel, $data)
    {
        try {
            $this->db->insert($tabel, $data);
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function pembaharuan_data($tabel, $data, $kolom_seleksi, $seleksi)
    {
        try {
            $this->db->where($kolom_seleksi, $seleksi);
            $this->db->update($tabel, $data);
            $title = "Pembaharuan Data <br />Update tabel <b>" . $tabel . "</b>[Pada kolom<b>" . $kolom_seleksi . "</b>]";
            $descrip = null;
            $this->add_audittrail("UPDATE", $title, $tabel, $descrip);
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }
}