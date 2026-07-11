<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../helpers/flash.php';

if (!in_array($_SESSION['rol'], ['administrador', 'tecnico'])) {
    flash_set('danger', 'No tenés permisos para acceder a métricas.');
    header('Location: /index.php?pagina=dashboard');
    exit;
}

$db = Database::conectar();

$equipos_por_estado = $db->query("
    SELECT ee.nombre_estado, ee.color_hex, COUNT(*) AS cantidad
    FROM equipos e
    JOIN estados_equipo ee ON ee.id_estado_equipo = e.id_estado_equipo
    GROUP BY ee.id_estado_equipo ORDER BY ee.id_estado_equipo
")->fetchAll();

$tickets_por_estado = $db->query("
    SELECT et.nombre_estado, COUNT(*) AS cantidad
    FROM tickets t
    JOIN estados_ticket et ON et.id_estado_ticket = t.id_estado_ticket
    GROUP BY et.id_estado_ticket ORDER BY et.id_estado_ticket
")->fetchAll();

$tickets_por_prioridad = $db->query("
    SELECT p.nombre, p.color_hex, COUNT(*) AS cantidad
    FROM tickets t
    JOIN prioridades p ON p.id_prioridad = t.id_prioridad
    GROUP BY p.id_prioridad ORDER BY p.orden
")->fetchAll();

$equipos_mas_fallados = $db->query("
    SELECT eq.identificador, COUNT(*) AS cantidad_tickets
    FROM tickets t
    JOIN equipos eq ON eq.id_equipo = t.id_equipo
    GROUP BY t.id_equipo
    ORDER BY cantidad_tickets DESC
    LIMIT 5
")->fetchAll();

$solicitudes_por_tipo = $db->query("
    SELECT ts.nombre, COUNT(*) AS cantidad
    FROM solicitudes s
    JOIN tipos_solicitud ts ON ts.id_tipo_solicitud = s.id_tipo_solicitud
    GROUP BY s.id_tipo_solicitud ORDER BY cantidad DESC
")->fetchAll();

$titulo = 'Métricas';
$pagina_actual = 'metricas';

ob_start();
require_once __DIR__ . '/../../views/metricas.php';
$contenido = ob_get_clean();
require_once __DIR__ . '/../../views/layouts/base.php';