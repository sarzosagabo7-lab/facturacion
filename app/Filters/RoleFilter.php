<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // 1. Verificar si el usuario ha iniciado sesión
        if (! $session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userRole = $session->get('rol');

        // 2. Si no se especificaron roles permitidos, continuar
        if (empty($arguments)) {
            return;
        }

        // 3. Verificar si el rol del usuario está dentro de los permitidos
        if (! in_array($userRole, $arguments)) {
            // Si no tiene permiso, redirigir a una ruta accesible (ej. facturación o dashboard)
            return redirect()->to('/facturacion')->with('error', 'No tienes permisos para acceder a este módulo.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se requiere procesamiento posterior
    }
}