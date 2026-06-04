<?php
// Datos de conexión (en producción se usan variables de entorno)
$servidor_db = '127.0.0.1';
$nombre_db   = 'sgrsi';
$usuario_db  = 'root';
$clave_db    = '';
$charset     = 'utf8mb4';

// Cadena de conexión DSN para PDO
$dsn = "mysql:host=$servidor_db;dbname=$nombre_db;charset=$charset";
$opciones_pdo = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en errores SQL
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retorna arrays asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Usa preparación nativa del motor (más seguro)
];

try {
    // Creamos la instancia de conexión
    $conexion = new PDO($dsn, $usuario_db, $clave_db, $opciones_pdo);
} catch (PDOException $error) {
    // En desarrollo mostramos el error. En producción se loguea en archivo.
    die("❌ Error de conexión a la base de datos: " . htmlspecialchars($error->getMessage()));
}
?>