<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Gestión de Productos
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Administración de Productos
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title m-0 fw-bold">Catálogo de Productos</h3>
            <button type="button" class="btn btn-primary btn-sm" id="btnNuevoProducto">
                <i class="bi bi-box-seam me-1"></i> Nuevo Producto
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle w-100" id="tablaProductos">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;" class="text-center">ID</th>
                            <th>Código Barras</th>
                            <th>Nombre del Producto</th>
                            <th>Categoría</th>
                            <th>Marca</th>
                            <th class="text-end">Precio Venta</th>
                            <th class="text-center">Stock</th>
                            <th style="width: 110px;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($productos)): ?>
                            <?php foreach ($productos as $prod): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= esc($prod['id_producto']) ?></td>
                                    <td>
                                        <span class="badge bg-secondary font-monospace"><?= esc($prod['codigo_barras'] ?? 'S/C') ?></span>
                                    </td>
                                    <td class="fw-bold"><?= esc($prod['nombre']) ?></td>
                                    <td><?= esc($prod['categoria_nombre'] ?? 'Sin categoría') ?></td>
                                    <td><?= esc($prod['marca_nombre'] ?? 'Sin marca') ?></td>
                                    <td class="text-end fw-bold text-success">$<?= number_format($prod['precio_venta'] ?? 0, 2) ?></td>
                                    <td class="text-center">
                                        <span class="badge <?= ($prod['stock'] ?? 0) > 5 ? 'bg-success' : (($prod['stock'] ?? 0) > 0 ? 'bg-warning text-dark' : 'bg-danger') ?>">
                                            <?= esc($prod['stock'] ?? 0) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning btn-sm btn-editar me-1" 
                                                data-id="<?= $prod['id_producto'] ?>" 
                                                data-codigo_barras="<?= esc($prod['codigo_barras'] ?? '') ?>"
                                                data-nombre="<?= esc($prod['nombre']) ?>"
                                                data-id_categoria="<?= $prod['id_categoria'] ?>"
                                                data-id_marca="<?= $prod['id_marca'] ?>"
                                                data-precio_venta="<?= $prod['precio_venta'] ?>"
                                                data-stock="<?= $prod['stock'] ?>" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm btn-eliminar" 
                                                data-id="<?= $prod['id_producto'] ?>" title="Eliminar">
                                            <i class="bi bi-trash"></i>
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

<!-- Modal Producto -->
<div class="modal fade" id="modalProducto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formProducto" method="POST" action="<?= base_url('producto/guardar') ?>">
                <?= csrf_field() ?>
                <input type="hidden" id="id_producto" name="id_producto" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalProductoLabel">Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="codigo_barras" class="form-label fw-bold">Código de Barras</label>
                            <input type="text" class="form-control" id="codigo_barras" name="codigo_barras" maxlength="50" placeholder="Ej. 786100012345">
                        </div>
                        <div class="col-md-6">
                            <label for="nombre" class="form-label fw-bold">Nombre del Producto <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required minlength="2" maxlength="100" placeholder="Ej. Monitor 24 Pulgadas">
                        </div>
                        <div class="col-md-6">
                            <label for="id_categoria" class="form-label fw-bold">Categoría <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_categoria" name="id_categoria" required>
                                <option value="">-- Seleccionar Categoría --</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat['id_categoria'] ?>"><?= esc($cat['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="id_marca" class="form-label fw-bold">Marca <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_marca" name="id_marca" required>
                                <option value="">-- Seleccionar Marca --</option>
                                <?php foreach ($marcas as $mrc): ?>
                                    <option value="<?= $mrc['id_marca'] ?>"><?= esc($mrc['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="precio_venta" class="form-label fw-bold">Precio Venta ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control" id="precio_venta" name="precio_venta" required placeholder="0.00">
                        </div>
                        <div class="col-md-6">
                            <label for="stock" class="form-label fw-bold">Stock Inicial <span class="text-danger">*</span></label>
                            <input type="number" min="0" class="form-control" id="stock" name="stock" required placeholder="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    const Toast = (typeof Swal !== 'undefined') ? Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
    }) : null;

    $('#tablaProductos').DataTable({
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
        responsive: true, order: [[0, 'asc']], columnDefs: [{ orderable: false, targets: 7 }]
    });

    const modal = new bootstrap.Modal(document.getElementById('modalProducto'));
    const formProducto = document.getElementById('formProducto');

    $('#btnNuevoProducto').on('click', function () {
        $('#modalProductoLabel').text('Nuevo Producto');
        $('#id_producto').val('');
        $('#codigo_barras').val('');
        $('#nombre').val('');
        $('#id_categoria').val('');
        $('#id_marca').val('');
        $('#precio_venta').val('');
        $('#stock').val('0');
        $(formProducto).removeClass('was-validated');
        modal.show();
    });

    $('#tablaProductos tbody').on('click', '.btn-editar', function (e) {
        e.preventDefault();
        const $btn = $(this).closest('.btn-editar');
        $('#modalProductoLabel').text('Editar Producto');
        $('#id_producto').val($btn.attr('data-id'));
        $('#codigo_barras').val($btn.attr('data-codigo_barras'));
        $('#nombre').val($btn.attr('data-nombre'));
        $('#id_categoria').val($btn.attr('data-id_categoria'));
        $('#id_marca').val($btn.attr('data-id_marca'));
        $('#precio_venta').val($btn.attr('data-precio_venta'));
        $('#stock').val($btn.attr('data-stock'));
        $(formProducto).removeClass('was-validated');
        modal.show();
    });

    $(formProducto).on('submit', function (e) {
        e.preventDefault();
        if (!formProducto.checkValidity()) {
            e.stopPropagation();
            $(formProducto).addClass('was-validated');
            return;
        }

        $('#btnGuardar').prop('disabled', true);
        fetch(formProducto.action, {
            method: 'POST', body: new FormData(formProducto), headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(res => {
            if (res.status !== 200) throw new Error(res.body.message || 'Error en la solicitud');
            modal.hide();
            if (Toast) Toast.fire({ icon: 'success', title: res.body.message });
            setTimeout(() => location.reload(), 1200);
        })
        .catch(err => {
            if (Toast) Toast.fire({ icon: 'error', title: err.message });
        })
        .finally(() => $('#btnGuardar').prop('disabled', false));
    });

    $('#tablaProductos tbody').on('click', '.btn-eliminar', function (e) {
        e.preventDefault();
        const id = $(this).closest('.btn-eliminar').attr('data-id');
        Swal.fire({
            title: '¿Estás seguro?', text: 'Se eliminará el producto seleccionado.', icon: 'warning',
            showCancelButton: true, confirmColor: '#dc3545', confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?= base_url('producto/eliminar') ?>/' + id, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', '<?= csrf_token() ?>': '<?= csrf_hash() ?>' }
                })
                .then(res => res.json())
                .then(data => {
                    if (Toast) Toast.fire({ icon: 'success', title: data.message });
                    setTimeout(() => location.reload(), 1200);
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?>