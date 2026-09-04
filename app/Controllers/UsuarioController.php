<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class UsuarioController extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function index()
    {
        $data['usuarios'] = $this->usuarioModel->findAll();
        return view('usuarios/index', $data);
    }

    public function store()
    {
        $rules = [
            'nombre' => [
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'   => 'El nombre es obligatorio.',
                    'min_length' => 'El nombre debe tener al menos 3 caracteres.'
                ]
            ],
            'correo' => [
                'rules'  => 'required|valid_email|is_unique[usuario.correo]',
                'errors' => [
                    'required'    => 'El correo electrónico es obligatorio.',
                    'valid_email' => 'Ingrese un correo electrónico válido.',
                    'is_unique'   => 'Este correo electrónico ya está registrado.'
                ]
            ],
            'clave' => [
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required'   => 'La contraseña es obligatoria.',
                    'min_length' => 'La contraseña debe tener al menos 6 caracteres.'
                ]
            ],
            'rol' => [
                'rules'  => 'required|in_list[administrador,encargado]',
                'errors' => [
                    'required' => 'Debe seleccionar un rol.',
                    'in_list'  => 'El rol seleccionado no es válido.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->usuarioModel->save([
            'nombre' => trim($this->request->getPost('nombre')),
            'correo' => trim($this->request->getPost('correo')),
            'clave'  => password_hash($this->request->getPost('clave'), PASSWORD_DEFAULT),
            'rol'    => $this->request->getPost('rol'),
            'estado' => $this->request->getPost('estado') ?? 1
        ]);

        return redirect()->to(base_url('usuarios'))->with('success', 'Usuario creado correctamente.');
    }

    public function update($id)
    {
        $rules = [
            'nombre' => [
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'   => 'El nombre es obligatorio.',
                    'min_length' => 'El nombre debe tener al menos 3 caracteres.'
                ]
            ],
            'correo' => [
                'rules'  => "required|valid_email|is_unique[usuario.correo,id_usuario,{$id}]",
                'errors' => [
                    'required'    => 'El correo es obligatorio.',
                    'valid_email' => 'Ingrese un correo válido.',
                    'is_unique'   => 'Este correo ya pertenece a otro usuario.'
                ]
            ],
            'clave' => [
                'rules'  => 'permit_empty|min_length[6]',
                'errors' => [
                    'min_length' => 'La contraseña debe tener al menos 6 caracteres.'
                ]
            ],
            'rol' => [
                'rules'  => 'required|in_list[administrador,encargado]',
                'errors' => [
                    'required' => 'Debe seleccionar un rol.',
                    'in_list'  => 'El rol seleccionado no es válido.'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nombre' => trim($this->request->getPost('nombre')),
            'correo' => trim($this->request->getPost('correo')),
            'rol'    => $this->request->getPost('rol'),
            'estado' => $this->request->getPost('estado') ?? 1
        ];

        $nuevaClave = $this->request->getPost('clave');
        if (!empty($nuevaClave)) {
            $data['clave'] = password_hash($nuevaClave, PASSWORD_DEFAULT);
        }

        $this->usuarioModel->update($id, $data);

        return redirect()->to(base_url('usuarios'))->with('success', 'Usuario actualizado correctamente.');
    }

    public function delete($id)
    {
        if (session()->get('id_usuario') == $id) {
            return redirect()->to(base_url('usuarios'))
                ->with('error', 'No puedes eliminar tu propia cuenta en sesión.');
        }

        $this->usuarioModel->delete($id);
        return redirect()->to(base_url('usuarios'))->with('success', 'Usuario eliminado exitosamente.');
    }
}