<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoriaModel;

class CategoriaController extends BaseController
{
    protected $categoriaModel;

    public function __construct()
    {
        $this->categoriaModel = new CategoriaModel();
    }

    public function index()
    {
        $data = [
            'categorias' => $this->categoriaModel->findAll()
        ];

        return view('categorias/index', $data);
    }

    public function store()
    {
        $id = $this->request->getPost('id_categoria');
        $nombre = trim($this->request->getPost('nombre'));

        $data = ['nombre' => $nombre];

        if (!empty($id)) {
            $this->categoriaModel->update($id, $data);
        } else {
            $this->categoriaModel->insert($data);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Operación realizada con éxito.'
        ]);
    }

    public function delete($id = null)
    {
        if (!$id || !$this->categoriaModel->find($id)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'La categoría no existe.'
            ]);
        }

        try {
            $this->categoriaModel->delete($id);
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Categoría eliminada.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'No se puede eliminar la categoría.'
            ]);
        }
    }
}