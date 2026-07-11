<?php
require_once __DIR__ . '/../src/config/Database.php';

$db = Database::conectar();
$hash = password_hash('admin123', PASSWORD_DEFAULT);

$stmt = $db->prepare("UPDATE usuarios SET contrasena_hash = ?");
$stmt->execute([$hash]);

echo "Usuarios actualizados: " . $stmt->rowCount() . "<br>";
echo "Todos tienen contraseña: admin123";