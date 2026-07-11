<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../helpers/flash.php';

$db = Database::conectar();

// POST — nuevo equipo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'nuevo') {
    $identificador  = trim($_POST['identificador'] ?? '');
    $id_tipo_equipo = $_POST['id_tipo_equipo'] ?? null;
    $marca          = trim($_POST['marca'] ?? '');
    $modelo         = trim($_POST['modelo'] ?? '');
    $id_estado      = $_POST['id_estado_equipo'] ?? null;
    $id_laboratorio = $_POST['id_laboratorio'] ?: null;
    $observaciones  = trim($_POST['observaciones'] ?? '');

    if (!$identificador || !$id_tipo_equipo || !$id_estado) {
        flash_set('warning', 'Completá los campos obligatorios.');
    } else {
        $stmt = $db->prepare("
            INSERT INTO equipos
                (identificador, id_tipo_equipo, marca, modelo, id_estado_equipo, id_laboratorio, observaciones)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$identificador, $id_tipo_equipo, $marca, $modelo, $id_estado, $id_laboratorio, $observaciones]);
        flash_set('success', "Equipo \"$identificador\" agregado correctamente.");
    }
    header('Location: /index.php?pagina=inventario');
    exit;
}

// POST — actualizar estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'estado') {
    $id_equipo    = $_POST['id_equipo'] ?? null;
    $nuevo_estado = $_POST['id_estado_equipo'] ?? null;
    $descripcion  = trim($_POST['descripcion'] ?? '');

    $db->prepare("UPDATE equipos SET id_estado_equipo = ? WHERE id_equipo = ?")
       ->execute([$nuevo_estado, $id_equipo]);

    $db->prepare("
        INSERT INTO historial_equipos (id_equipo, id_usuario, id_estado_equipo, descripcion)
        VALUES (?, ?, ?, ?)
    ")->execute([$id_equipo, $_SESSION['id_usuario'], $nuevo_estado, $descripcion]);

    flash_set('success', 'Estado del equipo actualizado.');
    header('Location: /index.php?pagina=inventario');
    exit;
}

// GET — listar equipos
$equipos = $db->query("
    SELECT e.id_equipo, e.identificador, e.marca, e.modelo, e.observaciones,
           te.nombre_tipo AS tipo,
           ee.nombre_estado AS estado, ee.color_hex,
           l.nombre AS laboratorio
    FROM equipos e
    JOIN tipos_equipo te   ON te.id_tipo_equipo = e.id_tipo_equipo
    JOIN estados_equipo ee ON ee.id_estado_equipo = e.id_estado_equipo
    LEFT JOIN laboratorios l ON l.id_laboratorio = e.id_laboratorio
    ORDER BY e.identificador
")->fetchAll();

$tipos_equipo  = $db->query("SELECT * FROM tipos_equipo ORDER BY nombre_tipo")->fetchAll();
$estados_equipo = $db->query("SELECT * FROM estados_equipo ORDER BY id_estado_equipo")->fetchAll();
$laboratorios  = $db->query("SELECT * FROM laboratorios WHERE activo = 1 ORDER BY nombre")->fetchAll();

$titulo = 'Inventario';
$pagina_actual = 'inventario';

ob_start();
require_once __DIR__ . '/../../views/inventario.php';
$contenido = ob_get_clean();
require_once __DIR__ . '/../../views/layouts/base.php';