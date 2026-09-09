<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Gestión de Categorías
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Administración de Categorías
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- CDN de DataTables y SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">

    <!-- Card Principal -->
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title m-0 fw-bold">Lista de Categorías</h3>
            <button type="button" class="btn btn-primary btn-sm" id="btnNuevaCategoria">
                <i class="bi bi-plus-lg me-1"></i> Nueva Categoría
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle w-100" id="tablaCategorias">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 80px;" class="text-center">ID</th>
                            <th>Nombre de la Categoría</th>
                            <th style="width: 120px;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($categorias)): ?>
                            <?php foreach ($categorias as $cat): ?>
                                <tr>
                                    <!-- Muestra el ID real de la categoría -->
                                    <td class="text-center fw-bold"><?= esc($cat['id_categoria']) ?></td>
                                    <td><?= esc($cat['nombre']) ?></td>
                                    <td class="text-center">
                                        <button type="button" 
                                                class="btn btn-warning btn-sm btn-editar me-1" 
                                                data-id="<?= $cat['id_categoria'] ?>" 
                                                data-nombre="<?= esc($cat['nombre']) ?>"
                                                title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm btn-eliminar" 
                                                data-id="<?= $cat['id_categoria'] ?>"
                                                title="Eliminar">
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

<!-- Modal Unificado para Registro y Edición -->
<div class="modal fade" id="modalCategoria" tabindex="-1" aria-labelledby="modalCategoriaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formCategoria" method="POST" action="<?= base_url('categoria/guardar') ?>">
                <?= csrf_field() ?>
                <input type="hidden" id="id_categoria" name="id_categoria" value="">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalCategoriaLabel">Nueva Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre de la Categoría <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required minlength="3" maxlength="50" placeholder="Ej. Lácteos, Electrónica...">
                        <div class="invalid-feedback">Por favor ingrese un nombre válido (mínimo 3 caracteres).</div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts de Integración -->
<script>
$(document).ready(function () {

    // 1. Instancia de Toast por si helpers.js no está disponible
    const Toast = (typeof Swal !== 'undefined') ? Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    }) : null;

    // 2. Mostrar Notificaciones Flash del Servidor
    <?php if (session()->getFlashdata('success')): ?>
        if (Toast) Toast.fire({ icon: 'success', title: '<?= esc(session()->getFlashdata('success')) ?>' });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        if (Toast) Toast.fire({ icon: 'error', title: '<?= esc(session()->getFlashdata('error')) ?>' });
    <?php endif; ?>

    // 3. Inicializar DataTable
    const tablaData = $('#tablaCategorias').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        responsive: true,
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: 2 }
        ]
    });

    const modalElement = document.getElementById('modalCategoria');
    const modal = new bootstrap.Modal(modalElement);
    const formCategoria = document.getElementById('formCategoria');

    // 4. Modal para Crear Categoría
    $('#btnNuevaCategoria').on('click', function () {
        $('#modalCategoriaLabel').text('Nueva Categoría');
        $('#id_categoria').val('');
        $('#nombre').val('');
        $(formCategoria).removeClass('was-validated');
        modal.show();
    });

    // 5. DELEGACIÓN DE EVENTOS: Abrir Modal de Edición
    $('#tablaCategorias tbody').on('click', '.btn-editar', function (e) {
        e.preventDefault();
        
        const $btn = $(this).closest('.btn-editar');
        const id = $btn.attr('data-id');
        const nombre = $btn.attr('data-nombre');

        $('#modalCategoriaLabel').text('Editar Categoría');
        $('#id_categoria').val(id);
        $('#nombre').val(nombre);
        $(formCategoria).removeClass('was-validated');

        modal.show();
    });

    // 6. Guardar / Editar mediante AJAX
    $(formCategoria).on('submit', function (e) {
        e.preventDefault();

        if (!formCategoria.checkValidity()) {
            e.stopPropagation();
            $(formCategoria).addClass('was-validated');
            return;
        }

        $('#btnGuardar').prop('disabled', true);
        const formData = new FormData(formCategoria);

        fetch(formCategoria.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            modal.hide();
            if (Toast) {
                Toast.fire({
                    icon: 'success',
                    title: data.message || 'Operación realizada correctamente'
                });
            }
            setTimeout(() => location.reload(), 1200);
        })
        .catch(err => {
            console.error(err);
            if (Toast) {
                Toast.fire({
                    icon: 'error',
                    title: 'Ocurrió un error al procesar la solicitud'
                });
            }
        })
        .finally(() => {
            $('#btnGuardar').prop('disabled', false);
        });
    });

    // 7. DELEGACIÓN DE EVENTOS: Eliminar
    $('#tablaCategorias tbody').on('click', '.btn-eliminar', function (e) {
        e.preventDefault();

        const $btn = $(this).closest('.btn-eliminar');
        const id = $btn.attr('data-id');

        if (typeof Swal === 'undefined') {
            if (confirm('¿Estás seguro de eliminar este registro?')) {
                ejecutarEliminacion(id);
            }
            return;
        }

        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmColor: '#dc3545',
            cancelColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                ejecutarEliminacion(id);
            }
        });
    });

    // Función auxiliar para enviar la petición de eliminación
    function ejecutarEliminacion(id) {
        fetch('<?= base_url('categoria/eliminar') ?>/' + id, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (Toast) {
                Toast.fire({
                    icon: 'success',
                    title: data.message || 'Categoría eliminada con éxito'
                });
            }
            setTimeout(() => location.reload(), 1200);
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo eliminar la categoría.'
            });
        });
    }

});
</script>

<?= $this->endSection() ?>