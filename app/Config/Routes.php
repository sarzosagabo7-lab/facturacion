<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('inicio', 'Home::index');

$routes->get('/saludo/(:any)/(:any)', 'Home::saludo/$1/$2', ['as' => 'saludo']);

$routes->get('/sumar/(:num)/(:num)', 'Home::sumita/$1/$2', ['as' => 'sumita']);

$routes->get('/prueba', 'prueba::index');


