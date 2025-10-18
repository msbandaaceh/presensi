$(function () {
    var ipLokal = config.ipServer;
    var mobile = config.isMobile;
    var token = config.tokenNow;
    var token_cookies = config.tokenCookies;

    $('#tambah').on('click', async function () {
        cekToken();
        // Ambil IP koneksi (ipKonek) dari API
        const ipKonek = await getIpKonek();
        if (mobile != '1') {
            salahModePerangkat();
        } else if (token == 'plh/plt') {
            notifPlh();
        } else if (token == '') {
            simpanPerangkat();
        } else if (token != token_cookies) {
            salahPerangkat();
        } else if (ipLokal != ipKonek) {
            $.getJSON('get_status_pegawai', function (data) {
                //console.log("Data dari server:", data);
                if (data.status == '1') {
                    BukaModalLokasiMPP();
                } else {
                    salahJaringan();
                }
            });
        } else {
            BukaModal();
        }
    });

    $('#istirahat').on('click', async function () {
        cekToken();
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
            BukaModalIstirahat();
        }
    });

    $('#apel').on('click', async function () {
        cekToken();
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

    $('#acara').on('click', async function () {
        cekToken();
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

    $('#lapDetailApel').on('click', function () {
        loadPegawai();
    });

    if (document.getElementsByClassName('presensi-lainnya')) {
        $('.presensi-lainnya').on('click', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            window.location.href = 'detail_kegiatan?id=' + id;
        });
    }

    $(document).off('submit', '#formEditPresensi').on('submit', '#formEditPresensi', function (e) {
        e.preventDefault();
        let form = this;
        let formData = new FormData(form);

        Swal.fire({
            title: 'Menyimpan...',
            text: 'Silakan tunggu sebentar.',
            imageUrl: 'assets/images/loader.gif',
            imageWidth: 200,
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'simpan_edit',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                Swal.close();
                notifikasi(res.message, res.success);
                if (res.success == '1') {
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    loadPage('laporan_satker');
                }
            },
            error: function () {
                Swal.close();
                notifikasi('Terjadi kesalahan saat menyimpan data.', 4);
            }
        });
    });

    $(document).off('submit', '#formLaporanHarianBulanan').on('submit', '#formLaporanHarianBulanan', function (e) {
        e.preventDefault();
        let form = this;
        let formData = new FormData(form);

        Swal.fire({
            title: 'Mencari data...',
            text: 'Silakan tunggu sebentar.',
            imageUrl: 'assets/images/loader.gif',
            imageWidth: 200,
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'laporan_bulan',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                Swal.close();
                notifikasi(res.message, res.success);
                if (res.success == 1) {
                    $('#main').html(res.html);
                }
            },
            error: function () {
                Swal.close();
                notifikasi('Terjadi kesalahan saat mencari data.', 4);
            }
        });
    });

    $(document).off('submit', '#formLaporanHarianSatkerBulanan').on('submit', '#formLaporanHarianSatkerBulanan', function (e) {
        e.preventDefault();
        let form = this;
        let formData = new FormData(form);

        Swal.fire({
            title: 'Mencari data...',
            text: 'Silakan tunggu sebentar.',
            imageUrl: 'assets/images/loader.gif',
            imageWidth: 200,
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'laporan_satker_bulan',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                Swal.close();
                notifikasi(res.message, res.success);
                if (res.success == 1) {
                    $('#main').html(res.html);
                }
            },
            error: function () {
                Swal.close();
                notifikasi('Terjadi kesalahan saat mencari data.', 4);
            }
        });
    });

    $(document).off('submit', '#formKegiatan').on('submit', '#formKegiatan', function (e) {
        e.preventDefault();
        let form = this;
        let formData = new FormData(form);

        Swal.fire({
            title: 'Menyimpan data...',
            text: 'Silakan tunggu sebentar.',
            imageUrl: 'assets/images/loader.gif',
            imageWidth: 200,
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'simpan_kegiatan',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                Swal.close();
                notifikasi(res.message, res.success);
                if (res.success == 1) {
                    loadPage('kegiatan');
                }
            },
            error: function () {
                Swal.close();
                notifikasi('Terjadi kesalahan saat mencari data.', 4);
            }
        });
    });

    $(document).off('submit', '#formDetailPresensiApel').on('submit', '#formDetailPresensiApel', function (e) {
        e.preventDefault();
        let form = this;
        let formData = new FormData(form);

        Swal.fire({
            title: 'Mencari data...',
            text: 'Silakan tunggu sebentar.',
            imageUrl: 'assets/images/loader.gif',
            imageWidth: 200,
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'laporan_apel_detail',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                Swal.close();
                notifikasi(res.message, res.success);
                if (res.success == 1) {
                    $('#main').html(res.html);
                }
            },
            error: function () {
                Swal.close();
                notifikasi('Terjadi kesalahan saat mencari data.', 4);
            }
        });
    });

    $(document).off('submit', '#formDetailPresensiKegiatan').on('submit', '#formDetailPresensiKegiatan', function (e) {
        e.preventDefault();
        let form = this;
        let formData = new FormData(form);

        Swal.fire({
            title: 'Mencari data...',
            text: 'Silakan tunggu sebentar.',
            imageUrl: 'assets/images/loader.gif',
            imageWidth: 200,
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            url: 'laporan_detail_kegiatan',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                Swal.close();
                notifikasi(res.message, res.success);
                if (res.success == 1) {
                    $('#main').html(res.html);
                }
            },
            error: function () {
                Swal.close();
                notifikasi('Terjadi kesalahan saat mencari data.', 4);
            }
        });
    });
});

