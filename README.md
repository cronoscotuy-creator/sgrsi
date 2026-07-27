# SGRSI — Sistema de Gestión de Recursos y Soporte Informático
**Grupo Cronos · Change On Time · ITI – CETP 2026**

> Desarrollado por: Gisse Silvera · Diego de Bethencourt · Andrés Cabrera

---

## ¿Qué es el SGRSI?

Sistema web de uso interno para el departamento de asistentes técnicos del ITI.
Digitaliza y centraliza la gestión de recursos tecnológicos, incidencias, solicitudes
de servicio y préstamos de equipos, reemplazando las planillas de papel y correos
electrónicos del flujo actual.

---

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Backend | PHP 8.2 |
| Servidor web | Apache (dentro de Docker) |
| Base de datos | MariaDB 10.11 |
| Administrador BD | phpMyAdmin |
| Frontend | Bootstrap 5.3 + HTML5 + CSS3 + JavaScript |
| Contenedores | Docker + Docker Compose v2 |
| Control de versiones | Git + GitHub |
| Gestión del proyecto | Trello + Miro |

---

## Estructura de archivos

```
sgrsi-php/
├── docker-compose.yml               ← orquesta los 3 contenedores
├── docker/
│   └── php/
│       └── Dockerfile               ← imagen PHP 8.2 + Apache + PDO + mod_rewrite
├── public/
│   ├── index.php                    ← router principal (front controller)
│   └── assets/
│       ├── css/style.css            ← estilos con paleta Plan Ceibal
│       ├── js/main.js               ← JavaScript general
│       └── img/logo.png
├── src/
│   ├── config/
│   │   └── Database.php             ← conexión PDO singleton
│   ├── includes/
│   │   └── proteccion.inc.php       ← control de sesiones seguro (httponly, samesite, XSS)
│   ├── controllers/
│   │   ├── AuthController.php       ← login / logout
│   │   ├── DashboardController.php  ← panel principal con métricas
│   │   ├── InventarioController.php ← gestión de equipos (RF-02, RF-04)
│   │   ├── TicketsController.php    ← mesa de ayuda (RF-03)
│   │   ├── SolicitudesController.php← solicitudes de servicio (RF-05)
│   │   ├── PrestamosController.php  ← préstamos de equipos (RF-07)
│   │   ├── MetricasController.php   ← reportes y estadísticas (RF-10)
│   │   └── UsuariosController.php   ← ABM de usuarios (RF-06)
│   └── helpers/
│       └── flash.php                ← mensajes flash entre redirecciones
├── views/
│   ├── layouts/
│   │   └── base.php                 ← layout compartido: navbar, footer, alertas
│   ├── login.php
│   ├── dashboard.php
│   ├── inventario.php
│   ├── tickets.php
│   ├── solicitudes.php
│   ├── prestamos.php
│   ├── metricas.php
│   └── usuarios.php                 ← gestión de usuarios (solo administrador)
└── database/
    ├── esquema.sql                  ← DDL: 14 tablas normalizadas a 3FN
    ├── seed_data.sql                ← datos de prueba
    └── limpiar_db.sql               ← limpia todas las tablas
```

---

## Módulos del sistema

### Login y control de acceso (RF-06)
Autenticación con email y contraseña. Contraseñas almacenadas con `password_hash()`
bcrypt. Sesiones PHP con cookies seguras (`httponly`, `samesite: Lax`). Protección XSS.
Mensaje de sesión expirada. `session_unset()` en logout.

**Tres roles:**
- `administrador` — acceso total incluyendo gestión de usuarios
- `tecnico` — tickets, inventario, solicitudes, préstamos y métricas
- `solicitante` — crea tickets y solicitudes, ve solo los suyos

### Dashboard
Panel principal con métricas en tiempo real: total de equipos, tickets abiertos,
préstamos activos, estado de equipos por categoría y tickets prioritarios pendientes.

### Inventario de equipos (RF-02 y RF-04)
ABM de equipos con identificador, tipo, marca, modelo, laboratorio y estado.
Los cambios de estado generan automáticamente un registro en `historial_equipos`
vinculado al técnico responsable y con descripción de la intervención.

**5 estados:** Correcto · Faltante · Problema de hardware · Problema de software · Dañado

### Mesa de Ayuda / Tickets (RF-03)
Registro de incidencias con prioridad (Alta, Media, Baja) codificada por color.
Menú desplegable de tipo de incidente con campo editable libre al elegir "Otro" (JS).
Flujo completo: Pendiente → En proceso → Resuelto, con nota de resolución y
fecha de cierre automática.

### Solicitudes de servicio (RF-05)
Reemplaza el correo electrónico. Canal formal para pedidos de docentes.
El solicitante ve el estado de sus solicitudes en tiempo real.
Los técnicos actualizan el estado directamente desde la tabla.

### Préstamos de equipos (RF-07)
Registro y devolución de equipos móviles con un clic.
Preparado para cuando el ITI incorpore laptops u otros equipos prestables.

