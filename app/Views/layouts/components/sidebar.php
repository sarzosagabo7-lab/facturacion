<aside class="app-sidebar shadow-sm">

    <!-- BRAND / LOGO -->
    <div class="sidebar-brand">
        <a href="<?= base_url() ?>" class="brand-link text-decoration-none">
            <span class="brand-text fw-bold">Facturación App</span>
        </a>
    </div>

    <!-- MENÚ SIDEBAR -->
    <div class="sidebar-wrapper">
        <nav class="mt-3">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                <!-- SECCIÓN: PRINCIPAL -->
                <li class="nav-header">Principal</li>

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>"
                       class="nav-link <?= url_is('dashboard') || url_is('/') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-grid-1x2-fill"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- FACTURACIÓN (Desplegable) -->
                <li class="nav-item <?= url_is('facturas*') || url_is('facturacion*') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= url_is('facturas*') || url_is('facturacion*') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-receipt-cutoff"></i>
                        <p>
                            Facturación
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('facturas/nueva') ?>"
                               class="nav-link <?= url_is('facturas/nueva') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-plus-circle-fill"></i>
                                <p>Nueva Factura</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('facturas') ?>"
                               class="nav-link <?= url_is('facturas') && !url_is('facturas/nueva') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-card-heading"></i>
                                <p>Historial</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- SECCIÓN: OPERACIONES -->
                <li class="nav-header">Operaciones</li>

                <!-- Compras -->
                <li class="nav-item">
                    <a href="<?= base_url('compras') ?>"
                       class="nav-link <?= url_is('compras*') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-bag-check-fill"></i>
                        <p>Compras</p>
                    </a>
                </li>

                <?php if (session()->get('rol') === 'administrador'): ?>
                <!-- Clientes (Solo Admin) -->
                <li class="nav-item">
                    <a href="<?= base_url('clientes') ?>"
                       class="nav-link <?= url_is('clientes*') || url_is('cliente*') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>Clientes</p>
                    </a>
                </li>

                <!-- Proveedores (Solo Admin) -->
                <li class="nav-item">
                    <a href="<?= base_url('proveedores') ?>"
                       class="nav-link <?= url_is('proveedores*') || url_is('proveedor*') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-truck-flatbed"></i>
                        <p>Proveedores</p>
                    </a>
                </li>

                <!-- SECCIÓN: INVENTARIO -->
                <li class="nav-header">Inventario</li>

                <!-- Productos -->
                <li class="nav-item">
                    <a href="<?= base_url('productos') ?>"
                       class="nav-link <?= url_is('productos*') || url_is('producto*') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>Productos</p>
                    </a>
                </li>

                <!-- Categorías -->
                <li class="nav-item">
                    <a href="<?= base_url('categorias') ?>"
                       class="nav-link <?= url_is('categorias*') || url_is('categoria*') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-tags-fill"></i>
                        <p>Categorías</p>
                    </a>
                </li>

                <!-- Marcas -->
                <li class="nav-item">
                    <a href="<?= base_url('marcas') ?>"
                       class="nav-link <?= url_is('marcas*') || url_is('marca*') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-bookmark-star-fill"></i>
                        <p>Marcas</p>
                    </a>
                </li>

                <!-- SECCIÓN: SISTEMA -->
                <li class="nav-header">Sistema</li>

                <!-- Usuarios -->
                <li class="nav-item">
                    <a href="<?= base_url('usuarios') ?>"
                       class="nav-link <?= url_is('usuarios*') || url_is('usuario*') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-person-gear"></i>
                        <p>Usuarios</p>
                    </a>
                </li>

                <!-- Reportes -->
                <li class="nav-item">
                    <a href="<?= base_url('reportes') ?>"
                       class="nav-link <?= url_is('reportes*') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-graph-up-arrow"></i>
                        <p>Reportes</p>
                    </a>
                </li>
                <?php endif; ?>

            </ul>
        </nav>
    </div>
</aside>