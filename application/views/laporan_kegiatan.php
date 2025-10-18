<div class="row">
    <div class="col">
        <ol class="breadcrumb align-right">
            <li>
                <a href="javascript:;" data-page="dashboard">
                    <i class="material-icons">home</i> Beranda
                </a>
            </li>
            <li class="active">
                <a>
                    <i class="material-icons">content_paste</i> Laporan Presensi Kegiatan Lainnya
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
                    DAFTAR PRESENSI KEGIATAN LAINNYA
                </h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table id="tblKegiatan" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NAMA KEGIATAN</th>
                                <th>TANGGAL</th>
                                <th>JUMLAH PRESENSI PESERTA</th>
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
                                        <a href="javascript:void(0)"
                                            onclick="detailPresensiKegiatan('<?= base64_encode($this->encryption->encrypt($item->id_kegiatan)); ?>')">
                                            <?= $item->nama_kegiatan; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?= $this->tanggalhelper->convertDayDate($item->tanggal); ?>
                                    </td>
                                    <td>
                                        <?= $item->total; ?> Pegawai
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
                                <th>NAMA KEGIATAN</th>
                                <th>TANGGAL</th>
                                <th>JUMLAH PRESENSI PESERTA</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $("#tblKegiatan").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false
        }).buttons().container().appendTo('#tblKegiatan_wrapper .col-md-6:eq(0)');
    });
</script>