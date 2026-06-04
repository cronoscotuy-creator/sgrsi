// Esperamos a que el DOM esté completamente cargado antes de manipularlo
$(document).ready(function() {
    // Interceptamos el evento submit del formulario de login
    $('#formulario-login').on('submit', function(evento) {
        if (!validar_campos_login()) {
            evento.preventDefault(); // Bloqueamos el envío si hay errores
        }
    });
});

/**
 * Valida campos del formulario de inicio de sesión.
 * Retorna true si pasan la validación, false en caso contrario.
 */
function validar_campos_login() {
    let todo_correcto = true;

    // Limpiamos errores previos
    $('.mensaje-error').remove();
    $('.form-control').removeClass('campo-invalido');

    const correo = $('#correo').val().trim();
    const contrasena = $('#contrasena').val().trim();

    // Validación básica de formato de email
    if (correo.length < 5 || !correo.includes('@')) {
        mostrar_error('#correo', 'Por favor, ingresa un correo electrónico válido.');
        todo_correcto = false;
    }

    // Validación de longitud mínima de contraseña
    if (contrasena.length < 6) {
        mostrar_error('#contrasena', 'La contraseña debe tener al menos 6 caracteres.');
        todo_correcto = false;
    }

    return todo_correcto;
}

/**
 * Inyecta un mensaje de error visual junto al campo indicado.
 */
function mostrar_error(selector, mensaje) {
    $(selector).addClass('campo-invalido');
    $(selector).after(`<div class="mensaje-error">${mensaje}</div>`);
}