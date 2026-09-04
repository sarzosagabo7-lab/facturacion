<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="<?= base_url() ?>" class="brand-link">
            <span class="brand-text fw-light">Facturación App</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu"
                data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>"
                       class="nav-link <?= url_is('dashboard') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- FACTURACIÓN -->
                <li class="nav-item <?= url_is('facturas*') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= url_is('facturas*') ? 'active' : '' ?>">
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
                                <i class="nav-icon bi bi-file-earmark-plus"></i>
                                <p>Nueva Factura</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('facturas') ?>"
                               class="nav-link <?= url_is('facturas') ? 'active' : '' ?>">
                                <i class="nav-icon bi bi-clock-history"></i>
                                <p>Historial</p>
                            </a>
                        </li>

                    </ul>
                </li>

              <li class="nav-item <?= (url_is('categorias*') || url_is('marcas*')) ? 'menu-open' : '' ?>">
            <a href="#" class="nav-link <?= (url_is('categorias*') || url_is('marcas*')) ? 'active' : '' ?>">
                <i class="nav-icon bi bi-boxes"></i>
                <p>
                    Inventario
                    <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?= base_url('categorias') ?>" class="nav-link <?= url_is('categorias*') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-tags"></i>
                        <p>Categorías</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('marcas') ?>" class="nav-link <?= url_is('marcas*') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-bookmark-star"></i>
                        <p>Marcas</p>
                    </a>
                </li>
            </ul>
        </li>


               

            </ul>
        </nav>
    </div>
</aside>