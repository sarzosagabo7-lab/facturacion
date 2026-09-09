<?php
namespace App\Controllers;

use App\Models\UsuarioModel;

class AuthController extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function index()
    {
        // Si ya está autenticado, redirigir al módulo principal
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('facturacion'));
        }
        return view('auth/login');
    }

    public function authenticate()
    {
        // En el formulario el input puede llamarse 'username' o 'correo'
        $correo   = trim($this->request->getPost('username') ?? $this->request->getPost('correo'));
        $password = $this->request->getPost('password');

        if (empty($correo) || empty($password)) {
            return redirect()->back()->with('error', 'Por favor, ingrese el correo y la contraseña.');
        }

        // Buscar el usuario en la base de datos por su correo
        $usuario = $this->usuarioModel->where('correo', $correo)->first();

        // 1. Verificar si el usuario existe
        if (!$usuario) {
            return redirect()->back()->with('error', 'Las credenciales ingresadas son incorrectas.');
        }

        // 2. Verificar si la cuenta está activa (estado == 1)
        if (!$usuario['estado']) {
            return redirect()->back()->with('error', 'El usuario se encuentra inactivo. Contacte al administrador.');
        }

        // 3. Verificar la contraseña encriptada (soporta password_hash)
        // Nota: Si tus usuarios iniciales tienen clave en texto plano, puedes usar ($password === $usuario['clave'])
        $passwordValida = password_verify($password, $usuario['clave']) || ($password === $usuario['clave']);

        if ($passwordValida) {
            // Guardar datos reales del usuario en la sesión
            session()->set([
                'id_usuario' => $usuario['id_usuario'],
                'nombre'     => $usuario['nombre'],
                'correo'     => $usuario['correo'],
                'rol'        => $usuario['rol'], // 'administrador' o 'encargado'
                'isLoggedIn' => true,
            ]);
            

            return redirect()->to(base_url('facturacion'));
        }

        return redirect()->back()->with('error', 'Las credenciales ingresadas son incorrectas.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}