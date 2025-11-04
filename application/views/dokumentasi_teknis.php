<div class="row">
    <div class="col">
        <ol class="breadcrumb align-right">
            <li>
                <a href="javascript:;" data-page="dashboard">
                    <i class="material-icons">home</i> Beranda
                </a>
            </li>
            <li class="active">
                <a>
                    <i class="material-icons">code</i> Dokumentasi Teknis
                </a>
            </li>
        </ol>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-purple">
                <h2>
                    <i class="material-icons">engineering</i> DOKUMENTASI TEKNIS SISTEM HADIR-IN
                </h2>
            </div>
            <div class="body">
                <div class="alert alert-warning">
                    <strong><i class="material-icons">warning</i> Catatan:</strong> Dokumentasi ini hanya dapat diakses oleh Administrator sistem.
                </div>

                <div class="panel-group" id="accordion_teknis" role="tablist" aria-multiselectable="true">
                    
                    <!-- Bagian 1: Arsitektur Sistem -->
                    <div class="panel panel-col-purple">
                        <div class="panel-heading" role="tab" id="heading_teknis_1">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion_teknis" href="#collapse_teknis_1" aria-expanded="true">
                                    <i class="material-icons">architecture</i> Arsitektur Sistem
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_teknis_1" class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">
                                <h4>1. Framework dan Teknologi</h4>
                                <ul>
                                    <li><strong>Framework:</strong> CodeIgniter 3.x</li>
                                    <li><strong>PHP Version:</strong> 7.4 atau lebih tinggi</li>
                                    <li><strong>Database:</strong> MySQL/MariaDB</li>
                                    <li><strong>Frontend:</strong> Bootstrap 4, jQuery, Material Design</li>
                                    <li><strong>Maps:</strong> Leaflet.js untuk geolocation</li>
                                    <li><strong>API:</strong> RESTful API untuk integrasi dengan sistem eksternal</li>
                                </ul>

                                <h4>2. Struktur Direktori</h4>
                                <pre>
