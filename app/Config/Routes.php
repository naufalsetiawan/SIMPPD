<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// auth
$routes->get('/', 'AuthController::login');
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::login');
$routes->get('/logout', 'AuthController::logout');

// USER
$routes->post('/user/switch_role', 'UserController::switchRole');

// DASHBOARD
$routes->get('/penugasan/dashboard', 'DashboardController::dashboard_st');
$routes->get('/espj/dashboard', 'DashboardController::dashboard_espj');

// LIST PENUGASAN 
$routes->get('/penugasan/tugas', 'PenugasanController::daftar_penugasan');
$routes->get('/penugasan/pengajuan_surat_tugas', 'PenugasanController::pengajuan_surat_tugas');
$routes->get('/penugasan/draft_st/(:num)', 'PenugasanController::view_draft_st/$1');

$routes->post('/penugasan/(:num)/update_status', 'PenugasanController::update_status_st/$1');
// KOMENTAR
$routes->post('/penugasan/tugas/add_komentar', 'PenugasanController::addKomentar');
$routes->post('/penugasan/tugas/pengajuan_spd', 'PenugasanController::pengajuan_spd');

//DOKUMEN
$routes->get('/generate-pdf', 'PenugasanController::generate');
$routes->get('/pdf/(:num)', 'PenugasanController::generate/$1');
$routes->get('/cetak_visum/(:num)', 'PenugasanController::cetak_visum/$1');

// FORM PENUGASAN
// ADD PENUGASAN
$routes->get('/penugasan/add_tugas/form_tugas', 'FormController::form_tugas');
$routes->post('/penugasan/add_tugas/form_tugas/submit', 'FormController::submit_form_tugas');
$routes->get('/penugasan/add_tugas/form_st/(:num)', 'FormController::form_st/$1');
$routes->post('/penugasan/add_tugas/form_st/(:num)/submit', 'FormController::submit_form_st');
$routes->get('/penugasan/add_tugas/form_peserta/(:num)', 'FormController::form_peserta/$1');
$routes->post('/penugasan/add_tugas/form_peserta/(:num)/submit', 'FormController::submit_form_peserta/$1');
// EDIT PENUGASAN
$routes->get('penugasan/edit_tugas/form_tugas/(:num)', 'FormController::form_tugas/$1');
$routes->post('/penugasan/edit_tugas/form_tugas/(:num)/update', 'FormController::submit_form_tugas');
$routes->get('/penugasan/edit_tugas/form_st/(:num)', 'FormController::form_st/$1');
$routes->post('/penugasan/edit_tugas/form_st/(:num)/update', 'FormController::submit_form_st');
$routes->get('/penugasan/edit_tugas/form_peserta/(:num)', 'FormController::form_peserta/$1');
$routes->post('/penugasan/edit_tugas/form_peserta/(:num)/update', 'FormController::submit_form_peserta/$1');

// DELETE PENUGASAN
$routes->post('/penugasan/tugas/delete', 'PenugasanController::deleteTugas');


$routes->post('/penugasan/tugas/tolak', 'PenugasanController::tolakST');
$routes->post('/penugasan/tugas/setuju', 'PenugasanController::setujuiST');

$routes->post('/penugasan/tugas/kembalikan', 'PenugasanController::tolakST');
$routes->post('/penugasan/draf_st/update', 'PenugasanController::update_st');

$routes->get('/penugasan/rekap', 'PenugasanController::rekap');
$routes->get('/penugasan/rekap/export', 'PenugasanController::rekap_export');

$routes->group('admin', ['filter' => 'role:0'], function ($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');

    $routes->get('kota', 'AdminController::daftar_kota');
    $routes->post('import_provinsi', 'AdminController::import_provinsi');
    $routes->post('import_kota', 'AdminController::import_kota');

    $routes->get('tim_kerja', 'AdminController::list_tim_kerja');
    $routes->post('tim_kerja/add', 'AdminController::add_tim_kerja');
    $routes->post('tim_kerja/save', 'AdminController::save_tim_kerja');
    $routes->post('tim_kerja/delete', 'AdminController::delete_tim_kerja');

    $routes->post('landasan/save', 'AdminController::save_landasan');
    $routes->post('landasan/delete', 'AdminController::delete_landasan');

    $routes->get('kop_surat', 'AdminController::kop_surat');
    $routes->post('kop_surat/upload', 'AdminController::kop_surat_upload');

    $routes->get('users', 'AdminController::list_users');
    $routes->post('user/save', 'AdminController::save_user');
    $routes->post('user/delete', 'AdminController::delete_user');
    $routes->get('add_user', 'AdminController::add_user_page');

    $routes->get('pegawai', 'AdminController::list_pegawai');
    $routes->post('pegawai/save', 'AdminController::save_pegawai');
    $routes->post('pegawai/delete', 'AdminController::delete_pegawai');
    $routes->post('pegawai/import-csv', 'AdminController::add_pegawai_csv');
    $routes->get('pegawai/filter', 'PegawaiController::filter');

    $routes->get('landasan_surat', 'AdminController::landasan_surat');

    $routes->get('petugas', 'AdminController::list_petugas');
    $routes->post('petugas/save', 'AdminController::save_petugas');
    $routes->post('petugas/delete', 'AdminController::delete_petugas');
    $routes->post('petugas/nonaktif', 'AdminController::nonaktif_petugas');

    $routes->get('penugasan/tugas', 'PenugasanController::daftar_penugasan');
    $routes->post('st/update_status', 'AdminController::update_status_st');

    // FORM PENUGASAN
    // ADD PENUGASAN
    $routes->get('/penugasan/add_tugas/form_tugas', 'FormController::form_tugas');
    $routes->post('/penugasan/add_tugas/form_tugas/submit', 'FormController::submit_form_tugas');
    $routes->get('/penugasan/add_tugas/form_st/(:num)', 'FormController::form_st/$1');
    $routes->post('/penugasan/add_tugas/form_st/(:num)/submit', 'FormController::submit_form_st');
    $routes->get('/penugasan/add_tugas/form_peserta/(:num)', 'FormController::form_peserta/$1');
    $routes->post('/penugasan/add_tugas/form_peserta/(:num)/submit', 'FormController::submit_form_peserta/$1');
    // EDIT PENUGASAN
    $routes->get('penugasan/edit_tugas/form_tugas/(:num)', 'FormController::form_tugas/$1');
    $routes->post('/penugasan/edit_tugas/form_tugas/(:num)/update', 'FormController::submit_form_tugas');
    $routes->get('/penugasan/edit_tugas/form_st/(:num)', 'FormController::form_st/$1');
    $routes->post('/penugasan/edit_tugas/form_st/(:num)/update', 'FormController::submit_form_st');
    $routes->get('/penugasan/edit_tugas/form_peserta/(:num)', 'FormController::form_peserta/$1');
    $routes->post('/penugasan/edit_tugas/form_peserta/(:num)/update', 'FormController::submit_form_peserta/$1');

    // DELETE PENUGASAN
    $routes->post('/penugasan/tugas/delete', 'PenugasanController::deleteTugas');
    $routes->post('/penugasan/(:num)/update_status', 'PenugasanController::update_status_st/$1');
});
