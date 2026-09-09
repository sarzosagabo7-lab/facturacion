<!DOCTYPE html>
<html lang="es">

<!-- ==========================================
     HEAD
     ========================================== -->
<?= $this->include('layouts/components/head') ?>

<body class="layout-fixed sidebar-expand-lg">

<div class="app-wrapper">

    <!-- ==========================================
         NAVBAR
         ========================================== -->
    <?= $this->include('layouts/components/navbar') ?>

    <!-- ==========================================
         SIDEBAR
         ========================================== -->
    <?= $this->include('layouts/components/sidebar') ?>

    <!-- ==========================================
         CONTENIDO PRINCIPAL
         ========================================== -->
    <main class="app-main">

        <!-- ======================================
             HEADER
             ====================================== -->
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="page-title mb-0">
                            <?= $this->renderSection('page_title') ?>
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================
             CONTENIDO DINÁMICO
             ====================================== -->
        <div class="app-content">
            <div class="container-fluid">
                <?= $this->renderSection('content') ?>
            </div>
        </div>

    </main>

    <!-- ==========================================
         FOOTER
         ========================================== -->
    <?= $this->include('layouts/components/footer') ?>

</div>

<!-- ==========================================
     SCRIPTS
     ========================================== -->
<?= $this->include('layouts/components/scripts') ?>

<!-- ==========================================
     ESTILOS PERSONALIZADOS (EMERALD SLATE THEMING)
     ========================================== -->
<style>
/* ==========================================
       SIDEBAR - DARK CYBER & ELECTRIC CYAN (ULTRA)
       ========================================== */
    .app-sidebar {
        background: rgba(10, 14, 23, 0.94) !important;
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
        box-shadow: 10px 0 35px rgba(0, 0, 0, 0.65);
    }

    /* LOGOTIPO / ENCABEZADO CON EFECTO VIDRIO Y BORDE NEÓN */
    .app-sidebar .sidebar-brand {
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.25) 0%, rgba(3, 105, 161, 0.15) 100%) !important;
        border-bottom: 1px solid rgba(56, 189, 248, 0.25) !important;
        padding: 1.1rem 1.2rem !important;
        backdrop-filter: blur(10px);
    }

    .app-sidebar .sidebar-brand .brand-text {
        color: #ffffff !important;
        font-weight: 800 !important;
        font-size: 1.15rem;
        letter-spacing: 0.8px;
        background: linear-gradient(135deg, #ffffff 30%, #7dd3fc 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(0 2px 8px rgba(56, 189, 248, 0.4));
    }

    /* TÍTULOS DE SECCIÓN (Principal, Operaciones, Inventario...) */
    .app-sidebar .nav-header {
        color: #0284c7 !important; /* Azul eléctrico neón */
        font-weight: 800 !important;
        font-size: 0.66rem !important;
        letter-spacing: 2px !important;
        text-transform: uppercase;
        padding: 1.4rem 1.2rem 0.4rem 1.2rem !important;
        opacity: 0.95;
        text-shadow: 0 0 12px rgba(2, 132, 199, 0.3);
    }

    /* ÍTEMS Y ENLACÉS (Efecto Flotante) */
    .app-sidebar .nav-link {
        color: #94a3b8 !important;
        border-radius: 12px !important;
        margin: 4px 12px !important;
        padding: 0.65rem 0.95rem !important;
        transition: all 0.28s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
        border: 1px solid rgba(255, 255, 255, 0.03);
        background: rgba(255, 255, 255, 0.015);
    }

    .app-sidebar .nav-link .nav-icon {
        color: #475569 !important;
        font-size: 1.15rem;
        margin-right: 0.6rem;
        transition: all 0.25s ease !important;
    }

    /* EFECTO HOVER (Interactividad Fluida) */
    .app-sidebar .nav-link:hover {
        background: rgba(14, 165, 233, 0.12) !important;
        color: #f8fafc !important;
        transform: translateX(6px) scale(1.01);
        border-color: rgba(56, 189, 248, 0.35);
        box-shadow: 0 4px 15px rgba(14, 165, 233, 0.15);
    }

    .app-sidebar .nav-link:hover .nav-icon {
        color: #38bdf8 !important;
        transform: scale(1.2) rotate(-4deg);
        filter: drop-shadow(0 0 6px rgba(56, 189, 248, 0.6));
    }

    /* ENLACE ACTIVO (Página seleccionada) */
    .app-sidebar .nav-link.active {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%) !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        border-radius: 12px !important;
        border: 1px solid rgba(255, 255, 255, 0.25) !important;
        box-shadow: 0 6px 20px rgba(2, 132, 199, 0.45), inset 0 1px 0 rgba(255, 255, 255, 0.3) !important;
        transform: translateX(2px);
    }

    .app-sidebar .nav-link.active .nav-icon {
        color: #ffffff !important;
        transform: scale(1.1);
        filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.8));
    }

    /* SUBMENÚS DESPLEGABLES (Historial, Nueva Factura...) */
    .app-sidebar .nav-treeview {
        background: rgba(0, 0, 0, 0.25) !important;
        border-radius: 12px;
        margin: 4px 12px 8px 12px !important;
        padding: 6px 0 !important;
        border: 1px solid rgba(255, 255, 255, 0.04);
    }

    .app-sidebar .nav-treeview .nav-link {
        margin: 2px 8px !important;
        padding: 0.5rem 0.8rem !important;
        font-size: 0.88rem;
    }
</style>

</body>
</html>