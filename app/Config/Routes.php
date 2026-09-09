<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Configuración por defecto
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

// --------------------------------------------------------------------
// Rutas Públicas (Login y Sesión)
// --------------------------------------------------------------------
$routes->get('login', 'AuthController::index');
$routes->post('login/authenticate', 'AuthController::authenticate');
$routes->get('logout', 'AuthController::logout');

// --------------------------------------------------------------------
// Rutas Protegidas - VISTAS Y FORMULARIOS (Requieren autenticación)
// --------------------------------------------------------------------
$routes->group('', ['filter' => 'auth'], function ($routes) {

    // Inicio / Dashboard / Facturación
    $routes->get('/', 'Home::index');
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('dashboard/getData', 'DashboardController::getData');
    $routes->get('facturacion', 'Home::index');

    // --- MÓDULO CATEGORÍAS ---
    $routes->get('categorias', 'CategoriaController::index');
    $routes->get('categoria', 'CategoriaController::index');

    // --- MÓDULO USUARIOS ---
    $routes->get('usuarios', 'UsuarioController::index');
    $routes->get('usuario', 'UsuarioController::index');

    // --- MÓDULO MARCAS ---
    $routes->get('marcas', 'MarcaController::index');
    $routes->get('marca', 'MarcaController::index');

    // --- MÓDULO CLIENTES ---
    $routes->get('clientes', 'ClienteController::index');
    $routes->get('cliente', 'ClienteController::index');

    // --- MÓDULO PROVEEDORES ---
    $routes->get('proveedores', 'ProveedorController::index');
    $routes->get('proveedor', 'ProveedorController::index');

    // --- MÓDULO PRODUCTOS ---
    $routes->get('productos', 'ProductoController::index');
    $routes->get('producto', 'ProductoController::index');

    // --- MÓDULO FACTURAS / VENTAS ---
    $routes->get('facturas', 'VentaController::index');
    $routes->get('facturas/nueva', 'VentaController::nueva');
    $routes->get('facturas/ver/(:num)', 'VentaController::ver/$1');

    // --- MÓDULO COMPRAS ---
    $routes->get('compras', 'Compras::index');
    $routes->get('compra', 'Compras::index');
    $routes->post('compras/guardar', 'Compras::guardar');
    $routes->post('compra/guardar', 'Compras::guardar');
    $routes->get('compras/buscarProducto', 'Compras::buscarProducto');
});

// --------------------------------------------------------------------
// Rutas Protegidas - ACCIONES AJAX (Requieren autenticación Y petición AJAX)
// --------------------------------------------------------------------
$routes->group('', ['filter' => ['auth', 'ajax']], function ($routes) {

    // --- CATEGORÍAS ---
    $routes->post('categorias/guardar', 'CategoriaController::store');
    $routes->post('categorias/actualizar/(:num)', 'CategoriaController::update/$1');
    $routes->post('categorias/eliminar/(:num)', 'CategoriaController::delete/$1');
    $routes->post('categoria/guardar', 'CategoriaController::store');
    $routes->post('categoria/actualizar/(:num)', 'CategoriaController::update/$1');
    $routes->post('categoria/eliminar/(:num)', 'CategoriaController::delete/$1');

    // --- USUARIOS ---
    $routes->post('usuarios/guardar', 'UsuarioController::store');
    $routes->post('usuarios/actualizar/(:num)', 'UsuarioController::update/$1');
    $routes->post('usuarios/eliminar/(:num)', 'UsuarioController::delete/$1');
    $routes->post('usuario/guardar', 'UsuarioController::store');
    $routes->post('usuario/actualizar/(:num)', 'UsuarioController::update/$1');
    $routes->post('usuario/eliminar/(:num)', 'UsuarioController::delete/$1');

    // --- MARCAS ---
    $routes->post('marcas/guardar', 'MarcaController::store');
    $routes->post('marcas/eliminar/(:num)', 'MarcaController::delete/$1');
    $routes->post('marca/guardar', 'MarcaController::store');
    $routes->post('marca/eliminar/(:num)', 'MarcaController::delete/$1');

    // --- CLIENTES ---
    $routes->post('clientes/guardar', 'ClienteController::store');
    $routes->post('clientes/eliminar/(:num)', 'ClienteController::delete/$1');
    $routes->post('cliente/guardar', 'ClienteController::store');
    $routes->post('cliente/eliminar/(:num)', 'ClienteController::delete/$1');

    // --- PROVEEDORES ---
    $routes->post('proveedores/guardar', 'ProveedorController::store');
    $routes->post('proveedores/eliminar/(:num)', 'ProveedorController::delete/$1');
    $routes->post('proveedor/guardar', 'ProveedorController::store');
    $routes->post('proveedor/eliminar/(:num)', 'ProveedorController::delete/$1');

    // --- PRODUCTOS ---
    $routes->post('productos/guardar', 'ProductoController::store');
    $routes->post('productos/eliminar/(:num)', 'ProductoController::delete/$1');
    $routes->post('producto/guardar', 'ProductoController::store');
    $routes->post('producto/eliminar/(:num)', 'ProductoController::delete/$1');

    // --- FACTURACIÓN Y VENTAS ---
    $routes->get('facturas/buscar-cliente', 'VentaController::buscarCliente');
    $routes->get('facturas/buscar-producto', 'VentaController::buscarProducto');
    $routes->post('facturas/guardar', 'VentaController::guardar');
});

// --------------------------------------------------------------------
// Rutas por Roles
// --------------------------------------------------------------------
$routes->group('facturacion', ['filter' => 'role:administrador,encargado'], function ($routes) {
    $routes->get('/', 'VentaController::index');
    $routes->get('nueva', 'VentaController::nueva');
    $routes->post('guardar', 'VentaController::guardar');
    $routes->get('facturas/imprimir/(:num)', 'VentaController::imprimir/$1');
});

// Rutas accesibles ÚNICAMENTE por 'administrador'
$routes->group('', ['filter' => 'role:administrador'], function ($routes) {
    $routes->resource('usuarios', ['controller' => 'UsuarioController']);
    $routes->resource('productos', ['controller' => 'ProductoController']);
    $routes->resource('categorias', ['controller' => 'CategoriaController']);
    $routes->resource('marcas', ['controller' => 'MarcaController']);
    $routes->resource('proveedores', ['controller' => 'ProveedorController']);
    $routes->resource('clientes', ['controller' => 'ClienteController']);

    $routes->get('facturas/imprimir/(:num)', 'VentaController::imprimir/$1');
});