<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Gestión de Proveedores<?= $this->endSection() ?>
<?= $this->section('page_title') ?>Administración de Proveedores<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title m-0 fw-bold"><i class="bi bi-truck me-2"></i>Lista de Proveedores</h3>
            <button type="button" class="btn btn-primary btn-sm" id="btnNuevoProveedor">
                <i class="bi bi-plus-lg me-1"></i>Nuevo Proveedor
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle w-100" id="tablaProveedores">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 60px;">ID</th>
                            <th>Identificación</th>
                            <th>Razón Social / Nombre</th>
                            <th>Teléfono</th>
                            <th class="text-center" style="width: 140px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($proveedores)): ?>
                            <?php foreach ($proveedores as $prov): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= esc($prov['id_proveedor']) ?></td>
                                    <td><span class="badge bg-secondary"><?= esc($prov['identificacion']) ?></span></td>
                                    <td><?= esc($prov['nombre']) ?></td>
                                    <td><?= esc($prov['telefono'] ?? 'N/A') ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning btn-sm btn-editar me-1"
                                            data-id="<?= esc($prov['id_proveedor']) ?>"
                                            data-identificacion="<?= esc($prov['identificacion']) ?>"
                                            data-nombre="<?= esc($prov['nombre']) ?>"
                                            data-telefono="<?= esc($prov['telefono']) ?>" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <a href="<?= base_url('proveedores/eliminar/' . $prov['id_proveedor']) ?>"
                                            class="btn btn-danger btn-sm btn-eliminar"
                                            data-nombre="<?= esc($prov['nombre']) ?>" title="Eliminar">
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

<!-- MODAL NUEVO / EDITAR PROVEEDOR -->
<div class="modal fade" id="modalProveedor" tabindex="-1" aria-labelledby="modalProveedorLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formProveedor" method="POST" action="<?= base_url('proveedores/guardar') ?>">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalProveedorLabel">
                        <i class="bi bi-truck me-2"></i>Nuevo Proveedor
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="identificacion" class="form-label fw-bold">Identificación <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="identificacion" name="identificacion" required maxlength="10" placeholder="Ej. 1720000000">
                        <div class="invalid-feedback" id="feedbackIdentificacion">Ingrese un número de cédula válido (10 dígitos).</div>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Razón Social / Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required minlength="3" maxlength="100" placeholder="Ej. Distribuidora S.A.">
                        <div class="invalid-feedback">Por favor ingrese el nombre del proveedor.</div>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label fw-bold">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" maxlength="20" placeholder="Ej. 022123456">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">
                        <i class="bi bi-save me-1"></i>Guardar Proveedor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Función JS reutilizada para validar cédula ecuatoriana
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
    const formProveedor = document.getElementById('formProveedor');
    const modalElement = document.getElementById('modalProveedor');
    const modal = new bootstrap.Modal(modalElement);
    const modalLabel = document.getElementById('modalProveedorLabel');
    const inputIdentificacion = document.getElementById('identificacion');
    const inputNombre = document.getElementById('nombre');
    const inputTelefono = document.getElementById('telefono');
    const btnGuardar = document.getElementById('btnGuardar');
    const urlGuardar = '<?= base_url('proveedores/guardar') ?>';

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

    new DataTable('#tablaProveedores', {
        language: {
            emptyTable: 'No hay proveedores registrados',
            info: 'Mostrando _START_ a _END_ de _TOTAL_ proveedores',
            infoEmpty: 'Mostrando 0 a 0 de 0 proveedores',
            infoFiltered: '(filtrado de _MAX_ proveedores)',
            lengthMenu: 'Mostrar _MENU_ registros',
            loadingRecords: 'Cargando...',
            processing: 'Procesando...',
            search: 'Buscar:',
            zeroRecords: 'No se encontraron proveedores',
            paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' }
        },
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Todos']],
        order: [[0, 'asc']],
        columnDefs: [
            { targets: 0, className: 'text-center', type: 'num' },
            { targets: 4, orderable: false, searchable: false, className: 'text-center' }
        ],
        layout: { topStart: 'pageLength', topEnd: 'search', bottomStart: 'info', bottomEnd: 'paging' }
    });

    // Solo números en Identificación
    inputIdentificacion.addEventListener('input', (e) => {
        e.target.value = e.target.value.replace(/\D/g, '');
    });

    document.getElementById('btnNuevoProveedor').addEventListener('click', () => {
        modalLabel.innerHTML = '<i class="bi bi-truck me-2"></i>Nuevo Proveedor';
        formProveedor.action = urlGuardar;
        formProveedor.reset();
        inputIdentificacion.classList.remove('is-invalid');
        formProveedor.classList.remove('was-validated');
        modal.show();
        setTimeout(() => inputIdentificacion.focus(), 500);
    });

    // Delegación de eventos para Editar
    document.addEventListener('click', (e) => {
        const btnEditar = e.target.closest('.btn-editar');
        if (btnEditar) {
            const id = btnEditar.getAttribute('data-id');
            modalLabel.innerHTML = '<i class="bi bi-pencil-square me-2"></i>Editar Proveedor';
            formProveedor.action = `<?= base_url('proveedores/actualizar/') ?>/${id}`;
            inputIdentificacion.value = btnEditar.getAttribute('data-identificacion');
            inputNombre.value = btnEditar.getAttribute('data-nombre');
            inputTelefono.value = btnEditar.getAttribute('data-telefono') || '';
            inputIdentificacion.classList.remove('is-invalid');
            formProveedor.classList.remove('was-validated');
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
                title: '¿Eliminar proveedor?',
                html: `El proveedor <strong>${nombre}</strong> será eliminado.<br><br><span class="text-danger">Esta acción no se puede deshacer.</span>`,
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
        modalLabel.innerHTML = '<i class="bi bi-truck me-2"></i>Nuevo Proveedor';
        formProveedor.action = urlGuardar;
        formProveedor.reset();
        inputIdentificacion.classList.remove('is-invalid');
        formProveedor.classList.remove('was-validated');
    });

    formProveedor.addEventListener('submit', (e) => {
        e.preventDefault();
        e.stopPropagation();

        const identificacion = inputIdentificacion.value;
        const esIdentificacionValida = validarCedulaEcuador(identificacion);

        if (!esIdentificacionValida) {
            inputIdentificacion.classList.add('is-invalid');
            Swal.fire({
                icon: 'error',
                title: 'Identificación Inválida',
                text: 'El número de identificación ingresado no es válido.',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#dc3545'
            });
            return;
        } else {
            inputIdentificacion.classList.remove('is-invalid');
        }

        if (!formProveedor.checkValidity()) {
            formProveedor.classList.add('was-validated');
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor complete todos los campos obligatorios correctamente.',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#0d6efd'
            });
            return;
        }

        formProveedor.classList.add('was-validated');
        const esEdicion = formProveedor.action.includes('/actualizar/');

        Swal.fire({
            icon: 'question',
            title: esEdicion ? '¿Actualizar proveedor?' : '¿Guardar proveedor?',
            text: esEdicion ? 'Se actualizará la información del proveedor.' : 'El nuevo proveedor será registrado en el sistema.',
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
                formProveedor.submit();
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