function loadPage(page) {
    cekToken();
    $('#main').html(`
        <div class="text-center p-4"><div class="preloader">
            <div class="spinner-layer pl-red">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div> Memuat halaman, Mohon tunggu sebentar ...</div>`);
    $.get("halamanutama/page/" + page, function (data) {
        $('#main').html(data);
    }).fail(function () {
        $('#main').html('<div class="text-danger">Halaman tidak ditemukan.</div>');
    });
}

function cekToken() {
    $.ajax({
        url: 'cek_token',
        type: 'POST',
        dataType: 'json',
        success: function (res) {
            if (!res.valid) {
                alert(res.message);
                window.location.href = res.url;
            }
        }
    });
}

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
    Swal.fire({
        icon: "error",
        title: "Oops...",
        html: "<h5>Anda Mengakses Aplikasi Menggunakan Jaringan Lain<br><br>Silakan Presensi Menggunakan Jaringan Wifi Kantor</h5>",
        confirmButtonColor: "#8EC165"
    });
}

function notifPlh() {
    Swal.fire({
        icon: "warning",
        title: "Oops...",
        html: "<h5>Anda login sebagai plh/plt<br><br>Silakan login atas diri anda sendiri untuk melakukan presensi</h5>",
        confirmButtonColor: "#8EC165"
    });
}

function salahModePerangkat() {
    Swal.fire({
        icon: "warning",
        title: "Oops...",
        html: "<h5>Anda Tidak Menggunakan Handphone<br><br>Silakan Presensi Melalui Handphone</h5>",
        confirmButtonColor: "#8EC165"
    });
}

function simpanApel() {
    Swal.fire({
        title: "PRESENSI APEL",
        html: "<h5>SIMPAN PRESENSI APEL HARI INI?</h5>",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#8EC165",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Simpan!",
        cancelButtonText: "Tidak!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('simpan_presensi_apel', function (response) {
                var json = jQuery.parseJSON(response);
                if (json.st == 1) {
                    Swal.fire({
                        title: "Berhasil!",
                        text: "Anda sudah presensi Apel hari ini.",
                        icon: "success",
                        confirmButtonColor: "#8EC165"
                    }).then(() => {
                        location.reload();
                    });
                } else if (json.st == 0) {
                    Swal.fire("Gagal", "Anda Gagal presensi Apel, Silakan Ulangi Lagi", "error");
                } else if (json.st == 2) {
                    Swal.fire("Peringatan", "Anda Sudah Presensi Apel, Silakan Lanjutkan Pekerjaan", "info");
                }
            }).fail(() => {
                Swal.fire("Gagal", "Terjadi kesalahan saat menghubungi server.", "error");
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire("Batal", "Anda Tidak Presensi Apel", "error");
        }
    });
}

