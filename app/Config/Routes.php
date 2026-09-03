<?php

use CodeIgniter\Router\RouteCollection;

// Rutas Públicas (Login)
$routes->get('login', 'AuthController::index');
$routes->post('login/authenticate', 'AuthController::authenticate');
$routes->get('logout', 'AuthController::logout');

// Rutas Protegidas (Requieren autenticación)
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Home::index');
    $routes->get('facturacion', 'Home::index');
    // Registra aquí los demás módulos protegidos...

    
    // --- MÓDULO CATEGORÍAS ---
    $routes->get('categorias', 'CategoriaController::index');
    $routes->post('categorias/guardar', 'CategoriaController::store');
    $routes->post('categorias/actualizar/(:num)', 'CategoriaController::update/$1');
    $routes->get('categorias/eliminar/(:num)', 'CategoriaController::delete/$1');
});








