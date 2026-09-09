<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VentaModel;
use App\Models\DetalleVentaModel;
use App\Models\ClienteModel;
use App\Models\ProductoModel;

class VentaController extends BaseController
{
    protected $ventaModel;
    protected $detalleVentaModel;
    protected $clienteModel;
    protected $productoModel;

    public function __construct()
    {
        $this->ventaModel = new VentaModel();
        $this->detalleVentaModel = new DetalleVentaModel();
        $this->clienteModel = new ClienteModel();
        $this->productoModel = new ProductoModel();
    }

    public function index()
    {
        $data = [
            'ventas' => $this->ventaModel->getVentasConDetalles()
        ];

        return view('facturacion/index', $data);
    }

    public function nueva()
    {
        $data = [
            'clientes'  => $this->clienteModel->findAll(),
            'productos' => $this->productoModel->where('stock >', 0)->findAll()
        ];

        return view('facturacion/nueva', $data);
    }

    // Buscar Cliente por identificacion o nombre (AJAX)
    public function buscarCliente()
    {
        $term = $this->request->getGet('term');

        $clientes = $this->clienteModel
            ->like('identificacion', $term)
            ->orLike('nombre', $term)
            ->findAll(10);

        return $this->response->setJSON($clientes);
    }

    // Buscar Producto por codigo de barras o nombre (AJAX)
    public function buscarProducto()
    {
        $term = $this->request->getGet('term');

        $productos = $this->productoModel
            ->where('stock >', 0)
            ->groupStart()
                ->like('codigo_barras', $term)
                ->orLike('nombre', $term)
            ->groupEnd()
            ->findAll(10);

        return $this->response->setJSON($productos);
    }

