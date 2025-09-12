<?php defined('BASEPATH') or exit('No direct script access allowed');

class ModelPresensi extends CI_Model
{
    private $db_sso;

    public function __construct()
    {
        parent::__construct();

        // Inisialisasi variabel private dengan nilai dari session
        $this->db_sso = $this->session->userdata('sso_db');
    }

    private function add_audittrail($action, $title, $table, $descrip)
    {
        $params = [
            'tabel' => 'sys_audittrail',
            'data' => [
                'datetime' => date("Y-m-d H:i:s"),
                'ipaddress' => $this->input->ip_address(),
                'action' => $action,
                'title' => $title,
                'tablename' => $table,
                'description' => $descrip,
                'username' => $this->session->userdata('username')
            ]
        ];

        $this->apihelper->post('apiclient/simpan_data', $params);
    }

    public function cek_aplikasi($id)
    {
        $params = [
            'tabel' => 'ref_client_app',
            'kolom_seleksi' => 'id',
            'seleksi' => $id
        ];

        $result = $this->apihelper->get('apiclient/get_data_seleksi', $params);

        if ($result['status_code'] === 200 && $result['response']['status'] === 'success') {
            $user_data = $result['response']['data'][0];
            $this->session->set_userdata(
                [
                    'nama_client_app' => $user_data['nama_app'],
                    'deskripsi_client_app' => $user_data['deskripsi']
                ]
            );
        }
    }

    public function all_pres_pengguna($id)
    {
        // Subquery to generate the dates for the current month
        $subquery = "(SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(CONCAT(YEAR(NOW()), '-', MONTH(NOW()), '-', n)), '%Y-%m-%d') AS Date 
                  FROM (SELECT (((b4.0 << 1 | b3.0) << 1 | b2.0) << 1 | b1.0) << 1 | b0.0 AS n 
                        FROM (SELECT 0 UNION ALL SELECT 1) AS b0, 
                             (SELECT 0 UNION ALL SELECT 1) AS b1, 
                             (SELECT 0 UNION ALL SELECT 1) AS b2, 
                             (SELECT 0 UNION ALL SELECT 1) AS b3, 
                             (SELECT 0 UNION ALL SELECT 1) AS b4 ) t 
                  WHERE n > 0 AND n <= DAY(LAST_DAY(NOW()))
                  ORDER BY Date)";

        // Join the subquery with the register_presensi table
        $this->db->select('DateTable.Date, register_presensi.*')
            ->from("($subquery) AS DateTable", false)
            ->join('register_presensi', 'DateTable.Date = DATE(register_presensi.tgl) AND register_presensi.userid = ' . $this->db->escape($id), 'left')
            ->where('DateTable.Date IS NOT NULL')
            ->order_by('DateTable.Date');

