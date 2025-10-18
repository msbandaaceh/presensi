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
                    <i class="material-icons">content_paste</i> Laporan Presensi Apel
                </a>
            </li>
        </ol>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header bg-cyan">
                <h2>
                    LAPORAN PRESENSI APEL
                </h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table id="tabel_apel" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>TANGGAL APEL</th>
                                <th>JUMLAH PESERTA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($apel as $item) {
                                ?>
                                <tr>
                                    <td>
                                        <?= $no; ?>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" onclick="detailPresensiApel('<?= $item->tanggal ?>')">
                                            <?= $this->tanggalhelper->convertDayDate($item->tanggal); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?= $item->total; ?> Peserta
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
                                <th>TANGGAL APEL</th>
                                <th>JUMLAH PESERTA</th>
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
        $("#tabel_apel").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false
        }).buttons().container().appendTo('#tblKegiatan_wrapper .col-md-6:eq(0)');
    });
</script>