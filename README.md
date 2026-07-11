# SGRSI — Sistema de Gestión de Recursos y Soporte Informático
**Grupo Cronos · Change On Time · ITI – CETP 2026**

> Desarrollado por: Gisse Silvera · Diego de Bethencourt · Andrés Cabrera

---

## ¿Qué es el SGRSI?

Sistema web de uso interno para el departamento de asistentes técnicos del ITI. Digitaliza y centraliza la gestión de recursos tecnológicos, incidencias, solicitudes de servicio y préstamos de equipos, reemplazando las planillas de papel y correos electrónicos del flujo actual.

---

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Backend | PHP 8.2 |
| Servidor web | Apache (dentro de Docker) |
| Base de datos | MariaDB 10.11 |
| Administrador BD | phpMyAdmin |
| Frontend | Bootstrap 5.3 + HTML5 + CSS3 + JavaScript |
| Contenedores | Docker + Docker Compose |
| Control de versiones | Git + GitHub |
| Gestión del proyecto | Trello + Miro |

---

## Estructura de archivos

```
sgrsi-php/
├── docker-compose.yml          ← orquesta los 3 contenedores
├── docker/
│   └── php/
│       └── Dockerfile          ← imagen PHP 8.2 + Apache + extensiones PDO
├── public/
│   ├── index.php               ← router principal (front controller)
│   └── assets/
│       ├── css/style.css       ← estilos con paleta Plan Ceibal
│       ├── js/main.js          ← JavaScript general
│       └── img/logo.png        ← logo Cronos
├── src/
│   ├── config/
│   │   └── Database.php        ← conexión PDO a MySQL (singleton)
│   ├── controllers/
│   │   ├── AuthController.php          ← login / logout
│   │   ├── DashboardController.php     ← panel principal con métricas
│   │   ├── InventarioController.php    ← gestión de equipos (RF-02, RF-04)
│   │   ├── TicketsController.php       ← mesa de ayuda (RF-03)
│   │   ├── SolicitudesController.php   ← solicitudes de servicio (RF-05)
│   │   ├── PrestamosController.php     ← préstamos de equipos (RF-07)
│   │   └── MetricasController.php      ← reportes y estadísticas (RF-10)
│   └── helpers/
│       └── flash.php           ← mensajes flash entre redirecciones
├── views/
│   ├── layouts/
│   │   └── base.php            ← layout compartido: navbar, footer, alertas
│   ├── login.php
│   ├── dashboard.php
│   ├── inventario.php
│   ├── tickets.php
│   ├── solicitudes.php
│   ├── prestamos.php
│   └── metricas.php
└── database/
    ├── esquema.sql             ← DDL: 14 tablas normalizadas a 3FN
    ├── seed_data.sql           ← datos de prueba
    └── limpiar_db.sql          ← limpia todas las tablas
```

---

## Módulos del sistema

### Login y control de acceso (RF-06)
Autenticación con email y contraseña. Contraseñas almacenadas con hash `password_hash()` de PHP (bcrypt). Sesiones PHP nativas. Control de acceso por rol en cada controlador y vista.

**Tres roles disponibles:**
- `administrador` — acceso total incluyendo gestión de usuarios
- `tecnico` — gestión de tickets, inventario, solicitudes y préstamos
- `solicitante` — puede crear tickets y solicitudes, ve solo los suyos

### Dashboard (RF-10)
Panel principal con métricas en tiempo real: total de equipos, tickets abiertos, préstamos activos, estado de equipos por categoría y lista de tickets prioritarios pendientes.

### Inventario de equipos (RF-02 y RF-04)
ABM de equipos tecnológicos con identificador, tipo, marca, modelo, laboratorio asignado y estado. Los cambios de estado se registran automáticamente en el historial vinculado al equipo y al técnico responsable.

**5 estados de equipo:** Correcto · Faltante · Problema de hardware · Problema de software · Dañado

### Mesa de Ayuda / Tickets (RF-03)
Registro de incidencias con prioridad (Alta, Media, Baja) codificada por color. Formulario con menú desplegable de tipo de incidente y campo editable libre cuando se elige "Otro". Los técnicos actualizan el estado y agregan nota de resolución. Flujo completo: Pendiente → En proceso → Resuelto.

