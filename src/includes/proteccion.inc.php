<?php
/**
 * Archivo: proteccion.inc.php
 * Descripción: Control de sesiones y seguridad de cookies.
 * Grupo Cronos — SGRSI — ITI 2026
 */

// 1. Evitar acceso directo al archivo desde la URL
if (count(get_included_files()) === 1) {
    http_response_code(403);
    exit('Acceso denegado.');
}

// 2. Configurar cookies seguras ANTES de session_start
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// 3. Control de acceso
if (!isset($_SESSION['id_usuario'])) {
    header("Location: /index.php?pagina=login&expirado=1");
    exit();
}