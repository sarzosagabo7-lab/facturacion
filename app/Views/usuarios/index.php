<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Gestión de Usuarios<?= $this->endSection() ?>
<?= $this->section('page_title') ?>Administración de Usuarios<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="card-title m-0 fw-bold"><i class="bi bi-shield-lock-fill me-2"></i>Lista de Usuarios</h3>
            <button type="button" class="btn btn-primary btn-sm" id="btnNuevoUsuario">
                <i class="bi bi-person-plus-fill me-1"></i>Nuevo Usuario
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle w-100" id="tablaUsuarios">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 60px;">ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th class="text-center">Rol</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center" style="width: 140px;">Acciones</th>
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
                                        <span class="badge <?= $usr['rol'] === 'administrador' ? 'bg-danger' : 'bg-info text-dark' ?>">
                                            <?= ucfirst(esc($usr['rol'])) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge <?= $usr['estado'] == 1 ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= $usr['estado'] == 1 ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning btn-sm btn-editar me-1"
                                            data-id="<?= esc($usr['id_usuario']) ?>"
                                            data-nombre="<?= esc($usr['nombre']) ?>"
                                            data-correo="<?= esc($usr['correo']) ?>"
                                            data-rol="<?= esc($usr['rol']) ?>"
                                            data-estado="<?= esc($usr['estado']) ?>" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <a href="<?= base_url('usuarios/eliminar/' . $usr['id_usuario']) ?>"
                                            class="btn btn-danger btn-sm btn-eliminar"
                                            data-nombre="<?= esc($usr['nombre']) ?>" title="Eliminar">
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

<!-- MODAL NUEVO / EDITAR USUARIO -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formUsuario" method="POST" action="<?= base_url('usuarios/guardar') ?>">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalUsuarioLabel">
                        <i class="bi bi-person-plus-fill me-2"></i>Nuevo Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre Completo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required minlength="3" maxlength="100" placeholder="Ej. Juan Pérez">
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label fw-bold">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="correo" name="correo" required placeholder="correo@ejemplo.com">
                    </div>
                    <div class="mb-3">
                        <label for="clave" class="form-label fw-bold" id="labelClave">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="clave" name="clave" required minlength="6" placeholder="******">
                        <small class="form-text text-muted d-none" id="helpClave">Déjelo en blanco si no desea modificar la clave actual.</small>
                    </div>
                    <div class="mb-3">
                        <label for="rol" class="form-label fw-bold">Rol <span class="text-danger">*</span></label>
                        <select class="form-select" id="rol" name="rol" required>
                            <option value="">Seleccione un rol...</option>
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
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const formUsuario = document.getElementById('formUsuario');
    const modalElement = document.getElementById('modalUsuario');
    const modal = new bootstrap.Modal(modalElement);
    const modalLabel = document.getElementById('modalUsuarioLabel');
    const inputNombre = document.getElementById('nombre');
    const inputCorreo = document.getElementById('correo');
    const inputClave = document.getElementById('clave');
    const labelClave = document.getElementById('labelClave');
    const helpClave = document.getElementById('helpClave');
    const inputRol = document.getElementById('rol');
    const inputEstado = document.getElementById('estado');
    const urlGuardar = '<?= base_url('usuarios/guardar') ?>';

    document.getElementById('btnNuevoUsuario').addEventListener('click', () => {
        modalLabel.innerHTML = '<i class="bi bi-person-plus-fill me-2"></i>Nuevo Usuario';
        formUsuario.action = urlGuardar;
        formUsuario.reset();
        inputClave.required = true;
        labelClave.innerHTML = 'Contraseña <span class="text-danger">*</span>';
        helpClave.classList.add('d-none');
        modal.show();
    });

    document.addEventListener('click', (e) => {
        const btnEditar = e.target.closest('.btn-editar');
        if (btnEditar) {
            const id = btnEditar.getAttribute('data-id');
            modalLabel.innerHTML = '<i class="bi bi-pencil-square me-2"></i>Editar Usuario';
            formUsuario.action = `<?= base_url('usuarios/actualizar/') ?>/${id}`;
            
            inputNombre.value = btnEditar.getAttribute('data-nombre');
            inputCorreo.value = btnEditar.getAttribute('data-correo');
            inputRol.value = btnEditar.getAttribute('data-rol');
            inputEstado.value = btnEditar.getAttribute('data-estado');
            
            inputClave.value = '';
            inputClave.required = false;
            labelClave.innerHTML = 'Nueva Contraseña';
            helpClave.classList.remove('d-none');

            modal.show();
        }

        const btnEliminar = e.target.closest('.btn-eliminar');
        if (btnEliminar) {
            e.preventDefault();
            const url = btnEliminar.getAttribute('href');
            const nombre = btnEliminar.getAttribute('data-nombre');

            Swal.fire({
                icon: 'warning',
                title: '¿Eliminar usuario?',
                text: `El usuario ${nombre} será eliminado.`,
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) window.location.href = url;
            });
        }
    });
});
</script>
<?= $this->endSection() ?>