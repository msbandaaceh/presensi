$(function () {
    var ipLokal = config.ipServer;
    var mobile = config.isMobile;
    var token = config.tokenNow;
    var token_cookies = config.tokenCookies;

    $('#tambah').on('click', async function () {

        // Ambil IP koneksi (ipKonek) dari API
        const ipKonek = await getIpKonek();
        if (ipLokal != ipKonek) {
            salahJaringan();
        } else if (mobile != '1') {
            salahModePerangkat();
        } else if (token == 'plh/plt') {
            notifPlh();
        } else if (token == '') {
            simpanPerangkat();
        } else if (token != token_cookies) {
            salahPerangkat();
        } else {
            BukaModal();
        }
    });

    $('#apel').on('click', async function () {
        // Ambil IP koneksi (ipKonek) dari API
        const ipKonek = await getIpKonek();
        if (ipLokal != ipKonek) {
            salahJaringan();
        } else if (mobile != '1') {
            salahModePerangkat();
        } else if (token == 'plh/plt') {
            notifPlh();
        } else if (token == '') {
            simpanPerangkat();
        } else if (token != token_cookies) {
            salahPerangkat();
        } else {
            //console.log("1");
            simpanApel();
        }
    });

    $('#rapat').on('click', async function () {
        // Ambil IP koneksi (ipKonek) dari API
        const ipKonek = await getIpKonek();
        if (ipLokal != ipKonek) {
            salahJaringan();
        } else if (mobile != '1') {
            salahModePerangkat();
        } else if (token == 'plh/plt') {
            notifPlh();
        } else if (token == '') {
            simpanPerangkat();
        } else if (token != token_cookies) {
            salahPerangkat();
        } else {
            presensiRapat();
        }
    });

    $('#acara').on('click', async function () {
        // Ambil IP koneksi (ipKonek) dari API
        const ipKonek = await getIpKonek();
        if (ipLokal != ipKonek) {
            salahJaringan();
        } else if (mobile != '1') {
            salahModePerangkat();
        } else if (token == 'plh/plt') {
            notifPlh();
        } else if (token == '') {
            simpanPerangkat();
        } else if (token != token_cookies) {
            salahPerangkat();
        } else {
            presensiLainnya();
        }
    });

    $('#lapPegawai').on('click', function () {
        loadPegawai();
    });

    $('#lapDetailApel').on('click', function () {
        loadPegawai();
    });

    if (document.getElementById('tblKegiatan')) {
        $("#tblKegiatan").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false
        }).buttons().container().appendTo('#tblKegiatan_wrapper .col-md-6:eq(0)');
    }

    if (document.getElementById('tabel_apel')) {
        $("#tabel_apel").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false
        }).buttons().container().appendTo('#tblKegiatan_wrapper .col-md-6:eq(0)');
    }

    if (document.getElementById('tblRapat')) {
        $("#tblRapat").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false
        }).buttons().container().appendTo('#tblRapat_wrapper .col-md-6:eq(0)');
    }

    if (document.getElementById('tblPresensiPribadi')) {
        $("#tblPresensiPribadi").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false,
            "buttons": ["excel", {
                extend: 'pdf',
                messageTop: "Periode : <?= date('F, Y') ?>"
            }, {
                    extend: 'print',
                    messageTop: "Periode : <?= date('F, Y') ?>"
                }, "colvis"]
        }).buttons().container().appendTo('#tblPresensiPribadi_wrapper .col-md-6:eq(0)');
    }

    if (document.getElementById('bs_datepicker_container')) {
        $('#bs_datepicker_container input').datepicker({
            format: "dd MM yyyy",
            autoclose: true,
            container: '#bs_datepicker_container'
        });
    }

    if (document.getElementById('bs_datepicker_container1')) {
        $('#bs_datepicker_container1 input').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            container: '#bs_datepicker_container1'
        });
    }

    //Bootstrap datepicker plugin
    if (document.getElementById('bs_datepicker_container_pribadi')) {
        $('#bs_datepicker_container_pribadi input').datepicker({
            format: "MM yyyy",
            minViewMode: 1,
            autoclose: true,
            container: '#bs_datepicker_container_pribadi'
        });
    }

    if (document.getElementsByClassName('timepicker')) {
        $('.timepicker').bootstrapMaterialDatePicker({
            format: 'HH:mm',
            clearButton: true,
            date: false
        });
    }

    if (document.getElementById('tambahKegiatan')) {
        $('#tambahKegiatan').on('click', function () {
            var id = $(this).data('id');
            //console.log(id);
            loadKegiatan(id);
        });
    }

    if (document.getElementsByClassName('presensi-lainnya')) {
        $('.presensi-lainnya').on('click', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            window.location.href = 'detail_kegiatan?id=' + id;
        });
    }

    /*##################################################*/
    /* UNTUK MENCETAK PADA LAPORAN SATUAN KERJA (START) */
    /*##################################################*/

    var hakim = '1';
    var cakim = '4';
    var asn = '2';
    var honor = '3';
    var pppk = '6';

    var tanggal = document.getElementById('bulanSatker');
    if (tanggal) {
        var tgl = document.getElementById('bulanSatker').value;
    }

    if (document.getElementById('tabel_hakim')) {
        $("#tabel_hakim").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false,
            "buttons": [{
                text: 'Cetak Presensi Masuk',
                action: function (e, dt, node, config) {
                    // Membuat formulir tersembunyi
                    var form = $('<form action="cetak_masuk" target="blank" method="post">' +
                        '<input type="hidden" name="jenis" value="' + hakim + '">' +
                        '<input type="hidden" name="tgl" value="' + tgl + '">' +
                        '</form>');
                    // Menambahkan formulir ke dalam dokumen
                    $('body').append(form);
                    // Mengirimkan formulir
                    form.submit();
                }
            }, {
                text: 'Cetak Presensi Pulang',
                action: function (e, dt, node, config) {
                    // Membuat formulir tersembunyi
                    var form = $('<form action="cetak_pulang" target="blank" method="post">' +
                        '<input type="hidden" name="jenis" value="' + hakim + '">' +
                        '<input type="hidden" name="tgl" value="' + tgl + '">' +
                        '</form>');
                    // Menambahkan formulir ke dalam dokumen
                    $('body').append(form);
                    // Mengirimkan formulir
                    form.submit();
                }
            }]
        }).buttons().container().appendTo('#tabel_hakim_wrapper .col-md-6:eq(0) ');
    }

    if (document.getElementById('tabel_cakim')) {
        $("#tabel_cakim").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false,
            "buttons": [{
                text: 'Cetak Presensi Masuk',
                action: function (e, dt, node, config) {
                    // Membuat formulir tersembunyi
                    var form = $('<form action="cetak_masuk" target="blank" method="post">' +
                        '<input type="hidden" name="jenis" value="' + cakim + '">' +
                        '<input type="hidden" name="tgl" value="' + tgl + '">' +
                        '</form>');
                    // Menambahkan formulir ke dalam dokumen
                    $('body').append(form);
                    // Mengirimkan formulir
                    form.submit();
                }
            }, {
                text: 'Cetak Presensi Pulang',
                action: function (e, dt, node, config) {
                    // Membuat formulir tersembunyi
                    var form = $('<form action="cetak_pulang" target="blank" method="post">' +
                        '<input type="hidden" name="jenis" value="' + cakim + '">' +
                        '<input type="hidden" name="tgl" value="' + tgl + '">' +
                        '</form>');
                    // Menambahkan formulir ke dalam dokumen
                    $('body').append(form);
                    // Mengirimkan formulir
                    form.submit();
                }
            }]
        }).buttons().container().appendTo('#tabel_cakim_wrapper .col-md-6:eq(0)');
    }

    if (document.getElementById('tabel_pns')) {
        $("#tabel_pns").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false,
            "buttons": [{
                text: 'Cetak Presensi Masuk',
                action: function (e, dt, node, config) {
                    // Membuat formulir tersembunyi
                    var form = $('<form action="cetak_masuk" target="blank" method="post">' +
                        '<input type="hidden" name="jenis" value="' + asn + '">' +
                        '<input type="hidden" name="tgl" value="' + tgl + '">' +
                        '</form>');
                    // Menambahkan formulir ke dalam dokumen
                    $('body').append(form);
                    // Mengirimkan formulir
                    form.submit();
                }
            }, {
                text: 'Cetak Presensi Pulang',
                action: function (e, dt, node, config) {
                    // Membuat formulir tersembunyi
                    var form = $('<form action="cetak_pulang" target="blank" method="post">' +
                        '<input type="hidden" name="jenis" value="' + asn + '">' +
                        '<input type="hidden" name="tgl" value="' + tgl + '">' +
                        '</form>');
                    // Menambahkan formulir ke dalam dokumen
                    $('body').append(form);
                    // Mengirimkan formulir
                    form.submit();
                }
            }]
        }).buttons().container().appendTo('#tabel_pns_wrapper .col-md-6:eq(0)');
    }

    if (document.getElementById('tabel_pppk')) {
        $("#tabel_pppk").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false,
            "buttons": [{
                text: 'Cetak Presensi Masuk',
                action: function (e, dt, node, config) {
                    // Membuat formulir tersembunyi
                    var form = $('<form action="cetak_masuk" target="blank" method="post">' +
                        '<input type="hidden" name="jenis" value="' + pppk + '">' +
                        '<input type="hidden" name="tgl" value="' + tgl + '">' +
                        '</form>');
                    // Menambahkan formulir ke dalam dokumen
                    $('body').append(form);
                    // Mengirimkan formulir
                    form.submit();
                }
            }, {
                text: 'Cetak Presensi Pulang',
                action: function (e, dt, node, config) {
                    // Membuat formulir tersembunyi
                    var form = $('<form action="cetak_pulang" target="blank" method="post">' +
                        '<input type="hidden" name="jenis" value="' + pppk + '">' +
                        '<input type="hidden" name="tgl" value="' + tgl + '">' +
                        '</form>');
                    // Menambahkan formulir ke dalam dokumen
                    $('body').append(form);
                    // Mengirimkan formulir
                    form.submit();
                }
            }]
        }).buttons().container().appendTo('#tabel_pppk_wrapper .col-md-6:eq(0)');
    }

    if (document.getElementById('tabel_honor')) {
        $("#tabel_honor").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false,
            "buttons": [{
                text: 'Cetak Presensi Masuk',
                action: function (e, dt, node, config) {
                    // Membuat formulir tersembunyi
                    var form = $('<form action="cetak_masuk" target="blank" method="post">' +
                        '<input type="hidden" name="jenis" value="' + honor + '">' +
                        '<input type="hidden" name="tgl" value="' + tgl + '">' +
                        '</form>');
                    // Menambahkan formulir ke dalam dokumen
                    $('body').append(form);
                    // Mengirimkan formulir
                    form.submit();
                }
            }, {
                text: 'Cetak Presensi Pulang',
                action: function (e, dt, node, config) {
                    // Membuat formulir tersembunyi
                    var form = $('<form action="cetak_pulang" target="blank" method="post">' +
                        '<input type="hidden" name="jenis" value="' + honor + '">' +
                        '<input type="hidden" name="tgl" value="' + tgl + '">' +
                        '</form>');
                    // Menambahkan formulir ke dalam dokumen
                    $('body').append(form);
                    // Mengirimkan formulir
                    form.submit();
                }
            }]
        }).buttons().container().appendTo('#tabel_honor_wrapper .col-md-6:eq(0)');
    }


    /*################################################*/
    /* UNTUK MENCETAK PADA LAPORAN SATUAN KERJA (END) */
    /*################################################*/

});

