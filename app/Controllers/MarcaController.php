<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MarcaModel;

class MarcaController extends BaseController
{
    protected $marcaModel;

    public function __construct()
    {
        $this->marcaModel = new MarcaModel();
    }

    public function index()
    {
        $data = [
            'marcas' => $this->marcaModel->findAll()
        ];
        return view('marcas/index', $data);
    }

    public function store()
    {
        $id     = $this->request->getPost('id_marca');
        $nombre = trim($this->request->getPost('nombre'));

        // Validar unicidad del nombre
        $existente = $this->marcaModel->where('nombre', $nombre)->first();
        if ($existente && (empty($id) || $existente['id_marca'] != $id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'La marca ingresada ya existe.'
            ]);
        }

        $data = ['nombre' => $nombre];

        if (!empty($id)) {
            $this->marcaModel->update($id, $data);
        } else {
            $this->marcaModel->insert($data);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Marca guardada correctamente.'
        ]);
    }

    public function delete($id = null)
    {
        if (!$id || !$this->marcaModel->find($id)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'La marca no existe.'
            ]);
        }

        try {
            $this->marcaModel->delete($id);
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Marca eliminada con éxito.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'No se puede eliminar la marca porque tiene productos vinculados.'
            ]);
        }
    }
}