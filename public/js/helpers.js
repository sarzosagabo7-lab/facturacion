/**
 * helpers.js - Funciones globales para todo el sistema
 */

// 1. Instancia reusable para Notificaciones Toast
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

// 2. Función genérica para mostrar un modal de confirmación con Swal.fire
function confirmarAccion({
    title = '¿Estás seguro?',
    text = 'Esta acción no se puede deshacer.',
    icon = 'warning',
    confirmButtonText = 'Sí, continuar',
    cancelButtonText = 'Cancelar',
    onConfirm = () => {}
}) {
    Swal.fire({
        title,
        text,
        icon,
        showCancelButton: true,
        confirmColor: '#dc3545',
        cancelColor: '#6c757d',
        confirmButtonText,
        cancelButtonText,
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            onConfirm();
        }
    });
}

// 3. Configuración por defecto para DataTables en español
const dataTablesSpanish = {
    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
};