function getIpKonek() {
    return fetch('https://api.ipify.org?format=json')
        .then(response => response.json())
        .then(data => data.ip)
        .catch(error => {
            console.error('Terjadi kesalahan:', error);
            return null;
        });
}

function salahJaringan() {
    swal({
        icon: "error",
        title: "<h4>Oops...<h4>",
        type: "warning",
        text: "<h5>Anda Mengakses Aplikasi Menggunakan Jaringan Lain</br></br>Silakan Presensi Menggunakan Jaringan Wifi Kantor</h5>",
        html: true
    });
}

function notifPlh() {
    swal({
        icon: "error",
        title: "<h4>Oops...<h4>",
        type: "warning",
        text: "<h5>Anda login sebagai plh/plt</br></br>Silakan login atas diri anda sendiri untuk melakukan presensi</h5>",
        html: true
    });
}

function salahModePerangkat() {
    swal({
        icon: "error",
        title: "<h4>Oops...<h4>",
        type: "warning",
        text: "<h5>Anda Tidak Menggunakan Handphone</br></br>Silakan Presensi Melalui Handphone</h5>",
        html: true
    });
}

function simpanApel() {
    swal({
        title: "<h5>PRESENSI APEL</h5>",
        text: "<h5>SIMPAN PRESENSI APEL HARI INI?</h5>",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#8EC165",
        confirmButtonText: "Ya, Simpan !",
        cancelButtonText: "Tidak !",
        html: true,
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            $.post('pres_apel_js', function (response) {
                var json = jQuery.parseJSON(response);
                if (json.st == 1) {
                    swal({
                        title: "Berhasil !",
                        text: "Anda sudah presensi Apel hari ini",
                        type: "success",
                        confirmButtonColor: "#8EC165",
                        confirmButtonText: "Oke",
                    }, function () {
                        location.reload();
                    });
                } else if (json.st == 0) {
                    swal("Gagal", "Anda Gagal presensi Apel, Silakan Ulangi Lagi", "error");
                } else if (json.st == 2) {
                    swal("Peringatan", "Anda Sudah Presensi Apel, Silakan Lanjutkan Pekerjaan", "info");
                }
            })
        } else {
            swal("Batal", "Anda Tidak Presensi Apel", "error");
        }
    }
    );
}

