<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../helpers/flash.php';

$db = Database::conectar();

// POST — nuevo ticket
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'nuevo') {
    $incidente      = trim($_POST['incidente'] ?? '');
    $incidente_otro = trim($_POST['incidente_otro'] ?? '');
    $descripcion    = trim($_POST['descripcion'] ?? '');
    $id_prioridad   = $_POST['id_prioridad'] ?? null;
    $id_equipo      = $_POST['id_equipo'] ?: null;
    $id_laboratorio = $_POST['id_laboratorio'] ?: null;

    if ($incidente === 'Otro' && $incidente_otro) {
        $incidente = $incidente_otro;
    }

    if (!$incidente || !$id_prioridad) {
        flash_set('warning', 'Completá el tipo de incidente y la prioridad.');
    } else {
        $db->prepare("
            INSERT INTO tickets
                (id_usuario, id_equipo, id_laboratorio, incidente, descripcion, id_prioridad)
            VALUES (?, ?, ?, ?, ?, ?)
        ")->execute([$_SESSION['id_usuario'], $id_equipo, $id_laboratorio, $incidente, $descripcion, $id_prioridad]);
        flash_set('success', 'Ticket creado correctamente.');
    }
    header('Location: /index.php?pagina=tickets');
    exit;
}

// POST — actualizar estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'estado') {
    $id_ticket      = $_POST['id_ticket'] ?? null;
    $nuevo_estado   = $_POST['id_estado_ticket'] ?? null;
    $nota           = trim($_POST['nota_resolucion'] ?? '');

    if ($nuevo_estado == 3) {
        $db->prepare("
            UPDATE tickets
            SET id_estado_ticket = ?, id_tecnico = ?,
                nota_resolucion = ?, fecha_cierre = NOW()
            WHERE id_ticket = ?
        ")->execute([$nuevo_estado, $_SESSION['id_usuario'], $nota, $id_ticket]);
    } else {
        $db->prepare("
            UPDATE tickets
            SET id_estado_ticket = ?, id_tecnico = ?, nota_resolucion = ?
            WHERE id_ticket = ?
        ")->execute([$nuevo_estado, $_SESSION['id_usuario'], $nota, $id_ticket]);
    }
    flash_set('success', 'Ticket actualizado.');
    header('Location: /index.php?pagina=tickets');
    exit;
}

// GET — listar tickets
$tickets = $db->query("
    SELECT t.id_ticket, t.incidente, t.descripcion,
           t.fecha_apertura, t.nota_resolucion, t.id_estado_ticket,
           u.nombre AS solicitante_nombre, u.apellido AS solicitante_apellido,
           tec.nombre AS tecnico_nombre, tec.apellido AS tecnico_apellido,
           eq.identificador AS equipo,
           l.nombre AS laboratorio,
           p.nombre AS prioridad, p.color_hex AS color_prioridad,
           et.nombre_estado AS estado
    FROM tickets t
    JOIN usuarios u         ON u.id_usuario = t.id_usuario
    LEFT JOIN usuarios tec  ON tec.id_usuario = t.id_tecnico
    LEFT JOIN equipos eq    ON eq.id_equipo = t.id_equipo
    LEFT JOIN laboratorios l ON l.id_laboratorio = t.id_laboratorio
    JOIN prioridades p      ON p.id_prioridad = t.id_prioridad
    JOIN estados_ticket et  ON et.id_estado_ticket = t.id_estado_ticket
    ORDER BY p.orden ASC, t.fecha_apertura DESC
")->fetchAll();

$prioridades    = $db->query("SELECT * FROM prioridades ORDER BY orden")->fetchAll();
$estados_ticket = $db->query("SELECT * FROM estados_ticket ORDER BY id_estado_ticket")->fetchAll();
$equipos        = $db->query("SELECT id_equipo, identificador FROM equipos ORDER BY identificador")->fetchAll();
$laboratorios   = $db->query("SELECT id_laboratorio, nombre, numero_salon FROM laboratorios WHERE activo = 1 ORDER BY nombre")->fetchAll();

$incidentes_comunes = ['Problema de hardware','Problema de software','Faltante','Equipo dañado','Solicitud de mantenimiento','Otro'];

$titulo = 'Mesa de Ayuda';
$pagina_actual = 'tickets';

ob_start();
require_once __DIR__ . '/../../views/tickets.php';
$contenido = ob_get_clean();
require_once __DIR__ . '/../../views/layouts/base.php';