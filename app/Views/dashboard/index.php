<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    /* ==========================================
       DASHBOARD GLASSMORPHISM & NEON THEME
       ========================================== */
    .dashboard-header-title {
        font-weight: 800;
        letter-spacing: -0.5px;
        background: linear-gradient(135deg, #ffffff 0%, #cbd5e1 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Tarjetas de Métricas (KPIs) */
    .kpi-card {
        background: rgba(15, 23, 42, 0.65) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 16px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px -5px rgba(0, 0, 0, 0.5);
        border-color: rgba(56, 189, 248, 0.3) !important;
    }

    /* Resplandor lateral en las tarjetas */
    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 4px; height: 100%;
        border-radius: 16px 0 0 16px;
    }
    .kpi-ventas::before { background: #06b6d4; box-shadow: 0 0 12px #06b6d4; }
    .kpi-ingresos::before { background: #10b981; box-shadow: 0 0 12px #10b981; }
    .kpi-clientes::before { background: #f59e0b; box-shadow: 0 0 12px #f59e0b; }
    .kpi-stock::before { background: #ef4444; box-shadow: 0 0 12px #ef4444; }

    .kpi-title {
        color: #94a3b8 !important;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .kpi-num {
        color: #f8fafc !important;
        font-size: 1.85rem;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    /* Tarjetas de Gráficos y Tablas */
    .dash-card {
        background: rgba(15, 23, 42, 0.65) !important;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
    }

    .dash-card .card-header {
        background: transparent !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
        padding: 1.2rem 1.5rem 0.8rem 1.5rem;
    }

    .dash-card .card-title {
        color: #f1f5f9 !important;
        font-size: 1.05rem;
        font-weight: 700;
        letter-spacing: -0.2px;
    }

    /* Tablas Estilizadas */
    .dash-table {
        color: #cbd5e1 !important;
        margin-bottom: 0;
    }

    .dash-table thead th {
        background: rgba(0, 0, 0, 0.2) !important;
        color: #38bdf8 !important;
        font-size: 0.72rem;
        letter-spacing: 1px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        padding: 0.85rem 1.2rem;
    }

    .dash-table tbody td {
        border-bottom: 1px solid rgba(255, 255, 255, 0.04) !important;
        padding: 0.85rem 1.2rem;
        vertical-align: middle;
    }

    .dash-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.03) !important;
    }

    /* Listas de Alertas */
    .dash-list .list-group-item {
        background: transparent !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04) !important;
        color: #e2e8f0 !important;
        padding: 0.9rem 1.2rem;
    }

    .dash-list .list-group-item:hover {
        background: rgba(255, 255, 255, 0.02) !important;
    }

    /* Badges / Etiquetas */
    .badge-cyber-cyan {
        background: rgba(6, 182, 212, 0.15) !important;
        color: #38bdf8 !important;
        border: 1px solid rgba(56, 189, 248, 0.3);
    }

    .badge-cyber-warning {
        background: rgba(245, 158, 11, 0.15) !important;
        color: #fbbf24 !important;
        border: 1px solid rgba(251, 191, 36, 0.3);
    }
</style>

<div class="content-header pt-3 pb-2">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div>
            <h2 class="dashboard-header-title mb-1">Decisiones con datos claros</h2>
            <p class="text-muted small mb-0" style="color: #94a3b8 !important;">Revisa el pulso del negocio y detecta lo que necesita atención hoy.</p>
        </div>
        <div>
            <span class="badge badge-cyber-cyan px-3 py-2 rounded-pill font-weight-normal">
                <i class="far fa-calendar-alt mr-1"></i> <?= date('d/m/Y') ?>
            </span>
        </div>
    </div>
</div>

<section class="content mt-3">
    <div class="container-fluid">
        <!-- TARJETAS DE MÉTRICAS (KPIs) -->
        <div class="row">
            <div class="col-lg-3 col-6 mb-4">
                <div class="kpi-card kpi-ventas p-3 shadow-sm">
                    <span class="kpi-title font-weight-bold d-block">Ventas de hoy</span>
                    <div class="d-flex align-items-baseline justify-content-between mt-2">
                        <h3 id="kpi-ventas" class="kpi-num mb-0">0</h3>
                        <i class="bi bi-cart-check text-info fs-4" style="opacity: 0.6;"></i>
                    </div>
                    <small class="text-muted d-block mt-1" style="color: #64748b !important;">transacciones registradas</small>
                </div>
            </div>
            <div class="col-lg-3 col-6 mb-4">
                <div class="kpi-card kpi-ingresos p-3 shadow-sm">
                    <span class="kpi-title font-weight-bold d-block">Ingresos del mes</span>
                    <div class="d-flex align-items-baseline justify-content-between mt-2">
                        <h3 id="kpi-ingresos" class="kpi-num mb-0">$ 0.00</h3>
                        <i class="bi bi-currency-dollar text-success fs-4" style="opacity: 0.6;"></i>
                    </div>
                    <small class="text-muted d-block mt-1" style="color: #64748b !important;">acumulado mensual</small>
                </div>
            </div>
            <div class="col-lg-3 col-6 mb-4">
                <div class="kpi-card kpi-clientes p-3 shadow-sm">
                    <span class="kpi-title font-weight-bold d-block">Clientes registrados</span>
                    <div class="d-flex align-items-baseline justify-content-between mt-2">
                        <h3 id="kpi-clientes" class="kpi-num mb-0">0</h3>
                        <i class="bi bi-people text-warning fs-4" style="opacity: 0.6;"></i>
                    </div>
                    <small class="text-muted d-block mt-1" style="color: #64748b !important;">base de clientes</small>
                </div>
            </div>
            <div class="col-lg-3 col-6 mb-4">
                <div class="kpi-card kpi-stock p-3 shadow-sm">
                    <span class="kpi-title font-weight-bold d-block">Stock por revisar</span>
                    <div class="d-flex align-items-baseline justify-content-between mt-2">
                        <h3 id="kpi-stock" class="kpi-num mb-0">0</h3>
                        <i class="bi bi-exclamation-triangle text-danger fs-4" style="opacity: 0.6;"></i>
                    </div>
                    <small class="text-muted d-block mt-1" style="color: #64748b !important;">5 o menos unidades</small>
                </div>
            </div>
        </div>

        <!-- GRÁFICOS -->
        <div class="row">
            <div class="col-md-8 mb-4">
                <div class="card dash-card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="bi bi-graph-up-arrow text-info mr-2"></i>Actividad de los últimos 7 días</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="chartActividad" style="min-height: 260px; height: 260px; max-height: 260px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card dash-card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="bi bi-bar-chart-line text-info mr-2"></i>Ingresos mensuales</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="chartIngresos" style="min-height: 260px; height: 260px; max-height: 260px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABLAS Y ALERTAS -->
        <div class="row">
            <div class="col-md-7 mb-4">
                <div class="card dash-card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="bi bi-trophy text-warning mr-2"></i>Productos más vendidos</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table dash-table">
                                <thead>
                                    <tr>
                                        <th>PRODUCTO</th>
                                        <th class="text-center">UNIDADES</th>
                                        <th class="text-right">INGRESOS</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla-top-productos">
                                    <tr><td colspan="3" class="text-center text-muted py-4">Cargando datos...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 mb-4">
                <div class="card dash-card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="bi bi-box-seam text-danger mr-2"></i>Alertas de inventario</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush dash-list" id="lista-alertas-stock">
                            <li class="list-group-item text-center text-muted py-4">Cargando datos...</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ChartJS & AJAX Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const textColor = '#94a3b8';
    const gridColor = 'rgba(255, 255, 255, 0.05)';

    Chart.defaults.color = textColor;
    Chart.defaults.font.family = "system-ui, -apple-system, sans-serif";

    let ctxActividad = document.getElementById('chartActividad').getContext('2d');
    let ctxIngresos = document.getElementById('chartIngresos').getContext('2d');

    // Gradiente para la línea de ingresos
    let gradientIngresos = ctxActividad.createLinearGradient(0, 0, 0, 260);
    gradientIngresos.addColorStop(0, 'rgba(16, 185, 129, 0.35)');
    gradientIngresos.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

    let chartActividad = new Chart(ctxActividad, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                { 
                    label: 'Ventas', 
                    data: [], 
                    borderColor: '#06b6d4', 
                    borderWidth: 3,
                    pointBackgroundColor: '#06b6d4',
                    tension: 0.4, 
                    fill: false 
                },
                { 
                    label: 'Ingresos ($)', 
                    data: [], 
                    borderColor: '#10b981', 
                    borderWidth: 3,
                    pointBackgroundColor: '#10b981',
                    tension: 0.4, 
                    fill: true, 
                    backgroundColor: gradientIngresos 
                }
            ]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            plugins: { legend: { labels: { color: '#cbd5e1' } } },
            scales: {
                x: { ticks: { color: textColor }, grid: { color: gridColor } },
                y: { ticks: { color: textColor }, grid: { color: gridColor } }
            }
        }
    });

    let chartIngresos = new Chart(ctxIngresos, {
        type: 'bar',
        data: {
            labels: ['Mes Actual'],
            datasets: [{ 
                label: 'Ingresos ($)', 
                data: [], 
                backgroundColor: '#06b6d4',
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            plugins: { legend: { labels: { color: '#cbd5e1' } } },
            scales: {
                x: { ticks: { color: textColor }, grid: { display: false } },
                y: { ticks: { color: textColor }, grid: { color: gridColor } }
            }
        }
    });

    function cargarDashboard() {
        fetch('<?= base_url('dashboard/getData') ?>', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            // Actualizar KPIs
            document.getElementById('kpi-ventas').innerText = data.kpis.ventas_hoy;
            document.getElementById('kpi-ingresos').innerText = '$ ' + data.kpis.ingresos_mes;
            document.getElementById('kpi-clientes').innerText = data.kpis.total_clientes;
            document.getElementById('kpi-stock').innerText = data.kpis.stock_bajo;

            // Actualizar Gráfico 7 Días
            chartActividad.data.labels = data.grafico_7dias.map(item => item.dia);
            chartActividad.data.datasets[0].data = data.grafico_7dias.map(item => item.total_ventas);
            chartActividad.data.datasets[1].data = data.grafico_7dias.map(item => item.total_ingresos);
            chartActividad.update();

            // Actualizar Gráfico Ingresos
            let ingresosMesLimpio = parseFloat(String(data.kpis.ingresos_mes).replace(/,/g, '')) || 0;
            chartIngresos.data.datasets[0].data = [ingresosMesLimpio];
            chartIngresos.update();

            // Actualizar Tabla Top Productos
            let htmlProductos = '';
            if (data.top_productos && data.top_productos.length > 0) {
                data.top_productos.forEach(p => {
                    let totalIngresos = parseFloat(p.ingresos) || 0;
                    htmlProductos += `<tr>
                        <td class="font-weight-bold text-white">${p.nombre}</td>
                        <td class="text-center"><span class="badge badge-cyber-cyan px-2 py-1">${p.unidades}</span></td>
                        <td class="text-right font-weight-bold text-success">$ ${totalIngresos.toFixed(2)}</td>
                    </tr>`;
                });
            } else {
                htmlProductos = '<tr><td colspan="3" class="text-center text-muted py-4">Sin ventas registradas</td></tr>';
            }
            document.getElementById('tabla-top-productos').innerHTML = htmlProductos;

            // Actualizar Lista Alertas Inventario
            let htmlAlertas = '';
            if (data.alertas_stock && data.alertas_stock.length > 0) {
                data.alertas_stock.forEach(a => {
                    let precio = parseFloat(a.precio_venta) || 0;
                    htmlAlertas += `<li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="text-white">${a.nombre}</strong><br>
                            <small style="color: #94a3b8 !important;">Precio: $ ${precio.toFixed(2)}</small>
                        </div>
                        <span class="badge badge-cyber-warning px-2 py-1">${a.stock} unid.</span>
                    </li>`;
                });
            } else {
                htmlAlertas = '<li class="list-group-item text-center text-muted py-4">Stock adecuado en todos los productos</li>';
            }
            document.getElementById('lista-alertas-stock').innerHTML = htmlAlertas;
        })
        .catch(error => console.error('Error al cargar el dashboard:', error));
    }

    cargarDashboard();
    setInterval(cargarDashboard, 10000);
});
</script>
<?= $this->endSection() ?>