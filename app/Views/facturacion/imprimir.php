<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Factura #<?= str_pad($venta['id_venta'], 6, '0', STR_PAD_LEFT) ?>
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f1f3f5;
            margin: 0;
            padding: 30px;
            color: #212529;
        }

        /* CONTENEDOR PRINCIPAL */

        .factura {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.10);
        }

        /* BOTONES */

        .acciones {
            max-width: 900px;
            margin: 0 auto 20px auto;
            text-align: right;
        }

        .btn {
            border: none;
            padding: 11px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 5px;
        }

        .btn-imprimir {
            background: #198754;
            color: white;
        }

        .btn-volver {
            background: #6c757d;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* ENCABEZADO */

        .encabezado {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;

            border-bottom: 3px solid #0d6efd;

            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .empresa h1 {
            margin: 0;
            color: #0d6efd;
            font-size: 30px;
        }

        .empresa p {
            margin: 6px 0;
            color: #6c757d;
        }

        .numero-factura {
            text-align: right;
        }

        .numero-factura h2 {
            margin: 0 0 10px 0;
            color: #212529;
        }

        .numero-factura p {
            margin: 5px 0;
        }

        /* INFORMACIÓN */

        .datos {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .datos-box {
            width: 50%;

            border: 1px solid #dee2e6;

            border-radius: 8px;

            padding: 18px;
        }

        .datos-box h3 {
            margin-top: 0;

            color: #0d6efd;

            font-size: 17px;

            border-bottom: 1px solid #dee2e6;

            padding-bottom: 8px;
        }

        .datos-box p {
            margin: 8px 0;

            font-size: 14px;
        }

        /* TABLA */

        .titulo-detalle {
            color: #212529;

            margin-bottom: 10px;
        }

        table {
            width: 100%;

            border-collapse: collapse;

            margin-top: 10px;
        }

        table th {
            background: #0d6efd;

            color: white;

            padding: 12px;

            font-size: 14px;

            text-align: left;
        }

        table td {
            padding: 11px;

            border-bottom: 1px solid #dee2e6;

            font-size: 14px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* TOTAL */

        .totales {
            width: 350px;

            margin-left: auto;

            margin-top: 25px;
        }

        .total-final {
            display: flex;

            justify-content: space-between;

            background: #0d6efd;

            color: white;

            padding: 15px;

            border-radius: 6px;

            font-size: 20px;

            font-weight: bold;
        }

        /* PIE */

        .mensaje {
            text-align: center;

            margin-top: 40px;

            color: #6c757d;

            font-size: 13px;
        }

        /* IMPRESIÓN */

        @media print {

            body {
                background: white;

                padding: 0;
            }

            .acciones {
                display: none;
            }

            .factura {
                max-width: 100%;

                box-shadow: none;

                border-radius: 0;

                padding: 10px;
            }

            @page {
                size: A4;

                margin: 15mm;
            }
        }

    </style>

</head>

<body>


    <!-- =====================================================
         BOTONES
    ====================================================== -->

    <div class="acciones">

        <button
            class="btn btn-imprimir"
            onclick="window.print()">

            🖨️ Imprimir / Guardar PDF

        </button>


        <button
            class="btn btn-volver"
            onclick="window.history.back()">

            ← Volver

        </button>

    </div>


    <!-- =====================================================
         FACTURA
    ====================================================== -->

    <div class="factura">


        <!-- =================================================
             ENCABEZADO
        ================================================== -->

        <div class="encabezado">


            <div class="empresa">

                <h1>
                    FACTURACIÓN APP
                </h1>

                <p>
                    Sistema de Facturación
                </p>

                <p>
                    Comprobante de venta
                </p>

            </div>


            <div class="numero-factura">

                <h2>
                    FACTURA
                </h2>

                <p>

                    <strong>N°:</strong>

                    #<?= str_pad(
                        $venta['id_venta'],
                        6,
                        '0',
                        STR_PAD_LEFT
                    ) ?>

                </p>


                <p>

                    <strong>Fecha:</strong>

                    <?= esc($venta['fecha']) ?>

                </p>

            </div>

        </div>


        <!-- =================================================
             DATOS DEL CLIENTE Y VENTA
        ================================================== -->

        <div class="datos">


            <!-- CLIENTE -->

            <div class="datos-box">

                <h3>
                    Datos del Cliente
                </h3>


                <p>

                    <strong>Nombre:</strong>

                    <?= esc($venta['cliente_nombre']) ?>

                </p>


                <p>

                    <strong>Identificación:</strong>

                    <?= esc($venta['identificacion']) ?>

                </p>


                <p>

                    <strong>Teléfono:</strong>

                    <?= esc($venta['telefono'] ?? 'N/A') ?>

                </p>


                <p>

                    <strong>Correo:</strong>

                    <?= esc($venta['correo'] ?? 'N/A') ?>

                </p>

            </div>


            <!-- VENTA -->

            <div class="datos-box">

                <h3>
                    Información de Venta
                </h3>


                <p>

                    <strong>Vendedor:</strong>

                    <?= esc($venta['usuario_nombre']) ?>

                </p>


                <p>

                    <strong>N° Factura:</strong>

                    #<?= str_pad(
                        $venta['id_venta'],
                        6,
                        '0',
                        STR_PAD_LEFT
                    ) ?>

                </p>


                <p>

                    <strong>Fecha:</strong>

                    <?= esc($venta['fecha']) ?>

                </p>

            </div>

        </div>


        <!-- =================================================
             DETALLE DE PRODUCTOS
        ================================================== -->

        <h3 class="titulo-detalle">
            Detalle de productos
        </h3>


        <table>

            <thead>

                <tr>

                    <th>
                        Producto
                    </th>

                    <th class="text-center">
                        Cantidad
                    </th>

                    <th class="text-right">
                        Precio Unitario
                    </th>

                    <th class="text-right">
                        Subtotal
                    </th>

                </tr>

            </thead>


            <tbody>


                <?php if (!empty($detalles)): ?>


                    <?php foreach ($detalles as $detalle): ?>

                        <tr>


                            <td>

                                <?= esc(
                                    $detalle['producto_nombre']
                                ) ?>

                            </td>


                            <td class="text-center">

                                <?= esc(
                                    $detalle['cantidad']
                                ) ?>

                            </td>


                            <td class="text-right">

                                $<?= number_format(
                                    $detalle['precio_unitario'],
                                    2
                                ) ?>

                            </td>


                            <td class="text-right">

                                $<?= number_format(
                                    $detalle['subtotal'],
                                    2
                                ) ?>

                            </td>


                        </tr>

                    <?php endforeach; ?>


                <?php else: ?>


                    <tr>

                        <td
                            colspan="4"
                            class="text-center"
                        >

                            No existen productos registrados.

                        </td>

                    </tr>


                <?php endif; ?>


            </tbody>

        </table>


        <!-- =================================================
             TOTAL
        ================================================== -->

        <div class="totales">

            <div class="total-final">

                <span>
                    TOTAL
                </span>

                <span>

                    $<?= number_format(
                        $venta['total'],
                        2
                    ) ?>

                </span>

            </div>

        </div>


        <!-- =================================================
             PIE DE FACTURA
        ================================================== -->

        <div class="mensaje">

            <p>
                Gracias por su compra.
            </p>

            <p>
                Documento generado por Facturación App
            </p>

        </div>


    </div>


</body>

</html>