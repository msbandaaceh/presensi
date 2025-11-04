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
                    <i class="material-icons">help</i> Panduan Pengguna
                </a>
            </li>
        </ol>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-light-blue">
                <h2>
                    <i class="material-icons">person</i> PANDUAN PENGGUNAAN UNTUK PEGAWAI
                </h2>
            </div>
            <div class="body">
                <div class="panel-group" id="accordion_user" role="tablist" aria-multiselectable="true">
                    
                    <!-- Bagian 1: Presensi -->
                    <div class="panel panel-col-light-blue">
                        <div class="panel-heading" role="tab" id="heading_user_1">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion_user" href="#collapse_user_1" aria-expanded="true">
                                    <i class="material-icons">alarm_on</i> Presensi Kehadiran
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_user_1" class="panel-collapse collapse in" role="tabpanel">
                            <div class="panel-body">
                                <h4>1. Presensi Masuk</h4>
                                <ol>
                                    <li>Klik tombol <strong>"Presensi Kehadiran"</strong> di menu sidebar (tombol biru dengan animasi)</li>
                                    <li>Sistem akan menampilkan modal presensi dengan peta lokasi</li>
                                    <li>Pastikan lokasi GPS Anda aktif dan berada dalam area kantor yang ditentukan</li>
                                    <li>Jika lokasi tidak terdeteksi, pastikan browser mengizinkan akses lokasi</li>
                                    <li>Klik <strong>"Simpan Presensi"</strong> untuk menyimpan presensi masuk</li>
                                    <li>Presensi masuk biasanya dilakukan sebelum jam 12:00</li>
                                    <li>Setelah berhasil, waktu presensi masuk akan ditampilkan</li>
                                </ol>
                                
                                <h4>2. Presensi Pulang</h4>
                                <ol>
                                    <li>Klik tombol <strong>"Presensi Kehadiran"</strong> di menu sidebar</li>
                                    <li>Sistem akan menampilkan status presensi masuk dan pulang Anda</li>
                                    <li>Jika sudah presensi masuk, klik <strong>"Simpan Presensi"</strong> untuk menyimpan presensi pulang</li>
                                    <li>Presensi pulang biasanya dilakukan setelah jam 12:00</li>
                                    <li>Setelah berhasil, waktu presensi pulang akan ditampilkan</li>
                                </ol>

                                <h4>3. Presensi Apel</h4>
                                <ol>
                                    <li>Tombol <strong>"Presensi Apel"</strong> akan muncul otomatis pada hari Senin dan Jumat pada waktu yang ditentukan</li>
                                    <li>Tombol akan muncul dengan warna cyan (biru muda)</li>
                                    <li>Klik tombol tersebut untuk melakukan presensi apel</li>
                                    <li>Presensi apel hanya bisa dilakukan sekali per hari</li>
                                    <li>Jika sudah presensi apel, tombol tidak akan muncul lagi</li>
                                </ol>

                                <h4>4. Presensi Istirahat</h4>
                                <ol>
                                    <li>Tombol <strong>"Presensi Istirahat"</strong> akan muncul pada waktu istirahat yang ditentukan</li>
                                    <li>Tombol akan muncul dengan warna abu-abu (blue-grey)</li>
                                    <li>Klik tombol untuk mulai istirahat (muncul pada 10 menit pertama waktu istirahat)</li>
                                    <li>Klik tombol lagi untuk selesai istirahat (muncul pada 10 menit terakhir waktu istirahat)</li>
                                    <li>Waktu istirahat berbeda untuk hari Jumat</li>
                                    <li>Anda harus sudah presensi masuk terlebih dahulu sebelum bisa presensi istirahat</li>
                                </ol>

                                <h4>5. Presensi Kegiatan Lainnya</h4>
                                <ol>
                                    <li>Tombol <strong>"Presensi Lainnya"</strong> muncul jika ada kegiatan yang dijadwalkan pada hari tersebut</li>
                                    <li>Tombol akan muncul dengan warna orange</li>
                                    <li>Klik tombol untuk membuka modal presensi</li>
                                    <li>Pilih kegiatan dari dropdown yang tersedia</li>
                                    <li>Klik <strong>"Simpan Presensi"</strong> untuk menyimpan</li>
                                    <li>Setiap kegiatan hanya bisa dipresensi sekali</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 2: Laporan -->
                    <div class="panel panel-col-light-blue">
                        <div class="panel-heading" role="tab" id="heading_user_2">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion_user" href="#collapse_user_2">
                                    <i class="material-icons">assessment</i> Laporan Presensi Pribadi
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_user_2" class="panel-collapse collapse" role="tabpanel">
                            <div class="panel-body">
                                <h4>1. Melihat Laporan Harian</h4>
                                <ol>
                                    <li>Klik menu <strong>"Laporan Presensi"</strong> → <strong>"Laporan Harian"</strong></li>
                                    <li>Pilih bulan yang ingin dilihat menggunakan date picker</li>
                                    <li>Klik tombol <strong>"Cari"</strong> (ikon search) untuk menampilkan data</li>
                                    <li>Data akan menampilkan presensi masuk dan pulang per hari dalam bulan tersebut</li>
                                    <li>Anda dapat melihat status presensi: masuk, pulang, dan keterangan (jika ada)</li>
                                </ol>

                                <h4>2. Memahami Tabel Laporan</h4>
                                <ul>
                                    <li><strong>Tanggal:</strong> Hari dan tanggal presensi</li>
                                    <li><strong>Masuk:</strong> Waktu presensi masuk</li>
                                    <li><strong>Pulang:</strong> Waktu presensi pulang</li>
                                    <li><strong>Keterangan:</strong> Status atau catatan khusus (jika ada)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 3: Troubleshooting -->
                    <div class="panel panel-col-light-blue">
                        <div class="panel-heading" role="tab" id="heading_user_3">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion_user" href="#collapse_user_3">
                                    <i class="material-icons">build</i> Masalah yang Sering Terjadi
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_user_3" class="panel-collapse collapse" role="tabpanel">
                            <div class="panel-body">
                                <h4>1. Lokasi Tidak Terdeteksi</h4>
                                <ul>
                                    <li>Pastikan GPS pada perangkat Anda aktif</li>
                                    <li>Izinkan browser mengakses lokasi Anda saat diminta</li>
                                    <li>Pastikan Anda berada dalam area kantor yang ditentukan</li>
                                    <li>Jika menggunakan komputer, pastikan memiliki akses WiFi atau koneksi internet</li>
                                </ul>

                                <h4>2. Presensi Gagal Disimpan</h4>
                                <ul>
                                    <li>Periksa koneksi internet Anda</li>
                                    <li>Pastikan Anda belum melakukan presensi pada waktu yang sama</li>
                                    <li>Refresh halaman dan coba lagi</li>
                                    <li>Jika masalah berlanjut, hubungi admin atau operator</li>
                                </ul>

                                <h4>3. Tombol Presensi Tidak Muncul</h4>
                                <ul>
                                    <li>Pastikan waktu Anda sesuai dengan jadwal presensi</li>
                                    <li>Untuk presensi apel, hanya muncul pada hari Senin dan Jumat</li>
                                    <li>Untuk presensi istirahat, hanya muncul pada waktu yang ditentukan</li>
                                    <li>Refresh halaman untuk memperbarui tampilan</li>
                                </ul>

                                <h4>4. Data Presensi Tidak Sesuai</h4>
                                <ul>
                                    <li>Hubungi admin atau operator untuk memperbaiki data presensi</li>
                                    <li>Siapkan informasi: tanggal, waktu yang seharusnya, dan bukti jika ada</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 4: Tips -->
                    <div class="panel panel-col-light-blue">
                        <div class="panel-heading" role="tab" id="heading_user_4">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion_user" href="#collapse_user_4">
                                    <i class="material-icons">lightbulb</i> Tips Penting
                                </a>
                            </h4>
                        </div>
                        <div id="collapse_user_4" class="panel-collapse collapse" role="tabpanel">
                            <div class="panel-body">
                                <ul>
                                    <li><strong>Presensi Tepat Waktu:</strong> Lakukan presensi masuk tepat waktu untuk menghindari keterlambatan</li>
                                    <li><strong>Presensi Pulang:</strong> Jangan lupa melakukan presensi pulang sebelum meninggalkan kantor</li>
                                    <li><strong>Cek Laporan:</strong> Secara rutin cek laporan harian untuk memastikan semua presensi tercatat</li>
                                    <li><strong>Koneksi Internet:</strong> Pastikan koneksi internet stabil saat melakukan presensi</li>
                                    <li><strong>Browser:</strong> Gunakan browser terbaru (Chrome, Firefox, Edge) untuk pengalaman terbaik</li>
                                    <li><strong>Perangkat:</strong> Jika menggunakan smartphone, pastikan baterai cukup dan koneksi stabil</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

