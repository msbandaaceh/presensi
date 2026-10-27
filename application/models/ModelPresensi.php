<?php defined('BASEPATH') or exit('No direct script access allowed');

class ModelPresensi extends CI_Model
{
    private $db_sso;

    public function __construct()
    {
        parent::__construct();

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
        $id = $this->db->escape($id);

        $sql = "
            WITH RECURSIVE DateTable AS (
                SELECT DATE_FORMAT(NOW(), '%Y-%m-01') AS Date
                UNION ALL
                SELECT Date + INTERVAL 1 DAY
                FROM DateTable
                WHERE Date < LAST_DAY(NOW())
            )
            SELECT DateTable.Date, r.*
            FROM DateTable
            LEFT JOIN register_presensi r 
                ON DateTable.Date = DATE(r.tgl) 
                AND r.userid = $id
            WHERE DateTable.Date IS NOT NULL
            ORDER BY DateTable.Date
        ";

        return $this->db->query($sql)->result();
    }

    public function all_pres_pengguna_bulan($id, $bulan)
    {
        $bulan_date = date('Y-m-01', strtotime($bulan));

        $id_esc = $this->db->escape($id);
        $bulan_esc = $this->db->escape($bulan_date);

        $sql = "
            WITH RECURSIVE DateTable AS (
                SELECT $bulan_esc AS Date
                UNION ALL
                SELECT Date + INTERVAL 1 DAY
                FROM DateTable
                WHERE Date < LAST_DAY($bulan_esc)
            )
            SELECT DateTable.Date, r.*
            FROM DateTable
            LEFT JOIN register_presensi r 
                ON DateTable.Date = DATE(r.tgl) 
                AND r.userid = $id_esc
            WHERE DateTable.Date IS NOT NULL
            ORDER BY DateTable.Date
        ";

        return $this->db->query($sql)->result();
    }

    public function presensi_harian($jenis, $tgl)
    {
        $api = $this->apihelper->get('apiclient/get_semua_user_aktif');
        $users = $api['response']['data'] ?? $api['data'] ?? [];

        if (empty($users)) {
            return [];
        }

        $filtered = [];
        foreach ($users as $u) {
            $id_grup = $u['id_grup'] ?? $u['grup_jabatan'] ?? null;
            $status = $u['status_pegawai'] ?? 0;

            if ((int) $id_grup === $jenis && (int) $status === 1) {
                $filtered[] = $u;
            }
        }

        $user_ids = array_column($filtered, 'userid');
        if (empty($user_ids))
            return [];

        $presensi = $this->db
            ->where('DATE(tgl)', $tgl)
            ->where_in('userid', $user_ids)
            ->get('register_presensi')
            ->result_array();

        $presensi_map = array_column($presensi, null, 'userid');

        $result = [];
        foreach ($filtered as $u) {
            $uid = $u['userid'];
            $p = $presensi_map[$uid] ?? [];

            $result[] = (object) [
                'userid' => $uid,
                'jab_id' => $u['jab_id'],
                'jabatan' => $u['jabatan'],
                'pegawai_id' => $u['pegawai_id'],
                'fullname' => $u['fullname'],
                'status' => $u['status_pegawai'],
                'id_presensi' => $p['id'] ?? null,
                'tgl' => $p['tgl'] ?? null,
                'masuk' => $p['masuk'] ?? null,
                'pulang' => $p['pulang'] ?? null,
                'ket' => $p['ket'] ?? null,
                'grup_jabatan' => $u['id_grup'],
                'tgl_now' => $tgl
            ];
        }

        return $result;
    }

    private function get_users_aktif_cached()
    {
        $this->load->driver('cache', ['adapter' => 'file']);
        $cache_key = 'sso_users_aktif_' . date('Y-m-d');

        $users = $this->cache->get($cache_key);
        if ($users === FALSE) {
            $api = $this->apihelper->get('apiclient/get_semua_user_aktif');
            $users = $api['response']['data'] ?? $api['data'] ?? [];
            // Cache 6 jam, jangan hit Seudati terus
            $this->cache->save($cache_key, $users, 21600);
        }
        return $users;
    }

