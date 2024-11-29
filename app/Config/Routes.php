<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// custom routes
$routes->get('/premium', 'Premium::index');
$routes->get('/method-list', 'Premium::methodList');
$routes->get('/motor-premium', 'Premium::calculateMotorPremium');
