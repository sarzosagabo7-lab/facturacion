<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductoModel;
use App\Models\CategoriaModel;
use App\Models\MarcaModel;

class ProductoController extends BaseController
{
    protected $productoModel;
    protected $categoriaModel;
    protected $marcaModel;

    public function __construct()
    {
        $this->productoModel  = new ProductoModel();
        $this->categoriaModel = new CategoriaModel();
        $this->marcaModel     = new MarcaModel();
    }

    public function index()
    {
        $data = [
            'productos'  => $this->productoModel->getProductosConRelaciones(),
            'categorias' => $this->categoriaModel->findAll(),
            'marcas'     => $this->marcaModel->findAll()
        ];
        return view('productos/index', $data);
    }

    public function store()
    {
        $id            = $this->request->getPost('id_producto');
        $codigo_barras = trim($this->request->getPost('codigo_barras'));
        $nombre        = trim($this->request->getPost('nombre'));
        $precio_venta  = floatval($this->request->getPost('precio_venta'));
        $stock         = intval($this->request->getPost('stock'));
        $id_categoria  = $this->request->getPost('id_categoria');
        $id_marca      = $this->request->getPost('id_marca');

        // Validar unicidad del código de barras si fue ingresado
        if (!empty($codigo_barras)) {
            $existenteCodigo = $this->productoModel->where('codigo_barras', $codigo_barras)->first();
            if ($existenteCodigo && (empty($id) || $existenteCodigo['id_producto'] != $id)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status'  => 'error',
                    'message' => 'El código de barras ingresado ya existe en la base de datos.'
                ]);
            }
        }

        $data = [
            'codigo_barras' => !empty($codigo_barras) ? $codigo_barras : null,
            'nombre'        => $nombre,
            'precio_venta'  => $precio_venta,
            'stock'         => $stock,
            'id_categoria'  => $id_categoria,
            'id_marca'      => $id_marca
        ];

        if (!empty($id)) {
            $this->productoModel->update($id, $data);
        } else {
            $this->productoModel->insert($data);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Producto guardado correctamente.'
        ]);
    }

    public function delete($id = null)
    {
        if (!$id || !$this->productoModel->find($id)) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'El producto no existe.'
            ]);
        }

        try {
            $this->productoModel->delete($id);
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Producto eliminado con éxito.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'No se puede eliminar el producto porque está asociado a otros registros.'
            ]);
        }
    }
}