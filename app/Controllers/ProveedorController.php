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
        $data['proveedores'] = $this->proveedorModel->findAll();
        return view('proveedores/index', $data);
    }

    public function store()
    {
        // Reglas de validación para CREAR
        $rules = [
            'identificacion' => [
                'rules'  => 'required|validar_cedula|is_unique[proveedor.identificacion]',
                'errors' => [
                    'required'       => 'La cédula/RUC del proveedor es obligatoria.',
                    'validar_cedula' => 'Ingrese un número de identificación válido.',
                    'is_unique'      => 'Esta identificación ya se encuentra registrada.'
                ]
            ],
            'nombre' => [
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'   => 'El nombre o razón social es obligatorio.',
                    'min_length' => 'El nombre debe tener al menos 3 caracteres.',
                    'max_length' => 'El nombre no puede exceder los 100 caracteres.'
                ]
            ],
            'telefono' => [
                'rules'  => 'permit_empty|min_length[7]|max_length[20]',
                'errors' => [
                    'min_length' => 'El teléfono debe tener al menos 7 caracteres.',
                    'max_length' => 'El teléfono no puede exceder los 20 caracteres.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->proveedorModel->save([
            'identificacion' => trim($this->request->getPost('identificacion')),
            'nombre'         => trim($this->request->getPost('nombre')),
            'telefono'       => trim($this->request->getPost('telefono'))
        ]);

        return redirect()->to(base_url('proveedores'))->with('success', 'Proveedor guardado correctamente.');
    }

    public function update($id)
    {
        // Reglas de validación para ACTUALIZAR
        $rules = [
            'identificacion' => [
                'rules'  => "required|validar_cedula|is_unique[proveedor.identificacion,id_proveedor,{$id}]",
                'errors' => [
                    'required'       => 'La cédula/RUC del proveedor es obligatoria.',
                    'validar_cedula' => 'Ingrese un número de identificación válido.',
                    'is_unique'      => 'Esta identificación ya pertenece a otro proveedor.'
                ]
            ],
            'nombre' => [
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'   => 'El nombre o razón social es obligatorio.',
                    'min_length' => 'El nombre debe tener al menos 3 caracteres.',
                    'max_length' => 'El nombre no puede exceder los 100 caracteres.'
                ]
            ],
            'telefono' => [
                'rules'  => 'permit_empty|min_length[7]|max_length[20]',
                'errors' => [
                    'min_length' => 'El teléfono debe tener al menos 7 caracteres.',
                    'max_length' => 'El teléfono no puede exceder los 20 caracteres.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->proveedorModel->update($id, [
            'identificacion' => trim($this->request->getPost('identificacion')),
            'nombre'         => trim($this->request->getPost('nombre')),
            'telefono'       => trim($this->request->getPost('telefono'))
        ]);

        return redirect()->to(base_url('proveedores'))->with('success', 'Proveedor actualizado correctamente.');
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $enUso = $db->table('compra')->where('id_proveedor', $id)->countAllResults();

        if ($enUso > 0) {
            return redirect()->to(base_url('proveedores'))
                ->with('error', 'No se puede eliminar el proveedor porque tiene compras asociadas.');
        }

        $this->proveedorModel->delete($id);
        return redirect()->to(base_url('proveedores'))->with('success', 'Proveedor eliminado exitosamente.');
    }
}