https://github.com/cronoscotuy-creator/sgrsi.git

Alumnos de 3MF del turno NOCTURNO del Instituto Tecnológico de Informática- Nombre fantasía de la empresa y slogan "CRONOS" - "CHANGE ON TIME".

# SGRSI - Sistema de Gestión de Recursos y Soporte ITI
Proyecto de fin de año | Equipo Cronos | ITI 2026

## 🚀 Inicio Rápido
1. Clonar repositorio: `git clone <url-repo>`
2. Importar base de datos: `mysql -u root -p < base_datos/esquema.sql`
3. Ajustar credenciales en `configuracion/conexion_db.php`
4. Servir entorno local: `php -S localhost:8000`
5. Acceder en navegador: `http://localhost:8000/index.php?pagina=login`

## 📁 Estructura
- `configuracion/`: Conexión PDO y constantes globales
- `plantillas/`: Encabezado y pie de página reutilizables
- `modulos/`: Lógica y vistas por funcionalidad (RF-01 a RF-06)
- `assets/`: CSS, JS, imágenes
- `documentacion/`: Entregables académicos (IEEE 830, FODA, DER, Gantt)

## 🛠️ Stack Tecnológico
PHP 8+, MySQL 8+, Bootstrap 5, jQuery 3.7, AES-256-GCM