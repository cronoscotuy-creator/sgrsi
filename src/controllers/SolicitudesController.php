<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../helpers/flash.php';

$db = Database::conectar();

// POST — nueva solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'nuevo') {
    $id_tipo      = $_POST['id_tipo_solicitud'] ?? null;
    $descripcion  = trim($_POST['descripcion'] ?? '');
    $id_lab       = $_POST['id_laboratorio'] ?: null;
    $fecha_nec    = $_POST['fecha_necesaria'] ?: null;

    if (!$id_tipo || !$descripcion) {
        flash_set('warning', 'Completá el tipo y la descripción.');
    } else {
        $db->prepare("
            INSERT INTO solicitudes
                (id_usuario, id_tipo_solicitud, id_laboratorio, descripcion, fecha_necesaria)
            VALUES (?, ?, ?, ?, ?)
        ")->execute([$_SESSION['id_usuario'], $id_tipo, $id_lab, $descripcion, $fecha_nec]);
        flash_set('success', 'Solicitud enviada correctamente.');
    }
    header('Location: /index.php?pagina=solicitudes');
    exit;
}

// POST — actualizar estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'estado') {
    $id_solicitud = $_POST['id_solicitud'] ?? null;
    $nuevo_estado = $_POST['id_estado_solicitud'] ?? null;
    $db->prepare("UPDATE solicitudes SET id_estado_solicitud = ? WHERE id_solicitud = ?")
       ->execute([$nuevo_estado, $id_solicitud]);
    flash_set('success', 'Solicitud actualizada.');
    header('Location: /index.php?pagina=solicitudes');
    exit;
}

// GET
$solicitudes = $db->query("
    SELECT s.id_solicitud, s.descripcion, s.fecha_solicitud, s.fecha_necesaria,
           s.id_estado_solicitud,
           u.nombre AS solicitante_nombre, u.apellido AS solicitante_apellido,
           ts.nombre AS tipo,
           l.nombre AS laboratorio,
           es.nombre_estado AS estado
    FROM solicitudes s
    JOIN usuarios u            ON u.id_usuario = s.id_usuario
    JOIN tipos_solicitud ts    ON ts.id_tipo_solicitud = s.id_tipo_solicitud
    LEFT JOIN laboratorios l   ON l.id_laboratorio = s.id_laboratorio
    JOIN estados_solicitud es  ON es.id_estado_solicitud = s.id_estado_solicitud
    ORDER BY s.fecha_solicitud DESC
")->fetchAll();

$tipos_solicitud   = $db->query("SELECT * FROM tipos_solicitud ORDER BY nombre")->fetchAll();
$estados_solicitud = $db->query("SELECT * FROM estados_solicitud ORDER BY id_estado_solicitud")->fetchAll();
$laboratorios      = $db->query("SELECT id_laboratorio, nombre, numero_salon FROM laboratorios WHERE activo = 1 ORDER BY nombre")->fetchAll();

$titulo = 'Solicitudes';
$pagina_actual = 'solicitudes';

ob_start();
require_once __DIR__ . '/../../views/solicitudes.php';
$contenido = ob_get_clean();
require_once __DIR__ . '/../../views/layouts/base.php';