<?php
// Páginas que no requieren sesión activa
$pagina = $_GET['pagina'] ?? 'login';
$paginas_publicas = ['login'];

if (!in_array($pagina, $paginas_publicas)) {
    require_once __DIR__ . '/../src/includes/proteccion.inc.php';
} else {
    // Solo para login: iniciar sesión con cookies seguras
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
}

require_once __DIR__ . '/../src/helpers/flash.php';

switch ($pagina) {
    case 'login':
    case 'logout':
        require_once __DIR__ . '/../src/controllers/AuthController.php';
        break;
    case 'dashboard':
        require_once __DIR__ . '/../src/controllers/DashboardController.php';
        break;
    case 'inventario':
        require_once __DIR__ . '/../src/controllers/InventarioController.php';
        break;
    case 'tickets':
        require_once __DIR__ . '/../src/controllers/TicketsController.php';
        break;
    case 'solicitudes':
        require_once __DIR__ . '/../src/controllers/SolicitudesController.php';
        break;
    case 'prestamos':
        require_once __DIR__ . '/../src/controllers/PrestamosController.php';
        break;
    case 'metricas':
        require_once __DIR__ . '/../src/controllers/MetricasController.php';
        break;
    case 'usuarios':
        require_once __DIR__ . '/../src/controllers/UsuariosController.php';
        break;
    default:
        http_response_code(404);
        echo "<h1>Página no encontrada</h1>";
        break;
}