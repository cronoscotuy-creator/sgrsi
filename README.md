https://github.com/cronoscotuy-creator/sgrsi.git

"CRONOS" - "CHANGE ON TIME".

# SGRSI - Sistema de Gestión de Recursos y Soporte ITI
Cronos | 2026

## Inicio Rápido
1. Clonar repositorio: `git clone <url-repo>`
2. Importar base de datos: `mysql -u root -p < base_datos/esquema.sql`
3. Ajustar credenciales en `configuracion/conexion_db.php`
4. Servir entorno local: `php -S localhost:8000`
5. Acceder en navegador: `http://localhost:8000/index.php?pagina=login`

## 📁 Estructura
- `configuracion/`: Conexión PDO y constantes globales
- `plantillas/`: Encabezado y pie de página reutilizables
- `modulos/`: Lógica y vistas por funcionalidad
- `assets/`: CSS, JS, imágenes
- `documentacion/`: Entregables académicos (IEEE 830, FODA, DER, Gantt)

## 🛠️ Stack Tecnológico
PHP 8+, MySQL 8+, Bootstrap 5, jQuery 3.7

## Variables Relevantes del Proyecto

### Interfaz y Diseño (CSS)
- `--color-primario`: Color institucional principal. Usado en botones, enlaces y acentos visuales.
- `--fuente-principal`: Tipografía definida por la guía institucional (Verdana).
- `--radio-borde`: Estándar de redondeo para tarjetas, inputs y botones (8px).

### Configuración y Backend (PHP)
- `$conexion`: Instancia PDO configurada con `EMULATE_PREPARES=false` para utilizar preparación nativa del motor de base de datos.
- `RUTA_ABSOLUTA` y `URL_BASE`: Constantes para manejo de rutas internas, inclusión de archivos y generación de enlaces dinámicos.
- `PDO::FETCH_ASSOC`: Modo de retorno por defecto para consultas, generando arrays asociativos con nombres de columnas como claves.

### Autenticación y Sesión
- `$_SESSION['rol_usuario']`: Almacena el perfil activo tras el login. Controla el acceso a módulos y el renderizado condicional en vistas.
- `$_SESSION['nombre_usuario']`: Personaliza el panel y permite registrar actividad por usuario.
- `verificar_rol()`: Función de seguridad que intercepta rutas no autorizadas antes de la ejecución del módulo.

### Seguridad
- `CLAVE_AES_32_BYTES`: Clave de exactamente 32 bytes requerida para el cifrado AES-256-GCM.
- `password_hash()` / `password_verify()`: Estándar PHP moderno para almacenamiento y validación segura de credenciales.

---

## Resumen de Paleta de Colores (Identidad Institucional)

Todos los colores están definidos como variables CSS nativas en `assets/css/principal.css` y sobreescriben los valores por defecto de Bootstrap 5.

| Variable CSS | Código HEX | Uso Principal |
|--------------|------------|---------------|
| `--color-primario` | `#00a096` | Navbar, botones, enlaces activos, métricas destacadas |
| `--color-primario-hover` | `#008f85` | Estado hover en elementos interactivos |
| `--color-info` | `#005a9b` | Badges informativos, estados "en proceso" |
| `--color-exito` | `#009641` | Confirmaciones, validaciones positivas, estado correcto |
| `--color-advertencia` | `#96be1e` | Alertas moderadas, tickets pendientes, observaciones |
| `--color-error` | `#e60064` | Errores críticos, campos inválidos, estado dañado |
| `--color-fondo` | `#ffffff` | Fondo general del sistema |
| `--color-superficie` | `#f8fafc` | Tarjetas, secciones alternas, contenedores |
| `--color-borde` | `#e2e8f0` | Inputs, tablas, divisores visuales |
| `--color-texto` | `#1f2328` | Títulos, cuerpo de texto, navegación |
| `--color-texto-secundario` | `#64748b` | Placeholders, notas, texto descriptivo |

Nota de aplicación: El fondo blanco combinado con el texto `#1f2328` cumple con los estándares de contraste WCAG AA. Los colores institucionales se utilizan exclusivamente para acentos, estados y componentes de interfaz, nunca como fondo de bloques de texto extenso.

---

## Lógica y Arquitectura del Sistema SGRSI

### 1. Patrón de Enrutamiento
El sistema utiliza un Front Controller centralizado (`index.php`) que gestiona el flujo de cada petición HTTP de manera stateless:
- Recepción de la ruta mediante `$_GET['pagina']`.
- Validación contra una lista blanca de módulos permitidos.
- Inclusión de la cabecera (`encabezado.php`) que carga la estructura HTML5, Bootstrap y jQuery.
- Ejecución del módulo solicitado mediante `switch/case`.
- Inclusión del pie de página (`pie_pagina.php`) para cierre de etiquetas y carga de scripts globales.

### 2. Gestión de Roles y Permisos
- Los perfiles (Coordinador, Asistente, Docente) se definen tras la validación de credenciales y se almacenan en `$_SESSION`.
- La función `verificar_rol()` actúa como filtro en `index.php`, redirigiendo automáticamente si el usuario no posee los privilegios requeridos.
- En las vistas (ej. `panel/index.php`), variables como `$rol_actual` condicionan la visibilidad de tarjetas, enlaces y métricas, garantizando que cada usuario solo interactúe con los módulos autorizados.

### 3. Capa de Seguridad
- Base de Datos: Uso estricto de `PDO::prepare()` con preparación nativa para prevenir inyección SQL.
- Autenticación: Almacenamiento de contraseñas con `password_hash()` (BCrypt) y verificación segura con `password_verify()`.
- Sesiones: Configuración con `HttpOnly`, `SameSite=Lax` y regeneración de ID tras el login para mitigar secuestro de sesión y CSRF básico.
- Salida de Datos: Aplicación de `htmlspecialchars()` en todas las variables dinámicas renderizadas en HTML para prevenir XSS.
- Datos Sensibles: Implementación de clase dedicada `CifradorAES` utilizando el estándar `AES-256-GCM` para tokens o información crítica.

### 4. Frontend y Experiencia de Usuario
- Framework CSS: Bootstrap 5 como base estructural y responsive.
- Personalización: Variables CSS `:root` que adaptan la paleta y componentes a la identidad visual institucional sin modificar archivos del framework.
- Interactividad: jQuery 3.7 para validación de formularios en el cliente, manipulación segura del DOM y preparación de llamadas asíncronas futuras.
- Adaptabilidad: Breakpoints definidos en 576px (móvil) y 768px (tablet) con ajustes de tipografía, espaciado y navegación.

### 5. Escalabilidad y Mantenimiento
- Arquitectura modular: Cada requerimiento funcional (RF) reside en su propia carpeta dentro de `modulos/`, permitiendo desarrollo paralelo sin conflictos de fusión.
- Preparación para API: Los contenedores de métricas y tablas utilizan identificadores semánticos (`id="tabla-actividad"`, `id="fecha-actualizacion"`) listos para ser poblados dinámicamente mediante JSON/AJAX cuando el backend esté completo.
- Documentación integrada: La estructura del repositorio incluye directorios dedicados a la documentación académica (IEEE 830, DER, FODA, planificación), facilitando el seguimiento, la evaluación y el handover.
