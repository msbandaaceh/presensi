<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'HalamanUtama';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['kegiatan'] = 'HalamanKegiatan';

$route['laporan_kegiatan'] = 'HalamanLaporan/laporan_kegiatan';
$route['laporan_detail_kegiatan'] = 'HalamanLaporan/laporan_detail_kegiatan';

$route['cetak_laporan_kegiatan'] = 'HalamanLaporan/cetak_detail_laporan_kegiatan';

$route['simpan_perangkat'] = 'HalamanUtama/simpan_perangkat';
$route['simpan_presensi'] = 'HalamanUtama/simpan_presensi';
$route['simpan_presensi_apel'] = 'HalamanUtama/simpan_presensi_apel';
$route['simpan_presensi_rapat'] = 'HalamanUtama/simpan_presensi_rapat';
$route['simpan_presensi_kegiatan'] = 'HalamanUtama/simpan_presensi_kegiatan';
$route['simpan_kegiatan'] = 'HalamanKegiatan/simpan_kegiatan';

$route['hapus_kegiatan'] = 'HalamanKegiatan/hapus_kegiatan';

$route['show_presensi'] = 'HalamanUtama/show';
$route['show_rapat'] = 'HalamanUtama/show_rapat';
$route['show_kegiatan'] = 'HalamanUtama/show_kegiatan';
$route['show_data_kegiatan'] = 'HalamanKegiatan/show';
