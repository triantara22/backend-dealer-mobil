<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setTranslateURIDashes(false);
$routes->setAutoRoute(false);
$routes->get('/Home', 'Home::index');

$routes->post('/login', 'LoginController::login');
$routes->options('/login', 'LoginController::login');

$routes->group('', ['filter' => 'Auth:admin'], function ($routes) {

    $routes->get('/mobil', 'MobilController::index');
    $routes->get('/mobil/detail/(:num)', 'MobilController::detail/$1');
    $routes->post('/mobil/create', 'MobilController::create');
    $routes->get('/mobil/datafilter', 'MobilController::ambildatafilter');
    $routes->get('/mobil/filter/(:any)', 'MobilController::filter/$1');
    $routes->put('/mobil/update/(:num)', 'MobilController::update/$1');
    $routes->delete('/mobil/delete/(:num)', 'MobilController::delete/$1');

    $routes->get('/pelanggan', 'PelangganController::index');
    $routes->get('/pelanggan/filter/(:any)', 'PelangganController::filter/$1');
    $routes->get('/pelanggan/detail/(:segment)', 'PelangganController::detail/$1');
    $routes->post('/pelanggan/create', 'PelangganController::create');
    $routes->put('/pelanggan/update/(:segment)', 'PelangganController::update/$1');
    $routes->delete('/pelanggan/delete/(:segment)', 'PelangganController::delete/$1');

});
