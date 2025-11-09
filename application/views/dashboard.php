<?php if ($this->session->userdata('peran') == 'admin') { ?>
    <div class="row">
        <div class="col">
            <div class="align-right">
                <button class="btn btn-success" onclick="ModalRole('-1')"><i class="material-icons">verified_user</i>
                    Peran</button>
            </div>
        </div>
    </div>
<?php } ?>

<div class="row">
    <div class="col">
        <ol class="breadcrumb align-right">
            <li>
                <a>
                    <i class="material-icons">home</i> Beranda
                    <?= $this->session->userdata('nama_client_app') ?>
                </a>
            </li>
        </ol>
    </div>
</div>

<?php 
// Inisialisasi default untuk mencegah error
if (!isset($presensi_hari_ini)) {
    $presensi_hari_ini = null;
}
if (!isset($presensi_apel_hari_ini)) {
    $presensi_apel_hari_ini = null;
}
if (!isset($statistik_bulan)) {
    $statistik_bulan = ['total_hari' => 0, 'sudah_presensi' => 0, 'belum_presensi' => 0, 'persentase' => 0];
}
if (!isset($statistik_hari_ini)) {
    $statistik_hari_ini = ['total_pegawai' => 0, 'sudah_presensi' => 0, 'belum_presensi' => 0, 'persentase' => 0];
}
if (!isset($presensi_terbaru)) {
    $presensi_terbaru = [];
}
if (!isset($kegiatan_mendatang)) {
    $kegiatan_mendatang = [];
}
?>

