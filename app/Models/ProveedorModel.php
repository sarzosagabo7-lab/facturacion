<?php

namespace App\Models;

use CodeIgniter\Model;

class ProveedorModel extends Model
{
    protected $table            = 'proveedor';
    protected $primaryKey       = 'id_proveedor';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['identificacion', 'nombre', 'telefono'];
}