<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'HalamanUtama';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['kegiatan'] = 'HalamanKegiatan';

$route['laporan'] = 'HalamanLaporan';
$route['laporan_bulan'] = 'HalamanLaporan/laporan_bulan';
$route['laporan_satker'] = 'HalamanLaporan/laporan_satker';
$route['laporan_satker_bulan'] = 'HalamanLaporan/laporan_satker_bulan';
$route['laporan_kegiatan'] = 'HalamanLaporan/laporan_kegiatan';
$route['laporan_detail_kegiatan'] = 'HalamanLaporan/laporan_detail_kegiatan';
$route['laporan_all'] = 'HalamanLaporan/cetak_laporan_semua';
$route['laporan_apel'] = 'HalamanLaporan/laporan_apel';
$route['laporan_apel_detail'] = 'HalamanLaporan/laporan_apel_detail';

$route['cetak_laporan_kegiatan'] = 'HalamanLaporan/cetak_detail_laporan_kegiatan';

$route['simpan_perangkat'] = 'HalamanUtama/simpan_perangkat';
$route['simpan_presensi'] = 'HalamanUtama/simpan_presensi';
$route['simpan_presensi_apel'] = 'HalamanUtama/simpan_presensi_apel';
$route['simpan_presensi_rapat'] = 'HalamanUtama/simpan_presensi_rapat';
$route['simpan_presensi_kegiatan'] = 'HalamanUtama/simpan_presensi_kegiatan';
$route['simpan_kegiatan'] = 'HalamanKegiatan/simpan_kegiatan';
$route['simpan_edit'] = 'HalamanLaporan/simpan_edit_presensi';
$route['simpan_peran'] = 'HalamanUtama/simpan_peran';

$route['hapus_kegiatan'] = 'HalamanKegiatan/hapus_kegiatan';

$route['aktif_peran'] = 'HalamanUtama/aktif_peran';
$route['blok_peran'] = 'HalamanUtama/blok_peran';

$route['show_presensi'] = 'HalamanUtama/show';
$route['show_rapat'] = 'HalamanUtama/show_rapat';
$route['show_kegiatan'] = 'HalamanUtama/show_kegiatan';
$route['show_data_kegiatan'] = 'HalamanKegiatan/show';
$route['show_list_pegawai'] = 'HalamanLaporan/show_all_pegawai';
$route['show_role'] = 'HalamanUtama/show_role';

$route['edit_presensi'] = 'HalamanLaporan/edit';
$route['edit_peran'] = 'HalamanUtama/edit_peran';

$route['cetak_masuk'] = 'HalamanLaporan/cetak_laporan_masuk';
$route['cetak_pulang'] = 'HalamanLaporan/cetak_laporan_pulang';