<!-- Status Presensi Hari Ini -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-light-blue">
                <h2>
                    <i class="material-icons">today</i> STATUS PRESENSI HARI INI
                    <small><?= $this->tanggalhelper->convertDayDate(date('Y-m-d')) ?></small>
                </h2>
            </div>
            <div class="body">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-green hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">check_circle</i>
                            </div>
                            <div class="content">
                                <div class="text">Presensi Masuk</div>
                                <div class="number count-to" data-from="0" data-to="<?= $presensi_hari_ini && $presensi_hari_ini->masuk ? 1 : 0 ?>" data-speed="1000" data-fresh-interval="20">
                                    <?= $presensi_hari_ini && $presensi_hari_ini->masuk ? $this->tanggalhelper->konversiJam($presensi_hari_ini->masuk) : 'Belum' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-orange hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">exit_to_app</i>
                            </div>
                            <div class="content">
                                <div class="text">Presensi Pulang</div>
                                <div class="number count-to" data-from="0" data-to="<?= $presensi_hari_ini && $presensi_hari_ini->pulang ? 1 : 0 ?>" data-speed="1000" data-fresh-interval="20">
                                    <?= $presensi_hari_ini && $presensi_hari_ini->pulang ? $this->tanggalhelper->konversiJam($presensi_hari_ini->pulang) : 'Belum' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-cyan hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">flag</i>
                            </div>
                            <div class="content">
                                <div class="text">Presensi Apel</div>
                                <div class="number count-to" data-from="0" data-to="<?= $presensi_apel_hari_ini ? 1 : 0 ?>" data-speed="1000" data-fresh-interval="20">
                                    <?= $presensi_apel_hari_ini ? 'Sudah' : 'Belum' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-blue-grey hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">restaurant</i>
                            </div>
                            <div class="content">
                                <div class="text">Istirahat</div>
                                <div class="number">
                                    <?php 
                                    if ($presensi_hari_ini && $presensi_hari_ini->istirahat_mulai) {
                                        echo $this->tanggalhelper->konversiJam($presensi_hari_ini->istirahat_mulai);
                                        if ($presensi_hari_ini->istirahat_selesai) {
                                            echo ' - ' . $this->tanggalhelper->konversiJam($presensi_hari_ini->istirahat_selesai);
                                        }
                                    } else {
                                        echo 'Belum';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (in_array($peran, ['admin', 'operator'])) { ?>
<!-- Statistik Presensi Hari Ini (Admin/Operator) -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-red">
                <h2>
                    <i class="material-icons">assessment</i> STATISTIK PRESENSI HARI INI
                </h2>
            </div>
            <div class="body">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-red hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">people</i>
                            </div>
                            <div class="content">
                                <div class="text">Total Pegawai</div>
                                <div class="number count-to" data-from="0" data-to="<?= $statistik_hari_ini['total_pegawai'] ?>" data-speed="1000" data-fresh-interval="20">
                                    <?= $statistik_hari_ini['total_pegawai'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-green hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">check_circle</i>
                            </div>
                            <div class="content">
                                <div class="text">Sudah Presensi</div>
                                <div class="number count-to" data-from="0" data-to="<?= $statistik_hari_ini['sudah_presensi'] ?>" data-speed="1000" data-fresh-interval="20">
                                    <?= $statistik_hari_ini['sudah_presensi'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-orange hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">warning</i>
                            </div>
                            <div class="content">
                                <div class="text">Belum Presensi</div>
                                <div class="number count-to" data-from="0" data-to="<?= $statistik_hari_ini['belum_presensi'] ?>" data-speed="1000" data-fresh-interval="20">
                                    <?= $statistik_hari_ini['belum_presensi'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-blue hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">trending_up</i>
                            </div>
                            <div class="content">
                                <div class="text">Persentase</div>
                                <div class="number count-to" data-from="0" data-to="<?= $statistik_hari_ini['persentase'] ?>" data-speed="1000" data-fresh-interval="20" data-suffix="%">
                                    <?= $statistik_hari_ini['persentase'] ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Presensi Terbaru (Admin/Operator) -->
<?php if (!empty($presensi_terbaru)) { ?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-teal">
                <h2>
                    <i class="material-icons">access_time</i> PRESENSI TERBARU HARI INI
                </h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pegawai</th>
                                <th>Jabatan</th>
                                <th>Waktu Masuk</th>
                                <th>Waktu Pulang</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1; 
                            foreach ($presensi_terbaru as $presensi) { 
                                $nama = isset($presensi->fullname) ? $presensi->fullname : 'N/A';
                                $jabatan = isset($presensi->jabatan) ? $presensi->jabatan : 'N/A';
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $nama ?></td>
                                <td><?= $jabatan ?></td>
                                <td>
                                    <?php if (isset($presensi->masuk) && $presensi->masuk) { ?>
                                        <span class="badge bg-green"><?= $this->tanggalhelper->konversiJam($presensi->masuk) ?></span>
                                    <?php } else { ?>
                                        <span class="badge bg-red">Belum</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if (isset($presensi->pulang) && $presensi->pulang) { ?>
                                        <span class="badge bg-orange"><?= $this->tanggalhelper->konversiJam($presensi->pulang) ?></span>
                                    <?php } else { ?>
                                        <span class="badge bg-red">Belum</span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<?php } ?>

<!-- Ringkasan Presensi Bulan Ini -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-purple">
                <h2>
                    <i class="material-icons">calendar_month</i> RINGKASAN PRESENSI BULAN INI
                    <small><?= $this->tanggalhelper->convertMonthDate(date('Y-m-d')) ?></small>
                </h2>
            </div>
            <div class="body">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-purple hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">event</i>
                            </div>
                            <div class="content">
                                <div class="text">Total Hari Kerja</div>
                                <div class="number count-to" data-from="0" data-to="<?= $statistik_bulan['total_hari'] ?>" data-speed="1000" data-fresh-interval="20">
                                    <?= $statistik_bulan['total_hari'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-green hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">check_circle</i>
                            </div>
                            <div class="content">
                                <div class="text">Sudah Presensi</div>
                                <div class="number count-to" data-from="0" data-to="<?= $statistik_bulan['sudah_presensi'] ?>" data-speed="1000" data-fresh-interval="20">
                                    <?= $statistik_bulan['sudah_presensi'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-orange hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">cancel</i>
                            </div>
                            <div class="content">
                                <div class="text">Belum Presensi</div>
                                <div class="number count-to" data-from="0" data-to="<?= $statistik_bulan['belum_presensi'] ?>" data-speed="1000" data-fresh-interval="20">
                                    <?= $statistik_bulan['belum_presensi'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box bg-blue hover-expand-effect">
                            <div class="icon">
                                <i class="material-icons">trending_up</i>
                            </div>
                            <div class="content">
                                <div class="text">Persentase</div>
                                <div class="number count-to" data-from="0" data-to="<?= $statistik_bulan['persentase'] ?>" data-speed="1000" data-fresh-interval="20" data-suffix="%">
                                    <?= $statistik_bulan['persentase'] ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kegiatan Mendatang -->
<?php if (!empty($kegiatan_mendatang)) { ?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-orange">
                <h2>
                    <i class="material-icons">event_note</i> KEGIATAN MENDATANG
                </h2>
            </div>
            <div class="body">
                <div class="list-group">
                    <?php foreach ($kegiatan_mendatang as $kegiatan) { ?>
                    <a href="javascript:;" class="list-group-item">
                        <h4 class="list-group-item-heading">
                            <i class="material-icons">event</i> <?= $kegiatan->nama_kegiatan ?>
                        </h4>
                        <p class="list-group-item-text">
                            <i class="material-icons">calendar_today</i> 
                            <?= $this->tanggalhelper->convertDayDate($kegiatan->tanggal) ?>
                        </p>
                    </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<!-- Informasi Versi Aplikasi -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-green">
                <h2><i class="material-icons">announcement</i>INFORMASI APLIKASI</h2>
            </div>
            <div class="body">
                <div class="panel-group" id="accordion_11" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-col-teal">
                        <div class="panel-heading" role="tab" id="headingOne_112">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion_11" href="#collapseOne_112"
                                    aria-expanded="true" aria-controls="collapseOne_112">
                                    APLIKASI PRESENSI PEGAWAI versi 1.1.2
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne_112" class="panel-collapse collapse in" role="tabpanel"
                            aria-labelledby="headingOne_112">
                            <div class="panel-body">
                                <h4>Penambahan Fitur :</h4>
                                <ol>
                                    <li>Integrasi dengan API tanggal merah dari aplikasi Seudati untuk perhitungan hari kerja yang lebih akurat.
                                    </li>
                                    <li>Perhitungan hari kerja otomatis mengecualikan hari Sabtu, Minggu, dan tanggal merah (hari libur nasional).
                                    </li>
                                    <li>Penambahan menu Panduan Penggunaan berdasarkan peran (Admin, Operator, Pengguna).
                                    </li>
                                    <li>Penambahan menu Dokumentasi Teknis untuk Admin.
                                    </li>
                                </ol>
                                <h4>Optimasi :</h4>
                                <ol>
                                    <li>Dashboard diperkaya dengan informasi statistik presensi yang lebih lengkap.
                                    </li>
                                    <li>Perhitungan statistik presensi bulanan menggunakan data hari kerja yang akurat (tidak termasuk hari libur).
                                    </li>
                                    <li>Caching data tanggal merah untuk meningkatkan performa aplikasi.
                                    </li>
                                </ol>
                                Buku Panduan penggunaan aplikasi dapat di unduh melalui <a
                                    href="https://drive.google.com/file/d/15TFYfLGWsq40X9B5wHVbtCGvD51b6sQo/view?usp=drive_link">tautan
                                    ini</a>.
                            </div>
                        </div>
                        <div class="panel-heading" role="tab" id="headingOne_111">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion_11" href="#collapseOne_111"
                                    aria-expanded="false" aria-controls="collapseOne_111">
                                    APLIKASI PRESENSI PEGAWAI versi 1.1.1
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne_111" class="panel-collapse collapse" role="tabpanel"
                            aria-labelledby="headingOne_111">
                            <div class="panel-body">
                                <h4>Optimasi :</h4>
                                <ol>
                                    <li>Pemisahan Laporan Presensi Pegawai antara PNS dan PPPK.
                                    </li>
                                </ol>
                                Buku Panduan penggunaan aplikasi dapat di unduh melalui <a
                                    href="https://drive.google.com/file/d/15TFYfLGWsq40X9B5wHVbtCGvD51b6sQo/view?usp=drive_link">tautan
                                    ini</a>.
                            </div>
                        </div>
                        <div class="panel-heading" role="tab" id="headingOne_110">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion_11" href="#collapseOne_110"
                                    aria-expanded="true" aria-controls="collapseOne_110">
                                    APLIKASI PRESENSI PEGAWAI versi 1.1.0
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne_110" class="panel-collapse collapse" role="tabpanel"
                            aria-labelledby="headingOne_110">
                            <div class="panel-body">
                                <h4>Perbaikan Bug :</h4>
                                <ol>
                                    <li>Penambahan Kontrol Presensi Apabila Pegawai Melakukan Presensi Dua Kali.
                                    </li>
                                </ol>
                                <h4>Optimasi :</h4>
                                <ol>
                                    <li>Optimasi Fitur Presensi bagi Satpam Yang Piket Malam.
                                    </li>
                                    <li>Optimasi Laporan Presensi Seluruh Pegawai.
                                    </li>
                                    <li>Optimasi Notifikasi Presensi Pegawai.
                                    </li>
                                </ol>
                                <h4>Penambahan Fitur :</h4>
                                <ol>
                                    <li>Fitur Presensi Apel Senin Pagi dan Jumat Sore beserta Laporannya.
                                    </li>
                                    <li>Fitur Cetak Presensi Kehadiran Bagi Petugas Presensi.
                                    </li>
                                </ol>
                                Buku Panduan penggunaan aplikasi dapat di unduh melalui <a
                                    href="https://drive.google.com/file/d/15TFYfLGWsq40X9B5wHVbtCGvD51b6sQo/view?usp=drive_link">tautan
                                    ini</a>.
                            </div>
                        </div>
                        <div class="panel-heading" role="tab" id="headingOne_100">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion_11" href="#collapseOne_100"
                                    aria-expanded="true" aria-controls="collapseOne_100">
                                    APLIKASI PRESENSI PEGAWAI versi 1.0.0
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne_100" class="panel-collapse collapse" role="tabpanel"
                            aria-labelledby="headingOne_100">
                            <div class="panel-body">
                                <h4>Fitur Utama :</h4>
                                <ol>
                                    <li>Rekam presensi harian datang dan pulang bagi seluruh Pegawai.
                                    </li>
                                    <li>Laporan Informasi presensi selama satu bulan.
                                    </li>
                                </ol>
                                Buku Panduan penggunaan aplikasi dapat di unduh melalui <a
                                    href="https://drive.google.com/file/d/15TFYfLGWsq40X9B5wHVbtCGvD51b6sQo/view?usp=drive_link">tautan
                                    ini</a>.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="role-pegawai" data-backdrop="static">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Daftar Operator Kepegawaian</h5>
            </div>
            <form method="POST" id="formSM" action="<?= site_url('simpan_peran') ?>">
                <input type="hidden" id="id" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <h5 class="form-label">Pilih Pegawai : </h5>
                        <div id="pegawai_">
                        </div>
                    </div>
                    <div class="form-group">
                        <h5 class="form-label">Pilih Peran : </h5>
                        <div id="peran_"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnSimpanKegiatan" class="btn btn-link waves-effect">Simpan
                        Operator</button>
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Tutup</button>
                </div>
            </form>

            <div class="modal-body" id="tabel-role">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>