function simpanPerangkat() {
    swal({
        title: "<h5>Anda Belum Mendaftarkan Perangkat Untuk Melakukan Presensi</h5>",
        text: "<h5>Apa Anda Yakin Akan Mendaftarkan Perangkat Ini Untuk Melakukan Presensi?</h5>",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#8EC165",
        confirmButtonText: "Ya, Simpan !",
        cancelButtonText: "Tidak !",
        html: true,
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            $.post('simpan_perangkat', function (response) {
                var json = jQuery.parseJSON(response);
                if (json.st == 1) {
                    swal({
                        title: "Berhasil !",
                        text: "Anda sudah mendaftarkan perangkat, silakan mengisi presensi",
                        type: "success",
                        confirmButtonColor: "#8EC165",
                        confirmButtonText: "Oke",
                    }, function () {
                        location.reload();
                    });
                } else if (json.st == 0) {
                    swal("Gagal", "Anda Gagal Mendaftarkan Perangkat, SIlakan Ulangi Lagi", "error");
                }
            })
        } else {
            swal("Batal", "Anda Tidak Mendaftarkan Perangkat", "error");
        }
    }
    );
}

function salahPerangkat() {
    swal({
        icon: "error",
        title: "<h4>Oops...<h4>",
        type: "warning",
        text: "<h5>Anda Menggunakan Perangkat Lain Untuk Presensi</br></br>Silakan Menggunakan Perangkat Yang Telah Didaftarkan Untuk Presensi</h5>",
        html: true
    });
}