    // Guardar Factura / Venta mediante Transacción Atómica
    public function guardar()
    {
        $id_cliente = $this->request->getPost('id_cliente');
        $detalles   = json_decode($this->request->getPost('detalles'), true);

        if (empty($id_cliente) || empty($detalles)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Debe seleccionar un cliente y al menos un producto.'
            ]);
        }

        $id_usuario = session()->get('id_usuario') ?? 1;

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $totalVenta = 0;

            // 1. Validar Stock e Integridad
            foreach ($detalles as $item) {

                $prod = $this->productoModel->find($item['id_producto']);

                if (!$prod || $prod['stock'] < $item['cantidad']) {
                    $db->transRollback();

                    return $this->response->setStatusCode(400)->setJSON([
                        'status'  => 'error',
                        'message' => "Stock insuficiente para el producto: " . ($prod['nombre'] ?? 'Desconocido')
                    ]);
                }

                $subtotalItem = $item['cantidad'] * $item['precio_unitario'];

                $totalVenta += $subtotalItem;
            }

            // 2. Insertar Cabecera (Venta)
            $id_venta = $this->ventaModel->insert([
                'id_cliente' => $id_cliente,
                'id_usuario' => $id_usuario,
                'total'      => $totalVenta
            ]);

            // 3. Insertar Detalle y Descontar Stock
            foreach ($detalles as $item) {

                $subtotalItem = $item['cantidad'] * $item['precio_unitario'];

                $this->detalleVentaModel->insert([
                    'id_venta'        => $id_venta,
                    'id_producto'     => $item['id_producto'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal'        => $subtotalItem
                ]);

                // Descontar Stock
                $this->productoModel
                    ->where('id_producto', $item['id_producto'])
                    ->set(
                        'stock',
                        'stock - ' . (int)$item['cantidad'],
                        false
                    )
                    ->update();
            }

            $db->transCommit();

            return $this->response->setJSON([
                'status'   => 'success',
                'message'  => 'Factura generada exitosamente.',
                'id_venta' => $id_venta
            ]);

        } catch (\Exception $e) {

            $db->transRollback();

            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Ocurrió un error al procesar la factura: ' . $e->getMessage()
            ]);
        }
    }

    // --------------------------------------------------------------------
    // VER FACTURA
    // --------------------------------------------------------------------

    public function ver($id_venta)
    {
        $venta = $this->ventaModel
            ->select('venta.*, 
                      cliente.nombre as cliente_nombre, 
                      cliente.identificacion, 
                      cliente.telefono, 
                      cliente.correo, 
                      usuario.nombre as usuario_nombre')
            ->join('cliente', 'cliente.id_cliente = venta.id_cliente')
            ->join('usuario', 'usuario.id_usuario = venta.id_usuario')
            ->where('venta.id_venta', $id_venta)
            ->first();

        if (!$venta) {
            return redirect()
                ->to(base_url('facturas'))
                ->with('error', 'La factura no existe.');
        }

        $detalles = $this->detalleVentaModel
            ->getDetallesPorVenta($id_venta);

        return $this->response->setJSON([
            'venta'    => $venta,
            'detalles' => $detalles
        ]);
    }

    // --------------------------------------------------------------------
    // IMPRIMIR FACTURA
    // --------------------------------------------------------------------

    public function imprimir($id_venta)
    {
        $venta = $this->ventaModel
            ->select('venta.*, 
                      cliente.nombre as cliente_nombre, 
                      cliente.identificacion, 
                      cliente.telefono, 
                      cliente.correo, 
                      usuario.nombre as usuario_nombre')
            ->join('cliente', 'cliente.id_cliente = venta.id_cliente')
            ->join('usuario', 'usuario.id_usuario = venta.id_usuario')
            ->where('venta.id_venta', $id_venta)
            ->first();

        // Verificar que la factura exista
        if (!$venta) {
            return redirect()
                ->to(base_url('facturas'))
                ->with('error', 'La factura no existe.');
        }

        // Obtener los detalles de la factura
        $detalles = $this->detalleVentaModel
            ->getDetallesPorVenta($id_venta);

        // Enviar información a la vista de impresión
        $data = [
            'venta'    => $venta,
            'detalles' => $detalles
        ];

        return view('facturacion/imprimir', $data);
    }

    // --------------------------------------------------------------------
    // MÉTODOS DE EDICIÓN Y ELIMINACIÓN
    // --------------------------------------------------------------------

    // Cargar la vista de edición de factura
    public function editar($id_venta)
    {
        $venta = $this->ventaModel->find($id_venta);

        if (!$venta) {
            return redirect()
                ->to(base_url('facturas'))
                ->with('error', 'La factura no existe.');
        }

        $data = [
            'venta'     => $venta,
            'detalles'  => $this->detalleVentaModel->getDetallesPorVenta($id_venta),
            'clientes'  => $this->clienteModel->findAll(),
            'productos' => $this->productoModel->findAll()
        ];

        return view('facturacion/editar', $data);
    }

    // Actualizar datos de la factura vía AJAX
    public function actualizar($id_venta)
    {
        $id_cliente = $this->request->getPost('id_cliente');
        $detalles   = json_decode($this->request->getPost('detalles'), true);

        if (empty($id_cliente) || empty($detalles)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Debe seleccionar un cliente y al menos un producto.'
            ]);
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {

            // 1. Revertir el stock de los productos de la venta original
            $detallesAnteriores = $this->detalleVentaModel
                ->where('id_venta', $id_venta)
                ->findAll();

            foreach ($detallesAnteriores as $det) {

                $this->productoModel
                    ->where('id_producto', $det['id_producto'])
                    ->set(
                        'stock',
                        'stock + ' . (int)$det['cantidad'],
                        false
                    )
                    ->update();
            }

            // 2. Eliminar detalles anteriores
            $this->detalleVentaModel
                ->where('id_venta', $id_venta)
                ->delete();

            // 3. Validar stock e integrar nuevos precios/totales
            $totalVenta = 0;

            foreach ($detalles as $item) {

                $prod = $this->productoModel->find($item['id_producto']);

                if (!$prod || $prod['stock'] < $item['cantidad']) {

                    $db->transRollback();

                    return $this->response->setStatusCode(400)->setJSON([
                        'status'  => 'error',
                        'message' => "Stock insuficiente para el producto: " . ($prod['nombre'] ?? 'Desconocido')
                    ]);
                }

                $subtotalItem = $item['cantidad'] * $item['precio_unitario'];

                $totalVenta += $subtotalItem;
            }

            // 4. Actualizar cabecera de la factura
            $this->ventaModel->update($id_venta, [
                'id_cliente' => $id_cliente,
                'total'      => $totalVenta
            ]);

            // 5. Insertar nuevos detalles y descontar nuevo stock
            foreach ($detalles as $item) {

                $subtotalItem = $item['cantidad'] * $item['precio_unitario'];

                $this->detalleVentaModel->insert([
                    'id_venta'        => $id_venta,
                    'id_producto'     => $item['id_producto'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal'        => $subtotalItem
                ]);

                $this->productoModel
                    ->where('id_producto', $item['id_producto'])
                    ->set(
                        'stock',
                        'stock - ' . (int)$item['cantidad'],
                        false
                    )
                    ->update();
            }

            $db->transCommit();

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Factura actualizada exitosamente.'
            ]);

        } catch (\Exception $e) {

            $db->transRollback();

            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Ocurrió un error al actualizar la factura: ' . $e->getMessage()
            ]);
        }
    }

    // Eliminar / Anular Factura vía AJAX y devolver stock
    public function eliminar($id_venta)
    {
        $db = \Config\Database::connect();
        $db->transBegin();

        try {

            // 1. Obtener detalles para reponer stock
            $detalles = $this->detalleVentaModel
                ->where('id_venta', $id_venta)
                ->findAll();

            foreach ($detalles as $item) {

                $this->productoModel
                    ->where('id_producto', $item['id_producto'])
                    ->set(
                        'stock',
                        'stock + ' . (int)$item['cantidad'],
                        false
                    )
                    ->update();
            }

            // 2. Eliminar detalles de la venta
            $this->detalleVentaModel
                ->where('id_venta', $id_venta)
                ->delete();

            // 3. Eliminar cabecera de la venta
            $this->ventaModel->delete($id_venta);

            $db->transCommit();

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Factura eliminada y stock devuelto exitosamente.'
            ]);

        } catch (\Exception $e) {

            $db->transRollback();

            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Ocurrió un error al eliminar la factura: ' . $e->getMessage()
            ]);
        }
    }
}