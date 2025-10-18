<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title><?= $this->session->userdata('nama_client_app') ?> | <?= $this->session->userdata('deskripsi_client_app') ?>
    </title>

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
    <link rel="stylesheet" href="<?= site_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
    <link rel="stylesheet"
        href="<?= site_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= site_url('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">

    <link href="<?= site_url('assets/plugins/select2/css/select2.min.css') ?>" rel="stylesheet" />
    <!-- Bootstrap Material Datetime Picker Css -->
    <link
        href="<?= site_url('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') ?>"
        rel="stylesheet" />

    <!-- Bootstrap DatePicker Css -->
    <link href="<?= site_url('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') ?>" rel="stylesheet" />

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Leaflet Draw (plugin untuk edit polygon) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
    <script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>
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

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="<?= site_url() ?>">Presensi Pegawai - Beranda</a>
            </div>
        </div>
    </nav>
    <!-- /.navbar -->

    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="<?= $this->session->userdata('foto') ?>" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= $this->session->userdata("fullname"); ?>
                    </div>
                    <div class="email"><?= $this->session->userdata("jabatan"); ?></div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li id="dashboard">
                        <a href="javascript:;" data-page="dashboard">
                            <i class="material-icons">home</i>
                            <span>Beranda</span>
                        </a>
                    </li>
                    <li>
                        <a type="button" id="tambah" class="btn btn-block btn-lg bg-light-blue waves-effect">
                            <p class="align-left">
                                <i class="material-icons">alarm_on</i>
                                <span class="col-white">Presensi Kehadiran</span>
                            </p>
                        </a>
                    </li>
                    <?php
                    $hari = $this->tanggalhelper->convertDayDate(date("Y-m-d"));
                    $jam = strtotime(date('H:i'));
                    $mulaiApelSenin = strtotime($this->session->userdata("mulai_apel_senin"));
                    $selesaiApelSenin = strtotime($this->session->userdata("selesai_apel_senin"));
                    $mulaiApelJumat = strtotime($this->session->userdata("mulai_apel_jumat"));
                    $selesaiApelJumat = strtotime($this->session->userdata("selesai_apel_jumat"));
                    $mulaiIstirahat = strtotime($this->session->userdata("mulai_istirahat"));
                    $selesaiIstirahat = strtotime($this->session->userdata("selesai_istirahat"));
                    $mulaiIstirahatJumat = strtotime($this->session->userdata("mulai_istirahat_jumat"));
                    $selesaiIstirahatJumat = strtotime($this->session->userdata("selesai_istirahat_jumat"));
                    if (strpos($hari, "enin") && ($jam >= $mulaiApelSenin && $jam <= $selesaiApelSenin) || strpos($hari, "umat") && ($jam >= $mulaiApelJumat && $jam <= $selesaiApelJumat)) {
                        ?>
                        <li>
                            <a type="button" id="apel" class="btn btn-block btn-lg bg-cyan waves-effect">
                                <p class="align-left">
                                    <i class="material-icons">alarm_on</i>
                                    <span class="col-white">Presensi Apel</span>
                                </p>
                            </a>
                        </li>
                    <?php }

                    if (strpos($hari, "umat")) {
                        // Rentang waktu tombol presensi istirahat hari Jumat
                        if ($jam >= $mulaiIstirahatJumat && $jam <= ($mulaiIstirahatJumat + 600)) {
                            // 10 menit pertama: tombol mulai istirahat
                            ?>
                            <li>
                                <a type="button" id="istirahat" class="btn btn-block btn-lg bg-blue-grey waves-effect">
                                    <p class="align-left">
                                        <i class="material-icons">alarm_on</i>
                                        <span class="col-white">Presensi Istirahat</span>
                                    </p>
                                </a>
                            </li>
                            <?php
                        } elseif ($jam >= ($selesaiIstirahatJumat - 600) && $jam <= $selesaiIstirahatJumat) {
                            // 10 menit terakhir: tombol selesai istirahat
                            ?>
                            <li>
                                <a type="button" id="istirahat" class="btn btn-block btn-lg bg-blue-grey waves-effect">
                                    <p class="align-left">
                                        <i class="material-icons">alarm_off</i>
                                        <span class="col-white">Presensi Istirahat</span>
                                    </p>
                                </a>
                            </li>
                            <?php
                        }
                    } else {
                        if ($jam >= $mulaiIstirahat && $jam <= ($mulaiIstirahat + 600)) {
                            ?>
                            <li>
                                <a type="button" id="istirahat" class="btn btn-block btn-lg bg-blue-grey waves-effect">
                                    <p class="align-left">
                                        <i class="material-icons">alarm_on</i>
                                        <span class="col-white">Presensi Istirahat</span>
                                    </p>
                                </a>
                            </li>
                            <?php
                        } elseif ($jam >= ($selesaiIstirahat - 600) && $jam <= $selesaiIstirahat) {
                            ?>
                            <li>
                                <a type="button" id="istirahat" class="btn btn-block btn-lg bg-blue-grey waves-effect">
                                    <p class="align-left">
                                        <i class="material-icons">alarm_off</i>
                                        <span class="col-white">Presensi Istirahat</span>
                                    </p>
                                </a>
                            </li>
                            <?php
                        }
                    }

                    if ($this->session->userdata('agenda_lainnya') == 1) {
                        ?>
                        <li>
                            <a type="button" id="acara" class="btn btn-block btn-lg bg-orange waves-effect">
                                <p class="align-left">
                                    <i class="material-icons">alarm_on</i>
                                    <span class="col-white">Presensi Lainnya</span>
                                </p>
                            </a>
                        </li>
                    <?php } ?>

                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">content_paste</i>
                            <span>Laporan Presensi</span>
                        </a>
                        <ul class="ml-menu">
                            <li id="laporan_harian">
                                <a href="javascript:;" data-page="laporan_harian">Laporan Harian</a>
                            </li>
                            <?php
                            if (in_array($peran, ['admin', 'operator'])) {
                                ?>
                                <li><a href="javascript:;" data-page="laporan_satker">Laporan Satker</a></li>
                                <li><a href="javascript:;" data-page="laporan_apel">Laporan Presensi Apel</a></li>
                                <li><a href="javascript:;" data-page="laporan_kegiatan">Laporan Kegiatan Lainnya</a></li>
                            <?php } ?>
                        </ul>
                        <?php
                        if ($peran == 'admin') {
                            ?>
                        <li><a href="javascript:;" data-page="kegiatan">
                                <i class="material-icons">schedule</i>
                                <span>Kegiatan Lainnya</span></a>
                        </li>
                    <?php } ?>
                    <li>
                        <a href="<?= site_url('keluar') ?>">
                            <i class="material-icons">exit_to_app</i>
                            <span>Keluar</span></a>
                    </li>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; 2024 <a href="">MS Banda Aceh</a>.
                </div>
                <div class="version">
                    <b>Version: </b> 1.1.0
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
    </section>

    <section class="content">
        <div class="container-fluid" id="main">
        </div>
    </section>

    <div class="modal fade" id="tambah-modal" data-backdrop="static">
        <div class="modal-dialog modal-md">
            <div class="modal-content modal-col-green">
                <form method="POST" id="formSM" action="<?= site_url('simpan_presensi') ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Presensi Online</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row g-2">
                                <div class="col" style="display: grid; place-items: center;">
                                    <label class="form-label" id="hari"></label>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col" style="display: grid; place-items: center;">
                                    <label for="nameBackdrop" class="form-label">Saat ini pukul <label
                                            class="form-label" id="jam"></label></label>
                                    <input type="hidden" name="jam_absen" id="jam_absen" class="form-control" />
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col" style="display: grid; place-items: center;">
                                    <div id="map" style="height:250px;"></div><br />
                                    <div id="ket_map" class="text-center"></div><br />
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col" style="display: grid; place-items: center;">
                                    <label for="nameBackdrop" class="form-label">Presensi Masuk : <span
                                            class="badge bg-red" id="jam_masuk">Belum Presensi</span></label>

                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col" style="display: grid; place-items: center;">
                                    <label for="nameBackdrop" class="form-label">Presensi Pulang : <span
                                            class="badge bg-red" id="jam_pulang">Belum Presensi</span></label>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnSimpan" class="btn btn-link waves-effect">Simpan Presensi</button>
                        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Tutup</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="istirahat-modal" data-backdrop="static">
        <div class="modal-dialog modal-md">
            <div class="modal-content modal-col-blue-grey">
                <form method="POST" id="formSM" action="<?php base_url() ?>simpan_istirahat">
                    <div class="modal-header">
                        <h5 class="modal-title">Presensi Istirahat</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row g-2">
                                <div class="col" style="display: grid; place-items: center;">
                                    <label class="form-label" id="hari_"></label>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col" style="display: grid; place-items: center;">
                                    <label for="nameBackdrop" class="form-label">Saat ini pukul <label
                                            class="form-label" id="jam_"></label></label>
                                    <input type="hidden" name="jam_istirahat" id="jam_istirahat" class="form-control" />
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col" style="display: grid; place-items: center;">
                                    <label for="nameBackdrop" class="form-label">Presensi Mulai Istirahat : <span
                                            class="badge bg-red" id="jam_istirahat_mulai">Belum Presensi</span></label>
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col" style="display: grid; place-items: center;">
                                    <label for="nameBackdrop" class="form-label">Presensi Selesai Istirahat : <span
                                            class="badge bg-red" id="jam_istirahat_selesai">Belum
                                            Presensi</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnSimpanIstirahat" class="btn btn-link waves-effect">Simpan Presensi
                            Istirahat</button>
                        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Tutup</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="presensi-kegiatan" data-backdrop="static">
        <div class="modal-dialog modal-md">
            <div class="modal-content modal-col-orange">
                <form method="POST" id="formSM" action="<?= site_url('simpan_presensi_kegiatan') ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Presensi Online Kegiatan Lainnya</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row g-2">
                                <div class="col" style="display: grid; place-items: center;">
                                    <h5 class="form-label" id="hariKegiatan"></h5>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col" style="display: grid; place-items: center;">
                                    <h5 class="form-label">Saat ini pukul <label class="form-label"
                                            id="jamKegiatan"></label></h5>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col" style="display: grid">
                                    <h5 class="form-label">Pilih Agenda Kegiatan : </h5>
                                    <div id="kegiatan_">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnSimpanKegiatan" class="btn btn-link waves-effect">Simpan
                            Presensi</button>
                        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Tutup</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- Core JS -->
    <!-- jQuery dan Bootstrap tetap tanpa defer -->
    <script src="<?= site_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/bootstrap/js/bootstrap.js') ?>"></script>

    <!-- Plugin tambahan (boleh defer) -->
    <script src="<?= site_url('assets/plugins/bootstrap-select/js/bootstrap-select.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/node-waves/waves.js') ?>" defer></script>
    <script src="<?= site_url('assets/plugins/jquery-countto/jquery.countTo.js') ?>" defer></script>
    <script src="<?= site_url('assets/plugins/momentjs/moment.js') ?>" defer></script>
    <script src="<?= site_url('assets/plugins/raphael/raphael.min.js') ?>" defer></script>
    <script src="<?= site_url('assets/plugins/morrisjs/morris.js') ?>" defer></script>
    <script src="<?= site_url('assets/plugins/sweetalert/sweetalert.min.js') ?>" defer></script>
    <script src="<?= site_url('assets/js/admin.js') ?>" defer></script>
    <script src="<?= site_url('assets/js/pages/ui/modals.js') ?>" defer></script>
    <script src="<?= site_url('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') ?>" defer></script>
    <script
        src="<?= site_url('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') ?>"
        defer></script>
    <script src="<?= site_url('assets/plugins/select2/js/select2.min.js') ?>" defer></script>

    <script src="<?= site_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>" defer></script>
    <script src="<?= site_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>" defer></script>
    <script src="<?= site_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"
        defer></script>
    <script src="<?= site_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"
        defer></script>
    <script src="<?= site_url('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>" defer></script>
    <script src="<?= site_url('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>" defer></script>
    <script src="<?= site_url('assets/plugins/jszip/jszip.min.js') ?>" defer></script>
    <script src="<?= site_url('assets/plugins/pdfmake/pdfmake.min.js') ?>" defer></script>
    <script src="<?= site_url('assets/plugins/pdfmake/vfs_fonts.js') ?>" defer></script>
    <script src="<?= site_url('assets/plugins/datatables-buttons/js/buttons.html5.min.js') ?>" defer></script>
    <script src="<?= site_url('assets/plugins/datatables-buttons/js/buttons.print.min.js') ?>" defer></script>
    <script src="<?= site_url('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') ?>" defer></script>

    <script src="assets/plugins/sweetalert2/sweetalert2.all.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Turf.js/6.5.0/turf.min.js"></script>

    <?php
    if ($this->session->flashdata('info')) {
        $result = $this->session->flashdata('info');
        if ($result == '1') {
            $pesan = $this->session->flashdata('pesan_sukses');
        } elseif ($result == '2') {
            $pesan = $this->session->flashdata('pesan_gagal');
        } else {
            $pesan = $this->session->flashdata('pesan_gagal');
        }
    } else {
        $result = "-1";
        $pesan = "";
    }
    ?>

    <script>
        $(document).ready(function () {
            // Load page
            loadPage('dashboard');
            var peran = '<?= $this->session->userdata('peran') ?>';
            var role = '<?= $this->session->userdata('role') ?>';

            // Navigasi SPA
            $(document).on('click', '[data-page]', function (e) {
                e.preventDefault();
                $('body').removeClass('overlay-open');
                let page = $(this).data('page');
                loadPage(page);
            });
        });
    </script>

    <script type="text/javascript">
        var config = {
            ipServer: '<?= $this->session->userdata('ip_satker') ?>',
            isMobile: '<?= $this->agent->is_mobile() ?>',
            tokenNow: '<?= $this->session->userdata("token_now") ?>',
            tokenCookies: '<?= $this->input->cookie('presensi_token', TRUE) ?>',
            result: '<?= $result ?>',
            pesan: '<?= $pesan ?>'
        };
    </script>

    <script src="<?= site_url('assets/js/presensi.js') ?>" defer></script>
    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

</body>

</html>