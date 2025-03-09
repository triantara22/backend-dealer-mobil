<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(true);
$routes->get('/', 'Home::index');

// Mobil routes
$routes->get('/mobil', 'MobilController::index');
$routes->get('/mobil/simpan', 'MobilController::simpan');
$routes->get('/mobil/filter/(:any)', 'MobilController::filter/$1');
$routes->get('/mobil/edit/(:num)', 'MobilController::edit/$1');
$routes->get('/mobil/update/(:num)', 'MobilController::update/$1');
$routes->get('/mobil/delete/(:num)', 'MobilController::delete/$1');