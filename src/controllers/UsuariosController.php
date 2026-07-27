<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../helpers/flash.php';

// Solo administradores
if ($_SESSION['rol'] !== 'administrador') {
    flash_set('danger', 'No tenés permisos para acceder a esta sección.');
    header('Location: /index.php?pagina=dashboard');
    exit;
}

$db = Database::conectar();

// POST — crear usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'nuevo') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $id_rol   = $_POST['id_rol'] ?? null;

    if (!$nombre || !$apellido || !$email || !$contrasena || !$id_rol) {
        flash_set('warning', 'Completá todos los campos obligatorios.');
    } else {
        // Verificar si el email ya existe
        $check = $db->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            flash_set('danger', 'Ya existe un usuario con ese email.');
        } else {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $db->prepare("
                INSERT INTO usuarios (nombre, apellido, email, contrasena_hash, id_rol)
                VALUES (?, ?, ?, ?, ?)
            ")->execute([$nombre, $apellido, $email, $hash, $id_rol]);
            flash_set('success', "Usuario $nombre $apellido creado correctamente.");
        }
    }
    header('Location: /index.php?pagina=usuarios');
    exit;
}

// POST — modificar rol
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'modificar') {
    $id_usuario = $_POST['id_usuario'] ?? null;
    $id_rol     = $_POST['id_rol'] ?? null;

    if ($id_usuario == $_SESSION['id_usuario']) {
        flash_set('danger', 'No podés modificar tu propio rol.');
    } else {
        $db->prepare("UPDATE usuarios SET id_rol = ? WHERE id_usuario = ?")
           ->execute([$id_rol, $id_usuario]);
        flash_set('success', 'Rol actualizado correctamente.');
    }
    header('Location: /index.php?pagina=usuarios');
    exit;
}

// POST — activar / desactivar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'toggle') {
    $id_usuario = $_POST['id_usuario'] ?? null;
    $activo     = $_POST['activo'] ?? 1;

    if ($id_usuario == $_SESSION['id_usuario']) {
        flash_set('danger', 'No podés desactivarte a vos mismo.');
    } else {
        $nuevo = $activo ? 0 : 1;
        $db->prepare("UPDATE usuarios SET activo = ? WHERE id_usuario = ?")
           ->execute([$nuevo, $id_usuario]);
        flash_set('success', $nuevo ? 'Usuario activado.' : 'Usuario desactivado.');
    }
    header('Location: /index.php?pagina=usuarios');
    exit;
}

// POST — buscar usuario
$busqueda = trim($_GET['buscar'] ?? '');
if ($busqueda) {
    $stmt = $db->prepare("
        SELECT u.id_usuario, u.nombre, u.apellido, u.email, u.activo,
               r.nombre_rol, r.id_rol
        FROM usuarios u
        JOIN roles r ON r.id_rol = u.id_rol
        WHERE u.nombre LIKE ? OR u.apellido LIKE ? OR u.email LIKE ?
        ORDER BY u.apellido, u.nombre
    ");
    $like = "%$busqueda%";
    $stmt->execute([$like, $like, $like]);
    $usuarios = $stmt->fetchAll();
} else {
    $usuarios = $db->query("
        SELECT u.id_usuario, u.nombre, u.apellido, u.email, u.activo,
               r.nombre_rol, r.id_rol
        FROM usuarios u
        JOIN roles r ON r.id_rol = u.id_rol
        ORDER BY u.apellido, u.nombre
    ")->fetchAll();
}

$roles = $db->query("SELECT * FROM roles ORDER BY id_rol")->fetchAll();

$titulo = 'Gestión de Usuarios';
$pagina_actual = 'usuarios';

ob_start();
require_once __DIR__ . '/../../views/usuarios.php';
$contenido = ob_get_clean();
require_once __DIR__ . '/../../views/layouts/base.php';