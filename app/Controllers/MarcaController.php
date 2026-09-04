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
        $data['marcas'] = $this->marcaModel->findAll();
        return view('marcas/index', $data);
    }

    public function store()
    {
        // Reglas para CREAR
        $rules = [
            'nombre' => [
                'rules'  => 'required|min_length[2]|max_length[50]|is_unique[marca.nombre]',
                'errors' => [
                    'required'   => 'El nombre de la marca es obligatorio.',
                    'min_length' => 'El nombre debe tener al menos 2 caracteres.',
                    'max_length' => 'El nombre no puede exceder los 50 caracteres.',
                    'is_unique'  => 'Esta marca ya se encuentra registrada.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->marcaModel->save([
            'nombre' => trim($this->request->getPost('nombre'))
        ]);

        return redirect()->to(base_url('marcas'))->with('success', 'Marca guardada correctamente.');
    }

    public function update($id)
    {
        // Reglas para ACTUALIZAR (ignorando el ID actual)
        $rules = [
            'nombre' => [
                'rules'  => "required|min_length[2]|max_length[50]|is_unique[marca.nombre,id_marca,{$id}]",
                'errors' => [
                    'required'   => 'El nombre de la marca es obligatorio.',
                    'min_length' => 'El nombre debe tener al menos 2 caracteres.',
                    'max_length' => 'El nombre no puede exceder los 50 caracteres.',
                    'is_unique'  => 'Esta marca ya pertenece a otro registro.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->marcaModel->update($id, [
            'nombre' => trim($this->request->getPost('nombre'))
        ]);

        return redirect()->to(base_url('marcas'))->with('success', 'Marca actualizada correctamente.');
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $enUso = $db->table('producto')->where('id_marca', $id)->countAllResults();

        if ($enUso > 0) {
            return redirect()->to(base_url('marcas'))
                ->with('error', 'No se puede eliminar la marca porque tiene productos asociados.');
        }

        $this->marcaModel->delete($id);
        return redirect()->to(base_url('marcas'))->with('success', 'Marca eliminada exitosamente.');
    }
}