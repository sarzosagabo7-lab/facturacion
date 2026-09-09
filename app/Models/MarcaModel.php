<?php
namespace App\Models;

use CodeIgniter\Model;

class MarcaModel extends Model
{
    protected $table            = 'marca';
    protected $primaryKey       = 'id_marca';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre'];

    protected $validationRules    = [];
    protected $validationMessages = [];
}