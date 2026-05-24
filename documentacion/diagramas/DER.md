Modelo Entidad-Relación con cardinalidades

https://app.diagrams.net/#G1POl4bpKAhRQMI6TNAHeKLtuTuoezyRSj#%7B%22pageId%22%3A%22rM_Aa4G87PMvSTcgMddE%22%7D



# DER — Sistema SGRSI

## ENTIDADES Y RELACIONES

+------------------+
|      ROLES       |
+------------------+
| PK id            |
| name             |
+------------------+
          |
          | 1
          |
          | N
+------------------+
|      USERS       |
+------------------+
| PK id            |
| fullname         |
| email            |
| password_hash    |
| FK role_id       |
| created_at       |
+------------------+
          |
          | 1
          |
  -------------------------
  |                       |
  |                       |
  | N                     | N
+------------------+   +------------------+
|     TICKETS      |   |     TICKETS      |
+------------------+   +------------------+
| PK id            |   | PK id            |
| FK requester_id  |   | FK assigned_to   |
| FK equipment_id  |   |                  |
| priority         |   |                  |
| status           |   |                  |
| description      |   |                  |
| created_at       |   |                  |
| updated_at       |   |                  |
+------------------+   +------------------+

                    ^
                    |
                    | N
                    |
                    | 1
+------------------+
|    EQUIPMENT     |
+------------------+
| PK id            |
| asset_code       |
| FK lab_id        |
| type             |
| status           |
| created_at       |
+------------------+
          ^
          |
          | N
          |
          | 1
+------------------+
|       LABS       |
+------------------+
| PK id            |
| name             |
| location         |
| capacity         |
+------------------+

---

# RELACIONES

## ROLES → USERS
- Un rol puede pertenecer a muchos usuarios.
- Un usuario solo tiene un rol.

Relación:
ROLES (1) -------- (N) USERS

---

## USERS → TICKETS (requester_id)
- Un usuario puede crear muchos tickets.
- Un ticket tiene un único solicitante.

Relación:
USERS (1) -------- (N) TICKETS

---

## USERS → TICKETS (assigned_to)
- Un técnico puede estar asignado a muchos tickets.
- Un ticket puede tener un técnico asignado.

Relación:
USERS (1) -------- (N) TICKETS

---

## LABS → EQUIPMENT
- Un laboratorio puede tener muchos equipos.
- Un equipo pertenece a un laboratorio.

Relación:
LABS (1) -------- (N) EQUIPMENT

---

## EQUIPMENT → TICKETS
- Un equipo puede aparecer en muchos tickets.
- Un ticket puede referirse a un equipo.

Relación:
EQUIPMENT (1) -------- (N) TICKETS

---

# CLAVES PRIMARIAS (PK)

- roles.id
- users.id
- labs.id
- equipment.id
- tickets.id

---

# CLAVES FORÁNEAS (FK)

## users
- role_id → roles.id

## equipment
- lab_id → labs.id

## tickets
- requester_id → users.id
- assigned_to → users.id
- equipment_id → equipment.id

---

# OBSERVACIONES IMPORTANTES

## users y tickets
La tabla tickets tiene DOS relaciones con users:
- requester_id → quien crea el ticket
- assigned_to → técnico asignado

Esto es completamente válido en DER.

---

## Cardinalidades

| Relación | Cardinalidad |
|---|---|
| roles → users | 1:N |
| labs → equipment | 1:N |
| equipment → tickets | 1:N |
| users → tickets | 1:N |

---

# POSIBLE INTERPRETACIÓN DEL SISTEMA

- Administradores gestionan usuarios y laboratorios.
- Solicitantes crean tickets.
- Técnicos reciben tickets asignados.
- Los tickets pueden estar vinculados a equipos específicos.
- Los equipos pertenecen a laboratorios.