    public function get_all_presensi_today_optimized()
    {
        $users = $this->get_users_aktif_cached();
        if (empty($users))
            return [];

        // Group mapping biar 1x loop
        // 1=hakim, 2=cakim, 3=pns, 4=pppk, 5=honor - sesuaikan sama seudati lu
        $today = date('Y-m-d');
        $user_ids = array_column($users, 'userid');

        // 1 QUERY PRESENSI AJA UNTUK SEMUA
        $presensi = $this->db
            ->where('DATE(tgl)', $today)
            ->where_in('userid', $user_ids)
            ->get('register_presensi')
            ->result_array();

        $presensi_map = array_column($presensi, null, 'userid');

        $result = [];
        foreach ($users as $u) {
            $status = (int) ($u['status_pegawai'] ?? 0);
            if ($status !== 1)
                continue; // cuma yang aktif

            $uid = $u['userid'];
            $p = $presensi_map[$uid] ?? [];

            $result[] = (object) [
                'userid' => $uid,
                'jab_id' => $u['jab_id'],
                'jabatan' => $u['jabatan'],
                'pegawai_id' => $u['pegawai_id'],
                'fullname' => $u['fullname'],
                'status' => $status,
                'id_presensi' => $p['id'] ?? null,
                'tgl' => $p['tgl'] ?? null,
                'masuk' => $p['masuk'] ?? null,
                'pulang' => $p['pulang'] ?? null,
                'ket' => $p['ket'] ?? null,
                'grup_jabatan' => $u['id_grup'] ?? $u['grup_jabatan'],
                'id_grup' => (int) ($u['id_grup'] ?? $u['grup_jabatan']),
                'tgl_now' => $today
            ];
        }
        return $result;
    }

    public function get_presensi_terbaru_today($limit = 5)
    {
        $all = $this->get_all_presensi_today_optimized();

        $masuk = array_filter($all, function ($p) {
            return !empty($p->masuk);
        });

        usort($masuk, function ($a, $b) {
            return strcmp($b->masuk, $a->masuk);
        });

        return array_slice($masuk, 0, $limit);
    }

    public function pres_hakim_now()
    {
        $all = $this->get_all_presensi_today_optimized();
        return array_filter($all, function ($x) {
            return $x->id_grup === 1; });
    }
    public function pres_pns_now()
    {
        $all = $this->get_all_presensi_today_optimized();
        return array_filter($all, function ($x) {
            return $x->id_grup === 2; });
    }
    public function pres_honor_now()
    {
        $all = $this->get_all_presensi_today_optimized();
        return array_filter($all, function ($x) {
            return $x->id_grup === 3; });
    }
    public function pres_cakim_now()
    {
        $all = $this->get_all_presensi_today_optimized();
        return array_filter($all, function ($x) {
            return $x->id_grup === 4; });
    }
    public function pres_pppk_now()
    {
        $all = $this->get_all_presensi_today_optimized();
        return array_filter($all, function ($x) {
            return $x->id_grup === 6; });
    }