function simpanPerangkat() {
    Swal.fire({
        title: "DAFTAR PERANGKAT PRESENSI",
        html: "<h5>Anda belum mendaftarkan perangkat ini.<br><br>Apakah Anda yakin ingin mendaftarkannya?</h5>",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#8EC165",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Simpan!",
        cancelButtonText: "Tidak!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('simpan_perangkat', function (response) {
                var json = jQuery.parseJSON(response);
                if (json.st == 1) {
                    Swal.fire({
                        title: "Berhasil!",
                        text: "Perangkat berhasil didaftarkan, silakan lanjutkan presensi.",
                        icon: "success",
                        confirmButtonColor: "#8EC165"
                    }).then(() => {
                        location.reload();
                    });
                } else if (json.st == 0) {
                    Swal.fire("Gagal", "Anda gagal mendaftarkan perangkat. Silakan ulangi lagi.", "error");
                }
            }).fail(() => {
                Swal.fire("Gagal", "Terjadi kesalahan koneksi ke server.", "error");
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire("Batal", "Anda Tidak Mendaftarkan Perangkat", "error");
        }
    });
}

function salahPerangkat() {
    Swal.fire({
        icon: "warning",
        title: "Oops...",
        html: "<h5>Anda Menggunakan Perangkat Lain Untuk Presensi<br><br>Silakan Gunakan Perangkat Yang Telah Didaftarkan</h5>",
        confirmButtonColor: "#8EC165"
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

function notifikasi(pesan, result) {
    if (result == '1') {
        sukses(pesan);
    } else if (result == '2') {
        peringatan(pesan);
    } else if (result == '3') {
        gagal(pesan);
    }
}

function sukses(pesan) {
    Swal.fire({
        title: 'Sukses',
        html: '<h5>' + pesan + '</h5>',
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: '#4caf50', // hijau
        customClass: {
            popup: 'swal2-small' // opsional styling custom
        }
    });
}

function peringatan(pesan) {
    Swal.fire({
        title: 'Oops...',
        html: '<h5>' + pesan + '</h5>',
        icon: 'warning',
        confirmButtonText: 'OK',
        confirmButtonColor: '#ff9800', // oranye
        customClass: {
            popup: 'swal2-small'
        }
    });
}

function gagal(pesan) {
    Swal.fire({
        title: 'Oops...',
        html: '<h5>' + pesan + '</h5>',
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: '#f44336', // merah
        customClass: {
            popup: 'swal2-small'
        }
    });
}

function BukaModal() {
    Swal.fire({
        title: 'Memuat...',
        text: 'Silakan tunggu sebentar.',
        imageUrl: 'assets/images/loader.gif',
        imageWidth: 200,
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
            Swal.close();
        } else if (json.st == 0) {
            pesan('PERINGATAN', json.msg, '');
            $('#table_pegawai').DataTable().ajax.reload();
            $("#modal-content").show();
            swal.close();
        }
    });
}

function BukaModalIstirahat() {
    Swal.fire({
        title: 'Memuat...',
        text: 'Silakan tunggu sebentar.',
        imageUrl: 'assets/images/loader.gif',
        imageWidth: 200,
        showConfirmButton: false,
        allowOutsideClick: false
    });

    $("#modal-content").hide();
    $("#btnSimpan").attr("disabled", true);
    $.post('show_istirahat', function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $("#istirahat-modal").modal('show');
            $("#jam_").html('');
            $("#hari_").html('');
            $("#jam_istirahat").val('');

            $("#jam_").append(json.jam);
            $("#hari_").append(json.hari);
            $("#jam_istirahat").val(json.jam);
            if (json.istirahat_mulai) {
                $("#jam_istirahat_mulai").html('');
                $("#jam_istirahat_mulai").append(json.istirahat_mulai);
                $('#jam_istirahat_mulai').toggleClass('bg-red bg-blue');
            }
            if (json.istirahat_selesai) {
                $("#jam_istirahat_selesai").html('');
                $("#jam_istirahat_selesai").append(json.istirahat_selesai);
                $('#jam_istirahat_selesai').toggleClass('bg-red bg-blue');
                $('#btnSimpanIstirahat').addClass('hidden');
            }

            Swal.close();
            $("#modal-content").show();
            $("#btnSimpan").attr("disabled", false);
        } else {
            peringatan(json.pesan);
        }
    });
}

function EditModal(id, userid) {
    Swal.fire({
        title: 'Memuat...',
        text: 'Silakan tunggu sebentar.',
        imageUrl: 'assets/images/loader.gif',
        imageWidth: 200,
        showConfirmButton: false,
        allowOutsideClick: false
    });

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

            Swal.close();
        } else if (json.st == 0) {
            pesan('PERINGATAN', json.msg, '');
            $('#table_pegawai').DataTable().ajax.reload();
        }
    });
}

