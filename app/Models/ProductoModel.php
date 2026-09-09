<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table            = 'producto';
    protected $primaryKey       = 'id_producto';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // Campos exactos de tu tabla MySQL
    protected $allowedFields    = ['codigo_barras', 'nombre', 'id_categoria', 'id_marca', 'precio_venta', 'stock'];

    public function getProductosConRelaciones()
    {
        return $this->select('producto.*, categoria.nombre as categoria_nombre, marca.nombre as marca_nombre')
                    ->join('categoria', 'categoria.id_categoria = producto.id_categoria', 'left')
                    ->join('marca', 'marca.id_marca = producto.id_marca', 'left')
                    ->findAll();
    }
}