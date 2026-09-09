<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProveedorModel;

class ProveedorController extends BaseController
{
    protected $proveedorModel;

    public function __construct()
    {
        $this->proveedorModel = new ProveedorModel();
    }

    public function index()
    {
        $data = [
            'proveedores' => $this->proveedorModel->findAll()
        ];
        return view('proveedores/index', $data);
    }

    public function store()
    {
        $id             = $this->request->getPost('id_proveedor');
        $identificacion = trim($this->request->getPost('identificacion'));
        $nombre         = trim($this->request->getPost('nombre'));
        $telefono       = trim($this->request->getPost('telefono'));

        // Validar unicidad por número de Cédula / RUC del Proveedor
        $existente = $this->proveedorModel->where('identificacion', $identificacion)->first();
        if ($existente && (empty($id) || $existente['id_proveedor'] != $id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'El RUC o Cédula ya se encuentra asignado a otro proveedor.'
            ]);
        }

        $data = [
            'identificacion' => $identificacion,
            'nombre'         => $nombre,
            'telefono'       => $telefono
        ];

        if (!empty($id)) {
            $this->proveedorModel->update($id, $data);
        } else {
            $this->proveedorModel->insert($data);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Proveedor guardado con éxito.'
        ]);
    }

    public function delete($id = null)
    {
        if (!$id || !$this->proveedorModel->find($id)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'El proveedor no existe.'
            ]);
        }

        try {
            $this->proveedorModel->delete($id);
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Proveedor eliminado.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'No se puede eliminar el proveedor ya que posee compras asociadas.'
            ]);
        }
    }
}