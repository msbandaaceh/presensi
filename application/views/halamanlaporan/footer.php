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
                                <label for="nameBackdrop" class="form-label">Saat ini pukul <label class="form-label"
                                        id="jam"></label></label>
                                <input type="hidden" name="jam_absen" id="jam_absen" class="form-control" />
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col" style="display: grid; place-items: center;">
                                <label for="nameBackdrop" class="form-label">Presensi Masuk : <span class="badge bg-red"
                                        id="jam_masuk">Belum Presensi</span></label>

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

<div class="modal fade" id="presensi-rapat" data-backdrop="static">
    <div class="modal-dialog modal-md">
        <div class="modal-content modal-col-deep-purple">
            <form method="POST" id="formSM" action="<?= site_url('simpan_presensi_rapat') ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Presensi Rapat Online</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row g-2">
                            <div class="col" style="display: grid; place-items: center;">
                                <h5 class="form-label" id="hariRapat"></h5>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col" style="display: grid; place-items: center;">
                                <h5 class="form-label">Saat ini pukul <label class="form-label" id="jamRapat"></label>
                                </h5>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col" style="display: grid">
                                <h5 class="form-label">Pilih Agenda Rapat : </h5>
                                <div id="rapat_">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnSimpanRapat" class="btn btn-link waves-effect">Simpan Presensi</button>
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
<!-- Jquery Core Js -->
<script src="<?= site_url('assets/plugins/jquery/jquery.min.js') ?>"></script>

<!-- Bootstrap Core Js -->
<script src="<?= site_url('assets/plugins/bootstrap/js/bootstrap.js') ?>"></script>

<!-- Select Plugin Js -->
<script src="<?= site_url('assets/plugins/bootstrap-select/js/bootstrap-select.js') ?>"></script>

<!-- Slimscroll Plugin Js -->
<script src="<?= site_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.js') ?>"></script>

<!-- Waves Effect Plugin Js -->
<script src="<?= site_url('assets/plugins/node-waves/waves.js') ?>"></script>

<!-- Jquery CountTo Plugin Js -->
<script src="<?= site_url('assets/plugins/jquery-countto/jquery.countTo.js') ?>"></script>

<!-- Moment Plugin Js -->
<script src="<?= site_url('assets/plugins/momentjs/moment.js') ?>"></script>

<!-- Morris Plugin Js -->
<script src="<?= site_url('assets/plugins/raphael/raphael.min.js') ?>"></script>
<script src="<?= site_url('assets/plugins/morrisjs/morris.js') ?>"></script>

<!-- SweetAlert Plugin Js -->
<script src="<?= site_url('assets/plugins/sweetalert/sweetalert.min.js') ?>"></script>

<!-- Custom Js -->
<script src="<?= site_url('assets/js/admin.js') ?>"></script>
<script src="<?= site_url('assets/js/pages/ui/modals.js') ?>"></script>

<!-- Bootstrap Datepicker Plugin Js -->
<script src="<?= site_url('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') ?>"></script>

<!-- Bootstrap Material Datetime Picker Plugin Js -->
<script
    src="<?= site_url('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') ?>"></script>

<?php
if (in_array($page, ['laporan_harian', 'laporan_satker', 'laporan_apel', 'kegiatan', 'laporan_kegiatan'])) {
    ?>
    <!-- DataTables  & Plugins -->
    <script src="<?= site_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/jszip/jszip.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/pdfmake/pdfmake.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/pdfmake/vfs_fonts.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/datatables-buttons/js/buttons.html5.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/datatables-buttons/js/buttons.print.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>
<?php } ?>

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

<script src="<?= site_url('assets/js/presensi.js') ?>"></script>
<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

</body>

</html>