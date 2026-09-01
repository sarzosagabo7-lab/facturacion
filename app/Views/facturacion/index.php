```php
<?= $this->extend('layouts/main') ?>

<!-- Título de la pestaña -->
<?= $this->section('title') ?>
Listado de Facturas
<?= $this->endSection() ?>

<!-- Título principal -->
<?= $this->section('page_title') ?>
Gestión de Facturas
<?= $this->endSection() ?>

<!-- Contenido Principal -->
<?= $this->section('content') ?>

<style>

    /* ==========================================
       FONDO DE LA INTERFAZ
       ========================================== */

    body {
        background:
            radial-gradient(
                circle at top left,
                rgba(124, 58, 237, 0.18),
                transparent 35%
            ),
            radial-gradient(
                circle at bottom right,
                rgba(6, 182, 212, 0.18),
                transparent 35%
            ),
            linear-gradient(
                135deg,
                #0f172a,
                #172554,
                #1e1b4b
            ) !important;

        min-height: 100vh;
    }


    /* ==========================================
       CONTENEDOR DEL CONTENIDO
       ========================================== */

    .content-wrapper {
        background: transparent !important;
    }


    .content {
        background: transparent !important;
    }


    /* ==========================================
       TARJETA DE FACTURAS
       ========================================== */

    .card-vibrant {

        background:
            linear-gradient(
                135deg,
                rgba(30, 41, 59, 0.96),
                rgba(49, 46, 129, 0.96)
            ) !important;

        border: 1px solid rgba(129, 140, 248, 0.35) !important;

        border-radius: 20px !important;

        box-shadow:
            0 15px 40px rgba(0, 0, 0, 0.30),
            0 0 25px rgba(99, 102, 241, 0.10);

        overflow: hidden;

        transition: all 0.3s ease;
    }


    .card-vibrant:hover {

        border-color: rgba(129, 140, 248, 0.65) !important;

        box-shadow:
            0 20px 45px rgba(0, 0, 0, 0.40),
            0 0 30px rgba(99, 102, 241, 0.20);

        transform: translateY(-2px);
    }


    /* ==========================================
       ENCABEZADO DE LA TARJETA
       ========================================== */

    .card-vibrant .card-header {

        background:
            linear-gradient(
                90deg,
                #4c1d95,
                #4338ca,
                #0369a1
            ) !important;

        border-bottom: 1px solid rgba(255,255,255,0.15);

        padding: 18px 22px;
    }


    /* ==========================================
       TÍTULO
       ========================================== */

    .card-vibrant .card-title {

        color: #ffffff !important;

        font-weight: 800;

        font-size: 1.2rem;

        margin: 0;

        display: flex;

        align-items: center;

        gap: 10px;
    }


    /* ==========================================
       ICONO
       ========================================== */

    .card-vibrant .card-title i {

        color: #67e8f9;

        font-size: 1.4rem;

        background: rgba(255,255,255,0.12);

        padding: 8px;

        border-radius: 10px;

        filter:
            drop-shadow(
                0 0 7px
                rgba(103,232,249,0.55)
            );
    }


    /* ==========================================
       CUERPO
       ========================================== */

    .card-vibrant .card-body {

        background:
            linear-gradient(
                135deg,
                rgba(15, 23, 42, 0.75),
                rgba(30, 41, 59, 0.65)
            );

        padding: 28px 24px;

        color: #e2e8f0;

        font-size: 16px;
    }


    /* ==========================================
       TEXTO
       ========================================== */

    .card-vibrant .card-body p {

        color: #cbd5e1;

        margin: 0;
    }


    /* ==========================================
       LÍNEA MULTICOLOR SUPERIOR
       ========================================== */

    .card-vibrant::before {

        content: "";

        display: block;

        height: 4px;

        background:
            linear-gradient(
                90deg,
                #06b6d4,
                #3b82f6,
                #6366f1,
                #8b5cf6,
                #ec4899
            );
    }


    /* ==========================================
       BREADCRUMB / ENCABEZADO DE ADMINLTE
       ========================================== */

    .content-header {

        color: white;
    }


    .content-header h1 {

        color: #f8fafc !important;

        font-weight: 800;
    }


    .breadcrumb {

        background: transparent !important;
    }


    .breadcrumb-item,
    .breadcrumb-item a {

        color: #94a3b8 !important;
    }


    .breadcrumb-item.active {

        color: #c4b5fd !important;
    }

</style>


<!-- ==========================================
     TARJETA EXISTENTE
     ========================================== -->

<div class="card card-vibrant">

    <div class="card-header">

        <h3 class="card-title">

            <i class="bi bi-file-earmark-text-fill"></i>

            Facturas Registradas

        </h3>

    </div>


    <div class="card-body">

        <p>
            Aquí irá la tabla o el formulario de tu módulo de facturación.
        </p>

    </div>

</div>


<?= $this->endSection() ?>
```


