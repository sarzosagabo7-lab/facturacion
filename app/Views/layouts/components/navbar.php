<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <!-- Start Navbar Links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="<?= base_url() ?>" class="nav-link">Inicio</a>
            </li>
        </ul>

        <!-- Right Navbar Links -->
        <ul class="navbar-nav ms-auto">
            <!-- Variables Dinámicas del Usuario -->
            <?php 
                $rol = session('rol') ?? 'administrador';
                $esAdmin = ($rol === 'administrador');
            ?>

            <!-- User Menu Dropdown -->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-5 <?= $esAdmin ? 'text-primary' : 'text-info' ?>"></i>
                    <span class="d-none d-md-inline fw-semibold text-secondary">
                        <?= session('nombre') ?? session('name') ?? 'Usuario' ?>
                    </span>
                </a>
                
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow-sm border-0 mt-2">
                    <!-- Header con información del usuario -->
                    <li class="user-header bg-body-tertiary p-3 text-center border-bottom">
                        <div class="mb-2">
                            <i class="bi bi-person-circle text-secondary display-6"></i>
                        </div>
                        <p class="mb-0 fw-bold">
                            <?= session('nombre') ?? session('name') ?? 'Usuario del Sistema' ?>
                        </p>
                        <small class="text-muted d-block mb-1">
                            @<?= session('usuario') ?? session('username') ?? 'usuario' ?>
                        </small>

                        <!-- Badge Dinámico según Rol -->
                        <?php if ($esAdmin): ?>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1">
                                Administrador
                            </span>
                        <?php else: ?>
                            <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-1">
                                Encargado
                            </span>
                        <?php endif; ?>
                    </li>

                    <!-- Opciones rápidas de usuario -->
                    <li>
                        <a href="<?= base_url('perfil') ?>" class="dropdown-item py-2 d-flex align-items-center">
                            <i class="bi bi-person me-2 text-muted fs-6"></i> Mi Perfil
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('configuracion') ?>" class="dropdown-item py-2 d-flex align-items-center">
                            <i class="bi bi-gear me-2 text-muted fs-6"></i> Configuración
                        </a>
                    </li>
                    
                    <li><hr class="dropdown-divider my-1"></li>

                    <!-- Footer: Cerrar Sesión -->
                    <li class="user-footer p-2 text-end">
                        <a href="<?= base_url('logout') ?>" class="btn btn-sm btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Cerrar Sesión</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>