    public function pres_hakim_bulan($tgl)
    {
        $api = $this->apihelper->get('apiclient/get_semua_user_aktif');
        $users = $api['response']['data'] ?? $api['data'] ?? [];

        if (empty($users)) {
            return [];
        }

        $filtered = [];
        foreach ($users as $u) {
            $id_grup = $u['id_grup'] ?? $u['grup_jabatan'] ?? null;
            $status = $u['status_pegawai'] ?? 0;

            if ((int) $id_grup === 1 && (int) $status === 1) {
                $filtered[] = $u;
            }
        }

        $user_ids = array_column($filtered, 'userid');
        if (empty($user_ids))
            return [];

        $today = $this->db->escape($tgl);
        $presensi = $this->db
            ->where('DATE(tgl)', $today)
            ->where_in('userid', $user_ids)
            ->get('register_presensi')
            ->result_array();

        $presensi_map = array_column($presensi, null, 'userid');

        $result = [];
        foreach ($filtered as $u) {
            $uid = $u['userid'];
            $p = $presensi_map[$uid] ?? [];

            $result[] = (object) [
                'userid' => $uid,
                'jab_id' => $u['jab_id'],
                'jabatan' => $u['jabatan'],
                'pegawai_id' => $u['pegawai_id'],
                'fullname' => $u['fullname'],
                'status' => $u['status_pegawai'],
                'id_presensi' => $p['id'] ?? null,
                'tgl' => $p['tgl'] ?? null,
                'masuk' => $p['masuk'] ?? null,
                'pulang' => $p['pulang'] ?? null,
                'ket' => $p['ket'] ?? null,
                'grup_jabatan' => $u['id_grup']
            ];
        }

        return $result;
    }

    public function pres_cakim_bulan($tgl)
    {
        $api = $this->apihelper->get('apiclient/get_semua_user_aktif');
        $users = $api['response']['data'] ?? $api['data'] ?? [];

        if (empty($users)) {
            return [];
        }

        $filtered = [];
        foreach ($users as $u) {
            $id_grup = $u['id_grup'] ?? $u['grup_jabatan'] ?? null;
            $status = $u['status_pegawai'] ?? 0;

            if ((int) $id_grup === 4 && (int) $status === 1) {
                $filtered[] = $u;
            }
        }

        $user_ids = array_column($filtered, 'userid');
        if (empty($user_ids))
            return [];

        $today = $this->db->escape($tgl);
        $presensi = $this->db
            ->where('DATE(tgl)', $today)
            ->where_in('userid', $user_ids)
            ->get('register_presensi')
            ->result_array();

        $presensi_map = array_column($presensi, null, 'userid');

        $result = [];
        foreach ($filtered as $u) {
            $uid = $u['userid'];
            $p = $presensi_map[$uid] ?? [];

            $result[] = (object) [
                'userid' => $uid,
                'jab_id' => $u['jab_id'],
                'jabatan' => $u['jabatan'],
                'pegawai_id' => $u['pegawai_id'],
                'fullname' => $u['fullname'],
                'status' => $u['status_pegawai'],
                'id_presensi' => $p['id'] ?? null,
                'tgl' => $p['tgl'] ?? null,
                'masuk' => $p['masuk'] ?? null,
                'pulang' => $p['pulang'] ?? null,
                'ket' => $p['ket'] ?? null,
                'grup_jabatan' => $u['id_grup']
            ];
        }

        return $result;
    }

    public function pres_pns_bulan($tgl)
    {
        $api = $this->apihelper->get('apiclient/get_semua_user_aktif');
        $users = $api['response']['data'] ?? $api['data'] ?? [];

        if (empty($users)) {
            return [];
        }

        $filtered = [];
        foreach ($users as $u) {
            $id_grup = $u['id_grup'] ?? $u['grup_jabatan'] ?? null;
            $status = $u['status_pegawai'] ?? 0;

            if ((int) $id_grup === 2 && (int) $status === 1) {
                $filtered[] = $u;
            }
        }

        $user_ids = array_column($filtered, 'userid');
        if (empty($user_ids))
            return [];

        $today = $this->db->escape($tgl);
        $presensi = $this->db
            ->where('DATE(tgl)', $today)
            ->where_in('userid', $user_ids)
            ->get('register_presensi')
            ->result_array();

        $presensi_map = array_column($presensi, null, 'userid');

        $result = [];
        foreach ($filtered as $u) {
            $uid = $u['userid'];
            $p = $presensi_map[$uid] ?? [];

            $result[] = (object) [
                'userid' => $uid,
                'jab_id' => $u['jab_id'],
                'jabatan' => $u['jabatan'],
                'pegawai_id' => $u['pegawai_id'],
                'fullname' => $u['fullname'],
                'status' => $u['status_pegawai'],
                'id_presensi' => $p['id'] ?? null,
                'tgl' => $p['tgl'] ?? null,
                'masuk' => $p['masuk'] ?? null,
                'pulang' => $p['pulang'] ?? null,
                'ket' => $p['ket'] ?? null,
                'grup_jabatan' => $u['id_grup']
            ];
        }

        return $result;
    }

