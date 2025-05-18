<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(true);
$routes->get('/Home', 'Home::index');

// penjualan routes
// admin
$routes->get('/penjualan', 'PenjualanController::index');
$routes->put('/penjualan/update/(:segment)', 'PenjualanController::update/$1');
$routes->get('/penjualan/filter', 'PenjualanController::filter');
// pembayaran routes
$routes->get('/pembayaran', 'PenjualanController::index');
$routes->get('/pembayaran/filter', 'PenjualanController::filterpembayaran');
// sales
$routes->get('/penjualan', 'SalesController::index');
$routes->post('/penjualan/create', 'SalesController::Create');
$routes->get('/penjualan/filter', 'PenjualanController::filter');