### Métricas y reportes (RF-10)
Estadísticas del sistema: equipos por estado, tickets por estado y prioridad,
top 5 de equipos con más incidencias, solicitudes por tipo.
Solo accesible para técnicos y administradores.

### Gestión de usuarios (RF-06)
Solo accesible para administradores. Permite:
- **Alta** — crear nuevo usuario con nombre, email, contraseña y rol
- **Baja lógica** — activar o desactivar usuarios sin borrarlos de la BD
- **Modificación** — cambiar el rol de cualquier usuario
- **Búsqueda** — buscar por nombre, apellido o email
- Protección: nadie puede modificarse ni desactivarse a sí mismo

---

## Seguridad de sesiones (según apuntes del docente)

Implementada en `src/includes/proteccion.inc.php`:

```php
session_set_cookie_params([
    'lifetime' => 0,       // Cookie se borra al cerrar el navegador
    'httponly' => true,    // Bloquea robo de cookies por JavaScript (XSS)
    'samesite' => 'Lax'   // Protege contra ataques CSRF
]);
```

- Bloqueo de acceso directo al archivo `.inc` con `get_included_files()`
- Redirección con `?expirado=1` al login cuando la sesión no existe
- `session_unset()` antes de `session_destroy()` en logout

---

## Usuarios de prueba

Todos tienen contraseña: `admin123`

| Email | Rol | Acceso |
|---|---|---|
| admin@iti.edu.uy | administrador | Total + gestión de usuarios |
| gissel@iti.edu.uy | tecnico | Tickets, inventario, solicitudes, préstamos, métricas |
| diego@iti.edu.uy | tecnico | Ídem |
| andres@iti.edu.uy | tecnico | Ídem |
| docente@iti.edu.uy | solicitante | Crear tickets y solicitudes propias |

---

## Instalación con Docker

### Requisitos
- Docker Desktop instalado y corriendo
- Git

### Levantar el proyecto

```bash
git clone https://github.com/cronoscotuy-creator/sgrsi.git
cd sgrsi
docker-compose up --build
```

La primera vez descarga las imágenes y crea la base de datos automáticamente
con el esquema y los datos de prueba.

### Activar contraseñas de prueba

Abrir phpMyAdmin en http://localhost:8081, ir a la pestaña SQL y ejecutar:

```sql
UPDATE usuarios SET contrasena_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uSsRV62' WHERE 1;
```

O generar el hash directamente desde el contenedor PHP:

```bash
docker exec sgrsi_web php -r "echo password_hash('admin123', PASSWORD_DEFAULT);"
```

Y actualizar en phpMyAdmin con el hash generado.

### Acceder al sistema

| Servicio | URL |
|---|---|
| Aplicación web | http://localhost:8080 |
| phpMyAdmin | http://localhost:8081 |

---

## Contenedores Docker

| Contenedor | Imagen | Puerto | Función |
|---|---|---|---|
| sgrsi_web | PHP 8.2 + Apache (custom) | 8080 | Servidor web |
| sgrsi_db | MariaDB 10.11 | 3307 | Base de datos |
| sgrsi_pma | phpMyAdmin latest | 8081 | Administrador visual BD |

---

## Base de datos

14 tablas normalizadas a 3FN:

**Catálogos:** `roles` · `tipos_equipo` · `estados_equipo` · `prioridades` ·
`estados_ticket` · `tipos_solicitud` · `estados_solicitud` · `estados_prestamo`

**Operativas:** `usuarios` · `laboratorios` · `equipos` · `historial_equipos` ·
`tickets` · `solicitudes` · `prestamos` · `repuestos`

---

## Convenciones de Git

**Ramas:** `main` (estable) · `develop` (activo) · `feature/[nombre]` · `fix/[nombre]`

**Formato de commits:**
```
feat: agrega módulo de gestión de usuarios con ABM
fix: corrige validación de email en login
docs: actualiza README con guía de despliegue
style: ajusta paleta Ceibal en navbar
chore: actualiza .gitignore
```

**Reglas:**
- Nadie hace push directo a `main`
- Mínimo 1 commit por integrante por semana
- El archivo `.env` nunca se sube al repositorio

---

## Links del proyecto

- **Repositorio:** https://github.com/cronoscotuy-creator/sgrsi
- **Trello:** https://trello.com/b/69e6af41
- **Miro:** https://miro.com/welcomeonboard/eUhXZFBwbkhLRVp6amR4UHRGUHNNSjNuZWNLSzNJSHNLVTd3QmNmQ3ZCRFBVQTdQQ21LUGtyYVdqMjYrTlppYkZSaGI3N2ZRZy9MbmZHRC9WbElvdFpkSEZLWHFpRitWNXUxMDc4VFBoUWtuc3M3cy9KdXIxbEd0UmxVYjNTR1pNakdSWkpBejJWRjJhRnhhb1UwcS9BPT0hdjE=

---

*SGRSI · Cronos · Change On Time · ITI – CETP 2026*
