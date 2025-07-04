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
                <?php if ($page == 'dashboard') {
                    echo '<li class="active">';
                    echo '<a>';
                } else {
                    echo '<li>';
                    echo '<a href="' . site_url() . '">';
                } ?>

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
                $jam = strtotime(date('H:i:s'));
                $mulaiApelSenin = strtotime($this->session->userdata("mulai_apel_senin"));
                $selesaiApelSenin = strtotime($this->session->userdata("selesai_apel_senin"));
                $mulaiApelJumat = strtotime($this->session->userdata("mulai_apel_jumat"));
                $selesaiApelJumat = strtotime($this->session->userdata("selesai_apel_jumat"));
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

                if ($this->session->userdata('agenda_rapat') == 1) {
                    ?>
                    <li>
                        <a type="button" id="rapat" class="btn btn-block btn-lg bg-deep-purple waves-effect">
                            <p class="align-left">
                                <i class="material-icons">alarm_on</i>
                                <span class="col-white">Presensi Rapat</span>
                            </p>
                        </a>
                    </li>
                <?php } ?>
                <?php
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

                <?php
                if (in_array($page, ['laporan_kegiatan'])) {
                    echo '<li class="active">';
                } else {
                    echo '<li>';
                }
                ?>
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="material-icons">content_paste</i>
                    <span>Laporan Presensi</span>
                </a>
                <ul class="ml-menu">
                    <li>
                        <a href="<?= site_url('laporan') ?>">Laporan Harian</a>
                    </li>
                    <?php
                    if ($peran == 'admin') {
                        ?>
                        <li>
                            <a href="<?= site_url('laporan_satker') ?>">Laporan Satuan Kerja</a>
                        </li>
                        <li>
                            <a href="<?= site_url('laporan_apel') ?>">Laporan Apel</a>
                        </li>
                        <li>
                            <a href="<?= site_url('laporan_rapat') ?>">Laporan Rapat</a>
                        </li>
                        <?php
                        if ($page == 'laporan_kegiatan') {
                            echo '<li class="active">';
                            echo '<a>Laporan Kegiatan Lainnya</a>';
                        } else {
                            echo '<li>';
                            echo '<a href="' . site_url('laporan_kegiatan') . '">Laporan Kegiatan Lainnya</a>';
                        }
                        ?>
                        </li>
                    <?php } ?>
                </ul>
                </li>
                <?php
                if ($peran == 'admin') {
                    if ($page == 'kegiatan') {
                        echo '<li class="active">';
                        echo '<a>';
                    } else {
                        echo '<li>';
                        echo '<a href="' . site_url('kegiatan') . '">';
                    }
                    ?>
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