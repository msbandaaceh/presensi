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
<div class="row justify-content-center">
    <div class="card">
        <div class="header bg-green">
            <h2><i class="material-icons">announcement</i>INFORMASI</h2>
        </div>
        <div class="body">
            <div class="panel-group" id="accordion_11" role="tablist" aria-multiselectable="true">
                <div class="panel panel-col-teal">
                    <div class="panel-heading" role="tab" id="headingOne_111">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion_11" href="#collapseOne_111"
                                aria-expanded="true" aria-controls="collapseOne_111">
                                APLIKASI PRESENSI PEGAWAI versi 1.1.1
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne_111" class="panel-collapse collapse in" role="tabpanel"
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