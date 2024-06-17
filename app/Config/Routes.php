<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setAutoRoute(true);
// $routes->setTranslateURIDashes(true);

$routes->get('/', 'Home::index');


