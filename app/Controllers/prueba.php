<?php

namespace App\Controllers;

class prueba extends BaseController
{
    public function index(): string
    {
       //echo "Hola"; 

       $datos["nombre"] = "JOHAM SARZOSA"; 
       $datos["direccion"] = "IBARRA";

       return view('prueba/index', $datos);
    }  
}