    public function pres_pppk_bulan($tgl)
    {
        $api = $this->apihelper->get('apiclient/get_semua_user_aktif');
        $users = $api['response']['data'] ?? $api['data'] ?? [];

        if (empty($users)) {
            return [];
        }

        $filtered = [];
        foreach ($users as $u) {
            $id_grup = $u['id_grup'] ?? $u['grup_jabatan'] ?? null;
            $status = $u['status_pegawai'] ?? 0;

            if ((int) $id_grup === 6 && (int) $status === 1) {
                $filtered[] = $u;
            }
        }

        $user_ids = array_column($filtered, 'userid');
        if (empty($user_ids))
            return [];

        $today = $this->db->escape($tgl);
        $presensi = $this->db
            ->where('DATE(tgl)', $today)
            ->where_in('userid', $user_ids)
            ->get('register_presensi')
            ->result_array();

        $presensi_map = array_column($presensi, null, 'userid');

        $result = [];
        foreach ($filtered as $u) {
            $uid = $u['userid'];
            $p = $presensi_map[$uid] ?? [];

            $result[] = (object) [
                'userid' => $uid,
                'jab_id' => $u['jab_id'],
                'jabatan' => $u['jabatan'],
                'pegawai_id' => $u['pegawai_id'],
                'fullname' => $u['fullname'],
                'status' => $u['status_pegawai'],
                'id_presensi' => $p['id'] ?? null,
                'tgl' => $p['tgl'] ?? null,
                'masuk' => $p['masuk'] ?? null,
                'pulang' => $p['pulang'] ?? null,
                'ket' => $p['ket'] ?? null,
                'grup_jabatan' => $u['id_grup']
            ];
        }

        return $result;
    }

