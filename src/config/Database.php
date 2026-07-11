<?php
class Database {
    private static $conexion = null;

    public static function conectar(): PDO {
        if (self::$conexion === null) {
            $host     = getenv('DB_HOST') ?: 'db';
            $usuario  = getenv('DB_USER') ?: 'sgrsi_user';
            $clave    = getenv('DB_PASSWORD') ?: 'sgrsi2026';
            $base     = getenv('DB_NAME') ?: 'sgrsi_db';

            try {
                self::$conexion = new PDO(
                    "mysql:host=$host;dbname=$base;charset=utf8mb4",
                    $usuario,
                    $clave,
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$conexion;
    }
}