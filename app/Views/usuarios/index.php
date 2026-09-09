<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Gestión de Usuarios
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Administración de Usuarios
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
            <h3 class="card-title m-0 fw-bold">Lista de Usuarios</h3>
            <button type="button" class="btn btn-primary btn-sm" id="btnNuevoUsuario">
                <i class="bi bi-person-plus-fill me-1"></i> Nuevo Usuario
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle w-100" id="tablaUsuarios">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 60px;" class="text-center">ID</th>
                            <th>Nombre</th>
                            <th>Correo Electrónico</th>
                            <th class="text-center">Rol</th>
                            <th class="text-center">Estado</th>
                            <th style="width: 120px;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($usuarios)): ?>
                            <?php foreach ($usuarios as $usr): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= esc($usr['id_usuario']) ?></td>
                                    <td><?= esc($usr['nombre']) ?></td>
                                    <td><?= esc($usr['correo']) ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $usr['rol'] === 'administrador' ? 'primary' : 'info' ?>">
                                            <?= ucfirst(esc($usr['rol'])) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $usr['estado'] ? 'success' : 'danger' ?>">
                                            <?= $usr['estado'] ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" 
                                                class="btn btn-warning btn-sm btn-editar me-1" 
                                                data-id="<?= $usr['id_usuario'] ?>" 
                                                data-nombre="<?= esc($usr['nombre']) ?>"
                                                data-correo="<?= esc($usr['correo']) ?>"
                                                data-rol="<?= esc($usr['rol']) ?>"
                                                data-estado="<?= $usr['estado'] ?>"
                                                title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm btn-eliminar" 
                                                data-id="<?= $usr['id_usuario'] ?>"
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
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formUsuario" method="POST" action="<?= base_url('usuario/guardar') ?>">
                <?= csrf_field() ?>
                <input type="hidden" id="id_usuario" name="id_usuario" value="">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUsuarioLabel">Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre Completo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required minlength="3" maxlength="100" placeholder="Ej. Juan Pérez">
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label fw-bold">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="correo" name="correo" required maxlength="100" placeholder="correo@ejemplo.com">
                    </div>

                    <div class="mb-3">
                        <label for="clave" class="form-label fw-bold" id="lblClave">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="clave" name="clave" minlength="6" placeholder="Mínimo 6 caracteres">
                        <small class="text-muted d-none" id="txtHelpClave">Dejar en blanco si no se desea modificar la contraseña actual.</small>
                    </div>

                    <div class="mb-3">
                        <label for="rol" class="form-label fw-bold">Rol de Usuario <span class="text-danger">*</span></label>
                        <select class="form-select" id="rol" name="rol" required>
                            <option value="" disabled selected>Seleccione un rol</option>
                            <option value="administrador">Administrador</option>
                            <option value="encargado">Encargado</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="estado" class="form-label fw-bold">Estado</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts de Integración -->
<script>
$(document).ready(function () {
    const Toast = (typeof Swal !== 'undefined') ? Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    }) : null;

    // Notificaciones Flash del Servidor
    <?php if (session()->getFlashdata('success')): ?>
        if (Toast) Toast.fire({ icon: 'success', title: '<?= esc(session()->getFlashdata('success')) ?>' });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        if (Toast) Toast.fire({ icon: 'error', title: '<?= esc(session()->getFlashdata('error')) ?>' });
    <?php endif; ?>

    // Inicializar DataTable
    const tablaData = $('#tablaUsuarios').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        responsive: true,
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: 5 }
        ]
    });

    const modalElement = document.getElementById('modalUsuario');
    const modal = new bootstrap.Modal(modalElement);
    const formUsuario = document.getElementById('formUsuario');

    // Abrir Modal para Crear Usuario
    $('#btnNuevoUsuario').on('click', function () {
        $('#modalUsuarioLabel').text('Nuevo Usuario');
        $('#id_usuario').val('');
        $('#nombre').val('');
        $('#correo').val('');
        $('#clave').val('').prop('required', true);
        $('#lblClave').find('span').removeClass('d-none');
        $('#txtHelpClave').addClass('d-none');
        $('#rol').val('');
        $('#estado').val('1');
        $(formUsuario).removeClass('was-validated');
        modal.show();
    });

    // Delegación de Eventos: Abrir Modal de Edición
    $('#tablaUsuarios tbody').on('click', '.btn-editar', function (e) {
        e.preventDefault();
        const $btn = $(this).closest('.btn-editar');
        
        $('#modalUsuarioLabel').text('Editar Usuario');
        $('#id_usuario').val($btn.attr('data-id'));
        $('#nombre').val($btn.attr('data-nombre'));
        $('#correo').val($btn.attr('data-correo'));
        $('#clave').val('').prop('required', false);
        $('#lblClave').find('span').addClass('d-none');
        $('#txtHelpClave').removeClass('d-none');
        $('#rol').val($btn.attr('data-rol'));
        $('#estado').val($btn.attr('data-estado'));
        
        $(formUsuario).removeClass('was-validated');
        modal.show();
    });

    // Guardar / Editar mediante AJAX
    $(formUsuario).on('submit', function (e) {
        e.preventDefault();
        if (!formUsuario.checkValidity()) {
            e.stopPropagation();
            $(formUsuario).addClass('was-validated');
            return;
        }

        $('#btnGuardar').prop('disabled', true);
        const formData = new FormData(formUsuario);

        fetch(formUsuario.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => {
            return res.json().then(data => ({ status: res.status, body: data }));
        })
        .then(res => {
            if (res.status !== 200) {
                throw new Error(res.body.message || 'Error en la petición');
            }
            modal.hide();
            if (Toast) {
                Toast.fire({
                    icon: 'success',
                    title: res.body.message || 'Operación realizada correctamente'
                });
            }
            setTimeout(() => location.reload(), 1200);
        })
        .catch(err => {
            console.error(err);
            if (Toast) {
                Toast.fire({
                    icon: 'error',
                    title: err.message || 'Ocurrió un error al procesar la solicitud'
                });
            }
        })
        .finally(() => {
            $('#btnGuardar').prop('disabled', false);
        });
    });

    // Delegación de Eventos: Eliminar
    $('#tablaUsuarios tbody').on('click', '.btn-eliminar', function (e) {
        e.preventDefault();
        const $btn = $(this).closest('.btn-eliminar');
        const id = $btn.attr('data-id');

        if (typeof Swal === 'undefined') {
            if (confirm('¿Estás seguro de eliminar este usuario?')) {
                ejecutarEliminacion(id);
            }
            return;
        }

        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción eliminará al usuario seleccionado.',
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

    function ejecutarEliminacion(id) {
        fetch('<?= base_url('usuario/eliminar') ?>/' + id, {
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
                    title: data.message || 'Usuario eliminado con éxito'
                });
            }
            setTimeout(() => location.reload(), 1200);
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo eliminar el usuario.'
            });
        });
    }
});
</script>
<?= $this->endSection() ?>