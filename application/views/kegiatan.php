<div class="row">
    <div class="col">
        <ol class="breadcrumb align-right">
            <li>
                <a href="javascript:;" data-page="dashboard">
                    <i class="material-icons">home</i> Beranda
                </a>
            </li>
            <li class="active">
                <a><i class="material-icons">content_paste</i> Daftar Kegiatan</a>
            </li>
        </ol>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-orange">
                <h2>
                    DAFTAR KEGIATAN LAINNYA
                </h2>
                <ul class="header-dropdown">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a class="waves-effect waves-block">
                                    <button id="tambahKegiatan"
                                        data-id="<?= base64_encode($this->encryption->encrypt('-1')); ?>"
                                        style="background: transparent; border: none !important;">
                                        Tambah Kegiatan Lainnya
                                    </button>
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
                                <th>NAMA KEGIATAN</th>
                                <th>TANGGAL</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($kegiatan as $item) {
                                ?>
                                <tr>
                                    <td>
                                        <?= $no; ?>
                                    </td>
                                    <td>
                                        <?= $item->nama_kegiatan ?>
                                    </td>
                                    <td>
                                        <?= $this->tanggalhelper->convertDayDate($item->tanggal); ?>
                                    </td>
                                    <td>

                                        <div class="dropdown">
                                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"
                                                role="button" aria-haspopup="true" aria-expanded="false">
                                                <i class="material-icons">more_vert</i>
                                            </a>
                                            <ul class="dropdown-menu pull-right">
                                                <li>
                                                    <a class="waves-effect waves-block">
                                                        <button
                                                            onclick="loadKegiatan('<?= base64_encode($this->encryption->encrypt($item->id)); ?>')"
                                                            style="background: transparent; border: none !important;">
                                                            Edit Kegiatan
                                                        </button>
                                                    </a>
                                                    <a class="waves-effect waves-block">
                                                        <button
                                                            onclick="hapusKegiatan('<?= base64_encode($this->encryption->encrypt($item->id)); ?>')"
                                                            style="background: transparent; border: none !important;">
                                                            Hapus
                                                        </button>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
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
                                <th>AGENDA RAPAT</th>
                                <th>TANGGAL</th>
                                <th>AKSI</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAddKegiatan" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-col-orange">
            <form id="formKegiatan">
                <div class="modal-header">
                    <h5 class="modal-title align-center">
                        <div id="judul_"></div>
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="id" name="id">
                    </div>
                    <div class="form-group">
                        <div class="row g-2">
                            <div class="col">
                                <label for="tgl_" class="form-label">TANGGAL KEGIATAN</label>
                                <div class="form-line col-black" id="bs_datepicker_container">
                                    <input type="hidden" id="tgl">
                                    <input type="text" id="tgl_" name="tgl" class="form-control bg-orange"
                                        placeholder="Pilih Tanggal" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row g-2">
                            <div class="col">
                                <label for="nama_kegiatan_" class="form-label">NAMA KEGIATAN</label>
                                <div class="form-line">
                                    <input type="text" id="nama_kegiatan_" name="nama_kegiatan"
                                        class="form-control bg-orange" placeholder="Tuliskan agenda kegiatan...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnSimpan" class="btn btn-link waves-effect">SIMPAN KEGIATAN</button>
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">TUTUP</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(function () {
        $("#tblKegiatan").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false
        }).buttons().container().appendTo('#tblKegiatan_wrapper .col-md-6:eq(0)');

        $('#tambahKegiatan').on('click', function () {
            var id = $(this).data('id');
            //console.log(id);
            loadKegiatan(id);
        });

        $('#bs_datepicker_container input').datepicker({
            format: "dd MM yyyy",
            autoclose: true,
            container: '#bs_datepicker_container'
        });
    });
</script>