<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>
                DETAIL PRESENSI KEGIATAN
            </h2>
        </div>
        <div class="row">
            <div class="col">
                <ol class="breadcrumb align-right">
                    <li>
                        <a href="<?= site_url() ?>">
                            <i class="material-icons">home</i> Beranda
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('laporan_kegiatan') ?>">
                            <i class="material-icons">content_paste</i> Laporan Kegiatan Lainnya
                        </a>
                    </li>
                    <li class="active">
                        <a>
                            <i class="material-icons">content_paste</i> Laporan Daftar Peserta
                        </a>
                    </li>
                </ol>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-orange">
                        <h2>
                            <?= $kegiatan ?>
                            <small><?= $this->tanggalhelper->convertDayDate($tanggal); ?></small>
                        </h2>
                        <ul class="header-dropdown">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"
                                    role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li>
                                        <a class="waves-effect waves-block">
                                            <form action="<?= site_url('cetak_laporan_kegiatan') ?>" method="POST"
                                                target="blank">
                                                <input type="hidden" name="id" value="<?= base64_encode($this->encryption->encrypt($id)); ?>">
                                                <button type="submit" id="lapDetailKegiatan"
                                                    style="background: transparent; border: none !important;">
                                                    Cetak Laporan
                                                </button>
                                            </form>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table id="tblKegiatan" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>NAMA PEGAWAI</th>
                                        <th>WAKTU PRESENSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($peserta as $item) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?= $no; ?>
                                            </td>
                                            <td>
                                                <?= $item->nama; ?>
                                            </td>
                                            <td>
                                                <?= $item->waktu; ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $no++;
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>NO</th>
                                        <th>NAMA PEGAWAI</th>
                                        <th>WAKTU PRESENSI</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>