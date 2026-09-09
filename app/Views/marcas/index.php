<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Gestión de Marcas
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Administración de Marcas
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
            <h3 class="card-title m-0 fw-bold">Lista de Marcas</h3>
            <button type="button" class="btn btn-primary btn-sm" id="btnNuevaMarca">
                <i class="bi bi-plus-lg me-1"></i> Nueva Marca
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle w-100" id="tablaMarcas">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 80px;" class="text-center">ID</th>
                            <th>Nombre de la Marca</th>
                            <th style="width: 120px;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($marcas)): ?>
                            <?php foreach ($marcas as $mrc): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= esc($mrc['id_marca']) ?></td>
                                    <td><?= esc($mrc['nombre']) ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning btn-sm btn-editar me-1" 
                                                data-id="<?= $mrc['id_marca'] ?>" 
                                                data-nombre="<?= esc($mrc['nombre']) ?>" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm btn-eliminar" 
                                                data-id="<?= $mrc['id_marca'] ?>" title="Eliminar">
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

<div class="modal fade" id="modalMarca" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formMarca" method="POST" action="<?= base_url('marca/guardar') ?>">
                <?= csrf_field() ?>
                <input type="hidden" id="id_marca" name="id_marca" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalMarcaLabel">Nueva Marca</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre de la Marca <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required minlength="2" maxlength="50" placeholder="Ej. Samsung, Nike...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Marca</button>
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

    $('#tablaMarcas').DataTable({
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
        responsive: true, order: [[0, 'asc']], columnDefs: [{ orderable: false, targets: 2 }]
    });

    const modal = new bootstrap.Modal(document.getElementById('modalMarca'));
    const formMarca = document.getElementById('formMarca');

    $('#btnNuevaMarca').on('click', function () {
        $('#modalMarcaLabel').text('Nueva Marca');
        $('#id_marca').val('');
        $('#nombre').val('');
        $(formMarca).removeClass('was-validated');
        modal.show();
    });

    $('#tablaMarcas tbody').on('click', '.btn-editar', function (e) {
        e.preventDefault();
        const $btn = $(this).closest('.btn-editar');
        $('#modalMarcaLabel').text('Editar Marca');
        $('#id_marca').val($btn.attr('data-id'));
        $('#nombre').val($btn.attr('data-nombre'));
        $(formMarca).removeClass('was-validated');
        modal.show();
    });

    $(formMarca).on('submit', function (e) {
        e.preventDefault();
        if (!formMarca.checkValidity()) {
            e.stopPropagation();
            $(formMarca).addClass('was-validated');
            return;
        }

        $('#btnGuardar').prop('disabled', true);
        fetch(formMarca.action, {
            method: 'POST', body: new FormData(formMarca), headers: { 'X-Requested-With': 'XMLHttpRequest' }
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

    $('#tablaMarcas tbody').on('click', '.btn-eliminar', function (e) {
        e.preventDefault();
        const id = $(this).closest('.btn-eliminar').attr('data-id');
        Swal.fire({
            title: '¿Estás seguro?', text: 'Se eliminará esta marca.', icon: 'warning',
            showCancelButton: true, confirmColor: '#dc3545', confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?= base_url('marca/eliminar') ?>/' + id, {
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