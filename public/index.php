<?php
session_start();

require_once __DIR__ . '/../src/helpers/flash.php';

$pagina = $_GET['pagina'] ?? 'login';

$paginas_publicas = ['login', 'logout'];

if (!in_array($pagina, $paginas_publicas) && !isset($_SESSION['id_usuario'])) {
    header('Location: /index.php?pagina=login');
    exit;
}

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
    default:
        http_response_code(404);
        echo "<h1>Página no encontrada</h1>";
        break;
}