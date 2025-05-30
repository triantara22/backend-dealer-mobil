<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setTranslateURIDashes(false);
$routes->setAutoRoute(false);

$routes->options('(:any)', function () {
    return response()->setJSON(['status' => 'OK']);
});

$routes->get('/Home', 'Home::index');

$routes->post('/login', 'LoginController::login');
$routes->options('/login', 'LoginController::login');

// $routes->get('/mobil', 'MobilController::index');

$routes->group('', ['filter' => 'Auth:admin'], function ($routes) {
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
    $routes->post('/pelanggan/create', 'PelangganController::create');
    $routes->put('/pelanggan/update/(:segment)', 'PelangganController::update/$1');
    $routes->DELETE('/pelanggan/delete/(:segment)', 'PelangganController::delete/$1');

    $routes->get('/penjualan', 'PenjualanController::index');
    $routes->get('/penjualan/(:segment)', 'PenjualanController::index/$1');
    $routes->post('/penjualan/create', 'PenjualanController::Create');
    $routes->put('/penjualan/update/(:segment)', 'PenjualanController::update/$1');
    $routes->get('/penjualan/filter/(:any)', 'PenjualanController::filter/$1');
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

    $routes->get('/klaimgaransi', 'Garansi::index');
    $routes->get('/klaimgaransi/filter/(:any)', 'Garansi::filter/$1');
    $routes->put('/garansi/update/(:segment)', 'Garansi::update/$1');
});

$routes->group('', ['filter' => 'Auth:sales'], function ($routes) {
// sales
    $routes->get('/salespenjualan', 'SalesController::index');
    $routes->post('/salespenjualan/create', 'SalesController::Create');
    $routes->get('/salespenjualan/filter', 'SalesController::filter');

});

$routes->group('', ['filter' => 'Auth:costumer service'], function ($routes) {

    $routes->get('/cslayanan', 'CsLayanan::index');
    $routes->get('/cslayanan/filter/(:any)', 'CsLayanan::filter/$1');
    $routes->post('/cslayanan/create', 'CsLayanan::create');
    $routes->put('/cslayanan/update/(:segment)', 'CsLayanan::update/$1');
    $routes->delete('/cslayanan/delete/(:segment)', 'CsLayanan::delete/$1');

    $routes->get('/csgaransi', 'Csgaransi::index');
    $routes->get('/csgaransi/filter/(:any)', 'Csgaransi::filter/$1');
    $routes->get('/csgaransi/detail/(:segment)', 'Csgaransi::detail/$1');
    $routes->post('/csgaransi/create', 'Csgaransi::create');
});
