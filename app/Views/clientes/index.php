<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Gestión de Clientes
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Administración de Clientes
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
            <h3 class="card-title m-0 fw-bold">Lista de Clientes</h3>
            <button type="button" class="btn btn-primary btn-sm" id="btnNuevoCliente">
                <i class="bi bi-person-plus-fill me-1"></i> Nuevo Cliente
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle w-100" id="tablaClientes">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 60px;" class="text-center">ID</th>
                            <th>Cédula / Identificación</th>
                            <th>Nombre Completo</th>
                            <th>Teléfono</th>
                            <th>Correo Electrónico</th>
                            <th style="width: 120px;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($clientes)): ?>
                            <?php foreach ($clientes as $cli): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= esc($cli['id_cliente']) ?></td>
                                    <td class="fw-bold text-primary"><?= esc($cli['identificacion']) ?></td>
                                    <td><?= esc($cli['nombre']) ?></td>
                                    <td><?= esc($cli['telefono']) ?></td>
                                    <td><?= esc($cli['correo']) ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning btn-sm btn-editar me-1" 
                                                data-id="<?= $cli['id_cliente'] ?>" 
                                                data-identificacion="<?= esc($cli['identificacion']) ?>"
                                                data-nombre="<?= esc($cli['nombre']) ?>"
                                                data-telefono="<?= esc($cli['telefono']) ?>"
                                                data-correo="<?= esc($cli['correo']) ?>" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm btn-eliminar" 
                                                data-id="<?= $cli['id_cliente'] ?>" title="Eliminar">
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

<div class="modal fade" id="modalCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formCliente" method="POST" action="<?= base_url('cliente/guardar') ?>">
                <?= csrf_field() ?>
                <input type="hidden" id="id_cliente" name="id_cliente" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalClienteLabel">Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="identificacion" class="form-label fw-bold">N° de Cédula / RUC <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="identificacion" name="identificacion" required minlength="10" maxlength="20" placeholder="Ej. 1002003004">
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre Completo / Razón Social <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required minlength="3" maxlength="100" placeholder="Ej. Carlos Mendoza">
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label fw-bold">Teléfono / Celular</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" maxlength="20" placeholder="Ej. 0991234567">
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label fw-bold">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" maxlength="100" placeholder="cliente@correo.com">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Cliente</button>
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

    $('#tablaClientes').DataTable({
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
        responsive: true, order: [[0, 'asc']], columnDefs: [{ orderable: false, targets: 5 }]
    });

    const modal = new bootstrap.Modal(document.getElementById('modalCliente'));
    const formCliente = document.getElementById('formCliente');

    $('#btnNuevoCliente').on('click', function () {
        $('#modalClienteLabel').text('Nuevo Cliente');
        $('#id_cliente').val('');
        $('#identificacion').val('');
        $('#nombre').val('');
        $('#telefono').val('');
        $('#correo').val('');
        $(formCliente).removeClass('was-validated');
        modal.show();
    });

    $('#tablaClientes tbody').on('click', '.btn-editar', function (e) {
        e.preventDefault();
        const $btn = $(this).closest('.btn-editar');
        $('#modalClienteLabel').text('Editar Cliente');
        $('#id_cliente').val($btn.attr('data-id'));
        $('#identificacion').val($btn.attr('data-identificacion'));
        $('#nombre').val($btn.attr('data-nombre'));
        $('#telefono').val($btn.attr('data-telefono'));
        $('#correo').val($btn.attr('data-correo'));
        $(formCliente).removeClass('was-validated');
        modal.show();
    });

    $(formCliente).on('submit', function (e) {
        e.preventDefault();
        if (!formCliente.checkValidity()) {
            e.stopPropagation();
            $(formCliente).addClass('was-validated');
            return;
        }

        $('#btnGuardar').prop('disabled', true);
        fetch(formCliente.action, {
            method: 'POST', body: new FormData(formCliente), headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(res => {
            if (res.status !== 200) throw new Error(res.body.message || 'Error al procesar solicitud');
            modal.hide();
            if (Toast) Toast.fire({ icon: 'success', title: res.body.message });
            setTimeout(() => location.reload(), 1200);
        })
        .catch(err => {
            if (Toast) Toast.fire({ icon: 'error', title: err.message });
        })
        .finally(() => $('#btnGuardar').prop('disabled', false));
    });

    $('#tablaClientes tbody').on('click', '.btn-eliminar', function (e) {
        e.preventDefault();
        const id = $(this).closest('.btn-eliminar').attr('data-id');
        Swal.fire({
            title: '¿Estás seguro?', text: 'Se eliminará la información del cliente.', icon: 'warning',
            showCancelButton: true, confirmColor: '#dc3545', confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?= base_url('cliente/eliminar') ?>/' + id, {
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