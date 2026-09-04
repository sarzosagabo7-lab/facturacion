<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Gestión de Marcas<?= $this->endSection() ?>
<?= $this->section('page_title') ?>Administración de Marcas<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title m-0 fw-bold"><i class="bi bi-bookmark-star-fill me-2"></i>Lista de Marcas</h3>
            <button type="button" class="btn btn-primary btn-sm" id="btnNuevaMarca">
                <i class="bi bi-plus-lg me-1"></i>Nueva Marca
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle w-100" id="tablaMarcas">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 80px;">ID</th>
                            <th>Nombre de la Marca</th>
                            <th class="text-center" style="width: 140px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($marcas)): ?>
                            <?php foreach ($marcas as $mar): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= esc($mar['id_marca']) ?></td>
                                    <td><?= esc($mar['nombre']) ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning btn-sm btn-editar me-1"
                                            data-id="<?= esc($mar['id_marca']) ?>"
                                            data-nombre="<?= esc($mar['nombre']) ?>" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <a href="<?= base_url('marcas/eliminar/' . $mar['id_marca']) ?>"
                                            class="btn btn-danger btn-sm btn-eliminar"
                                            data-nombre="<?= esc($mar['nombre']) ?>" title="Eliminar">
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

<div class="modal fade" id="modalMarca" tabindex="-1" aria-labelledby="modalMarcaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formMarca" method="POST" action="<?= base_url('marcas/guardar') ?>">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalMarcaLabel">
                        <i class="bi bi-plus-circle me-2"></i>Nueva Marca
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre de la Marca <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required minlength="2" maxlength="50" placeholder="Ej. Nike, Samsung, Nestle...">
                        <div class="invalid-feedback">Por favor ingrese un nombre válido de mínimo 2 caracteres.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">
                        <i class="bi bi-save me-1"></i>Guardar Marca
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const formMarca = document.getElementById('formMarca');
    const modalElement = document.getElementById('modalMarca');
    const modal = new bootstrap.Modal(modalElement);
    const modalLabel = document.getElementById('modalMarcaLabel');
    const inputNombre = document.getElementById('nombre');
    const btnGuardar = document.getElementById('btnGuardar');
    const urlGuardar = '<?= base_url('marcas/guardar') ?>';

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

    new DataTable('#tablaMarcas', {
        language: {
            emptyTable: 'No hay marcas registradas',
            info: 'Mostrando _START_ a _END_ de _TOTAL_ marcas',
            infoEmpty: 'Mostrando 0 a 0 de 0 marcas',
            infoFiltered: '(filtrado de _MAX_ marcas)',
            lengthMenu: 'Mostrar _MENU_ registros',
            loadingRecords: 'Cargando...',
            processing: 'Procesando...',
            search: 'Buscar:',
            zeroRecords: 'No se encontraron marcas',
            paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' }
        },
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'Todos']],
        order: [[0, 'asc']],
        columnDefs: [
            { targets: 0, className: 'text-center', type: 'num' },
            { targets: 2, orderable: false, searchable: false, className: 'text-center' }
        ],
        layout: { topStart: 'pageLength', topEnd: 'search', bottomStart: 'info', bottomEnd: 'paging' }
    });

    document.getElementById('btnNuevaMarca').addEventListener('click', () => {
        modalLabel.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Nueva Marca';
        formMarca.action = urlGuardar;
        formMarca.reset();
        formMarca.classList.remove('was-validated');
        modal.show();
        setTimeout(() => inputNombre.focus(), 500);
    });

    // Delegación de eventos para Editar
    document.addEventListener('click', (e) => {
        const btnEditar = e.target.closest('.btn-editar');
        if (btnEditar) {
            const id = btnEditar.getAttribute('data-id');
            const nombre = btnEditar.getAttribute('data-nombre');
            modalLabel.innerHTML = '<i class="bi bi-pencil-square me-2"></i>Editar Marca';
            formMarca.action = `<?= base_url('marcas/actualizar/') ?>/${id}`;
            inputNombre.value = nombre;
            formMarca.classList.remove('was-validated');
            modal.show();
            setTimeout(() => { inputNombre.focus(); inputNombre.select(); }, 500);
        }

        // Delegación de eventos para Eliminar
        const btnEliminar = e.target.closest('.btn-eliminar');
        if (btnEliminar) {
            e.preventDefault();
            const url = btnEliminar.getAttribute('href');
            const nombre = btnEliminar.getAttribute('data-nombre');

            Swal.fire({
                icon: 'warning',
                title: '¿Eliminar marca?',
                html: `La marca <strong>${nombre}</strong> será eliminada.<br><br><span class="text-danger">Esta acción no se puede deshacer.</span>`,
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
        modalLabel.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Nueva Marca';
        formMarca.action = urlGuardar;
        formMarca.reset();
        formMarca.classList.remove('was-validated');
    });

    formMarca.addEventListener('submit', (e) => {
        e.preventDefault();
        e.stopPropagation();

        if (!formMarca.checkValidity()) {
            formMarca.classList.add('was-validated');
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor complete correctamente el nombre de la marca.',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#0d6efd'
            });
            return;
        }

        formMarca.classList.add('was-validated');
        const esEdicion = formMarca.action.includes('/actualizar/');

        Swal.fire({
            icon: 'question',
            title: esEdicion ? '¿Actualizar marca?' : '¿Guardar marca?',
            text: esEdicion ? 'Se actualizará la información de la marca.' : 'La nueva marca será registrada en el sistema.',
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
                formMarca.submit();
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