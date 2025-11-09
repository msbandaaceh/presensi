<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApiHelper
{
    protected $CI;
    protected $url_api;

    public function __construct()
    {
        // Ambil instance CI dan URL API dari config
        $this->CI =& get_instance();
        $this->url_api = rtrim($this->CI->session->userdata('sso_server'), '/') . '/';
    }

    /**
     * Kirim request PATCH ke endpoint API di server SSO
     * @param string $endpoint - Contoh: 'api/update_partial'
     * @param array $payload - Data yang ingin dikirim
     * @param array $headers - Header tambahan jika ada
     * @return array ['status_code' => ..., 'response' => ...]
     */

    public function get($endpoint, $params = [], $headers = [])
    {
        $full_url = $this->url_api . ltrim($endpoint, '/');

        // Tambahkan query string jika ada parameter
        if (!empty($params)) {
            $full_url .= '?' . http_build_query($params);
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $full_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge([
            'Content-Type: application/json'
        ], $headers));

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'status_code' => $http_code,
            'response' => json_decode($response, true)
        ];
    }

    public function post($endpoint, $payload = [], $headers = [])
    {
        $full_url = $this->url_api . ltrim($endpoint, '/');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $full_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge([
            'Content-Type: application/json'
        ], $headers));

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'status_code' => $http_code,
            'response' => json_decode($response, true)
        ];
    }

    public function patch($endpoint, $payload = [], $headers = [])
    {
        $full_url = $this->url_api . ltrim($endpoint, '/');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $full_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH'); // method PATCH
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge([
            'Content-Type: application/json'
        ], $headers));

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'status_code' => $http_code,
            'response' => json_decode($response, true)
        ];
    }

    /**
     * Ambil data tanggal merah dari Seudati
     * @param string|null $tgl - Tanggal spesifik (Y-m-d) atau null untuk semua tanggal merah tahun ini
     * @return array - Array tanggal merah dalam format Y-m-d
     */
    public function get_tanggal_merah($tgl = null)
    {
        $seudati_url = $this->CI->config->item('seudati_url');
        $api_key = $this->CI->config->item('seudati_api_key');
        
        if (empty($seudati_url) || empty($api_key)) {
            return [];
        }

        $full_url = rtrim($seudati_url, '/') . '/api/cek_tgl_merah';
        $params = ['api_key' => $api_key];
        
        if ($tgl) {
            $params['tgl'] = $tgl;
        }

        $full_url .= '?' . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $full_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Timeout 5 detik
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        // Jika error atau timeout, return empty array
        if ($curl_error || $http_code !== 200) {
            return [];
        }

        $data = json_decode($response, true);
        
        if (isset($data['status']) && $data['status'] === 'success' && !empty($data['data'])) {
            // Jika ada tanggal spesifik, return boolean
            if ($tgl) {
                return true;
            }
            // Jika tidak ada tanggal spesifik, return array tanggal
            $tanggal_merah = [];
            foreach ($data['data'] as $row) {
                if (isset($row['tgl'])) {
                    $tanggal_merah[] = $row['tgl'];
                }
            }
            return $tanggal_merah;
        }

        return [];
    }

    /**
     * Cek apakah tanggal tertentu adalah tanggal merah
     * @param string $tgl - Tanggal dalam format Y-m-d
     * @return bool
     */
    public function is_tanggal_merah($tgl)
    {
        $tanggal_merah = $this->get_tanggal_merah($tgl);
        return !empty($tanggal_merah);
    }

    /**
     * Ambil semua tanggal merah untuk tahun tertentu (dari cache atau API)
     * @param int|null $tahun - Tahun atau null untuk tahun sekarang
     * @return array - Array tanggal merah dalam format Y-m-d
     */
    public function get_all_tanggal_merah($tahun = null)
    {
        if ($tahun === null) {
            $tahun = date('Y');
        }

        // Cek cache session dulu
        $cache_key = 'tanggal_merah_' . $tahun;
        $cached = $this->CI->session->userdata($cache_key);
        
        if ($cached !== false && is_array($cached)) {
            return $cached;
        }

        // Ambil dari API Seudati
        $seudati_url = $this->CI->config->item('seudati_url');
        $api_key = $this->CI->config->item('seudati_api_key');
        
        if (empty($seudati_url) || empty($api_key)) {
            return [];
        }

        // Gunakan endpoint API baru get_all_tanggal_merah yang lebih efisien
        $full_url = rtrim($seudati_url, '/') . '/api/get_all_tanggal_merah';
        $params = [
            'api_key' => $api_key,
            'tahun' => $tahun
        ];
        $full_url .= '?' . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $full_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_error || $http_code !== 200) {
            // Jika gagal, coba fallback ke endpoint get_tanggal_merah (dengan SSO session)
            return $this->get_all_tanggal_merah_fallback($tahun);
        }

        $data = json_decode($response, true);
        
        if (isset($data['status']) && $data['status'] === 'success' && isset($data['data'])) {
            $tanggal_merah = $data['data'];
            // Simpan ke cache session
            $this->CI->session->set_userdata($cache_key, $tanggal_merah);
            return $tanggal_merah;
        }

        return [];
    }

    /**
     * Fallback method: ambil dari endpoint get_tanggal_merah (menggunakan SSO session)
     * @param int $tahun
     * @return array
     */
    private function get_all_tanggal_merah_fallback($tahun)
    {
        $seudati_url = $this->CI->config->item('seudati_url');
        
        if (empty($seudati_url)) {
            return [];
        }

        // Coba ambil dari endpoint get_tanggal_merah (mengembalikan semua tanggal merah tahun ini)
        // Endpoint ini memerlukan SSO session, tapi karena menggunakan SSO yang sama, bisa diakses
        $full_url = rtrim($seudati_url, '/') . '/get_tanggal_merah';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $full_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // Kirim cookie SSO jika ada
        $sso_token = $this->CI->input->cookie('sso_token');
        if ($sso_token) {
            curl_setopt($ch, CURLOPT_COOKIE, 'sso_token=' . $sso_token);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code === 200) {
            $tanggal_merah = json_decode($response, true);
            if (is_array($tanggal_merah)) {
                // Simpan ke cache session
                $cache_key = 'tanggal_merah_' . $tahun;
                $this->CI->session->set_userdata($cache_key, $tanggal_merah);
                return $tanggal_merah;
            }
        }

        return [];
    }
}