        return $this->db->get()->result();
    }

    public function all_pres_pengguna_bulan($id, $bulan)
    {
        // Subquery to generate the dates for the given month
        $subquery = "(SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(CONCAT(YEAR('$bulan'), '-', MONTH('$bulan'), '-', n)), '%Y-%m-%d') AS Date 
                  FROM (SELECT (((b4.0 << 1 | b3.0) << 1 | b2.0) << 1 | b1.0) << 1 | b0.0 AS n 
                        FROM (SELECT 0 UNION ALL SELECT 1) AS b0, 
                             (SELECT 0 UNION ALL SELECT 1) AS b1, 
                             (SELECT 0 UNION ALL SELECT 1) AS b2, 
                             (SELECT 0 UNION ALL SELECT 1) AS b3, 
                             (SELECT 0 UNION ALL SELECT 1) AS b4 ) t 
                  WHERE n > 0 AND n <= DAY(LAST_DAY('$bulan')) 
                  ORDER BY Date)";

        // Join the subquery with the register_presensi table
        $this->db->select('DateTable.Date, register_presensi.*')
            ->from("($subquery) AS DateTable", false)
            ->join('register_presensi', 'DateTable.Date = DATE(register_presensi.tgl) AND register_presensi.userid = ' . $this->db->escape($id), 'left')
            ->where('DateTable.Date IS NOT NULL')
            ->order_by('DateTable.Date');

        return $this->db->get()->result();
    }

    public function presensi_harian($jenis, $tgl)
    {
        return $this->db
            ->select("u.userid AS userid, u.nip AS nip, u.fullname AS nama, u.jab_id AS jab_id, u.jabatan AS jabatan, u.pegawai_id AS pegawai_id, u.fullname AS fullname, u.status_pegawai AS status, p.id AS id_presensi, p.tgl AS tgl, p.masuk AS masuk, p.pulang AS pulang, p.ket AS ket, u.id_grup AS grup_jabatan, CURDATE() AS tgl_now")
            ->from($this->db_sso . ".v_users u")
            ->join("register_presensi p", "u.userid = p.userid AND p.tgl = " . $this->db->escape($tgl), "left")
            ->where("u.id_grup", $jenis)
            ->where("u.status_pegawai", 1)
            ->get()
            ->result();
    }

    public function pres_hakim_now()
    {
        return $this->db
            ->select("u.userid AS userid, u.jab_id AS jab_id, u.pegawai_id AS pegawai_id, u.fullname AS fullname, u.status_pegawai AS status, p.id AS id_presensi, p.tgl AS tgl, p.masuk AS masuk, p.pulang AS pulang, p.ket AS ket, u.id_grup AS grup_jabatan, CURDATE() AS tgl_now")
            ->from($this->db_sso . ".v_users u")
            ->join("register_presensi p", "u.userid = p.userid AND p.tgl = CURDATE()", "left")
            ->where("u.id_grup", 1)
            ->where("u.status_pegawai", 1)
            ->get()
            ->result();
    }

    public function pres_cakim_now()
    {
        return $this->db
            ->select("u.userid AS userid, u.jab_id AS jab_id, u.pegawai_id AS pegawai_id, u.fullname AS fullname, u.status_pegawai AS status, p.id AS id_presensi, p.tgl AS tgl, p.masuk AS masuk, p.pulang AS pulang, p.ket AS ket, u.id_grup AS grup_jabatan, CURDATE() AS tgl_now")
            ->from($this->db_sso . ".v_users u")
            ->join("register_presensi p", "u.userid = p.userid AND p.tgl = CURDATE()", "left")
            ->where("u.id_grup", 4)
            ->where("u.status_pegawai", 1)
            ->get()
            ->result();
    }

    public function pres_pns_now()
    {
        return $this->db
            ->select("u.userid AS userid, u.jab_id AS jab_id, u.pegawai_id AS pegawai_id, u.fullname AS fullname, u.status_pegawai AS status, p.id AS id_presensi, p.tgl AS tgl, p.masuk AS masuk, p.pulang AS pulang, p.ket AS ket, u.id_grup AS grup_jabatan, CURDATE() AS tgl_now")
            ->from($this->db_sso . ".v_users u")
            ->join("register_presensi p", "u.userid = p.userid AND p.tgl = CURDATE()", "left")
            ->where("u.id_grup", 2)
            ->where("u.status_pegawai", 1)
            ->get()
            ->result();
    }

    public function pres_pppk_now()
    {
        return $this->db
            ->select("u.userid AS userid, u.jab_id AS jab_id, u.pegawai_id AS pegawai_id, u.fullname AS fullname, u.status_pegawai AS status, p.id AS id_presensi, p.tgl AS tgl, p.masuk AS masuk, p.pulang AS pulang, p.ket AS ket, u.id_grup AS grup_jabatan, CURDATE() AS tgl_now")
            ->from($this->db_sso . ".v_users u")
            ->join("register_presensi p", "u.userid = p.userid AND p.tgl = CURDATE()", "left")
            ->where("u.id_grup", 6)
            ->where("u.status_pegawai", 1)
            ->get()
            ->result();
    }

    public function pres_honor_now()
    {
        return $this->db
            ->select("u.userid AS userid, u.jab_id AS jab_id, u.pegawai_id AS pegawai_id, u.fullname AS fullname, u.status_pegawai AS status, p.id AS id_presensi, p.tgl AS tgl, p.masuk AS masuk, p.pulang AS pulang, p.ket AS ket, u.id_grup AS grup_jabatan, CURDATE() AS tgl_now")
            ->from($this->db_sso . ".v_users u")
            ->join("register_presensi p", "u.userid = p.userid AND p.tgl = CURDATE()", "left")
            ->where("u.id_grup", 3)
            ->where("u.status_pegawai", 1)
            ->get()
            ->result();
    }

    public function pres_hakim_bulan($tgl)
    {
        return $this->db
            ->select("u.userid AS userid, u.jab_id, u.pegawai_id AS pegawai_id, u.fullname AS fullname, u.status_pegawai AS status, p.id AS id_presensi, p.tgl AS tgl, p.masuk AS masuk, p.pulang AS pulang, p.ket AS ket, u.id_grup AS grup_jabatan")
            ->from($this->db_sso . ".v_users u")
            ->join("register_presensi p", "u.userid = p.userid AND p.tgl = " . $this->db->escape($tgl), "left")
            ->where("u.id_grup", 1)
            ->where("u.status_pegawai", 1)
            ->get()
            ->result();
    }

    public function pres_cakim_bulan($tgl)
    {
        return $this->db
            ->select("u.userid AS userid, u.jab_id, u.pegawai_id AS pegawai_id, u.fullname AS fullname, u.status_pegawai AS status, p.id AS id_presensi, p.tgl AS tgl, p.masuk AS masuk, p.pulang AS pulang, p.ket AS ket, u.id_grup AS grup_jabatan")
            ->from($this->db_sso . ".v_users u")
            ->join("register_presensi p", "u.userid = p.userid AND p.tgl = " . $this->db->escape($tgl), "left")
            ->where("u.id_grup", 4)
            ->where("u.status_pegawai", 1)
            ->get()
            ->result();
    }

    public function pres_pns_bulan($tgl)
    {
        return $this->db
            ->select("u.userid AS userid, u.jab_id, u.pegawai_id AS pegawai_id, u.fullname AS fullname, u.status_pegawai AS status, p.id AS id_presensi, p.tgl AS tgl, p.masuk AS masuk, p.pulang AS pulang, p.ket AS ket, u.id_grup AS grup_jabatan")
            ->from($this->db_sso . ".v_users u")
            ->join("register_presensi p", "u.userid = p.userid AND p.tgl = " . $this->db->escape($tgl), "left")
            ->where("u.id_grup", 2)
            ->where("u.status_pegawai", 1)
            ->get()
            ->result();
    }

    public function pres_pppk_bulan($tgl)
    {
        return $this->db
            ->select("u.userid AS userid, u.jab_id, u.pegawai_id AS pegawai_id, u.fullname AS fullname, u.status_pegawai AS status, p.id AS id_presensi, p.tgl AS tgl, p.masuk AS masuk, p.pulang AS pulang, p.ket AS ket, u.id_grup AS grup_jabatan")
            ->from($this->db_sso . ".v_users u")
            ->join("register_presensi p", "u.userid = p.userid AND p.tgl = " . $this->db->escape($tgl), "left")
            ->where("u.id_grup", 6)
            ->where("u.status_pegawai", 1)
            ->get()
            ->result();
    }

    public function pres_honor_bulan($tgl)
    {
        return $this->db
            ->select("u.userid AS userid, u.jab_id, u.pegawai_id AS pegawai_id, u.fullname AS fullname, u.status_pegawai AS status, p.id AS id_presensi, p.tgl AS tgl, p.masuk AS masuk, p.pulang AS pulang, p.ket AS ket, u.id_grup AS grup_jabatan")
            ->from($this->db_sso . ".v_users u")
            ->join("register_presensi p", "u.userid = p.userid AND p.tgl = " . $this->db->escape($tgl), "left")
            ->where("u.id_grup", 3)
            ->where("u.status_pegawai", 1)
            ->get()
            ->result();
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

    public function get_seleksi2($tabel, $kolom_seleksi, $seleksi, $kolom_seleksi2, $seleksi2)
    {
        try {
            $this->db->where($kolom_seleksi2, $seleksi2);
            $this->db->where($kolom_seleksi, $seleksi);
            return $this->db->get($tabel);
        } catch (Exception $e) {
            return 0;
        }
    }

    public function get_presensi_data($id)
    {
        try {
            $this->db->where('id', $id);
            return $this->db->get('register_presensi')->row();
        } catch (Exception $e) {
            return $e;
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
            return $this->db->get('register_presensi')->row();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function get_presensi_pengguna_kemarin($id)
    {
        try {
            $this->db->where('tgl', date('Y-m-d', strtotime("-1 days")));
            $this->db->where('userid', $id);
            return $this->db->get('register_presensi')->row();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function get_presensi_apel($id)
    {
        try {
            $this->db->where('DATE(created_on)', date('Y-m-d'));
            $this->db->where('userid', $id);
            return $this->db->get('register_presensi_apel')->row();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function get_presensi_rapat($userid, $idrapat)
    {
        try {
            $this->db->where('idrapat', $idrapat);
            $this->db->where('userid', $userid);
            return $this->db->get('register_presensi_rapat')->row();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function get_presensi_kegiatan($userid, $idkegiatan)
    {
        try {
            $this->db->where('idkegiatan', $idkegiatan);
            $this->db->where('userid', $userid);
            return $this->db->get('register_presensi_lainnya')->row();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function semua_presensi($awal, $akhir)
    {
        return $this->db
            ->select("u.userid AS userid, u.jab_id, u.pegawai_id AS pegawai_id, u.fullname AS fullname, u.status_pegawai AS status, p.id AS id_presensi, p.tgl AS tgl, p.masuk AS masuk, p.pulang AS pulang, p.ket AS ket, u.id_grup AS grup_jabatan")
            ->from($this->db_sso . ".v_users u")
            ->join("register_presensi p", "u.userid = p.userid", "left")
            ->where('tgl >=', $awal)
            ->where('tgl <=', $akhir)
            ->where("u.status_pegawai", 1)
            ->order_by("jab_id")
            ->order_by("fullname")
            ->get()
            ->result();
    }

    public function semua_presensi_peg($awal, $akhir, $id)
    {
        return $this->db
            ->select("u.userid AS userid, u.jab_id, u.pegawai_id AS pegawai_id, u.fullname AS fullname, u.status_pegawai AS status, p.id AS id_presensi, p.tgl AS tgl, p.masuk AS masuk, p.pulang AS pulang, p.ket AS ket, u.id_grup AS grup_jabatan")
            ->from($this->db_sso . ".v_users u")
            ->join("register_presensi p", "u.userid = p.userid", "left")
            ->where('tgl >=', $awal)
            ->where('tgl <=', $akhir)
            ->where('p.userid', $id)
            ->where("u.status_pegawai", 1)
            ->order_by("tgl")
            ->get()
            ->result();
    }

    public function register_kegiatan()
    {
        $this->db->select('k.id AS id_kegiatan, k.nama_kegiatan, k.tanggal, COUNT(l.userid) AS total');
        $this->db->from('ref_kegiatan k');
        $this->db->join('register_presensi_lainnya l', 'k.id = l.idkegiatan', 'left');
        $this->db->group_by('k.id');
        $this->db->order_by('k.tanggal', 'DESC');
        $query = $this->db->get();

        return $query->result();
    }

    public function register_apel()
    {
        $this->db->select('DATE(created_on) AS tanggal, COUNT(*) AS total');
        $this->db->group_by('DATE(created_on)');
        $this->db->order_by('DATE(created_on)', 'DESC');
        $query = $this->db->get('register_presensi_apel');

        return $query->result();
    }

    public function get_detail_presensi($tgl)
    {
        $this->db->select('u.nip AS nip, u.fullname AS nama, u.jabatan AS jabatan, TIME(p.created_on) AS waktu');
        $this->db->where('u.userid = p.userid AND DATE(p.created_on) = "' . $tgl . '"');
        $this->db->order_by('TIME(p.created_on)', 'ASC');
        $query = $this->db->get('sso.v_users u, register_presensi_apel p');

        return $query->result();
    }

    public function get_detail_presensi_rapat($id)
    {
        $this->db->select('u.nip AS nip, u.fullname AS nama, u.jabatan AS jabatan, TIME(p.created_on) AS waktu');
        $this->db->where('u.userid = p.userid AND p.idrapat = "' . $id . '"');
        $this->db->order_by('u.id_grup, u.jab_id', 'ASC');
        $query = $this->db->get('v_users u, register_presensi_rapat p');

        return $query->result();
    }

    public function get_detail_presensi_kegiatan($id)
    {
        $this->db->select('u.nip AS nip, u.fullname AS nama, u.jabatan AS jabatan, TIME(l.created_on) AS waktu');
        $this->db->where('u.userid = l.userid AND l.idkegiatan = "' . $id . '"');
        $this->db->order_by('TIME(l.created_on)', 'ASC');
        $query = $this->db->get('v_users u, register_presensi_lainnya l');

        return $query->result();
    }

    public function get_data_peran()
    {
        $this->db->select('l.id AS id, u.userid AS userid, u.fullname AS nama, l.role AS peran, l.hapus AS hapus');
        $this->db->from('role_presensi l');
        $this->db->join($this->db_sso . '.v_users u', 'l.userid = u.userid', 'left');
        $this->db->order_by('l.id', 'ASC');
        $query = $this->db->get();

        return $query->result();
    }

    public function get_pres_pengguna($id)
    {
        try {
            $this->db->where('tgl', date('Y-m-d'));
            $this->db->where('userid', $id);
            return $this->db->get('register_presensi')->row();
        } catch (Exception $e) {
            return $e;
        }
    }

    #################################
    #    SIMPAN PRESENSI (START)    #
    #################################

    public function simpan_masuk($data, $id)
    {
        $table = 'register_presensi';
        try {
            $this->db->insert($table, $data);
            $title = "Data Aplikasi HADIR-IN";
            $descrip = "Update Jam Masuk Presensi Pegawai [No Id=<b>" . $id . "</b>]<br />Update jam masuk <b>register_presensi</b>]";
            $this->add_audittrail("INSERT", $title, $table, $descrip);
            return 1;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function simpan_pulang($data, $id)
    {
        $table = 'register_presensi';
        try {
            $this->db->where('id', $id);
            $this->db->update($table, $data);
            $title = "Update Jam Pulang Presensi Pegawai [No Id=<b>" . $id . "</b>]<br />Update jam pulang <b>register_presensi</b>]";
            $descrip = null;
            $this->add_audittrail("UPDATE", $title, $table, $descrip);
            return 1;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function simpan_apel($data, $id)
    {
        $table = 'register_presensi_apel';
        try {
            $this->db->insert($table, $data);
            $title = "Update Presensi Apel Pegawai [No Id=<b>" . $id . "</b>]<br />Update apel <b>register_presensi_apel</b>]";
            $descrip = null;
            $this->add_audittrail("INSERT", $title, $table, $descrip);
            return 1;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function simpan_kegiatan($data, $id)
    {
        $table = 'register_presensi_lainnya';
        try {
            $this->db->insert($table, $data);
            $title = "Update Presensi Kegiatan Lain Pegawai [User id=<b>" . $id . "</b>]<br />Update kegiatan lain <b>register_presensi_lainnya</b>]";
            $descrip = null;
            $this->add_audittrail("INSERT", $title, $table, $descrip);
            return 1;
        } catch (Exception $e) {
            return $e;
        }
    }

    #################################
    #     SIMPAN PRESENSI (END)     #
    #################################

    public function simpan_presensi($data)
    {
        $table = 'register_presensi';
        try {
            $this->db->insert($table, $data);
            $title = "Simpan Edit Presensi Pegawai <br />Simpan Edit Presensi <b>register_presensi</b>]";
            $descrip = null;
            $this->add_audittrail("INSERT", $title, $table, $descrip);
            return 1;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function update_presensi($data, $id)
    {
        $table = 'register_presensi';
        try {
            $this->db->where('id', $id);
            $this->db->update($table, $data);
            $title = "Update Edit Presensi Pegawai [No Id=<b>" . $id . "</b>]<br />Update edit data presensi <b>register_presensi</b>]";
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
            $title = "Tambah Data <br />Insert tabel <b>" . $tabel;
            $descrip = null;
            $this->add_audittrail("INSERT", $title, $tabel, $descrip);
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