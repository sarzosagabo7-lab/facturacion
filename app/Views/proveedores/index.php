<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Gestión de Proveedores
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Administración de Proveedores
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
            <h3 class="card-title m-0 fw-bold">Lista de Proveedores</h3>
            <button type="button" class="btn btn-primary btn-sm" id="btnNuevoProveedor">
                <i class="bi bi-truck me-1"></i> Nuevo Proveedor
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle w-100" id="tablaProveedores">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 60px;" class="text-center">ID</th>
                            <th>Cédula / RUC</th>
                            <th>Nombre / Empresa</th>
                            <th>Teléfono</th>
                            <th style="width: 120px;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($proveedores)): ?>
                            <?php foreach ($proveedores as $prov): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= esc($prov['id_proveedor']) ?></td>
                                    <td class="fw-bold text-primary"><?= esc($prov['identificacion']) ?></td>
                                    <td><?= esc($prov['nombre']) ?></td>
                                    <td><?= esc($prov['telefono']) ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning btn-sm btn-editar me-1" 
                                                data-id="<?= $prov['id_proveedor'] ?>" 
                                                data-identificacion="<?= esc($prov['identificacion']) ?>"
                                                data-nombre="<?= esc($prov['nombre']) ?>"
                                                data-telefono="<?= esc($prov['telefono']) ?>" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm btn-eliminar" 
                                                data-id="<?= $prov['id_proveedor'] ?>" title="Eliminar">
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

<div class="modal fade" id="modalProveedor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formProveedor" method="POST" action="<?= base_url('proveedor/guardar') ?>">
                <?= csrf_field() ?>
                <input type="hidden" id="id_proveedor" name="id_proveedor" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalProveedorLabel">Nuevo Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="identificacion" class="form-label fw-bold">N° de Cédula / RUC <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="identificacion" name="identificacion" required minlength="10" maxlength="20" placeholder="Ej. 1790011223001">
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre / Razón Social <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required minlength="3" maxlength="100" placeholder="Ej. Distribuidora S.A.">
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label fw-bold">Teléfono / Contacto</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" maxlength="20" placeholder="Ej. 022999888">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Proveedor</button>
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

    $('#tablaProveedores').DataTable({
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
        responsive: true, order: [[0, 'asc']], columnDefs: [{ orderable: false, targets: 4 }]
    });

    const modal = new bootstrap.Modal(document.getElementById('modalProveedor'));
    const formProveedor = document.getElementById('formProveedor');

    $('#btnNuevoProveedor').on('click', function () {
        $('#modalProveedorLabel').text('Nuevo Proveedor');
        $('#id_proveedor').val('');
        $('#identificacion').val('');
        $('#nombre').val('');
        $('#telefono').val('');
        $(formProveedor).removeClass('was-validated');
        modal.show();
    });

    $('#tablaProveedores tbody').on('click', '.btn-editar', function (e) {
        e.preventDefault();
        const $btn = $(this).closest('.btn-editar');
        $('#modalProveedorLabel').text('Editar Proveedor');
        $('#id_proveedor').val($btn.attr('data-id'));
        $('#identificacion').val($btn.attr('data-identificacion'));
        $('#nombre').val($btn.attr('data-nombre'));
        $('#telefono').val($btn.attr('data-telefono'));
        $(formProveedor).removeClass('was-validated');
        modal.show();
    });

    $(formProveedor).on('submit', function (e) {
        e.preventDefault();
        if (!formProveedor.checkValidity()) {
            e.stopPropagation();
            $(formProveedor).addClass('was-validated');
            return;
        }

        $('#btnGuardar').prop('disabled', true);
        fetch(formProveedor.action, {
            method: 'POST', body: new FormData(formProveedor), headers: { 'X-Requested-With': 'XMLHttpRequest' }
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

    $('#tablaProveedores tbody').on('click', '.btn-eliminar', function (e) {
        e.preventDefault();
        const id = $(this).closest('.btn-eliminar').attr('data-id');
        Swal.fire({
            title: '¿Estás seguro?', text: 'Se eliminará el proveedor seleccionado.', icon: 'warning',
            showCancelButton: true, confirmColor: '#dc3545', confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?= base_url('proveedor/eliminar') ?>/' + id, {
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