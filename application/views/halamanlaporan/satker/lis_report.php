<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <ol class="breadcrumb align-right">
                    <li>
                        <a href="<?= site_url() ?>">
                            <i class="material-icons">home</i> Beranda
                        </a>
                    </li>
                    <li class="active">
                        <a>
                            <i class="material-icons">content_paste</i> Laporan Satuan Kerja
                        </a>
                    </li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header bg-light-blue">
                        <div class="p-4">
                            <h2>
                                LAPORAN PRESENSI SATUAN KERJA
                            </h2>
                            <?php
                            if (in_array($peran, ['admin', 'petugas'])) {
                                ?>
                                <ul class="header-dropdown">
                                    <li class="dropdown">
                                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"
                                            role="button" aria-haspopup="true" aria-expanded="false">
                                            <i class="material-icons">more_vert</i>
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                            <li>
                                                <a class="waves-effect waves-block">
                                                    <button id="lapPegawai"
                                                        style="background: transparent; border: none !important;">
                                                        Laporan Pegawai
                                                    </button>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="body">
                        <?php $idpres = "-1"; ?>
                        <form action="<?= site_url('laporan_satker_bulan') ?>" method="POST">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php
                                        $tgl = $this->session->userdata("tanggal");
                                        $bulan = DateTime::createFromFormat('Y-m-d', $tgl);
                                        $konversi_tgl = $bulan->format('d F Y');
                                        ?>
                                        <div class="form-line" id="bs_datepicker_container">
                                            <input type="hidden" id="bulanSatker" value="<?= $tgl ?>">
                                            <input type="text" name="bulan" class="form-control"
                                                placeholder="Pilih Tanggal" value="<?= $konversi_tgl ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-12 col-xs-12">
                                    <button type="submit" class="btn btn-block bg-green waves-effect">
                                        <i class="material-icons">search</i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs tab-nav-right" role="tablist">
                            <li role="presentation">
                                <h5>JENIS PEGAWAI</h5>
                            </li>
                            <li role="presentation" class="active"><a href="#hakim" data-toggle="tab">HAKIM</a></li>
                            <li role="presentation"><a href="#cakim" data-toggle="tab">CALON HAKIM</a>
                            </li>
                            <li role="presentation"><a href="#pns" data-toggle="tab">PNS</a></li>
                            <li role="presentation"><a href="#pppk" data-toggle="tab">PPPK</a></li>
                            <li role="presentation"><a href="#honor" data-toggle="tab">HONORER</a></li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="hakim">
                                <table id="tabel_hakim" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAMA</th>
                                            <th>PRESENSI MASUK</th>
                                            <th>PRESENSI PULANG</th>
                                            <th>KETERANGAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($hakim as $item) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?= $no; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($peran == 'operator') {
                                                        ?>
                                                        <p><?= $item->fullname; ?></p>
                                                        <?php
                                                    } else {
                                                        if ($item->id_presensi != null) {
                                                            $idpres = $item->id_presensi;
                                                        } else {
                                                            $idpres = '-1';
                                                        } ?>
                                                        <button
                                                            onclick="EditModal('<?php echo base64_encode($this->encryption->encrypt($idpres)); ?>', '<?php echo base64_encode($this->encryption->encrypt($item->userid)); ?>')"
                                                            style="background: transparent; border: none !important;">
                                                            <p class="font-bold col-blue"><?= $item->fullname; ?></p>
                                                        </button>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($item->masuk) {
                                                        echo "<span class='badge bg-green'>" . $this->tanggalhelper->konversiJam($item->masuk) . "</span>";
                                                    } else {
                                                        echo "<span class='badge bg-red'>Belum Presensi</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($item->pulang) {
                                                        echo "<span class='badge bg-green'>" . $this->tanggalhelper->konversiJam($item->pulang) . "</span>";
                                                    } else {
                                                        echo "<span class='badge bg-red'>Belum Presensi</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?= $item->ket; ?>
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
                                            <th>TANGGAL PRESENSI</th>
                                            <th>PRESENSI MASUK</th>
                                            <th>PRESENSI PULANG</th>
                                            <th>KETERANGAN</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="cakim">
                                <table id="tabel_cakim" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAMA</th>
                                            <th>PRESENSI MASUK</th>
                                            <th>PRESENSI PULANG</th>
                                            <th>KETERANGAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($cakim as $item) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?= $no; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($peran == 'petugas') {
                                                        ?>
                                                        <p><?= $item->fullname; ?></p>
                                                        <?php
                                                    } else {
                                                        if ($item->id_presensi != null) {
                                                            $idpres = $item->id_presensi;
                                                        } else {
                                                            $idpres = '-1';
                                                        } ?>
                                                        <button
                                                            onclick="EditModal('<?php echo base64_encode($this->encryption->encrypt($idpres)); ?>', '<?php echo base64_encode($this->encryption->encrypt($item->userid)); ?>')"
                                                            style="background: transparent; border: none !important;">
                                                            <p class="font-bold col-blue"><?= $item->fullname; ?></p>
                                                        </button>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($item->masuk) {
                                                        echo "<span class='badge bg-green'>" . $this->tanggalhelper->konversiJam($item->masuk) . "</span>";
                                                    } else {
                                                        echo "<span class='badge bg-red'>Belum Presensi</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($item->pulang) {
                                                        echo "<span class='badge bg-green'>" . $this->tanggalhelper->konversiJam($item->pulang) . "</span>";
                                                    } else {
                                                        echo "<span class='badge bg-red'>Belum Presensi</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?= $item->ket; ?>
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
                                            <th>TANGGAL PRESENSI</th>
                                            <th>PRESENSI MASUK</th>
                                            <th>PRESENSI PULANG</th>
                                            <th>KETERANGAN</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="pns">
                                <table id="tabel_pns" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAMA</th>
                                            <th>PRESENSI MASUK</th>
                                            <th>PRESENSI PULANG</th>
                                            <th>KETERANGAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($pns as $item) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?= $no; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($peran == 'petugas') {
                                                        ?>
                                                        <p><?= $item->fullname; ?></p>
                                                        <?php
                                                    } else {
                                                        if ($item->id_presensi != null) {
                                                            $idpres = $item->id_presensi;
                                                        } else {
                                                            $idpres = '-1';
                                                        } ?>
                                                        <button
                                                            onclick="EditModal('<?php echo base64_encode($this->encryption->encrypt($idpres)); ?>', '<?php echo base64_encode($this->encryption->encrypt($item->userid)); ?>')"
                                                            style="background: transparent; border: none !important;">
                                                            <p class="font-bold col-blue"><?= $item->fullname; ?></p>
                                                        </button>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($item->masuk) {
                                                        echo "<span class='badge bg-green'>" . $this->tanggalhelper->konversiJam($item->masuk) . "</span>";
                                                    } else {
                                                        echo "<span class='badge bg-red'>Belum Presensi</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($item->pulang) {
                                                        echo "<span class='badge bg-green'>" . $this->tanggalhelper->konversiJam($item->pulang) . "</span>";
                                                    } else {
                                                        echo "<span class='badge bg-red'>Belum Presensi</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?= $item->ket; ?>
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
                                            <th>NAMA</th>
                                            <th>PRESENSI MASUK</th>
                                            <th>PRESENSI PULANG</th>
                                            <th>KETERANGAN</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="pppk">
                                <table id="tabel_pppk" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAMA</th>
                                            <th>PRESENSI MASUK</th>
                                            <th>PRESENSI PULANG</th>
                                            <th>KETERANGAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($pppk as $item) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?= $no; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($peran == 'petugas') {
                                                        ?>
                                                        <p><?= $item->fullname; ?></p>
                                                        <?php
                                                    } else {
                                                        if ($item->id_presensi != null) {
                                                            $idpres = $item->id_presensi;
                                                        } else {
                                                            $idpres = '-1';
                                                        } ?>
                                                        <button
                                                            onclick="EditModal('<?php echo base64_encode($this->encryption->encrypt($idpres)); ?>', '<?php echo base64_encode($this->encryption->encrypt($item->userid)); ?>')"
                                                            style="background: transparent; border: none !important;">
                                                            <p class="font-bold col-blue"><?= $item->fullname; ?></p>
                                                        </button>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($item->masuk) {
                                                        echo "<span class='badge bg-green'>" . $this->tanggalhelper->konversiJam($item->masuk) . "</span>";
                                                    } else {
                                                        echo "<span class='badge bg-red'>Belum Presensi</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($item->pulang) {
                                                        echo "<span class='badge bg-green'>" . $this->tanggalhelper->konversiJam($item->pulang) . "</span>";
                                                    } else {
                                                        echo "<span class='badge bg-red'>Belum Presensi</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?= $item->ket; ?>
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
                                            <th>NAMA</th>
                                            <th>PRESENSI MASUK</th>
                                            <th>PRESENSI PULANG</th>
                                            <th>KETERANGAN</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="honor">
                                <table id="tabel_honor" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAMA</th>
                                            <th>PRESENSI MASUK</th>
                                            <th>PRESENSI PULANG</th>
                                            <th>KETERANGAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($honor as $item) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?= $no; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($peran == 'petugas') {
                                                        ?>
                                                        <p><?= $item->fullname; ?></p>
                                                        <?php
                                                    } else {
                                                        if ($item->id_presensi != null) {
                                                            $idpres = $item->id_presensi;
                                                        } else {
                                                            $idpres = '-1';
                                                        } ?>
                                                        <button
                                                            onclick="EditModal('<?php echo base64_encode($this->encryption->encrypt($idpres)); ?>', '<?php echo base64_encode($this->encryption->encrypt($item->userid)); ?>')"
                                                            style="background: transparent; border: none !important;">
                                                            <p class="font-bold col-blue"><?= $item->fullname; ?></p>
                                                        </button>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($item->masuk) {
                                                        echo "<span class='badge bg-green'>" . $this->tanggalhelper->konversiJam($item->masuk) . "</span>";
                                                    } else {
                                                        echo "<span class='badge bg-red'>Belum Presensi</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($item->pulang) {
                                                        echo "<span class='badge bg-green'>" . $this->tanggalhelper->konversiJam($item->pulang) . "</span>";
                                                    } else {
                                                        echo "<span class='badge bg-red'>Belum Presensi</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?= $item->ket; ?>
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
                                            <th>NAMA</th>
                                            <th>PRESENSI MASUK</th>
                                            <th>PRESENSI PULANG</th>
                                            <th>KETERANGAN</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="dataModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="align-center">Laporan Presensi Pegawai</h4>
            </div>
            <form action="<?= site_url('laporan_all') ?>" target="blank" method="POST">
                <div class="modal-header">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="form-line" id="bs_datepicker_container1">
                                    <input class="form-control" type="text" name="tgl_awal"
                                        placeholder="Pilih Tanggal Awal..." autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="form-line" id="bs_datepicker_container1">
                                    <input class="form-control" type="text" name="tgl_akhir"
                                        placeholder="Pilih Tanggal Akhir..." autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="pegawai_">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12 align-center">
                                <button type="submit" class="btn btn-info waves-effect">CARI DATA</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>

<div class="modal fade" id="editModal" data-backdrop="static">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content modal-col-grey">
            <form action="<?= base_url() ?>simpan_edit" method="POST">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                    value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="modal-header">
                    <h2 class="modal-title" id="judul"></h2>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id" class="form-control" readonly>
                    <input type="hidden" id="userid" name="userid" class="form-control" readonly>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-line">
                                    <input type="hidden" name="tgl" class="bg-grey form-control" readonly
                                        value="<?= $this->session->userdata('tanggal'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>
                                    <p>Jam Masuk</p>
                                </label>
                                <div class="form-line">
                                    <input type="text" id="masuk" name="masuk" class="bg-grey timepicker form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>
                                    <p>Jam Pulang</p>
                                </label>
                                <div class="form-line">
                                    <input type="text" id="pulang" name="pulang"
                                        class="bg-grey timepicker form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <label>
                                <p>Keterangan</p>
                            </label>
                            <div id="ket_" class="col-md-12">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-link waves-effect">SIMPAN</button>
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.content -->