application/
├── config/          # Konfigurasi aplikasi
├── controllers/     # Controller files
├── core/            # MY_Controller (base controller)
├── libraries/       # Custom libraries (ApiHelper, TanggalHelper)
├── models/          # Model files (ModelPresensi)
└── views/           # View files
                                </pre>

                                <h4>3. Arsitektur MVC</h4>
                                <ul>
                                    <li><strong>Model:</strong> ModelPresensi - handle semua operasi database</li>
                                    <li><strong>View:</strong> Views untuk setiap halaman dengan layout.php sebagai template utama</li>
                                    <li><strong>Controller:</strong> 
                                        <ul>
                                            <li>HalamanUtama - presensi dan dashboard</li>
                                            <li>HalamanLaporan - semua fungsi laporan</li>
                                            <li>HalamanKegiatan - manajemen kegiatan</li>
                                            <li>HalamanPanduan - panduan dan dokumentasi</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 2: Database -->
                    <div class="panel panel-col-purple">
                        <div class="panel-heading" role="tab" id="heading_teknis_2">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion_teknis" href="#collapse_teknis_2">
                                    <i class="material-icons">storage</i> Struktur Database
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_teknis_2" class="panel-collapse collapse" role="tabpanel">
                            <div class="panel-body">
                                <h4>1. Tabel Utama</h4>
                                <ul>
                                    <li><strong>register_presensi:</strong> Menyimpan data presensi harian (masuk, pulang, istirahat)</li>
                                    <li><strong>register_apel:</strong> Menyimpan data presensi apel</li>
                                    <li><strong>register_kegiatan:</strong> Menyimpan data presensi kegiatan lainnya</li>
                                    <li><strong>ref_kegiatan:</strong> Master data kegiatan/agenda</li>
                                    <li><strong>role_presensi:</strong> Menyimpan role/peran operator</li>
                                </ul>

                                <h4>2. Integrasi dengan Sistem Eksternal</h4>
                                <ul>
                                    <li>Sistem menggunakan API untuk mengambil data pegawai dari sistem kepegawaian</li>
                                    <li>View: <code>v_users</code> - data pegawai lengkap</li>
                                    <li>Autentikasi menggunakan SSO (Single Sign-On)</li>
                                    <li>Token management untuk keamanan</li>
                                </ul>

                                <h4>3. Relasi Database</h4>
                                <ul>
                                    <li><code>register_presensi.userid</code> → <code>v_users.userid</code></li>
                                    <li><code>register_kegiatan.idkegiatan</code> → <code>ref_kegiatan.id</code></li>
                                    <li><code>register_kegiatan.userid</code> → <code>v_users.userid</code></li>
                                    <li><code>role_presensi.userid</code> → <code>v_users.userid</code></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 3: Autentikasi dan Keamanan -->
                    <div class="panel panel-col-purple">
                        <div class="panel-heading" role="tab" id="heading_teknis_3">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion_teknis" href="#collapse_teknis_3">
                                    <i class="material-icons">security</i> Autentikasi dan Keamanan
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_teknis_3" class="panel-collapse collapse" role="tabpanel">
                            <div class="panel-body">
                                <h4>1. Sistem Autentikasi</h4>
                                <ul>
                                    <li><strong>SSO Integration:</strong> Sistem menggunakan SSO untuk autentikasi</li>
                                    <li><strong>Token Management:</strong> Menggunakan cookie <code>sso_token</code> untuk validasi</li>
                                    <li><strong>Session Management:</strong> CodeIgniter session untuk menyimpan data user</li>
                                    <li><strong>Role Checking:</strong> Di-handle di MY_Controller pada konstruktor</li>
                                </ul>

                                <h4>2. Role dan Permission</h4>
                                <ul>
                                    <li><strong>Admin:</strong> Role dari sistem SSO (super, validator_kepeg_satker, admin_satker) atau dari role_presensi dengan role 'admin'</li>
                                    <li><strong>Operator:</strong> Dari role_presensi dengan role 'operator'</li>
                                    <li><strong>User:</strong> Pegawai biasa tanpa role khusus</li>
                                    <li>Checking dilakukan di MY_Controller dan disimpan di session</li>
                                </ul>

                                <h4>3. Keamanan Data</h4>
                                <ul>
                                    <li>Enkripsi data sensitif menggunakan CodeIgniter Encryption</li>
                                    <li>CSRF Protection untuk form</li>
                                    <li>Input validation menggunakan Form Validation library</li>
                                    <li>SQL Injection protection melalui Query Builder</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 4: API dan Integrasi -->
                    <div class="panel panel-col-purple">
                        <div class="panel-heading" role="tab" id="heading_teknis_4">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion_teknis" href="#collapse_teknis_4">
                                    <i class="material-icons">api</i> API dan Integrasi
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_teknis_4" class="panel-collapse collapse" role="tabpanel">
                            <div class="panel-body">
                                <h4>1. ApiHelper Library</h4>
                                <ul>
                                    <li>Library custom untuk komunikasi dengan API eksternal</li>
                                    <li>Method: <code>get()</code> untuk GET request, <code>patch()</code> untuk UPDATE</li>
                                    <li>Endpoint utama: <code>apiclient/get_data_seleksi</code></li>
                                    <li>Response format: JSON dengan struktur <code>{status_code, response: {status, data}}</code></li>
                                </ul>

                                <h4>2. Endpoint yang Digunakan</h4>
                                <ul>
                                    <li><code>apiclient/get_data_seleksi</code> - Mengambil data berdasarkan seleksi</li>
                                    <li><code>apiclient/get_lokasi_mpp</code> - Mengambil data lokasi untuk geofencing</li>
                                    <code>api_update</code> - Update data (PATCH)</li>
                                </ul>

                                <h4>3. Konfigurasi API</h4>
                                <ul>
                                    <li>Konfigurasi di <code>application/config/config.php</code></li>
                                    <li>SSO Server URL untuk redirect login</li>
                                    <li>Cookie domain untuk session sharing</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 5: Geolocation -->
                    <div class="panel panel-col-purple">
                        <div class="panel-heading" role="tab" id="heading_teknis_5">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion_teknis" href="#collapse_teknis_5">
                                    <i class="material-icons">location_on</i> Sistem Geolocation
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_teknis_5" class="panel-collapse collapse" role="tabpanel">
                            <div class="panel-body">
                                <h4>1. Teknologi yang Digunakan</h4>
                                <ul>
                                    <li><strong>Leaflet.js:</strong> Library untuk menampilkan peta</li>
                                    <li><strong>Turf.js:</strong> Library untuk validasi geolocation (point in polygon)</li>
                                    <li><strong>HTML5 Geolocation API:</strong> Untuk mendapatkan koordinat user</li>
                                </ul>

                                <h4>2. Geofencing</h4>
                                <ul>
                                    <li>Sistem menggunakan polygon untuk menentukan area presensi</li>
                                    <li>Data polygon disimpan di database sistem eksternal</li>
                                    <li>Validasi menggunakan Turf.js untuk check apakah point berada dalam polygon</li>
                                    <li>Lokasi diambil dari API endpoint <code>apiclient/get_lokasi_mpp</code></li>
                                </ul>

                                <h4>3. Implementasi</h4>
                                <ul>
                                    <li>Peta ditampilkan di modal presensi menggunakan Leaflet</li>
                                    <li>Koordinat user ditampilkan real-time di peta</li>
                                    <li>Validasi dilakukan di JavaScript sebelum submit form</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 6: Frontend -->
                    <div class="panel panel-col-purple">
                        <div class="panel-heading" role="tab" id="heading_teknis_6">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion_teknis" href="#collapse_teknis_6">
                                    <i class="material-icons">web</i> Frontend dan UI/UX
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_teknis_6" class="panel-collapse collapse" role="tabpanel">
                            <div class="panel-body">
                                <h4>1. SPA (Single Page Application)</h4>
                                <ul>
                                    <li>Sistem menggunakan pendekatan SPA dengan AJAX</li>
                                    <li>Navigasi menggunakan attribute <code>data-page</code></li>
                                    <li>Function <code>loadPage()</code> di <code>presensi.js</code> untuk load content</li>
                                    <li>Content di-load via AJAX tanpa reload halaman</li>
                                </ul>

                                <h4>2. Library JavaScript</h4>
                                <ul>
                                    <li><strong>jQuery:</strong> DOM manipulation dan AJAX</li>
                                    <li><strong>DataTables:</strong> Tabel dengan fitur sorting, filtering, export</li>
                                    <li><strong>SweetAlert2:</strong> Alert dan notification yang lebih menarik</li>
                                    <li><strong>Bootstrap DatePicker:</strong> Date picker untuk input tanggal</li>
                                    <li><strong>Select2:</strong> Dropdown dengan search capability</li>
                                </ul>

                                <h4>3. Styling</h4>
                                <ul>
                                    <li><strong>Bootstrap 4:</strong> Framework CSS utama</li>
                                    <li><strong>Material Design:</strong> Material icons dan styling</li>
                                    <li><strong>Custom CSS:</strong> Di <code>assets/css/style.css</code></li>
                                    <li>Theme system dengan multiple color themes</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 7: Logika Bisnis -->
                    <div class="panel panel-col-purple">
                        <div class="panel-heading" role="tab" id="heading_teknis_7">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion_teknis" href="#collapse_teknis_7">
                                    <i class="material-icons">psychology</i> Logika Bisnis
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_teknis_7" class="panel-collapse collapse" role="tabpanel">
                            <div class="panel-body">
                                <h4>1. Presensi Masuk dan Pulang</h4>
                                <ul>
                                    <li>Presensi masuk: sebelum jam 12:00 (default)</li>
                                    <li>Presensi pulang: setelah jam 12:00</li>
                                    <li>Khusus Satpam: logika berbeda untuk piket malam (presensi setelah 16:30 bisa dianggap masuk untuk hari berikutnya)</li>
                                    <li>Validasi: tidak bisa presensi masuk dua kali dalam satu hari</li>
                                </ul>

                                <h4>2. Presensi Apel</h4>
                                <ul>
                                    <li>Hanya muncul pada hari Senin dan Jumat</li>
                                    <li>Waktu ditentukan dari konfigurasi sistem (mulai_apel_senin, selesai_apel_senin, dll)</li>
                                    <li>Satu pegawai hanya bisa presensi apel sekali per hari</li>
                                </ul>

                                <h4>3. Presensi Istirahat</h4>
                                <ul>
                                    <li>Tombol muncul 10 menit pertama waktu istirahat (mulai istirahat)</li>
                                    <li>Tombol muncul 10 menit terakhir waktu istirahat (selesai istirahat)</li>
                                    <li>Waktu berbeda untuk hari Jumat</li>
                                    <li>Harus sudah presensi masuk terlebih dahulu</li>
                                </ul>

                                <h4>4. Keterangan Presensi</h4>
                                <ul>
                                    <li>Manual Presensi, Tidak Presensi, Izin Terlambat Masuk</li>
                                    <li>Izin Tidak Masuk, Piket Malam, Work From Home</li>
                                    <li>Tanpa Keterangan, Dinas Luar, Cuti (berbagai jenis)</li>
                                    <li>Libur</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 8: Troubleshooting Teknis -->
                    <div class="panel panel-col-purple">
                        <div class="panel-heading" role="tab" id="heading_teknis_8">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion_teknis" href="#collapse_teknis_8">
                                    <i class="material-icons">bug_report</i> Troubleshooting Teknis
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_teknis_8" class="panel-collapse collapse" role="tabpanel">
                            <div class="panel-body">
                                <h4>1. Masalah Autentikasi</h4>
                                <ul>
                                    <li>Pastikan SSO server dapat diakses</li>
                                    <li>Cek konfigurasi cookie domain</li>
                                    <li>Pastikan session di CodeIgniter berfungsi dengan baik</li>
                                    <li>Cek log error di <code>application/logs/</code></li>
                                </ul>

                                <h4>2. Masalah Database</h4>
                                <ul>
                                    <li>Cek koneksi database di <code>application/config/database.php</code></li>
                                    <li>Pastikan semua tabel sudah dibuat</li>
                                    <li>Cek relasi foreign key jika ada masalah data</li>
                                </ul>

                                <h4>3. Masalah API</h4>
                                <ul>
                                    <li>Pastikan API endpoint dapat diakses</li>
                                    <li>Cek response dari API menggunakan browser developer tools</li>
                                    <li>Validasi format response sesuai dengan yang diharapkan</li>
                                    <li>Cek timeout settings jika API lambat</li>
                                </ul>

                                <h4>4. Masalah Frontend</h4>
                                <ul>
                                    <li>Clear browser cache</li>
                                    <li>Cek console browser untuk JavaScript errors</li>
                                    <li>Pastikan semua library JavaScript ter-load dengan benar</li>
                                    <li>Cek compatibility browser</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
.panel-col-purple {
    border-color: #9c27b0;
}
.panel-col-purple > .panel-heading {
    background-color: #9c27b0;
    color: #fff;
}
</style>