function loadPegawai() {
    Swal.fire({
        title: 'Memuat...',
        text: 'Silakan tunggu sebentar.',
        imageUrl: 'assets/images/loader.gif',
        imageWidth: 200,
        showConfirmButton: false,
        allowOutsideClick: false
    });

    $.post('show_list_pegawai', function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $('#dataModal').modal('show');
            $("#pegawai_").html('');

            $("#pegawai_").append(json.pegawai);

            Swal.close();
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

function loadKegiatan(id) {
    Swal.fire({
        title: 'Memuat...',
        text: 'Silakan tunggu sebentar.',
        imageUrl: 'assets/images/loader.gif',
        imageWidth: 200,
        showConfirmButton: false,
        allowOutsideClick: false
    });

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

            Swal.close();
        } else if (json.st == 0) {
            pesan('PERINGATAN', json.msg, '');
            $('#table_pegawai').DataTable().ajax.reload();
        }
    });
}

function presensiLainnya() {
    Swal.fire({
        title: 'Memuat...',
        text: 'Silakan tunggu sebentar.',
        imageUrl: 'assets/images/loader.gif',
        imageWidth: 200,
        showConfirmButton: false,
        allowOutsideClick: false
    });

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

            Swal.close();
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
    var form = $('<form id="formDetailPresensiApel">' +
        '<input type="hidden" name="tgl_apel" value="' + tgl + '">' +
        '</form>');
    // Menambahkan formulir ke dalam dokumen
    $('body').append(form);
    // Mengirimkan formulir
    form.submit();
}

function detailPresensiKegiatan(id) {
    var form = $('<form id="formDetailPresensiKegiatan">' +
        '<input type="hidden" name="id" value="' + id + '">' +
        '</form>');
    // Menambahkan formulir ke dalam dokumen
    $('body').append(form);
    // Mengirimkan formulir
    form.submit();
}

