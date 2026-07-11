<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../helpers/flash.php';

$db = Database::conectar();

// POST — nuevo préstamo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'nuevo') {
    $id_equipo   = $_POST['id_equipo'] ?? null;
    $fecha_est   = $_POST['fecha_devolucion_est'] ?: null;
    $observaciones = trim($_POST['observaciones'] ?? '');

    if (!$id_equipo) {
        flash_set('warning', 'Seleccioná un equipo.');
    } else {
        $db->prepare("
            INSERT INTO prestamos
                (id_equipo, id_usuario, id_tecnico, fecha_devolucion_est, observaciones)
            VALUES (?, ?, ?, ?, ?)
        ")->execute([$id_equipo, $_SESSION['id_usuario'], $_SESSION['id_usuario'], $fecha_est, $observaciones]);
        flash_set('success', 'Préstamo registrado correctamente.');
    }
    header('Location: /index.php?pagina=prestamos');
    exit;
}

// POST — devolver
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'devolver') {
    $id_prestamo = $_POST['id_prestamo'] ?? null;
    $db->prepare("
        UPDATE prestamos SET id_estado_prestamo = 2, fecha_devolucion_real = NOW()
        WHERE id_prestamo = ?
    ")->execute([$id_prestamo]);
    flash_set('success', 'Préstamo marcado como devuelto.');
    header('Location: /index.php?pagina=prestamos');
    exit;
}

// GET
$prestamos = $db->query("
    SELECT p.id_prestamo, p.fecha_prestamo, p.fecha_devolucion_est,
           p.fecha_devolucion_real, p.observaciones, p.id_estado_prestamo,
           eq.identificador AS equipo,
           u.nombre AS usuario_nombre, u.apellido AS usuario_apellido,
           tec.nombre AS tecnico_nombre, tec.apellido AS tecnico_apellido,
           ep.nombre_estado AS estado
    FROM prestamos p
    JOIN equipos eq          ON eq.id_equipo = p.id_equipo
    JOIN usuarios u          ON u.id_usuario = p.id_usuario
    LEFT JOIN usuarios tec   ON tec.id_usuario = p.id_tecnico
    JOIN estados_prestamo ep ON ep.id_estado_prestamo = p.id_estado_prestamo
    ORDER BY p.fecha_prestamo DESC
")->fetchAll();

$equipos_disponibles = $db->query("
    SELECT e.id_equipo, e.identificador, te.nombre_tipo
    FROM equipos e
    JOIN estados_equipo ee ON ee.id_estado_equipo = e.id_estado_equipo
    JOIN tipos_equipo te   ON te.id_tipo_equipo = e.id_tipo_equipo
    WHERE ee.nombre_estado = 'Correcto'
    ORDER BY e.identificador
")->fetchAll();

$titulo = 'Préstamos';
$pagina_actual = 'prestamos';

ob_start();
require_once __DIR__ . '/../../views/prestamos.php';
$contenido = ob_get_clean();
require_once __DIR__ . '/../../views/layouts/base.php';