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
     ESTILOS PERSONALIZADOS
     ========================================== -->

<style>

    /* ==========================================
       FONDO GENERAL
       ========================================== */

    body {

        background:
            radial-gradient(
                circle at 10% 10%,
                rgba(124, 58, 237, 0.20),
                transparent 30%
            ),

            radial-gradient(
                circle at 90% 90%,
                rgba(6, 182, 212, 0.18),
                transparent 30%
            ),

            linear-gradient(
                135deg,
                #0f172a,
                #172554,
                #1e1b4b
            ) !important;

        color: #e2e8f0;

    }


    /* ==========================================
       WRAPPER
       ========================================== */

    .app-wrapper {

        background: transparent !important;

    }


    /* ==========================================
       NAVBAR
       ========================================== */

    .app-header {

        background:
            linear-gradient(
                90deg,
                #0f172a,
                #1e1b4b,
                #172554
            ) !important;

        border-bottom:
            1px solid rgba(139, 92, 246, 0.30) !important;

        box-shadow:
            0 4px 20px rgba(0, 0, 0, 0.25);

    }


    .app-header .nav-link {

        color: #cbd5e1 !important;

        transition: 0.25s;

    }


    .app-header .nav-link:hover {

        color: #67e8f9 !important;

    }


    /* ==========================================
       SIDEBAR
       ========================================== */

    .app-sidebar {

        background:
            linear-gradient(
                180deg,
                #0f172a 0%,
                #1e1b4b 50%,
                #172554 100%
            ) !important;

        border-right:
            1px solid rgba(139, 92, 246, 0.25);

        box-shadow:
            5px 0 25px rgba(0,0,0,0.25);

    }


    /* ==========================================
       LOGO DEL SIDEBAR
       ========================================== */

    .app-sidebar .sidebar-brand {

        background:
            linear-gradient(
                90deg,
                #312e81,
                #4c1d95,
                #0369a1
            ) !important;

        border-bottom:
            1px solid rgba(255,255,255,0.12);

    }


    .app-sidebar .sidebar-brand .brand-text {

        color: white !important;

        font-weight: 800;

    }


    /* ==========================================
       OPCIONES DEL SIDEBAR
       ========================================== */

    .app-sidebar .nav-link {

        color: #cbd5e1 !important;

        border-radius: 10px;

        margin: 4px 10px;

        transition: all 0.25s ease;

    }


    /* ICONOS */

    .app-sidebar .nav-link .nav-icon {

        color: #94a3b8 !important;

        transition: 0.25s;

    }


    /* ==========================================
       HOVER
       ========================================== */

    .app-sidebar .nav-link:hover {

        background:
            linear-gradient(
                90deg,
                rgba(124,58,237,0.35),
                rgba(37,99,235,0.25)
            ) !important;

        color: white !important;

        transform: translateX(3px);

    }


    .app-sidebar .nav-link:hover .nav-icon {

        color: #67e8f9 !important;

        filter:
            drop-shadow(
                0 0 6px
                rgba(103,232,249,0.6)
            );

    }


    /* ==========================================
       OPCIÓN ACTIVA
       ========================================== */

    .app-sidebar .nav-link.active {

        background:
            linear-gradient(
                90deg,
                #7c3aed,
                #4f46e5,
                #2563eb
            ) !important;

        color: white !important;

        font-weight: 700;

        box-shadow:
            0 6px 18px rgba(79,70,229,0.35);

    }


    .app-sidebar .nav-link.active .nav-icon {

        color: white !important;

    }


    /* ==========================================
       TÍTULOS DEL MENÚ
       ========================================== */

    .app-sidebar .nav-header {

        color: #818cf8 !important;

        font-weight: 800;

        font-size: 11px;

        letter-spacing: 1px;

    }


    /* ==========================================
       CONTENIDO PRINCIPAL
       ========================================== */

    .app-main {

        background: transparent !important;

    }


    .app-content-header {

        background: transparent !important;

    }


    /* ==========================================
       TÍTULO
       ========================================== */

    .page-title {

        color: #f8fafc !important;

        font-weight: 800;

        letter-spacing: 0.2px;

    }


    /* ==========================================
       CONTENIDO
       ========================================== */

    .app-content {

        background: transparent !important;

    }


    /* ==========================================
       FOOTER
       ========================================== */

    .app-footer {

        background:
            rgba(15,23,42,0.90) !important;

        border-top:
            1px solid rgba(139,92,246,0.20) !important;

        color: #94a3b8 !important;

    }


    .app-footer strong {

        color: #c4b5fd;

    }


    /* ==========================================
       SCROLLBAR
       ========================================== */

    ::-webkit-scrollbar {

        width: 7px;

    }


    ::-webkit-scrollbar-track {

        background: #0f172a;

    }


    ::-webkit-scrollbar-thumb {

        background:
            linear-gradient(
                #7c3aed,
                #06b6d4
            );

        border-radius: 10px;

    }


    ::-webkit-scrollbar-thumb:hover {

        background:
            linear-gradient(
                #8b5cf6,
                #22d3ee
            );

    }

</style>


</body>
</html>

