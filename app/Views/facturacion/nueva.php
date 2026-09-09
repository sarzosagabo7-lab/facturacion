<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Nueva Factura<?= $this->endSection() ?>
<?= $this->section('page_title') ?>Crear Nueva Factura de Venta<?= $this->endSection() ?>

<?= $this->section('content') ?>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <div class="row">
        <!-- SECCIÓN CLIENTE Y CONFIGURACIÓN -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title m-0"><i class="bi bi-person-fill me-2"></i>Datos del Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 position-relative">
                        <label class="form-label fw-bold">Buscar Cliente (Cédula/Nombre)</label>
                        <input type="text" id="buscar_cliente" class="form-control" placeholder="Escriba para buscar...">
                        <div id="res_cliente" class="list-group position-absolute w-100 shadow-sm" style="z-index: 1000;"></div>
                    </div>
                    <hr>
                    <input type="hidden" id="id_cliente" name="id_cliente">
                    <div class="mb-2"><strong>Identificación:</strong> <span id="lbl_identificacion">-</span></div>
                    <div class="mb-2"><strong>Nombre:</strong> <span id="lbl_nombre">-</span></div>
                    <div class="mb-2"><strong>Teléfono:</strong> <span id="lbl_telefono">-</span></div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title m-0"><i class="bi bi-calculator-fill me-2"></i>Resumen de Factura</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span class="fw-bold">$<span id="txt_subtotal">0.00</span></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>IVA (15%):</span>
                        <span class="fw-bold">$<span id="txt_iva">0.00</span></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3 fs-4 text-primary">
                        <strong>TOTAL:</strong>
                        <strong>$<span id="txt_total">0.00</span></strong>
                    </div>
                    <button type="button" class="btn btn-success w-100 btn-lg" id="btnFinalizarVenta">
                        <i class="bi bi-check-circle-fill me-1"></i> Concretar Venta
                    </button>
                </div>
            </div>
        </div>

        <!-- SECCIÓN BÚSQUEDA PRODUCTOS Y TABLA DETALLE -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title m-0"><i class="bi bi-box-seam me-2"></i>Agregar Productos</h5>
                </div>
                <div class="card-body">
                    <div class="position-relative">
                        <input type="text" id="buscar_producto" class="form-control form-control-lg" placeholder="Escanee código de barras o busque producto...">
                        <div id="res_producto" class="list-group position-absolute w-100 shadow-sm" style="z-index: 1000;"></div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title m-0">Detalle de la Venta</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle m-0" id="tablaDetalle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Producto</th>
                                    <th style="width: 100px;" class="text-center">Stock</th>
                                    <th style="width: 120px;" class="text-center">Precio</th>
                                    <th style="width: 130px;" class="text-center">Cantidad</th>
                                    <th style="width: 120px;" class="text-end">Subtotal</th>
                                    <th style="width: 50px;" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dinámico vía JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    let carrito = [];
    const IVA_PERCENT = 0.15; // Ajustar según norma local

    // Búsqueda de Clientes vía AJAX
    $('#buscar_cliente').on('keyup', function () {
        let term = $(this).val();
        if (term.length >= 2) {
            $.getJSON('<?= base_url('facturas/buscar-cliente') ?>', { term: term }, function (data) {
                let html = '';
                data.forEach(c => {
                    html += `<a href="#" class="list-group-item list-group-item-action item-cliente" 
                                data-id="${c.id_cliente}" data-nombre="${c.nombre}" data-identificacion="${c.identificacion}" data-telefono="${c.telefono || '-'}">
                                ${c.identificacion} - ${c.nombre}
                             </a>`;
                });
                $('#res_cliente').html(html).show();
            });
        } else {
            $('#res_cliente').hide();
        }
    });

    $(document).on('click', '.item-cliente', function (e) {
        e.preventDefault();
        $('#id_cliente').val($(this).data('id'));
        $('#lbl_identificacion').text($(this).data('identificacion'));
        $('#lbl_nombre').text($(this).data('nombre'));
        $('#lbl_telefono').text($(this).data('telefono'));
        $('#res_cliente').hide();
        $('#buscar_cliente').val('');
    });

    // Búsqueda de Productos vía AJAX
    $('#buscar_producto').on('keyup', function () {
        let term = $(this).val();
        if (term.length >= 2) {
            $.getJSON('<?= base_url('facturas/buscar-producto') ?>', { term: term }, function (data) {
                let html = '';
                data.forEach(p => {
                    html += `<a href="#" class="list-group-item list-group-item-action item-producto" 
                                data-id="${p.id_producto}" data-nombre="${p.nombre}" data-precio="${p.precio_venta}" data-stock="${p.stock}">
                                <strong>${p.nombre}</strong> - $${p.precio_venta} (Stock: ${p.stock})
                             </a>`;
                });
                $('#res_producto').html(html).show();
            });
        } else {
            $('#res_producto').hide();
        }
    });

    $(document).on('click', '.item-producto', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let nombre = $(this).data('nombre');
        let precio = parseFloat($(this).data('precio'));
        let stock = parseInt($(this).data('stock'));

        agregarAlCarrito(id, nombre, precio, stock);
        $('#res_producto').hide();
        $('#buscar_producto').val('');
    });

    function agregarAlCarrito(id, nombre, precio, stock) {
        let existe = carrito.find(p => p.id_producto === id);
        if (existe) {
            if (existe.cantidad + 1 > stock) {
                Swal.fire('Atención', 'No se puede superar el stock disponible (' + stock + ')', 'warning');
                return;
            }
            existe.cantidad++;
        } else {
            carrito.push({ id_producto: id, nombre: nombre, precio_unitario: precio, cantidad: 1, stock: stock });
        }
        renderTabla();
    }

    function renderTabla() {
        let html = '';
        let subtotalGeneral = 0;

        carrito.forEach((p, index) => {
            let subtotalItem = p.cantidad * p.precio_unitario;
            subtotalGeneral += subtotalItem;

            html += `<tr>
                <td>${p.nombre}</td>
                <td class="text-center"><span class="badge bg-secondary">${p.stock}</span></td>
                <td class="text-center">$${p.precio_unitario.toFixed(2)}</td>
                <td class="text-center">
                    <input type="number" class="form-control form-control-sm text-center input-cantidad" 
                           data-index="${index}" value="${p.cantidad}" min="1" max="${p.stock}">
                </td>
                <td class="text-end fw-bold">$${subtotalItem.toFixed(2)}</td>
                <td class="text-center">
                    <button class="btn btn-danger btn-sm btn-eliminar-item" data-index="${index}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>`;
        });

        $('#tablaDetalle tbody').html(html);

        let iva = subtotalGeneral * IVA_PERCENT;
        let total = subtotalGeneral + iva;

        $('#txt_subtotal').text(subtotalGeneral.toFixed(2));
        $('#txt_iva').text(iva.toFixed(2));
        $('#txt_total').text(total.toFixed(2));
    }

    $(document).on('change', '.input-cantidad', function () {
        let index = $(this).data('index');
        let val = parseInt($(this).val());
        if (val > carrito[index].stock) {
            Swal.fire('Atención', 'La cantidad excede el stock disponible', 'warning');
            $(this).val(carrito[index].stock);
            carrito[index].cantidad = carrito[index].stock;
        } else if (val < 1 || isNaN(val)) {
            $(this).val(1);
            carrito[index].cantidad = 1;
        } else {
            carrito[index].cantidad = val;
        }
        renderTabla();
    });

    $(document).on('click', '.btn-eliminar-item', function () {
        let index = $(this).data('index');
        carrito.splice(index, 1);
        renderTabla();
    });

    // Finalizar Venta AJAX
    $('#btnFinalizarVenta').on('click', function () {
        let id_cliente = $('#id_cliente').val();

        if (!id_cliente) {
            Swal.fire('Atención', 'Por favor seleccione un cliente.', 'warning');
            return;
        }
        if (carrito.length === 0) {
            Swal.fire('Atención', 'Debe incluir al menos un producto en la factura.', 'warning');
            return;
        }

        Swal.fire({
            title: '¿Confirmar Factura?',
            text: "Se registrará la venta y se descontará del inventario.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, Facturar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('facturas/guardar') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id_cliente: id_cliente,
                        detalles: JSON.stringify(carrito),
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    success: function (res) {
                        Swal.fire('¡Éxito!', res.message, 'success').then(() => {
                            window.location.href = '<?= base_url('facturas') ?>';
                        });
                    },
                    error: function (xhr) {
                        let err = xhr.responseJSON ? xhr.responseJSON.message : 'Error al procesar solicitud.';
                        Swal.fire('Error', err, 'error');
                    }
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?>