<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Gestión de Clientes<?= $this->endSection() ?>
<?= $this->section('page_title') ?>Administración de Clientes<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title m-0 fw-bold"><i class="bi bi-people-fill me-2"></i>Lista de Clientes</h3>
            <button type="button" class="btn btn-primary btn-sm" id="btnNuevoCliente">
                <i class="bi bi-person-plus-fill me-1"></i>Nuevo Cliente
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle w-100" id="tablaClientes">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 60px;">ID</th>
                            <th>Cédula</th>
                            <th>Nombres y Apellidos</th>
                            <th>Teléfono</th>
                            <th>Correo Electrónico</th>
                            <th class="text-center" style="width: 140px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($clientes)): ?>
                            <?php foreach ($clientes as $cli): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= esc($cli['id_cliente']) ?></td>
                                    <td><span class="badge bg-secondary"><?= esc($cli['identificacion']) ?></span></td>
                                    <td><?= esc($cli['nombre']) ?></td>
                                    <td><?= esc($cli['telefono'] ?? 'N/A') ?></td>
                                    <td><?= esc($cli['correo'] ?? 'N/A') ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning btn-sm btn-editar me-1"
                                            data-id="<?= esc($cli['id_cliente']) ?>"
                                            data-identificacion="<?= esc($cli['identificacion']) ?>"
                                            data-nombre="<?= esc($cli['nombre']) ?>"
                                            data-telefono="<?= esc($cli['telefono']) ?>"
                                            data-correo="<?= esc($cli['correo']) ?>" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <a href="<?= base_url('clientes/eliminar/' . $cli['id_cliente']) ?>"
                                            class="btn btn-danger btn-sm btn-eliminar"
                                            data-nombre="<?= esc($cli['nombre']) ?>" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </a>
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

<!-- MODAL NUEVO / EDITAR CLIENTE -->
<div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="modalClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formCliente" method="POST" action="<?= base_url('clientes/guardar') ?>">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalClienteLabel">
                        <i class="bi bi-person-plus-fill me-2"></i>Nuevo Cliente
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="identificacion" class="form-label fw-bold">Cédula <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="identificacion" name="identificacion" required maxlength="10" placeholder="Ej. 1720000000">
                            <div class="invalid-feedback" id="feedbackCedula">Ingrese una cédula ecuatoriana válida (10 dígitos).</div>
                        </div>
                        <div class="col-md-6">
                            <label for="nombre" class="form-label fw-bold">Nombres y Apellidos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required minlength="3" maxlength="100" placeholder="Ej. Juan Pérez">
                            <div class="invalid-feedback">Por favor ingrese el nombre del cliente.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="telefono" class="form-label fw-bold">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" maxlength="20" placeholder="Ej. 0991234567">
                        </div>
                        <div class="col-md-6">
                            <label for="correo" class="form-label fw-bold">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo" name="correo" maxlength="100" placeholder="Ej. cliente@correo.com">
                            <div class="invalid-feedback">Ingrese un correo válido.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">
                        <i class="bi bi-save me-1"></i>Guardar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Función JS para validar cédula ecuatoriana
function validarCedulaEcuador(cedula) {
    cedula = cedula.trim();
    if (!/^\d{10}$/.test(cedula)) return false;

    const provincia = parseInt(cedula.substring(0, 2), 10);
    if ((provincia < 1 || provincia > 24) && provincia !== 30) return false;

    const tercerDigito = parseInt(cedula.substring(2, 3), 10);
    if (tercerDigito >= 6) return false;

    const coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
    const digitoVerificador = parseInt(cedula.substring(9, 10), 10);
    let suma = 0;

    for (let i = 0; i < 9; i++) {
        let valor = parseInt(cedula.substring(i, i + 1), 10) * coeficientes[i];
        if (valor >= 10) valor -= 9;
        suma += valor;
    }

    const residuo = suma % 10;
    const resultado = (residuo === 0) ? 0 : 10 - residuo;

    return resultado === digitoVerificador;
}