    public function pres_honor_bulan($tgl)
    {
        $api = $this->apihelper->get('apiclient/get_semua_user_aktif');
        $users = $api['response']['data'] ?? $api['data'] ?? [];

        if (empty($users)) {
            return [];
        }

        $filtered = [];
        foreach ($users as $u) {
            $id_grup = $u['id_grup'] ?? $u['grup_jabatan'] ?? null;
            $status = $u['status_pegawai'] ?? 0;

            if ((int) $id_grup === 3 && (int) $status === 1) {
                $filtered[] = $u;
            }
        }

        $user_ids = array_column($filtered, 'userid');
        if (empty($user_ids))
            return [];

        $today = $this->db->escape($tgl);
        $presensi = $this->db
            ->where('DATE(tgl)', $today)
            ->where_in('userid', $user_ids)
            ->get('register_presensi')
            ->result_array();

        $presensi_map = array_column($presensi, null, 'userid');

        $result = [];
        foreach ($filtered as $u) {
            $uid = $u['userid'];
            $p = $presensi_map[$uid] ?? [];

            $result[] = (object) [
                'userid' => $uid,
                'jab_id' => $u['jab_id'],
                'jabatan' => $u['jabatan'],
                'pegawai_id' => $u['pegawai_id'],
                'fullname' => $u['fullname'],
                'status' => $u['status_pegawai'],
                'id_presensi' => $p['id'] ?? null,
                'tgl' => $p['tgl'] ?? null,
                'masuk' => $p['masuk'] ?? null,
                'pulang' => $p['pulang'] ?? null,
                'ket' => $p['ket'] ?? null,
                'grup_jabatan' => $u['id_grup']
            ];
        }

        return $result;
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
        $api = $this->apihelper->get('apiclient/get_semua_user_aktif');
        $users = $api['response']['data'] ?? $api['data'] ?? [];

        if (empty($users))
            return [];

        $filtered = [];
        foreach ($users as $u) {
            if ((int) ($u['status_pegawai'] ?? 0) === 1) {
                $filtered[] = $u;
            }
        }

        $user_ids = array_column($filtered, 'userid');
        if (empty($user_ids))
            return [];

        $presensi = $this->db
            ->where('DATE(tgl) >=', date('Y-m-d', strtotime($awal)))
            ->where('DATE(tgl) <=', date('Y-m-d', strtotime($akhir)))
            ->where_in('userid', $user_ids)
            ->get('register_presensi')
            ->result_array();

        $presensi_map = [];
        foreach ($presensi as $p) {
            $presensi_map[$p['userid']][] = $p;
        }

        $result = [];
        usort($filtered, function ($a, $b) {
            $jab = (int) ($a['jab_id'] ?? 0) <=> (int) ($b['jab_id'] ?? 0);
            if ($jab !== 0)
                return $jab;
            return strcmp($a['fullname'] ?? '', $b['fullname'] ?? '');
        });

        foreach ($filtered as $u) {
            $uid = $u['userid'];
            $list_pres = $presensi_map[$uid] ?? [null];

            foreach ($list_pres as $p) {
                $result[] = (object) [
                    'userid' => $uid,
                    'jab_id' => $u['jab_id'],
                    'pegawai_id' => $u['pegawai_id'],
                    'fullname' => $u['fullname'],
                    'status' => $u['status_pegawai'],
                    'id_presensi' => $p['id'] ?? null,
                    'tgl' => $p['tgl'] ?? null,
                    'masuk' => $p['masuk'] ?? null,
                    'pulang' => $p['pulang'] ?? null,
                    'ket' => $p['ket'] ?? null,
                    'grup_jabatan' => $u['id_grup'] ?? $u['grup_jabatan'] ?? null,
                ];
            }
        }

        $result = array_filter($result, function ($r) {
            return $r->tgl !== null;
        });

        return $result;
    }

    public function semua_presensi_peg($awal, $akhir, $id)
    {
        $api = $this->apihelper->get('apiclient/get_semua_user_aktif');
        $users = $api['response']['data'] ?? $api['data'] ?? [];

        if (empty($users))
            return [];

        $filtered = [];
        foreach ($users as $u) {
            if ((int) ($u['status_pegawai'] ?? 0) === 1) {
                $filtered[] = $u;
            }
        }

        $presensi = $this->db
            ->where('DATE(tgl) >=', date('Y-m-d', strtotime($awal)))
            ->where('DATE(tgl) <=', date('Y-m-d', strtotime($akhir)))
            ->where('userid', $id)
            ->get('register_presensi')
            ->result_array();

        $presensi_map = [];
        foreach ($presensi as $p) {
            $presensi_map[$p['userid']][] = $p;
        }

        $result = [];
        usort($filtered, function ($a, $b) {
            $jab = (int) ($a['jab_id'] ?? 0) <=> (int) ($b['jab_id'] ?? 0);
            if ($jab !== 0)
                return $jab;
            return strcmp($a['fullname'] ?? '', $b['fullname'] ?? '');
        });

        foreach ($filtered as $u) {
            $uid = $u['userid'];
            $list_pres = $presensi_map[$uid] ?? [null];

            foreach ($list_pres as $p) {
                $result[] = (object) [
                    'userid' => $uid,
                    'jab_id' => $u['jab_id'],
                    'pegawai_id' => $u['pegawai_id'],
                    'fullname' => $u['fullname'],
                    'status' => $u['status_pegawai'],
                    'id_presensi' => $p['id'] ?? null,
                    'tgl' => $p['tgl'] ?? null,
                    'masuk' => $p['masuk'] ?? null,
                    'pulang' => $p['pulang'] ?? null,
                    'ket' => $p['ket'] ?? null,
                    'grup_jabatan' => $u['id_grup'] ?? $u['grup_jabatan'] ?? null,
                ];
            }
        }

        $result = array_filter($result, function ($r) {
            return $r->tgl !== null;
        });

        return $result;
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
        $api = $this->apihelper->get('apiclient/get_semua_user_aktif');
        $users = $api['response']['data'] ?? $api['data'] ?? [];

        if (empty($users))
            return [];

        $filtered = [];
        foreach ($users as $u) {
            if ((int) ($u['status_pegawai'] ?? 0) === 1) {
                $filtered[] = $u;
            }
        }

        $presensi = $this->db
            ->where('DATE(created_on)', $tgl)
            ->order_by('created_on', 'ASC')
            ->get('register_presensi_apel')
            ->result_array();

        $presensi_map = [];
        foreach ($presensi as $p) {
            $presensi_map[$p['userid']][] = $p;
        }

        foreach ($filtered as $u) {
            $uid = $u['userid'];
            $list_pres = $presensi_map[$uid] ?? [null];

            foreach ($list_pres as $p) {
                if ($p != null) {
                    $result[] = (object) [
                        'userid' => $uid,
                        'nip' => $u['nip'],
                        'jabatan' => $u['jabatan'],
                        'nama' => $u['fullname'],
                        'waktu' => date('H:i:s', strtotime($p['created_on'])),
                    ];
                }
            }
        }

        return $result;
    }

