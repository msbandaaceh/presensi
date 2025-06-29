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
                            <i class="material-icons">content_paste</i> Laporan Harian
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
                            LAPORAN PRESENSI PRIBADI
                        </h2>
                    </div>
                    <div class="body">
                        <form action="<?= site_url('laporan_bulan') ?>" method="POST">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <div class="row clearfix">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <?php
                                        $tgl = $this->session->userdata("tanggal");
                                        //die(var_dump($tgl));
                                        
                                        $bulan = DateTime::createFromFormat('Y-m', $tgl);
                                        //die(var_dump($bulan));
                                        $konversi_tgl = $bulan->format('F Y');
                                        ?>
                                        <div class="form-line" id="bs_datepicker_container_pribadi">
                                            <input type="text" id="bulan" name="bulan" class="form-control"
                                                placeholder="Pilih Tanggal" value="<?= $konversi_tgl ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn bg-green waves-effect">
                                        <i class="material-icons">search</i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table id="tblPresensiPribadi" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>TANGGAL PRESENSI</th>
                                        <th>PRESENSI MASUK</th>
                                        <th>PRESENSI PULANG</th>
                                        <th>KETERANGAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($presensi as $item) {
                                        $weekend = $this->tanggalhelper->convertDayDate($item->Date);
                                        ?>
                                        <tr>
                                            <td>
                                                <?= $no; ?>
                                            </td>
                                            <td>
                                                <?= $this->tanggalhelper->convertDayDate($item->Date); ?>
                                            </td>
                                            <td>
                                                <?php
                                                //echo strpos($weekend,"abtu");
                                                if (strpos($weekend, "abtu") || strpos($weekend, "inggu")) {
                                                    if ($this->session->userdata('id_jabatan') == '31') {
                                                        if (strpos($weekend, "inggu") && $item->ket == "Piket Malam") {
                                                            echo "<span class='label label-success'>" . $this->tanggalhelper->konversiJam($item->masuk);
                                                        } else {
                                                            echo "<span class='label label-warning'>Hari Libur</span>";
                                                        }
                                                    } else {
                                                        echo "<span class='label label-warning'>Hari Libur</span>";
                                                    }
                                                } else {
                                                    if ($item->masuk) {
                                                        echo "<span class='label label-success'>" . $this->tanggalhelper->konversiJam($item->masuk) . "</span>";
                                                    } else {
                                                        echo "<span class='label label-danger'>Belum Presensi</span>";
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if (strpos($weekend, "abtu") != 0 || strpos($weekend, "inggu") != 0) {
                                                    if ($this->session->userdata('id_jabatan') == '31') {
                                                        if (strpos($weekend, "inggu") && $item->ket == "Piket Malam") {
                                                            echo "<span class='label label-success'>" . $this->tanggalhelper->konversiJam($item->pulang);
                                                        } else {
                                                            echo "<span class='label label-warning'>Hari Libur</span>";
                                                        }
                                                    } else {
                                                        echo "<span class='label label-warning'>Hari Libur</span>";
                                                    }
                                                } else {
                                                    if ($item->pulang) {
                                                        echo "<span class='label label-success'>" . $this->tanggalhelper->konversiJam($item->pulang) . "</span>";
                                                    } else {
                                                        echo "<span class='label label-danger'>Belum Presensi</span>";
                                                    }
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>