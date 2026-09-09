<?php

namespace App\Controllers;

use App\Models\ProveedorModel;
use App\Models\ProductoModel;
use App\Models\CompraModel;
use App\Models\DetalleCompraModel;

class Compras extends BaseController
{
    protected $proveedorModel;
    protected $productoModel;
    protected $compraModel;
    protected $detalleCompraModel;

    public function __construct()
    {
        $this->proveedorModel    = new ProveedorModel();
        $this->productoModel     = new ProductoModel();
        $this->compraModel       = new CompraModel();
        $this->detalleCompraModel = new DetalleCompraModel();
    }

    public function index()
    {
        $data = [
            'titulo'      => 'Registro de Compras',
            'proveedores' => $this->proveedorModel->findAll(),
            'productos'   => $this->productoModel->findAll()
        ];

        return view('compras/index', $data);
    }

public function guardar()
{
    $idProveedor  = $this->request->getPost('id_proveedor');
    $fecha        = $this->request->getPost('fecha');
    $total        = $this->request->getPost('total');

    $productosIds = $this->request->getPost('producto_ids');
    $cantidades   = $this->request->getPost('cantidades');
    $precios      = $this->request->getPost('precios');

    if (empty($productosIds) || count($productosIds) === 0) {
        return redirect()->back()->with('error', 'Debe agregar al menos un producto a la compra.')->withInput();
    }

    $db = \Config\Database::connect();

    try {
        $db->transStart();

        $idUsuario = session()->get('id_usuario') ?? session()->get('id') ?? 1;

        // 1. Insertar Cabecera de Compra
        $dataCompra = [
            'fecha'        => $fecha . ' ' . date('H:i:s'),
            'id_proveedor' => $idProveedor,
            'id_usuario'   => $idUsuario,
            'total'        => $total
        ];

        $compraId = $this->compraModel->insert($dataCompra);

        if (!$compraId) {
            $err = implode(', ', $this->compraModel->errors());
            throw new \Exception("Error en Compra: " . $err);
        }

        // 2. Insertar Detalle usando costo_unitario
        for ($i = 0; $i < count($productosIds); $i++) {
            $cant     = (float) $cantidades[$i];
            $precio   = (float) $precios[$i];
            $subtotal = $cant * $precio;

            $dataDetalle = [
                'id_compra'      => $compraId,
                'id_producto'    => $productosIds[$i],
                'cantidad'       => $cant,
                'costo_unitario' => $precio,
                'subtotal'       => $subtotal
            ];

            $detId = $this->detalleCompraModel->insert($dataDetalle);

            if (!$detId) {
                $errDet = implode(', ', $this->detalleCompraModel->errors());
                throw new \Exception("Error en DetalleCompra: " . $errDet);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \Exception("Transacción fallida en la base de datos.");
        }

        return redirect()->to(base_url('compras'))->with('mensaje', '¡Compra registrada con éxito!');

    } catch (\Throwable $e) {
        $db->transRollback();
        return redirect()->back()->with('error', 'Error BD: ' . $e->getMessage())->withInput();
    }


    }
}