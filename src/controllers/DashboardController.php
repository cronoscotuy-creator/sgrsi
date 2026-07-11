<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../helpers/flash.php';

$db = Database::conectar();

// Métricas rápidas
$stmt = $db->query("SELECT COUNT(*) AS total FROM equipos");
$total_equipos = $stmt->fetch()['total'];

$stmt = $db->query("
    SELECT COUNT(*) AS total FROM tickets t
    JOIN estados_ticket et ON et.id_estado_ticket = t.id_estado_ticket
    WHERE et.nombre_estado != 'Resuelto'
");
$total_tickets_abiertos = $stmt->fetch()['total'];

$stmt = $db->query("
    SELECT COUNT(*) AS total FROM prestamos p
    JOIN estados_prestamo ep ON ep.id_estado_prestamo = p.id_estado_prestamo
    WHERE ep.nombre_estado = 'Activo'
");
$total_prestamos_activos = $stmt->fetch()['total'];

// Equipos por estado
$stmt = $db->query("
    SELECT ee.nombre_estado, ee.color_hex, COUNT(*) AS cantidad
    FROM equipos e
    JOIN estados_equipo ee ON ee.id_estado_equipo = e.id_estado_equipo
    GROUP BY ee.id_estado_equipo
    ORDER BY ee.id_estado_equipo
");
$estados_equipos = $stmt->fetchAll();

// Tickets activos prioritarios
$stmt = $db->query("
    SELECT t.id_ticket, t.incidente, t.fecha_apertura,
           p.nombre AS prioridad, p.color_hex AS color_prioridad,
           et.nombre_estado AS estado,
           l.nombre AS laboratorio
    FROM tickets t
    JOIN prioridades p ON p.id_prioridad = t.id_prioridad
    JOIN estados_ticket et ON et.id_estado_ticket = t.id_estado_ticket
    LEFT JOIN laboratorios l ON l.id_laboratorio = t.id_laboratorio
    WHERE et.nombre_estado != 'Resuelto'
    ORDER BY p.orden ASC, t.fecha_apertura ASC
    LIMIT 5
");
$tickets_activos = $stmt->fetchAll();

// Solicitudes pendientes
$stmt = $db->query("
    SELECT s.id_solicitud, s.descripcion, s.fecha_solicitud,
           ts.nombre AS tipo, es.nombre_estado AS estado
    FROM solicitudes s
    JOIN tipos_solicitud ts ON ts.id_tipo_solicitud = s.id_tipo_solicitud
    JOIN estados_solicitud es ON es.id_estado_solicitud = s.id_estado_solicitud
    WHERE es.nombre_estado = 'Pendiente'
    ORDER BY s.fecha_solicitud ASC
    LIMIT 5
");
$solicitudes_pendientes = $stmt->fetchAll();

$titulo = 'Inicio';
$pagina_actual = 'dashboard';

ob_start();
require_once __DIR__ . '/../../views/dashboard.php';
$contenido = ob_get_clean();

require_once __DIR__ . '/../../views/layouts/base.php';