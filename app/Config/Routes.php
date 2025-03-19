<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(true);
$routes->get('/', 'Home::index');

// Mobil routes
$routes->get('/mobil', 'MobilController::index');
$routes->get('/mobil/detail/(:num)', 'MobilController::detail/$1');
$routes->get('/mobil/create', 'MobilController::create');
$routes->post('/mobil/simpan', 'MobilController::simpan');
$routes->get('/mobil/filter/(:any)', 'MobilController::filter/$1');
$routes->get('/mobil/edit/(:num)', 'MobilController::edit/$1');
$routes->put('/mobil/update/(:num)', 'MobilController::update/$1');
$routes->post('/mobil/delete/(:num)', 'MobilController::delete/$1');
