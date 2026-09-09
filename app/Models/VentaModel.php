<?php
namespace App\Models;

use CodeIgniter\Model;

class VentaModel extends Model
{
    protected $table            = 'venta';
    protected $primaryKey       = 'id_venta';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['fecha', 'id_cliente', 'id_usuario', 'total'];

    // Obtener ventas con información contextual de cliente y usuario
    public function getVentasConDetalles()
    {
        return $this->select('venta.*, cliente.nombre as cliente_nombre, cliente.identificacion, usuario.nombre as usuario_nombre')
                    ->join('cliente', 'cliente.id_cliente = venta.id_cliente')
                    ->join('usuario', 'usuario.id_usuario = venta.id_usuario')
                    ->orderBy('venta.fecha', 'DESC')
                    ->findAll();
    }
}