<?php
// Iniciamos sesión para mantener el estado del usuario entre peticiones
session_start();

// Cargamos configuración global y conexión a la base de datos
require_once 'configuracion/constantes.php';
require_once 'configuracion/conexion_db.php';

// Obtenemos la página solicitada vía URL. Si no viene, cargamos 'panel' por defecto
$pagina_solicitada = $_GET['pagina'] ?? 'panel';
$paginas_permitidas = ['login', 'panel', 'laboratorios', 'equipos', 'tickets'];

// Validación de seguridad: si la ruta no existe en nuestra lista, redirigimos al panel
if (!in_array($pagina_solicitada, $paginas_permitidas)) {
    $pagina_solicitada = 'panel';
}

// Incluimos la cabecera (HTML5, Bootstrap, jQuery, barra de navegación semántica)
include 'plantillas/encabezado.php';

// Enrutador básico: según la página, cargamos el módulo correspondiente
switch ($pagina_solicitada) {
    case 'login':
        include 'modulos/auth/login.php';
        break;
    case 'panel':
        include 'modulos/panel/index.php';
        break;
    // Aquí se irán agregando los casos a medida que desarrollen cada RF
    default:
        echo '<div class="alert alert-warning">🚧 Módulo en construcción</div>';
}

// Cerramos la estructura HTML con el pie de página y scripts globales
include 'plantillas/pie_pagina.php';
?>