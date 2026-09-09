<?php
namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table            = 'cliente';
    protected $primaryKey       = 'id_cliente';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['identificacion', 'nombre', 'telefono', 'correo'];

    protected $validationRules    = [];
    protected $validationMessages = [];
}