var result = config.result;
var pesan = config.pesan;
if (result != '-1') {
    if (result == '1') {
        sukses(pesan);
    } else if (result == '2') {
        peringatan(pesan);
    } else {
        gagal(pesan);
    }
}

function sukses(pesan) {
    swal({
        title: "<h4>Sukses<h4>",
        type: "success",
        text: "<h5>" + pesan + "</h5>",
        html: true
    });
}

function peringatan(pesan) {
    swal({
        title: "<h4>Oops...<h4>",
        type: "warning",
        text: "<h5>" + pesan + "</h5>",
        html: true
    });
}

function gagal(pesan) {
    swal({
        title: "<h4>Oops...<h4>",
        type: "error",
        text: "<h5>" + pesan + "</h5>",
        html: true
    });
}

function BukaModal() {
    swal({
        title: "Memuat...",
        text: "Silakan tunggu sebentar.",
        imageUrl: "assets/images/loader.gif", // loader custom jika mau (opsional)
        showConfirmButton: false,
        allowOutsideClick: false
    });

    $("#modal-content").hide();
    $("#btnSimpan").attr("disabled", true);
    $.post('show_presensi', function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $("#tambah-modal").modal('show');
            $("#jam").html('');
            $("#hari").html('');
            $("#jam_absen").val('');

            $("#jam").append(json.jam);
            $("#hari").append(json.hari);
            $("#jam_absen").val(json.jam);
            if (json.jam_masuk) {
                $("#jam_masuk").html('');
                $("#jam_masuk").append(json.jam_masuk);
                $('#jam_masuk').toggleClass('bg-red bg-blue');
            }
            if (json.jam_pulang) {
                $("#jam_pulang").html('');
                $("#jam_pulang").append(json.jam_pulang);
                $('#jam_pulang').toggleClass('bg-red bg-blue');
                $('#btnSimpan').addClass('hidden');
            }
            $("#modal-content").show();
            $("#btnSimpan").attr("disabled", false);
            swal.close();
        } else if (json.st == 0) {
            pesan('PERINGATAN', json.msg, '');
            $('#table_pegawai').DataTable().ajax.reload();
            $("#modal-content").show();
            swal.close();
        }
    });
}

