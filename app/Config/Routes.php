<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(true);
$routes->get('/Home', 'Home::index');

$routes->post('/login', 'LoginController::login');

// Mobil routes

