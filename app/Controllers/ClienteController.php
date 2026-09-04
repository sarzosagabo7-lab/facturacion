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
        $data['clientes'] = $this->clienteModel->findAll();
        return view('clientes/index', $data);
    }

    public function store()
    {
        // Reglas de validación para CREAR
        $rules = [
            'identificacion' => [
                'rules'  => 'required|validar_cedula|is_unique[cliente.identificacion]',
                'errors' => [
                    'required'       => 'La cédula de identidad es obligatoria.',
                    'validar_cedula' => 'Ingrese una cédula de identidad ecuatoriana válida.',
                    'is_unique'      => 'Esta cédula ya se encuentra registrada.'
                ]
            ],
            'nombre' => [
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'   => 'El nombre del cliente es obligatorio.',
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
            ],
            'correo' => [
                'rules'  => 'permit_empty|valid_email|max_length[100]',
                'errors' => [
                    'valid_email' => 'Por favor ingrese un correo electrónico válido.',
                    'max_length'  => 'El correo no puede exceder los 100 caracteres.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->clienteModel->save([
            'identificacion' => trim($this->request->getPost('identificacion')),
            'nombre'         => trim($this->request->getPost('nombre')),
            'telefono'       => trim($this->request->getPost('telefono')),
            'correo'         => trim($this->request->getPost('correo'))
        ]);

        return redirect()->to(base_url('clientes'))->with('success', 'Cliente guardado correctamente.');
    }

    public function update($id)
    {
        // Reglas de validación para ACTUALIZAR (ignorando la identificación actual)
        $rules = [
            'identificacion' => [
                'rules'  => "required|validar_cedula|is_unique[cliente.identificacion,id_cliente,{$id}]",
                'errors' => [
                    'required'       => 'La cédula de identidad es obligatoria.',
                    'validar_cedula' => 'Ingrese una cédula de identidad ecuatoriana válida.',
                    'is_unique'      => 'Esta cédula ya pertenece a otro cliente.'
                ]
            ],
            'nombre' => [
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'   => 'El nombre del cliente es obligatorio.',
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
            ],
            'correo' => [
                'rules'  => 'permit_empty|valid_email|max_length[100]',
                'errors' => [
                    'valid_email' => 'Por favor ingrese un correo electrónico válido.',
                    'max_length'  => 'El correo no puede exceder los 100 caracteres.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->clienteModel->update($id, [
            'identificacion' => trim($this->request->getPost('identificacion')),
            'nombre'         => trim($this->request->getPost('nombre')),
            'telefono'       => trim($this->request->getPost('telefono')),
            'correo'         => trim($this->request->getPost('correo'))
        ]);

        return redirect()->to(base_url('clientes'))->with('success', 'Cliente actualizado correctamente.');
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $enUso = $db->table('venta')->where('id_cliente', $id)->countAllResults();

        if ($enUso > 0) {
            return redirect()->to(base_url('clientes'))
                ->with('error', 'No se puede eliminar el cliente porque tiene ventas/facturas registradas.');
        }

        $this->clienteModel->delete($id);
        return redirect()->to(base_url('clientes'))->with('success', 'Cliente eliminado exitosamente.');
    }
}