### Solicitudes de servicio (RF-05)
Reemplaza el correo electrónico como canal formal de pedidos de los docentes. Tipos: instalación de software, configuración de equipo, preparación de laboratorio, otro. El solicitante puede ver el estado de sus propias solicitudes en tiempo real.

### Préstamos de equipos (RF-07)
Control de préstamos y devoluciones de equipos móviles. Registra quién retiró el equipo, cuándo y cuándo lo devolvió. Preparado para cuando el ITI incorpore laptops u otros equipos prestables.

### Métricas y reportes (RF-10)
Panel de estadísticas: equipos por estado, tickets por estado y prioridad, top 5 de equipos con más incidencias y solicitudes por tipo. Solo accesible para técnicos y administradores.

---

## Usuarios de prueba

Todos tienen contraseña: `admin123`

| Email | Rol | Acceso |
|---|---|---|
| admin@iti.edu.uy | administrador | Total |
| gissel@iti.edu.uy | tecnico | Tickets, inventario, solicitudes, préstamos, métricas |
| diego@iti.edu.uy | tecnico | Ídem |
| andres@iti.edu.uy | tecnico | Ídem |
| docente@iti.edu.uy | solicitante | Crear tickets y solicitudes propias |

---

## Instalación y despliegue con Docker

### Requisitos previos
- Docker Desktop instalado y corriendo
- Git

### Paso 1 — Clonar el repositorio
```bash
git clone https://github.com/cronoscotuy-creator/sgrsi.git
cd sgrsi
```

### Paso 2 — Levantar los contenedores
```bash
docker-compose up --build
```

La primera vez descarga las imágenes y crea la base de datos automáticamente con el esquema y los datos de prueba.

### Paso 3 — Activar contraseñas
Abrir http://localhost:8080/test.php (crear el archivo primero con el script de activación) o ejecutar en phpMyAdmin:
```sql
UPDATE usuarios SET contrasena_hash = '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B9tDyUy' WHERE 1;
```

### Paso 4 — Acceder al sistema
| Servicio | URL |
|---|---|
| Aplicación web | http://localhost:8080 |
| phpMyAdmin | http://localhost:8081 |

---

## Contenedores Docker

| Contenedor | Imagen | Puerto | Función |
|---|---|---|---|
| sgrsi_web | PHP 8.2 + Apache (custom) | 8080 | Servidor web de la aplicación |
| sgrsi_db | MariaDB 10.11 | 3307 | Base de datos MySQL |
| sgrsi_pma | phpMyAdmin latest | 8081 | Administrador visual de BD |

---

## Base de datos

14 tablas normalizadas a 3FN divididas en:

**Catálogos:** `roles` · `tipos_equipo` · `estados_equipo` · `prioridades` · `estados_ticket` · `tipos_solicitud` · `estados_solicitud` · `estados_prestamo`

**Operativas:** `usuarios` · `laboratorios` · `equipos` · `historial_equipos` · `tickets` · `solicitudes` · `prestamos` · `repuestos`

---

## Convenciones de Git

**Ramas:** `main` (estable) · `develop` (activo) · `feature/[nombre]` · `fix/[nombre]` · `docs/[nombre]`

**Formato de commits:**
```
feat: agrega módulo de tickets con prioridad
fix: corrige validación de email en login
docs: actualiza README con guía de despliegue
style: ajusta paleta Ceibal en navbar
```

**Reglas:**
- Nadie hace push directo a `main`
- Mínimo 1 commit por integrante por semana
- El archivo `.env` nunca se sube al repositorio

---

## Links del proyecto

- **Repositorio:** https://github.com/cronoscotuy-creator/sgrsi
- **Trello:** https://trello.com/invite/b/69e6af41e45061f26a194523/ATTIbc344a3b4f0f4d8104133b3f44eaf9f6229E460E/mi-tablero-de-trello
- **Miro:** https://miro.com/welcomeonboard/eUhXZFBwbkhLRVp6amR4UHRGUHNNSjNuZWNLSzNJSHNLVTd3QmNmQ3ZCRFBVQTdQQ21LUGtyYVdqMjYrTlppYkZSaGI3N2ZRZy9MbmZHRC9WbElvdFpkSEZLWHFpRitWNXUxMDc4VFBoUWtuc3M3cy9KdXIxbEd0UmxVYjNTR1pNakdSWkpBejJWRjJhRnhhb1UwcS9BPT0hdjE=

---

*SGRSI · Cronos · Change On Time · ITI – CETP 2026*