function EditModal(id, userid) {
    $.post('edit_presensi', {
        id: id, userid: userid
    }, function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $("#editModal").modal('show');
            $("#judul").html("");
            $("#id").val('');
            $("#userid").val('');
            $("#masuk").val('');
            $("#pulang").val('');
            $("#ket_").html('');

            $("#judul").append(json.judul);
            $("#id").val(json.id);
            $("#userid").val(json.userid);
            if (json.masuk) {
                $("#masuk").val(json.masuk);
            } else {
                $("#masuk").val('');
            }
            if (json.pulang) {
                $("#pulang").val(json.pulang);
            } else {
                $("#pulang").val('');
            }
            $("#ket_").append(json.ket);
        } else if (json.st == 0) {
            pesan('PERINGATAN', json.msg, '');
            $('#table_pegawai').DataTable().ajax.reload();
        }
    });
}

function loadPegawai() {
    $.post('show_list_pegawai', function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $('#dataModal').modal('show');
            $("#pegawai_").html('');

            $("#pegawai_").append(json.pegawai);
        } else if (json.st == 0) {
            pesan('PERINGATAN', json.msg, '');
            $('#table_pegawai').DataTable().ajax.reload();
        }
    });
}

function pilihPenandatangan(id) {
    $.post('show_all_pegawai', function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $('#dataModal').modal('show');
            $("#pegawai_").html('');

            $('#id_rapat').val(id);
            $("#pegawai_").append(json.pegawai);
        } else if (json.st == 0) {
            pesan('PERINGATAN', json.msg, '');
            $('#table_pegawai').DataTable().ajax.reload();
        }
    });
}

function presensiRapat() {
    $("#loader").show();
    $("#modal-content").hide();
    $("#btnSimpanRapat").attr("disabled", true);
    $.post('show_rapat', function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $("#presensi-rapat").modal('show');
            $("#jamRapat").html('');
            $("#hariRapat").html('');
            $("#rapat_").html('');

            $("#jamRapat").append(json.jam);
            $("#hariRapat").append(json.hari);
            $("#rapat_").append(json.rapat);

            $("#loader").hide();
            $("#modal-content").show();
            $("#btnSimpanRapat").attr("disabled", false);
        } else if (json.st == 0) {
            pesan('PERINGATAN', json.msg, '');
            $('#table_pegawai').DataTable().ajax.reload();
            $("#loader").hide();
            $("#modal-content").show();
        }
    });
}

function loadKegiatan(id) {
    $.post('show_data_kegiatan', {
        id: id
    }, function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $('#modalAddKegiatan').modal('show');
            $("#id").val('');
            $("#judul_").html('');
            $("#tgl_").val('');
            $("#nama_kegiatan_").val('');

            $("#id").val(json.id);
            $("#judul_").append(json.judul);
            $("#tgl_").val(json.tgl);
            $("#nama_kegiatan_").val(json.nama_kegiatan);
            //console.log(json.peserta);

        } else if (json.st == 0) {
            pesan('PERINGATAN', json.msg, '');
            $('#table_pegawai').DataTable().ajax.reload();
        }
    });
}

