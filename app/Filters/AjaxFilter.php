<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class AjaxFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verifica si la petición es AJAX mediante la cabecera de HTTP o el método del Request
        if (! $request->isAJAX() && $request->getHeaderLine('X-Requested-With') !== 'XMLHttpRequest') {
            return Services::response()
                ->setStatusCode(403)
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Acceso denegado. Este recurso solo acepta peticiones AJAX.'
                ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se requiere acción posterior
    }
}