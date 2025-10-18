<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="<?php echo base_url(); ?>assets/sneat/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Laporan Presensi Pulang</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/icon/siap32.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?= site_url('assets/plugins/bootstrap/css/bootstrap.css') ?>" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?= site_url('assets/plugins/node-waves/waves.css') ?>" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?= site_url('assets/plugins/animate-css/animate.css') ?>" rel="stylesheet" />

    <!-- Bootstrap DatePicker Css -->
    <link href="<?= site_url('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') ?>" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?= site_url('assets/css/style.css') ?>" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?= site_url('assets/css/themes/all-themes.css') ?>" rel="stylesheet" />
</head>

<body>
    <!-- Main content -->
    <section class="mt-40">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <table id="tabel1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <div class="thumbnail">
                                        <img src="<?= $this->session->userdata('kop_satker') ?>">
                                    </div>
                                </tr>
                                <tr>
                                    <div class="body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h5 class="font-underline align-center">DAFTAR HADIR PULANG
                                                    <?= $jenis ?>
                                                </h5>
                                                <h5 class="align-center">Hari
                                                    <?= $this->tanggalhelper->convertDayDate($this->session->userdata("tanggal")); ?>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                                <tr>
                                    <th class="text-center">NO</th>
                                    <th class="text-center">NIP</th>
                                    <th class="text-center">NAMA</th>
                                    <th class="text-center">JABATAN</th>
                                    <th class="text-center">KEHADIRAN</th>
                                    <th class="text-center">KETERANGAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($presensi as $item) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $no; ?>
                                        </td>
                                        <td>
                                            <?= $item->nip; ?>
                                        </td>
                                        <td>
                                            <?= $item->fullname; ?>
                                        </td>
                                        <td>
                                            <?= $item->jabatan; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            if ($item->pulang) {
                                                echo $this->tanggalhelper->konversiJam($item->pulang);
                                            } else {
                                                echo "-";
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
                        </table>
                    </div>
                    <i>Generated by </i>LITERASI MS BANDA ACEH<i> (<?= date('Y-m-d H:i:s') ?>)</i>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

    <script>
        window.onload = function () {
            window.print();
        };
    </script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

</body>

</html>