function presensiLainnya() {
    $("#loader").show();
    $("#modal-content").hide();
    $("#btnSimpanKegiatan").attr("disabled", true);
    $.post('show_kegiatan', function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $("#presensi-kegiatan").modal('show');
            $("#jamKegiatan").html('');
            $("#hariKegiatan").html('');
            $("#kegiatan_").html('');

            $("#jamKegiatan").append(json.jam);
            $("#hariKegiatan").append(json.hari);
            $("#kegiatan_").append(json.rapat);

            $("#loader").hide();
            $("#modal-content").show();
            $("#btnSimpanKegiatan").attr("disabled", false);
        } else if (json.st == 0) {
            pesan('PERINGATAN', json.msg, '');
            $('#table_pegawai').DataTable().ajax.reload();
            $("#loader").hide();
            $("#modal-content").show();
        }
    });
}

function detailPresensiApel(tgl) {
    var form = $('<form action="laporan_apel_detail" method="post">' +
        '<input type="hidden" name="tgl" value="' + tgl + '">' +
        '</form>');
    // Menambahkan formulir ke dalam dokumen
    $('body').append(form);
    // Mengirimkan formulir
    form.submit();
}

function detailPresensiKegiatan(id) {
    var form = $('<form action="laporan_detail_kegiatan" method="post">' +
        '<input type="hidden" name="id" value="' + id + '">' +
        '</form>');
    // Menambahkan formulir ke dalam dokumen
    $('body').append(form);
    // Mengirimkan formulir
    form.submit();
}

function detailPresensiRapat(id) {
    var form = $('<form action="detail_rapat" method="post">' +
        '<input type="hidden" name="id" value="' + id + '">' +
        '</form>');
    // Menambahkan formulir ke dalam dokumen
    $('body').append(form);
    // Mengirimkan formulir
    form.submit();
}

function ModalRole(id) {
    swal({
        title: "Memuat...",
        text: "Silakan tunggu sebentar.",
        imageUrl: "assets/images/loader.gif", // loader custom jika mau (opsional)
        showConfirmButton: false,
        allowOutsideClick: false
    });

    if (id != '-1') {
        $('#tabel-role').html('');
    }

    $.post('show_role',
        { id: id },
        function (response) {
            swal.close();

            try {
                const json = JSON.parse(response); // pastikan response valid JSON
                $('#pegawai_').html('');

                let html = `<select class="form-control" id="pegawai" name="pegawai" style="width:100%">`;
                json.pegawai.forEach(row => {
                    html += `<option value="${row.userid}" data-nama="${row.fullname}" data-jabatan="${row.jabatan}">${row.fullname}</option>`;
                });
                html += `</select>`;
                $('#pegawai_').append(html);

                $('#pegawai').select2({
                    dropdownParent: $('#role-pegawai'),
                    templateResult: formatPegawaiOption,
                    templateSelection: formatPegawaiSelection,
                    width: '100%',
                    placeholder: "Pilih pegawai"
                });

                $('#peran_').html('');
                let role = `<select class="form-control" id="peran" name="peran" style="width:100%">`;
                role += `<option value="validator">Validator Satker</option>`;
                role += `<option value="petugas">Petugas Kepegawaian</option>`;
                role += `</select>`;
                $('#peran_').append(role);

                $('#peran').select2({
                    dropdownParent: $('#role-pegawai'),
                    width: '100%',
                    placeholder: "Pilih Peran"
                });
                $('#role-pegawai').modal('show');
                if (id != '-1') {
                    $('#id').val('');

                    $('#id').val(json.id);
                    $('#pegawai').val(json.editPegawai).trigger('change');
                    $('#peran').val(json.editPeran).trigger('change');

                    $('#pegawai').on('select2:opening select2:selecting', function (e) {
                        e.preventDefault(); // mencegah dropdown terbuka
                    });
                } else {
                    $('#tabel-role').html('');

                    let data = `
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead><tbody>`;
                    json.data_peran.forEach(row => {
                        if (`${row.peran}` == 'validator') {
                            var peran = 'Validator Satker';
                        } else {
                            var peran = 'Petugas Kepegawaian';
                        }
                        data += `
                        <tr>
                            <td>${row.nama}</td>
                            <td>`;


                        if (`${row.hapus}` == '0') {
                            data += `<span class='badge bg-green'>${peran}</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-xs bg-orange" id="editPeran" onclick="ModalRole('${row.id}')" title="Edit Peran">
                                    <i class="material-icons">mode_edit</i>
                                </button>
                                <button type="button" class="btn btn-xs bg-red" id="hapusPeran" onclick="blokPeran('${row.id}')" title="Blok Pegawai">
                                    <i class="material-icons">block</i>
                                </button>`;
                        } else {
                            data += `<span class='badge bg-grey'>${peran}</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-xs bg-success" id="hapusPeran" onclick="aktifPeran('${row.id}')" title="Aktifkan Pegawai">
                                    <i class="material-icons">check</i>
                                </button>`;
                        }
                        data += `
                            </td>
                        </tr>`;
                    });
                    data += `
                        </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <span class='badge bg-green'>aktif</span>
                        <span class='badge bg-grey'>non-aktif</span>
                    </div>`;
                    $('#tabel-role').append(data);
                }
            } catch (e) {
                console.error("Gagal parsing JSON:", e);
                $('#pegawai_').html('<div class="alert alert-danger">Gagal memuat data pegawai.</div>');
            }
        }
    );
}

