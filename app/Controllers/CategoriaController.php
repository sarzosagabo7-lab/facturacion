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
        $data['categorias'] = $this->categoriaModel->findAll();
        return view('categorias/index', $data);
    }

    public function store()
    {
        // Reglas para CREAR
        $rules = [
            'nombre' => [
                'rules'  => 'required|min_length[3]|max_length[50]|is_unique[categoria.nombre]',
                'errors' => [
                    'required'   => 'El nombre de la categoría es obligatorio.',
                    'min_length' => 'El nombre debe tener al menos 3 caracteres.',
                    'max_length' => 'El nombre no puede exceder los 50 caracteres.',
                    'is_unique'  => 'Esta categoría ya se encuentra registrada.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->categoriaModel->save([
            'nombre' => trim($this->request->getPost('nombre'))
        ]);

        return redirect()->to(base_url('categorias'))->with('success', 'Categoría guardada correctamente.');
    }

    public function update($id)
    {
        // Reglas para ACTUALIZAR (ignorando el ID actual en is_unique)
        $rules = [
            'nombre' => [
                'rules'  => "required|min_length[3]|max_length[50]|is_unique[categoria.nombre,id_categoria,{$id}]",
                'errors' => [
                    'required'   => 'El nombre de la categoría es obligatorio.',
                    'min_length' => 'El nombre debe tener al menos 3 caracteres.',
                    'max_length' => 'El nombre no puede exceder los 50 caracteres.',
                    'is_unique'  => 'Esta categoría ya pertenece a otro registro.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->categoriaModel->update($id, [
            'nombre' => trim($this->request->getPost('nombre'))
        ]);

        return redirect()->to(base_url('categorias'))->with('success', 'Categoría actualizada correctamente.');
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $enUso = $db->table('producto')->where('id_categoria', $id)->countAllResults();

        if ($enUso > 0) {
            return redirect()->to(base_url('categorias'))
                ->with('error', 'No se puede eliminar la categoría porque tiene productos asociados.');
        }

        $this->categoriaModel->delete($id);
        return redirect()->to(base_url('categorias'))->with('success', 'Categoría eliminada exitosamente.');
    }
}