    public function get_detail_presensi_kegiatan($id)
    {
        $api = $this->apihelper->get('apiclient/get_semua_user_aktif');
        $users = $api['response']['data'] ?? $api['data'] ?? [];

        if (empty($users))
            return [];

        $filtered = [];
        foreach ($users as $u) {
            if ((int) ($u['status_pegawai'] ?? 0) === 1) {
                $filtered[] = $u;
            }
        }

        $presensi = $this->db
            ->where('idkegiatan', $id)
            ->order_by('created_on', 'ASC')
            ->get('register_presensi_apel')
            ->result_array();

        $presensi_map = [];
        foreach ($presensi as $p) {
            $presensi_map[$p['userid']][] = $p;
        }

        foreach ($filtered as $u) {
            $uid = $u['userid'];
            $list_pres = $presensi_map[$uid] ?? [null];

            foreach ($list_pres as $p) {
                if ($p != null) {
                    $result[] = (object) [
                        'userid' => $uid,
                        'nip' => $u['nip'],
                        'jabatan' => $u['jabatan'],
                        'nama' => $u['fullname'],
                        'waktu' => date('H:i:s', strtotime($p['created_on'])),
                    ];
                }
            }
        }

        return $result;
    }

    public function get_data_peran()
    {
        $api = $this->apihelper->get('apiclient/get_semua_user_aktif');
        $users = $api['response']['data'] ?? $api['data'] ?? [];

        if (empty($users))
            return [];

        $filtered = [];
        foreach ($users as $u) {
            if ((int) ($u['status_pegawai'] ?? 0) === 1) {
                $filtered[] = $u;
            }
        }

        $role = $this->db
            ->order_by('id', 'ASC')
            ->get('role_presensi')
            ->result_array();

        $role_map = [];
        foreach ($role as $r) {
            $role_map[$r['userid']][] = $r;
        }

        foreach ($filtered as $u) {
            $uid = $u['userid'];
            $list_pres = $role_map[$uid] ?? [null];

            foreach ($list_pres as $p) {
                if ($p != null) {
                    $result[] = (object) [
                        'id' => $r['id'],
                        'userid' => $uid,
                        'nama' => $u['fullname'],
                        'peran' => $r['role'],
                        'hapus' => $r['hapus']
                    ];
                }
            }
        }

        return $result;
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