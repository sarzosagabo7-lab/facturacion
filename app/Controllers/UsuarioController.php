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
        $data = [
            'usuarios' => $this->usuarioModel->findAll()
        ];
        return view('usuarios/index', $data);
    }

    public function store()
    {
        $id     = $this->request->getPost('id_usuario');
        $nombre = trim($this->request->getPost('nombre'));
        $correo = trim($this->request->getPost('correo'));
        $clave  = $this->request->getPost('clave');
        $rol    = $this->request->getPost('rol');
        $estado = $this->request->getPost('estado') !== null ? (int)$this->request->getPost('estado') : 1;

        // Validar unicidad de correo
        $existente = $this->usuarioModel->where('correo', $correo)->first();
        if ($existente && (empty($id) || $existente['id_usuario'] != $id)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'El correo electrónico ya se encuentra registrado.'
            ]);
        }

        $data = [
            'nombre' => $nombre,
            'correo' => $correo,
            'rol'    => $rol,
            'estado' => $estado
        ];

        // Encriptar contraseña solo si se ingresó una nueva
        if (!empty($clave)) {
            $data['clave'] = password_hash($clave, PASSWORD_BCRYPT);
        }

        if (!empty($id)) {
            $this->usuarioModel->update($id, $data);
        } else {
            if (empty($clave)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status'  => 'error',
                    'message' => 'La contraseña es requerida para un nuevo usuario.'
                ]);
            }
            $this->usuarioModel->insert($data);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Usuario guardado correctamente.'
        ]);
    }

    public function delete($id = null)
    {
        if (!$id || !$this->usuarioModel->find($id)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'El usuario no existe.'
            ]);
        }

        try {
            $this->usuarioModel->delete($id);
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Usuario eliminado correctamente.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'No se puede eliminar el usuario ya que posee registros asociados.'
            ]);
        }
    }
}