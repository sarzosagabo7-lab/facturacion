<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

// Login
$routes->get('login', 'AuthController::index');

$routes->post(
    'login/authenticate',
    'AuthController::authenticate'
);

$routes->get(
    'logout',
    'AuthController::logout'
);


/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
|
| Estas rutas requieren autenticación,
| pero NO requieren AJAX.
|
*/

$routes->group('', ['filter' => 'auth'], function ($routes) {

    /*
    |--------------------------------------------------------------------------
    | INICIO
    |--------------------------------------------------------------------------
    */

    $routes->get(
        '/',
        'Home::index'
    );

    $routes->get(
        'facturacion',
        'Home::index'
    );


    /*
    |--------------------------------------------------------------------------
    | CATEGORÍAS
    |--------------------------------------------------------------------------
    */

    // Mostrar interfaz
    $routes->get(
        'categorias',
        'CategoriaController::index'
    );


    // Guardar categoría
    $routes->post(
        'categorias/guardar',
        'CategoriaController::store'
    );


    // Actualizar categoría
    $routes->post(
        'categorias/actualizar/(:num)',
        'CategoriaController::update/$1'
    );


    // Eliminar categoría
    $routes->get(
        'categorias/eliminar/(:num)',
        'CategoriaController::delete/$1'
    );


    /*
    |--------------------------------------------------------------------------
    | AJAX - CATEGORÍAS
    |--------------------------------------------------------------------------
    |
    | Esta ruta SÍ estará protegida por AjaxFilter.
    |
    */

    $routes->get(
        'categorias/datos',
        'CategoriaController::datos',
        ['filter' => 'ajax']
    );

    /*
        |--------------------------------------------------------------------------
        | MARCAS
        |--------------------------------------------------------------------------
        */
        $routes->get('marcas', 'MarcaController::index');
        $routes->post('marcas/guardar', 'MarcaController::store');
        $routes->post('marcas/actualizar/(:num)', 'MarcaController::update/$1');
        $routes->get('marcas/eliminar/(:num)', 'MarcaController::delete/$1');

    /*
        |--------------------------------------------------------------------------
        | CLIENTES
        |--------------------------------------------------------------------------
        */
        $routes->get('clientes', 'ClienteController::index');
        $routes->post('clientes/guardar', 'ClienteController::store');
        $routes->post('clientes/actualizar/(:num)', 'ClienteController::update/$1');
        $routes->get('clientes/eliminar/(:num)', 'ClienteController::delete/$1');

        /*
        |--------------------------------------------------------------------------
        | PROVEEDORES
        |--------------------------------------------------------------------------
        */
        $routes->get('proveedores', 'ProveedorController::index');
        $routes->post('proveedores/guardar', 'ProveedorController::store');
        $routes->post('proveedores/actualizar/(:num)', 'ProveedorController::update/$1');
        $routes->get('proveedores/eliminar/(:num)', 'ProveedorController::delete/$1');

      // Rutas para la gestión de usuarios
$routes->get('usuarios', 'UsuarioController::index');
$routes->post('usuarios/guardar', 'UsuarioController::store');
$routes->post('usuarios/actualizar/(:num)', 'UsuarioController::update/$1');
$routes->get('usuarios/eliminar/(:num)', 'UsuarioController::delete/$1');

});