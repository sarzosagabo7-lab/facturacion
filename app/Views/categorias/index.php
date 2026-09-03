```php
<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
Gestión de Categorías
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Administración de Categorías
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <!-- =====================================================
         CARD PRINCIPAL
    ====================================================== -->
    <div class="card shadow-sm">

        <!-- HEADER -->
        <div class="card-header d-flex align-items-center justify-content-between">

            <h3 class="card-title m-0 fw-bold">
                <i class="bi bi-tags-fill me-2"></i>
                Lista de Categorías
            </h3>

            <!-- NUEVA CATEGORÍA -->
            <button type="button"
                    class="btn btn-primary btn-sm"
                    id="btnNuevaCategoria">

                <i class="bi bi-plus-lg me-1"></i>
                Nueva Categoría

            </button>

        </div>


        <!-- =================================================
             BODY
        ================================================== -->
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped table-hover align-middle w-100"
                       id="tablaCategorias">

                    <!-- =================================================
                         CABECERA DE TABLA
                    ================================================== -->
                    <thead class="table-dark">

                        <tr>

                            <th class="text-center" style="width: 80px;">
                                ID
                            </th>

                            <th>
                                Nombre de la Categoría
                            </th>

                            <th class="text-center" style="width: 140px;">
                                Acciones
                            </th>

                        </tr>

                    </thead>


                    <!-- =================================================
                         CUERPO DE TABLA
                    ================================================== -->
                    <tbody>

                        <?php if (!empty($categorias)): ?>

                            <?php foreach ($categorias as $cat): ?>

                                <tr>

                                    <!-- ID REAL DE LA BASE DE DATOS -->
                                    <td class="text-center fw-bold">

                                        <?= esc($cat['id_categoria']) ?>

                                    </td>


                                    <!-- NOMBRE -->
                                    <td>

                                        <?= esc($cat['nombre']) ?>

                                    </td>


                                    <!-- ACCIONES -->
                                    <td class="text-center">

                                        <!-- =================================
                                             BOTÓN EDITAR
                                        ================================== -->
                                        <button type="button"
                                                class="btn btn-warning btn-sm btn-editar me-1"
                                                data-id="<?= esc($cat['id_categoria']) ?>"
                                                data-nombre="<?= esc($cat['nombre']) ?>"
                                                title="Editar">

                                            <i class="bi bi-pencil-square"></i>

                                        </button>


                                        <!-- =================================
                                             BOTÓN ELIMINAR
                                        ================================== -->
                                        <a href="<?= base_url('categorias/eliminar/' . $cat['id_categoria']) ?>"
                                           class="btn btn-danger btn-sm btn-eliminar"
                                           data-nombre="<?= esc($cat['nombre']) ?>"
                                           title="Eliminar">

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


<!-- =========================================================
     MODAL NUEVA / EDITAR CATEGORÍA
========================================================== -->

<div class="modal fade"
     id="modalCategoria"
     tabindex="-1"
     aria-labelledby="modalCategoriaLabel"
     aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <!-- FORMULARIO -->
            <form id="formCategoria"
                  method="POST"
                  action="<?= base_url('categorias/guardar') ?>">

                <?= csrf_field() ?>


                <!-- =============================================
                     HEADER DEL MODAL
                ============================================== -->
                <div class="modal-header">

                    <h5 class="modal-title fw-bold"
                        id="modalCategoriaLabel">

                        <i class="bi bi-plus-circle me-2"></i>
                        Nueva Categoría

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar">

                    </button>

                </div>


                <!-- =============================================
                     BODY DEL MODAL
                ============================================== -->
                <div class="modal-body">

                    <div class="mb-3">

                        <label for="nombre"
                               class="form-label fw-bold">

                            Nombre de la Categoría

                            <span class="text-danger">*</span>

                        </label>


                        <input type="text"
                               class="form-control"
                               id="nombre"
                               name="nombre"
                               required
                               minlength="3"
                               maxlength="50"
                               placeholder="Ej. Lácteos, Electrónica...">


                        <div class="invalid-feedback">

                            Por favor ingrese un nombre válido
                            de mínimo 3 caracteres.

                        </div>

                    </div>

                </div>


                <!-- =============================================
                     FOOTER DEL MODAL
                ============================================== -->
                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        <i class="bi bi-x-lg me-1"></i>
                        Cancelar

                    </button>


                    <button type="submit"
                            class="btn btn-primary"
                            id="btnGuardar">

                        <i class="bi bi-save me-1"></i>
                        Guardar Categoría

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- =========================================================
     DATATABLES
========================================================== -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


<!-- CSS DATATABLES -->
<link rel="stylesheet"
      href="https://cdn.datatables.net/2.3.3/css/dataTables.dataTables.min.css">


<!-- JS DATATABLES -->
<script src="https://cdn.datatables.net/2.3.3/js/dataTables.min.js"></script>


<!-- =========================================================
     SWEETALERT2
========================================================== -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- =========================================================
     JAVASCRIPT
========================================================== -->

<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =========================================================
       ELEMENTOS DEL DOM
    ========================================================== */

    const formCategoria =
        document.getElementById('formCategoria');

    const modalElement =
        document.getElementById('modalCategoria');

    const modal =
        new bootstrap.Modal(modalElement);

    const modalLabel =
        document.getElementById('modalCategoriaLabel');

    const inputNombre =
        document.getElementById('nombre');

    const btnNuevaCategoria =
        document.getElementById('btnNuevaCategoria');

    const btnGuardar =
        document.getElementById('btnGuardar');


    /* =========================================================
       URL PARA GUARDAR
    ========================================================== */

    const urlGuardar =
        '<?= base_url('categorias/guardar') ?>';


    /* =========================================================
       TOAST DE SWEETALERT2
    ========================================================== */

    const Toast = Swal.mixin({

        toast: true,

        position: 'top-end',

        showConfirmButton: false,

        timer: 3000,

        timerProgressBar: true,

        didOpen: function (toast) {

            toast.addEventListener(
                'mouseenter',
                Swal.stopTimer
            );

            toast.addEventListener(
                'mouseleave',
                Swal.resumeTimer
            );

        }

    });


    /* =========================================================
       DATATABLES
    ========================================================== */

    const tabla =
        new DataTable('#tablaCategorias', {

        language: {

            emptyTable:
                'No hay categorías registradas',

            info:
                'Mostrando _START_ a _END_ de _TOTAL_ categorías',

            infoEmpty:
                'Mostrando 0 a 0 de 0 categorías',

            infoFiltered:
                '(filtrado de _MAX_ categorías)',

            lengthMenu:
                'Mostrar _MENU_ registros',

            loadingRecords:
                'Cargando...',

            processing:
                'Procesando...',

            search:
                'Buscar:',

            zeroRecords:
                'No se encontraron categorías',

            paginate: {

                first:
                    'Primero',

                last:
                    'Último',

                next:
                    'Siguiente',

                previous:
                    'Anterior'

            }

        },


        /* =====================================================
           CANTIDAD DE REGISTROS
        ====================================================== */

        pageLength: 10,


        lengthMenu: [

            [5, 10, 25, 50, -1],

            [5, 10, 25, 50, 'Todos']

        ],


        /* =====================================================
           ORDENAR POR ID
        ====================================================== */

        order: [

            [0, 'asc']

        ],


        /* =====================================================
           CONFIGURACIÓN DE COLUMNAS
        ====================================================== */

        columnDefs: [

            {
                /*
                 * ID REAL DE LA BASE DE DATOS
                 *
                 * DataTables NO modifica este valor.
                 */
                targets: 0,

                className:
                    'text-center',

                type:
                    'num'

            },


            {
                /*
                 * COLUMNA ACCIONES
                 */
                targets: 2,

                orderable:
                    false,

                searchable:
                    false,

                className:
                    'text-center'

            }

        ],


        /* =====================================================
           DISEÑO DE DATATABLES
        ====================================================== */

        layout: {

            topStart:
                'pageLength',

            topEnd:
                'search',

            bottomStart:
                'info',

            bottomEnd:
                'paging'

        }

    });


    /* =========================================================
       BOTÓN NUEVA CATEGORÍA
    ========================================================== */

    btnNuevaCategoria.addEventListener(
        'click',
        function () {


            /* Cambiar título */

            modalLabel.innerHTML =
                '<i class="bi bi-plus-circle me-2"></i>' +
                'Nueva Categoría';


            /* Acción del formulario */

            formCategoria.action =
                urlGuardar;


            /* Limpiar campo */

            inputNombre.value =
                '';


            /* Quitar validación */

            formCategoria.classList.remove(
                'was-validated'
            );


            /* Mostrar modal */

            modal.show();


            /* Colocar cursor */

            setTimeout(function () {

                inputNombre.focus();

            }, 500);

        }
    );


    /* =========================================================
       BOTONES EDITAR
    ========================================================== */

    document.querySelectorAll('.btn-editar')
        .forEach(function (button) {


            button.addEventListener(
                'click',
                function () {


                    /* Obtener ID */

                    const id =
                        this.getAttribute('data-id');


                    /* Obtener nombre */

                    const nombre =
                        this.getAttribute('data-nombre');


                    /* Cambiar título */

                    modalLabel.innerHTML =
                        '<i class="bi bi-pencil-square me-2"></i>' +
                        'Editar Categoría';


                    /* Cambiar acción */

                    formCategoria.action =
                        '<?= base_url('categorias/actualizar/') ?>' +
                        '/' +
                        id;


                    /* Cargar nombre */

                    inputNombre.value =
                        nombre;


                    /* Quitar validación */

                    formCategoria.classList.remove(
                        'was-validated'
                    );


                    /* Mostrar modal */

                    modal.show();


                    /* Seleccionar texto */

                    setTimeout(function () {

                        inputNombre.focus();

                        inputNombre.select();

                    }, 500);

                }
            );

        });


    /* =========================================================
       CERRAR MODAL
    ========================================================== */

    modalElement.addEventListener(
        'hidden.bs.modal',
        function () {


            /* Restaurar título */

            modalLabel.innerHTML =
                '<i class="bi bi-plus-circle me-2"></i>' +
                'Nueva Categoría';


            /* Restaurar acción */

            formCategoria.action =
                urlGuardar;


            /* Limpiar campo */

            inputNombre.value =
                '';


            /* Quitar validación */

            formCategoria.classList.remove(
                'was-validated'
            );

        }
    );


    /* =========================================================
       VALIDACIÓN Y GUARDADO
    ========================================================== */

    formCategoria.addEventListener(
        'submit',
        function (event) {


            /* Evitar envío inmediato */

            event.preventDefault();

            event.stopPropagation();


            /* Validar formulario */

            if (!formCategoria.checkValidity()) {


                formCategoria.classList.add(
                    'was-validated'
                );


                Swal.fire({

                    icon:
                        'warning',

                    title:
                        'Datos incompletos',

                    text:
                        'Por favor complete correctamente el nombre de la categoría.',

                    confirmButtonText:
                        'Aceptar',

                    confirmButtonColor:
                        '#0d6efd'

                });


                return;

            }


            formCategoria.classList.add(
                'was-validated'
            );


            /* Determinar si es edición */

            const esEdicion =
                formCategoria.action.includes(
                    '/actualizar/'
                );


            /* =================================================
               CONFIRMACIÓN
            ================================================== */

            Swal.fire({

                icon:
                    'question',

                title:
                    esEdicion
                        ? '¿Actualizar categoría?'
                        : '¿Guardar categoría?',

                text:
                    esEdicion
                        ? 'Se actualizará la información de la categoría.'
                        : 'La nueva categoría será registrada en el sistema.',

                showCancelButton:
                    true,

                confirmButtonText:
                    esEdicion
                        ? 'Sí, actualizar'
                        : 'Sí, guardar',

                cancelButtonText:
                    'Cancelar',

                confirmButtonColor:
                    '#0d6efd',

                cancelButtonColor:
                    '#6c757d',

                reverseButtons:
                    true

            }).then(function (result) {


                if (result.isConfirmed) {


                    /* Desactivar botón */

                    btnGuardar.disabled =
                        true;


                    /* Mostrar cargando */

                    btnGuardar.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-1"></span>' +
                        'Guardando...';


                    /* Enviar formulario */

                    formCategoria.submit();

                }

            });

        }
    );


    /* =========================================================
       ELIMINAR CATEGORÍA
    ========================================================== */

    document.querySelectorAll('.btn-eliminar')
        .forEach(function (button) {


            button.addEventListener(
                'click',
                function (event) {


                    /* Evitar navegación */

                    event.preventDefault();


                    /* URL */

                    const url =
                        this.getAttribute('href');


                    /* Nombre */

                    const nombre =
                        this.getAttribute('data-nombre');


                    /* =================================================
                       SWEETALERT DE CONFIRMACIÓN
                    ================================================== */

                    Swal.fire({

                        icon:
                            'warning',

                        title:
                            '¿Eliminar categoría?',

                        html:
                            'La categoría <strong>' +
                            nombre +
                            '</strong> será eliminada.<br><br>' +

                            '<span class="text-danger">' +
                            'Esta acción no se puede deshacer.' +
                            '</span>',

                        showCancelButton:
                            true,

                        confirmButtonText:
                            '<i class="bi bi-trash me-1"></i>' +
                            'Sí, eliminar',

                        cancelButtonText:
                            'Cancelar',

                        confirmButtonColor:
                            '#dc3545',

                        cancelButtonColor:
                            '#6c757d',

                        reverseButtons:
                            true,

                        focusCancel:
                            true

                    }).then(function (result) {


                        /* Confirmar eliminación */

                        if (result.isConfirmed) {


                            /* Ir a la ruta */

                            window.location.href =
                                url;

                        }

                    });

                }
            );

        });


    /* =========================================================
       MENSAJE SUCCESS
    ========================================================== */

    <?php if (session()->getFlashdata('success')): ?>

        Toast.fire({

            icon:
                'success',

            title:
                <?= json_encode(session()->getFlashdata('success')) ?>

        });

    <?php endif; ?>


    /* =========================================================
       MENSAJE ERROR
    ========================================================== */

    <?php if (session()->getFlashdata('error')): ?>

        Toast.fire({

            icon:
                'error',

            title:
                <?= json_encode(session()->getFlashdata('error')) ?>

        });

    <?php endif; ?>


    /* =========================================================
       ERRORES DE VALIDACIÓN
    ========================================================== */

    <?php if (session()->getFlashdata('errors')): ?>

        Toast.fire({

            icon:
                'error',

            title:
                <?= json_encode(
                    implode(
                        ' | ',
                        session()->getFlashdata('errors')
                    )
                ) ?>

        });

    <?php endif; ?>


});

</script>


<?= $this->endSection() ?>
```