function ModalRole(id) {
    Swal.fire({
        title: 'Memuat...',
        text: 'Silakan tunggu sebentar.',
        imageUrl: 'assets/images/loader.gif',
        imageWidth: 200,
        showConfirmButton: false,
        allowOutsideClick: false
    });

    if (id != '-1') {
        $('#tabel-role').html('');
    }

    $.post('show_role',
        { id: id },
        function (response) {
            Swal.close();

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
                role += `<option value="operator">Operator Kepegawaian</option>`;
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
                        if (`${row.peran}` == 'operator') {
                            var peran = 'Operator Kepegawaian';
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
    Swal.fire({
        title: "Yakin ingin mengaktifkan peran pegawai?",
        text: "Data peran ini akan diaktifkan kembali perannya.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, aktifkan!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            // Eksekusi pengaktifan setelah konfirmasi
            $.post('aktif_peran', { id: id }, function (response) {
                Swal.fire({
                    title: "Berhasil!",
                    text: "Peran telah diaktifkan.",
                    icon: "success",
                    confirmButtonColor: "#8EC165"
                }).then(() => {
                    ModalRole('-1'); // reload tabel atau tindakan lain
                });
            }).fail(function () {
                Swal.fire({
                    title: "Gagal",
                    text: "Terjadi kesalahan saat mengaktifkan data.",
                    icon: "error"
                });
            });
        }
    });
}

function blokPeran(id) {
    Swal.fire({
        title: "Yakin ingin menonaktifkan peran pegawai?",
        text: "Data peran ini akan dinonaktifkan perannya.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, nonaktifkan!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            // Eksekusi setelah konfirmasi
            $.post('blok_peran', { id: id }, function (response) {
                // Jika server kirim JSON, kamu bisa parse:
                // var res = JSON.parse(response);
                Swal.fire({
                    title: "Berhasil!",
                    text: "Peran telah dinonaktifkan.",
                    icon: "success",
                    confirmButtonColor: "#8EC165"
                }).then(() => {
                    ModalRole('-1'); // misal reload tabel atau modal
                });
            }).fail(function () {
                Swal.fire({
                    title: "Gagal!",
                    text: "Terjadi kesalahan saat menghapus data.",
                    icon: "error"
                });
            });
        }
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
    Swal.fire({
        title: "<h5>HAPUS AGENDA KEGIATAN LAINNYA</h5>",
        html: "<h5>Apa Anda Yakin Akan Menghapus Agenda Kegiatan Lainnya?</h5>",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#DD2A2A",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Tidak!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('hapus_kegiatan', { id: id }, function (response) {
                var json = jQuery.parseJSON(response);

                if (json.st == 1) {
                    Swal.fire({
                        title: "Berhasil!",
                        text: "Anda Sudah Menghapus Agenda Kegiatan Lainnya.",
                        icon: "success",
                        confirmButtonColor: "#8EC165",
                        confirmButtonText: "Oke"
                    }).then(() => {
                        loadPage('kegiatan');
                    });
                } else if (json.st == 0) {
                    Swal.fire({
                        title: "Gagal!",
                        text: "Anda Gagal Menghapus Agenda Kegiatan Lainnya, Silakan Ulangi Lagi.",
                        icon: "error"
                    });
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire({
                title: "Batal",
                text: "Anda Batal Menghapus Agenda Kegiatan Lainnya.",
                icon: "error"
            });
        }
    });
}

let map, userMarker, polygonLayer, polygonCoords = [];

function BukaModalLokasiMPP() {
    Swal.fire({
        title: 'Memuat...',
        text: 'Silakan tunggu sebentar.',
        imageUrl: 'assets/images/loader.gif',
        imageWidth: 200,
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

            Swal.close();
            $("#modal-content").show();

            $('#tambah-modal').on('shown.bs.modal', function () {
                $('#btnSimpan').addClass('hidden');
                if (!map) {
                    // Buat map pertama kali
                    map = L.map('map');

                    // Tambah tile layer
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    // Lokasi user
                    map.locate({
                        setView: true,
                        maxZoom: 20,
                        enableHighAccuracy: true,
                        watch: true
                    });

                    map.on("locationfound", function (e) {
                        if (userMarker) {
                            userMarker.setLatLng(e.latlng);
                        } else {
                            userMarker = L.marker(e.latlng).addTo(map);
                        }

                        map.setView(e.latlng, 19); // zoom dekat

                        // Cek apakah user ada di dalam polygon
                        if (polygonCoords.length > 0) {
                            checkInsidePolygon(e.latlng);
                        }
                    });

                    map.on("locationerror", function () {
                        alert("Tidak bisa mendapatkan lokasi Anda");
                        map.setView([-6.2, 106.816], 17); // fallback ke Jakarta
                    });

                    // Ambil polygon dari server
                    $.getJSON('get_lokasi', function (data) {
                        polygonCoords = data.koordinat.map(p => [p.lng, p.lat]); // lng,lat sesuai geojson

                        // Pastikan polygon tertutup
                        if (
                            polygonCoords[0][0] !== polygonCoords[polygonCoords.length - 1][0] ||
                            polygonCoords[0][1] !== polygonCoords[polygonCoords.length - 1][1]
                        ) {
                            polygonCoords.push(polygonCoords[0]);
                        }

                        // Gambar polygon di Leaflet (ingat Leaflet pakai [lat, lng])
                        polygonLayer = L.polygon(data.koordinat.map(p => [p.lat, p.lng]), {
                            color: "green",
                            fillColor: "rgba(89, 225, 119, 1)",
                            fillOpacity: 0.4
                        }).addTo(map);

                        map.setView(polygonLayer.getBounds().getCenter(), 17);
                    });

                } else {
                    map.invalidateSize();
                    map.locate({ setView: true, maxZoom: 17 });
                }
            });
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
            $("#btnSimpan").attr("disabled", false);
        } else if (json.st == 0) {
            pesan('PERINGATAN', json.msg, '');
            $('#table_pegawai').DataTable().ajax.reload();
            $("#loader").hide();
            $("#modal-content").show();
        }
    });
}

function checkInsidePolygon(latlng) {
    let point = turf.point([latlng.lng, latlng.lat]);
    let polygon = turf.polygon([polygonCoords]);
    $('#ket_map').html('');

    if (turf.booleanPointInPolygon(point, polygon)) {
        $('#ket_map').append('<p class="text-success">Anda Diperbolehkan Presensi</p>');
        $('#btnSimpan').removeClass('hidden');
        $("#btnSimpan").attr("disabled", false); // Aktifkan tombol
    } else {
        $('#ket_map').append('<p class="text-danger">Anda Tidak Diperbolehkan presensi karena diluar lokasi presensi</p>');
        $('#btnSimpan').addClass('hidden');
        $("#btnSimpan").attr("disabled", true); // Nonaktifkan tombol
    }
}
