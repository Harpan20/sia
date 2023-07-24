<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

// LOGIN
$route['default_controller'] = 'login/login';
$route['login'] = 'login/login';
$route['logout'] = 'user/logout';

// DASHBOARD
$route['dashboard'] = 'user/index';

// DATA AKUN
$route['data_akun'] = 'akun/dataAkun';
$route['data_akun/tambah'] = 'akun/createAkun';
$route['data_akun/edit/(:any)'] = 'akun/editAkun/$1';
$route['data_akun/hapus'] = 'akun/deleteAkun';

// JURNAL UMUM
$route['jurnal_umum'] = 'jurnal/jurnalUmum';
$route['jurnal_umum/detail'] = 'jurnal/jurnalUmumDetail';
$route['jurnal_umum/tambah'] = 'jurnal/createJurnal';
$route['jurnal_umum/edit'] = 'jurnal/editJurnal';
$route['jurnal_umum/edit_form'] = 'jurnal/editForm';
$route['jurnal_umum/hapus'] = 'user/deleteJurnal';

// BUKU BESAR
$route['buku_besar'] = 'Buku_besar/bukuBesar';
$route['buku_besar/detail'] = 'Buku_besar/bukuBesarDetail';

// NERACA SALDO
$route['neraca_saldo'] = 'Neraca_saldo/neracaSaldo';
$route['neraca_saldo/detail'] = 'Neraca_saldo/neracaSaldoDetail';

// LAPORAN
$route['laporan'] = 'Neraca_saldo/laporan';
$route['laporan/cetak'] = 'Neraca_saldo/laporanCetak';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;