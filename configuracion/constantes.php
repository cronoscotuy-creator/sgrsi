<?php
// Constantes globales del proyecto
define('RUTA_ABSOLUTA', dirname(__FILE__) . '/..');
define('URL_BASE', '/sgrsi'); // Ajustar según la carpeta donde sirvan el proyecto

// Configuración de seguridad para sesiones
ini_set('session.cookie_httponly', 1); // Evita acceso a cookies desde JS
ini_set('session.use_only_cookies', 1); // Fuerza uso de cookies, no URL
ini_set('session.cookie_samesite', 'Lax'); // Protección contra CSRF básico
?>