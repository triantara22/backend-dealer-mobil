<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->options('(:any)', function () {
    return response()->setJSON(['status' => 'OK']);
});

$routes->setTranslateURIDashes(false);
$routes->setAutoRoute(false);

$routes->options('(:any)', function () {
    return response()->setJSON(['status' => 'OK']);
});

$routes->get('/Home', 'Home::index');

$routes->post('/login', 'LoginController::login');
$routes->options('/login', 'LoginController::login');
$routes->post('/gantipw', 'LoginController::changePassword');

// $routes->get('/mobil', 'MobilController::index');

$routes->group('', ['filter' => 'Auth:admin'], function ($routes) {

    $routes->get('/admindashboard', 'Dashboardadmin::index');
    // mobil
    $routes->get('/mobil', 'MobilController::index');
    $routes->get('/mobil/detail/(:num)', 'MobilController::detail/$1');
    $routes->post('/mobil/create', 'MobilController::create');
    $routes->get('/mobil/all', 'MobilController::ambildatafilter');
    $routes->get('/mobil/filter/(:any)', 'MobilController::filter/$1');
    $routes->put('/mobil/update/(:num)', 'MobilController::update/$1');
    $routes->delete('/mobil/delete/(:num)', 'MobilController::delete/$1');

    // pelanggan
    $routes->get('/pelanggan', 'PelangganController::index');
    $routes->get('/pelanggan/filter/(:any)', 'PelangganController::filter/$1');
    $routes->get('/pelanggan/detail/(:segment)', 'PelangganController::detail/$1');
    $routes->get('/pelanggan/histori/(:segment)', 'PelangganController::histori/$1');
    $routes->post('/pelanggan/create', 'PelangganController::create');
    $routes->put('/pelanggan/update/(:segment)', 'PelangganController::update/$1');
    $routes->DELETE('/pelanggan/delete/(:segment)', 'PelangganController::delete/$1');

    $routes->get('/penjualan', 'PenjualanController::index');
    $routes->get('/penjualan/(:segment)', 'PenjualanController::index/$1');
    $routes->post('/penjualan/create', 'PenjualanController::Create');
    $routes->put('/penjualan/update/(:segment)', 'PenjualanController::update/$1');
    $routes->delete('/penjualan/delete/(:segment)', 'PenjualanController::delete/$1');
    $routes->get('/penjualan/filter/(:any)', 'PenjualanController::filter/$1');
    $routes->get('/penjualan/all', 'PenjualanController::ambildatafilter');
    // pembayaran routes
    $routes->get('/pembayaran', 'PenjualanController::pembayaran');
    $routes->get('/pembayaran/filter/(:segment)', 'PenjualanController::filterpembayaran/$1');

    // layanan
    $routes->get('/layanan', 'LayananController::index');
    $routes->get('/layanan/filter/(:any)', 'LayananController::filter/$1');
    $routes->get('/layanan/detail/(:segment)', 'LayananController::detail/$1');
    $routes->post('/layanan/create', 'LayananController::create');
    $routes->put('/layanan/update/(:segment)', 'LayananController::update/$1');
    $routes->delete('/layanan/delete/(:segment)', 'LayananController::delete/$1');

    // garansi
    $routes->get('/garansi', 'Garansi::index');
    $routes->get('/garansi/filter/(:any)', 'Garansi::filter/$1');
    $routes->post('/garansi/create', 'Garansi::create');
    $routes->put('/garansi/update/(:segment)', 'Garansi::update/$1');
    $routes->delete('/garansi/delete/(:segment)', 'Garansi::delete/$1');

    $routes->get('/klaimgaransi', 'Garansi::klaimgaransi');
    $routes->get('/klaimgaransi/filter/(:any)', 'Garansi::filterklaim/$1');
    $routes->put('/klaimgaransi/update/(:segment)', 'Garansi::updateklaim/$1');
    
    $routes->get('/laporanpenjualan', 'PenjualanController::laporan');
    $routes->get('/laporan/pdf', 'PenjualanController::cetakpdf');

    $routes->post('/gantipw', 'LoginController::changePassword');

});

$routes->group('', ['filter' => 'Auth:sales'], function ($routes) {
// sales
    $routes->get('/salespenjualan', 'SalesController::daftarmobil');
    $routes->get('/salespenjualan/(:num)', 'SalesController::daftarmobilid/$1');
    $routes->get('/salespenjualan/jual', 'SalesController::index');
    $routes->post('/salespenjualan/create', 'SalesController::Create');
    $routes->get('/salespenjualan/filter', 'SalesController::filter');

    $routes->get('/salesdashboard', 'Dashboardsales::index');
    $routes->get('/salespelanggan', 'Salespelanggan::index');
    $routes->post('/salespelanggan/create', 'Salespelanggan::create');
    $routes->get('/salespelanggan/filter/(:segment)', 'Salespelanggan::filtersales/$1');

    $routes->post('/salesgantipw', 'LoginController::changePassword');
});

$routes->group('', ['filter' => 'Auth:customer service'], function ($routes) {

    $routes->get('/dashboard', 'Dashboardcs::index');
    $routes->get('/dashboard/chart', 'Dashboardcs::grafikLayanan');
    $routes->get('/cslayanan', 'Cslayanan::index');
    $routes->get('/cslayanan/datamobil', 'Cslayanan::getmobil');
    $routes->get('/cslayanan/datapelanggan', 'Cslayanan::getpelanggan');
    $routes->get('/cslayanan/filter/(:any)', 'Cslayanan::filter/$1');
    $routes->post('/cslayanan/create', 'Cslayanan::create');
    $routes->put('/cslayanan/update/(:segment)', 'Cslayanan::update/$1');
    $routes->delete('/cslayanan/delete/(:segment)', 'Cslayanan::delete/$1');

    $routes->get('/csgaransi', 'CsGaransi::index');
    $routes->get('/csgaransi/filter/(:any)', 'CsGaransi::filter/$1');
    $routes->post('/csgaransi/create', 'CsGaransi::create');

    $routes->get('/csklaimgaransi', 'CsKlaimGaransi::index');
    $routes->get('/csklaimgaransi/filter/(:any)', 'CsKlaimGaransi::filter/$1');
    $routes->post('/csklaimgaransi/create', 'CsKlaimGaransi::ajukanklaim');

    $routes->post('/csgantipw', '::changePassword');
});
