<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('dashboard/index');
    }

    public function getData()
    {
        $db = \Config\Database::connect();

        // 1. Métricas rápidas (KPIs)
        $ventasHoy = $db->query("SELECT COUNT(*) as total FROM venta WHERE DATE(fecha) = CURDATE()")->getRow()->total ?? 0;
        $ingresosMes = $db->query("SELECT SUM(total) as total FROM venta WHERE MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE())")->getRow()->total ?? 0.00;
        $totalClientes = $db->query("SELECT COUNT(*) as total FROM cliente")->getRow()->total ?? 0;
        $stockBajo = $db->query("SELECT COUNT(*) as total FROM producto WHERE stock <= 5")->getRow()->total ?? 0;

        // 2. Ventas e Ingresos de los últimos 7 días
        $ultimos7Dias = $db->query("
            SELECT 
                DATE_FORMAT(f.fecha, '%d/%m') as dia,
                COUNT(v.id_venta) as total_ventas,
                IFNULL(SUM(v.total), 0) as total_ingresos
            FROM (
                SELECT CURDATE() - INTERVAL (a.a + (10 * b.a)) DAY as fecha
                FROM (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6) AS a
                CROSS JOIN (SELECT 0 AS a) AS b
            ) f
            LEFT JOIN venta v ON DATE(v.fecha) = f.fecha
            GROUP BY f.fecha
            ORDER BY f.fecha ASC
            LIMIT 7
        ")->getResultArray();

        // 3. Productos más vendidos
        $topProductos = $db->query("
            SELECT p.nombre, SUM(dv.cantidad) as unidades, SUM(dv.subtotal) as ingresos
            FROM detalle_venta dv
            INNER JOIN producto p ON p.id_producto = dv.id_producto
            GROUP BY dv.id_producto
            ORDER BY unidades DESC
            LIMIT 5
        ")->getResultArray();

        // 4. Alertas de Inventario
        $alertasStock = $db->query("
            SELECT nombre, precio_venta, stock 
            FROM producto 
            WHERE stock <= 5 
            ORDER BY stock ASC 
            LIMIT 5
        ")->getResultArray();

        return $this->response->setJSON([
            'kpis' => [
                'ventas_hoy' => $ventasHoy,
                'ingresos_mes' => number_format($ingresosMes, 2),
                'total_clientes' => $totalClientes,
                'stock_bajo' => $stockBajo
            ],
            'grafico_7dias' => $ultimos7Dias,
            'top_productos' => $topProductos,
            'alertas_stock' => $alertasStock
        ]);
    }
}