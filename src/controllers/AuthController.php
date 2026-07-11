<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../helpers/flash.php';

// Logout
if ($pagina === 'logout') {
    session_destroy();
    header('Location: /index.php?pagina=login');
    exit;
}

// Procesar POST (formulario enviado)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email     = trim($_POST['email'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    $db = Database::conectar();
    $stmt = $db->prepare("
        SELECT u.id_usuario, u.nombre, u.apellido, u.contrasena_hash, r.nombre_rol
        FROM usuarios u
        JOIN roles r ON r.id_rol = u.id_rol
        WHERE u.email = ? AND u.activo = 1
    ");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($contrasena, $usuario['contrasena_hash'])) {
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre']     = $usuario['nombre'] . ' ' . $usuario['apellido'];
        $_SESSION['rol']        = $usuario['nombre_rol'];
        header('Location: /index.php?pagina=dashboard');
        exit;
    }

    flash_set('danger', 'Email o contraseña incorrectos.');
    header('Location: /index.php?pagina=login');
    exit;
}

// GET — mostrar formulario
require_once __DIR__ . '/../../views/login.php';