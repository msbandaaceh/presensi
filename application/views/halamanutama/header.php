<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title><?= $this->session->userdata('nama_app') ?> | <?= $this->session->userdata('deskripsi') ?></title>

    <meta name="description" content="HADIR-IN (Sistem Informasi Manajemen Presensi Pegawai)" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= site_url('assets/icon/siap32.png'); ?>" />

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

    <!-- Bootstrap Select Css -->
    <link href="<?= site_url('assets/plugins/bootstrap-select/css/bootstrap-select.css') ?>" rel="stylesheet" />

    <!-- Sweetalert Css -->
    <link href="<?= site_url('assets/plugins/sweetalert/sweetalert.css') ?>" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?= site_url('assets/css/style.css') ?>" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?= site_url('assets/css/themes/all-themes.css') ?>" rel="stylesheet" />

    <!-- DataTables -->
    <?php
    if (in_array($page, ['kegiatan', 'laporan_kegiatan'])) {
        ?>
        <link rel="stylesheet" href="<?= site_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
        <link rel="stylesheet"
            href="<?= site_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
        <link rel="stylesheet" href="<?= site_url('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
    <?php } ?>

    <!-- Bootstrap Material Datetime Picker Css -->
    <link
        href="<?= site_url('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') ?>"
        rel="stylesheet" />

    <!-- Bootstrap DatePicker Css -->
    <link href="<?= site_url('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') ?>" rel="stylesheet" />
    <style>
        /* Define keyframes for the glow animation */
        @keyframes glowing {
            0% {
                box-shadow: 0 0 10px #fff;
            }

            50% {
                box-shadow: 0 0 20px #00BFFF;
            }

            100% {
                box-shadow: 0 0 10px #fff;
            }
        }

        /* Apply the glow animation to the link when it's hovered over */
        #tambah,
        #apel {
            animation: glowing 1.5s infinite;
        }
    </style>
</head>

<body class="theme-teal overlay-open">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Mohon Tunggu Sebentar...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->