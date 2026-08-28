<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function saludo($nombre,$apellido){
        echo "Hola " . $nombre . " " . $apellido;
    }

    public function sumita($num1, $num2){
        $suma = $num1 + $num2;
        echo "El resultado es: $suma";
    }
  
}
