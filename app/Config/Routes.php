<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(true);
$routes->get('/Home', 'Home::index');

$routes->post('/login', 'LoginController::login');
$routes->options('/login', 'LoginController::login');


$routes->get('/mobil', 'MobilController::index');
$routes->get('/mobil/detail/(:num)', 'MobilController::detail/$1');
$routes->post('/mobil/create', 'MobilController::create');
$routes->get('/mobil/datafilter', 'MobilController::ambildatafilter');
$routes->get('/mobil/filter/(:any)', 'MobilController::filter/$1');
$routes->put('/mobil/update/(:num)', 'MobilController::update/$1');
$routes->delete('/mobil/delete/(:num)', 'MobilController::delete/$1');

