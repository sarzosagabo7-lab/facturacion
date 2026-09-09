<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClienteModel;

class ClienteController extends BaseController
{
    protected $clienteModel;

    public function __construct()
    {
        $this->clienteModel = new ClienteModel();
    }

    public function index()
    {
        $data = [
            'clientes' => $this->clienteModel->findAll()
        ];
        return view('clientes/index', $data);
    }

    public function store()
    {
        $id             = $this->request->getPost('id_cliente');
        $identificacion = trim($this->request->getPost('identificacion'));
        $nombre         = trim($this->request->getPost('nombre'));
        $telefono       = trim($this->request->getPost('telefono'));
        $correo         = trim($this->request->getPost('correo'));

        // Validar unicidad por número de Cédula / Identificación
        $existente = $this->clienteModel->where('identificacion', $identificacion)->first();
        if ($existente && (empty($id) || $existente['id_cliente'] != $id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'El número de Cédula/Identificación ya está registrado.'
            ]);
        }

        $data = [
            'identificacion' => $identificacion,
            'nombre'         => $nombre,
            'telefono'       => $telefono,
            'correo'         => $correo
        ];

        if (!empty($id)) {
            $this->clienteModel->update($id, $data);
        } else {
            $this->clienteModel->insert($data);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Cliente guardado correctamente.'
        ]);
    }

    public function delete($id = null)
    {
        if (!$id || !$this->clienteModel->find($id)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'El cliente no existe.'
            ]);
        }

        try {
            $this->clienteModel->delete($id);
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Cliente eliminado con éxito.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'No se puede eliminar el cliente porque posee historial de ventas.'
            ]);
        }
    }
}