document.addEventListener('DOMContentLoaded', () => {
    const formCliente = document.getElementById('formCliente');
    const modalElement = document.getElementById('modalCliente');
    const modal = new bootstrap.Modal(modalElement);
    const modalLabel = document.getElementById('modalClienteLabel');
    const inputIdentificacion = document.getElementById('identificacion');
    const inputNombre = document.getElementById('nombre');
    const inputTelefono = document.getElementById('telefono');
    const inputCorreo = document.getElementById('correo');
    const btnGuardar = document.getElementById('btnGuardar');
    const urlGuardar = '<?= base_url('clientes/guardar') ?>';

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    new DataTable('#tablaClientes', {
        language: {
            emptyTable: 'No hay clientes registrados',
            info: 'Mostrando _START_ a _END_ de _TOTAL_ clientes',
            infoEmpty: 'Mostrando 0 a 0 de 0 clientes',
            infoFiltered: '(filtrado de _MAX_ clientes)',
            lengthMenu: 'Mostrar _MENU_ registros',
            loadingRecords: 'Cargando...',
            processing: 'Procesando...',
            search: 'Buscar:',
            zeroRecords: 'No se encontraron clientes',
            paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' }
        },
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Todos']],
        order: [[0, 'asc']],
        columnDefs: [
            { targets: 0, className: 'text-center', type: 'num' },
            { targets: 5, orderable: false, searchable: false, className: 'text-center' }
        ],
        layout: { topStart: 'pageLength', topEnd: 'search', bottomStart: 'info', bottomEnd: 'paging' }
    });

    // Solo permitir números en el campo Cédula
    inputIdentificacion.addEventListener('input', (e) => {
        e.target.value = e.target.value.replace(/\D/g, '');
    });

    document.getElementById('btnNuevoCliente').addEventListener('click', () => {
        modalLabel.innerHTML = '<i class="bi bi-person-plus-fill me-2"></i>Nuevo Cliente';
        formCliente.action = urlGuardar;
        formCliente.reset();
        inputIdentificacion.classList.remove('is-invalid');
        formCliente.classList.remove('was-validated');
        modal.show();
        setTimeout(() => inputIdentificacion.focus(), 500);
    });

    // Delegación de eventos para Editar
    document.addEventListener('click', (e) => {
        const btnEditar = e.target.closest('.btn-editar');
        if (btnEditar) {
            const id = btnEditar.getAttribute('data-id');
            modalLabel.innerHTML = '<i class="bi bi-pencil-square me-2"></i>Editar Cliente';
            formCliente.action = `<?= base_url('clientes/actualizar/') ?>/${id}`;
            inputIdentificacion.value = btnEditar.getAttribute('data-identificacion');
            inputNombre.value = btnEditar.getAttribute('data-nombre');
            inputTelefono.value = btnEditar.getAttribute('data-telefono') || '';
            inputCorreo.value = btnEditar.getAttribute('data-correo') || '';
            inputIdentificacion.classList.remove('is-invalid');
            formCliente.classList.remove('was-validated');
            modal.show();
            setTimeout(() => { inputIdentificacion.focus(); }, 500);
        }

        // Delegación de eventos para Eliminar
        const btnEliminar = e.target.closest('.btn-eliminar');
        if (btnEliminar) {
            e.preventDefault();
            const url = btnEliminar.getAttribute('href');
            const nombre = btnEliminar.getAttribute('data-nombre');

            Swal.fire({
                icon: 'warning',
                title: '¿Eliminar cliente?',
                html: `El cliente <strong>${nombre}</strong> será eliminado.<br><br><span class="text-danger">Esta acción no se puede deshacer.</span>`,
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-trash me-1"></i>Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) window.location.href = url;
            });
        }
    });

    modalElement.addEventListener('hidden.bs.modal', () => {
        modalLabel.innerHTML = '<i class="bi bi-person-plus-fill me-2"></i>Nuevo Cliente';
        formCliente.action = urlGuardar;
        formCliente.reset();
        inputIdentificacion.classList.remove('is-invalid');
        formCliente.classList.remove('was-validated');
    });

    formCliente.addEventListener('submit', (e) => {
        e.preventDefault();
        e.stopPropagation();

        const cedula = inputIdentificacion.value;
        const esCedulaValida = validarCedulaEcuador(cedula);

        if (!esCedulaValida) {
            inputIdentificacion.classList.add('is-invalid');
            Swal.fire({
                icon: 'error',
                title: 'Cédula Inválida',
                text: 'El número de cédula ingresado no es válido para Ecuador.',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#dc3545'
            });
            return;
        } else {
            inputIdentificacion.classList.remove('is-invalid');
        }

        if (!formCliente.checkValidity()) {
            formCliente.classList.add('was-validated');
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor complete todos los campos obligatorios correctamente.',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#0d6efd'
            });
            return;
        }

        formCliente.classList.add('was-validated');
        const esEdicion = formCliente.action.includes('/actualizar/');

        Swal.fire({
            icon: 'question',
            title: esEdicion ? '¿Actualizar cliente?' : '¿Guardar cliente?',
            text: esEdicion ? 'Se actualizará la información del cliente.' : 'El nuevo cliente será registrado en el sistema.',
            showCancelButton: true,
            confirmButtonText: esEdicion ? 'Sí, actualizar' : 'Sí, guardar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                btnGuardar.disabled = true;
                btnGuardar.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Guardando...';
                formCliente.submit();
            }
        });
    });

    // Flashdata Messages
    <?php if ($success = session()->getFlashdata('success')): ?>
        Toast.fire({ icon: 'success', title: <?= json_encode($success) ?> });
    <?php endif; ?>
    <?php if ($error = session()->getFlashdata('error')): ?>
        Toast.fire({ icon: 'error', title: <?= json_encode($error) ?> });
    <?php endif; ?>
    <?php if ($errors = session()->getFlashdata('errors')): ?>
        Toast.fire({ icon: 'error', title: <?= json_encode(implode(' | ', $errors)) ?> });
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?>