<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Historial de Facturas<?= $this->endSection() ?>
<?= $this->section('page_title') ?>Historial de Ventas / Facturación<?= $this->endSection() ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0 fw-bold">Facturas Emitidas</h3>
            <a href="<?= base_url('facturas/nueva') ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Nueva Factura
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle w-100" id="tablaVentas">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">N° Factura</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Identificación</th>
                            <th>Vendedor</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($ventas)): ?>
                            <?php foreach($ventas as $v): ?>
                                <tr>
                                    <td class="text-center fw-bold">#<?= str_pad($v['id_venta'], 6, '0', STR_PAD_LEFT) ?></td>
                                    <td><?= esc($v['fecha']) ?></td>
                                    <td><?= esc($v['cliente_nombre']) ?></td>
                                    <td><?= esc($v['identificacion']) ?></td>
                                    <td><?= esc($v['usuario_nombre']) ?></td>
                                    <td class="text-end fw-bold">$<?= number_format($v['total'], 2) ?></td>
                                    <td class="text-center">
                                        <!-- Botón Ver Detalle -->
                                        <button class="btn btn-info btn-sm btn-ver" data-id="<?= $v['id_venta'] ?>" title="Ver Detalle">
                                            <i class="bi bi-eye-fill"></i> Detalle
                                        </button>
                                        
                                        <!-- Botón Editar -->
                                        <a href="<?= base_url('facturas/editar/' . $v['id_venta']) ?>" class="btn btn-warning btn-sm" title="Editar Factura">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </a>

                                        <!-- Botón Imprimir PDF -->
<a
    href="<?= base_url('facturas/imprimir/' . $v['id_venta']) ?>"
    target="_blank"
    class="btn btn-success btn-sm"
    title="Imprimir factura">

    <i class="bi bi-printer-fill"></i> PDF

</a>

                                        <!-- Botón Eliminar -->
                                        <button class="btn btn-danger btn-sm btn-eliminar" data-id="<?= $v['id_venta'] ?>" title="Eliminar Factura">
                                            <i class="bi bi-trash-fill"></i> Eliminar
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle Factura -->
<div class="modal fade" id="modalDetalleVenta" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Detalle de Factura <span id="lbl_factura_num"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Cliente:</strong> <span id="lbl_modal_cliente"></span><br>
                        <strong>Identificación:</strong> <span id="lbl_modal_identificacion"></span>
                    </div>
                    <div class="col-md-6 text-end">
                        <strong>Fecha:</strong> <span id="lbl_modal_fecha"></span><br>
                        <strong>Atendido Por:</strong> <span id="lbl_modal_usuario"></span>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-end">Precio U.</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="tbl_modal_detalles"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#tablaVentas').DataTable({
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
        order: [[0, 'desc']]
    });

    // Ver Detalle
    $(document).on('click', '.btn-ver', function(){
        let id = $(this).data('id');
        $.getJSON('<?= base_url('facturas/ver') ?>/' + id, function(res){
            $('#lbl_factura_num').text('#' + String(res.venta.id_venta).padStart(6, '0'));
            $('#lbl_modal_cliente').text(res.venta.cliente_nombre);
            $('#lbl_modal_identificacion').text(res.venta.identificacion);
            $('#lbl_modal_fecha').text(res.venta.fecha);
            $('#lbl_modal_usuario').text(res.venta.usuario_nombre);

            let html = '';
            res.detalles.forEach(d => {
                html += `<tr>
                    <td>${d.producto_nombre}</td>
                    <td class="text-center">${d.cantidad}</td>
                    <td class="text-end">$${parseFloat(d.precio_unitario).toFixed(2)}</td>
                    <td class="text-end">$${parseFloat(d.subtotal).toFixed(2)}</td>
                </tr>`;
            });
            $('#tbl_modal_detalles').html(html);
            new bootstrap.Modal('#modalDetalleVenta').show();
        });
    });

    // Eliminar Factura
    $(document).on('click', '.btn-eliminar', function(){
        let id = $(this).data('id');

        if (confirm('¿Estás seguro de anular/eliminar la factura #' + String(id).padStart(6, '0') + '? Esta acción devolverá los productos al inventario.')) {
            $.ajax({
                url: '<?= base_url('facturas/eliminar') ?>/' + id,
                type: 'POST',
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        alert(res.message);
                        location.reload();
                    } else {
                        alert('Error: ' + res.message);
                    }
                },
                error: function(xhr) {
                    let err = xhr.responseJSON ? xhr.responseJSON.message : 'Error al procesar la solicitud.';
                    alert('Error: ' + err);
                }
            });
        }
    });
});
</script>
<?= $this->endSection() ?>