function aktifPeran(id) {
    swal({
        title: "Yakin ingin mengaktifkan peran pegawai?",
        text: "Data peran ini akan diaktifkan kembali perannya.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, aktifkan!",
        cancelButtonText: "Batal",
        closeOnConfirm: false
    }, function () {
        // Eksekusi penghapusan setelah konfirmasi
        $.post('aktif_peran', { id: id }, function (response) {
            // kamu bisa parse response jika berupa JSON
            swal("Berhasil!", "Peran telah aktifkan.", "success");
            // misal reload tabel setelah hapus:
            ModalRole('-1');
        }).fail(function () {
            swal("Gagal", "Terjadi kesalahan saat menghapus data.", "error");
        });
    });
}

function blokPeran(id) {
    swal({
        title: "Yakin ingin menonaktifkan peran pegawai?",
        text: "Data peran ini akan dinonaktifkan perannya.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, nonaktifkan!",
        cancelButtonText: "Batal",
        closeOnConfirm: false
    }, function () {
        // Eksekusi penghapusan setelah konfirmasi
        $.post('blok_peran', { id: id }, function (response) {
            // kamu bisa parse response jika berupa JSON
            swal("Berhasil!", "Peran telah nonaktifkan.", "success");
            // misal reload tabel setelah hapus:
            ModalRole('-1');
        }).fail(function () {
            swal("Gagal", "Terjadi kesalahan saat menghapus data.", "error");
        });
    });
}

function formatPegawaiOption(option) {
    if (!option.id) return option.text;

    const nama = $(option.element).data('nama');
    const jabatan = $(option.element).data('jabatan');

    return $(`
        <div style="line-height:1.2">
            <div style="font-weight:bold;">${nama}</div>
            <div style="font-size:12px; color:#555;">${jabatan}</div>
        </div>
    `);
}

// Menampilkan teks terpilih di kotak select
function formatPegawaiSelection(option) {
    if (!option.id) return option.text;

    const nama = $(option.element).data('nama');
    const jabatan = $(option.element).data('jabatan');

    return `${nama} > ${jabatan}`;
}

function hapusKegiatan(id) {
    swal({
        title: "<h5>HAPUS AGENDA KEGIATAN LAINNYA</h5>",
        text: "<h5>Apa Anda Yakin Akan Menghapus Agenda Kegiatan Lainnya?</h5>",
        type: "info",
        showCancelButton: true,
        confirmButtonColor: "#DD2A2A",
        confirmButtonText: "Ya, Hapus !",
        cancelButtonText: "Tidak !",
        html: true,
        closeOnConfirm: false,
        closeOnCancel: false
    }, function (isConfirm) {
        if (isConfirm) {
            $.post('hapus_kegiatan', { id: id }, function (response) {
                var json = jQuery.parseJSON(response);
                if (json.st == 1) {
                    swal({
                        title: "Berhasil !",
                        text: "Anda Sudah Menghapus Agenda Kegiatan Lainnya",
                        type: "success",
                        confirmButtonColor: "#8EC165",
                        confirmButtonText: "Oke",
                    }, function () {
                        location.reload();
                    });
                } else if (json.st == 0) {
                    swal("Gagal", "Anda Gagal Menghapus Agenda Kegiatan Lainnya, Silakan Ulangi Lagi", "error");
                }
            })
        } else {
            swal("Batal", "Anda Batal Menghapus Agenda Kegiatan Lainnya", "error");
        }
    }
    );
}
