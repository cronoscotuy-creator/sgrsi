sgrsi/
├── assets/
│   ├── css/
│   │   ├── principal.css         # Estilos base + responsive
│   │   └── modulos/              # CSS por módulo (laboratorios, tickets, etc.)
│   ├── js/
│   │   ├── validacion.js         # Validación cliente + manejo DOM
│   │   └── modulos/              # Scripts por funcionalidad
│   └── imagenes/
├── configuracion/
│   ├── conexion_db.php           # PDO + constantes
│   └── constantes.php            # Rutas, límites, ajustes
├── plantillas/
│   ├── encabezado.php            # <head>, nav semántico, carga de CSS/JS
│   └── pie_pagina.php            # Footer, scripts globales
├── modulos/
│   ├── auth/                     # login.php, cerrar_sesion.php
│   │    └── login.php            # login pedido para entrega 3/6
│   ├── laboratorios/             # RF-01
│   ├── equipos/                  # RF-02, RF-04
│   ├── tickets/                  # RF-03, RF-05
│   └── panel/                    # RF-10 (dashboard)
├── base_datos/
│   └── esquema.sql               # Tablas MVP + datos semilla
├── documentacion/                # 📚 Entregables académicos
│   ├── requisitos/               # IEEE 830 / AGESIC
│   ├── diagramas/                # ER, árboles/tablas de decisión
│   ├── analisis/                 # FODA, viabilidad técnica/operativa
│   └── planificacion/            # Gantt, reglas internas, ciclo de vida
├── .gitignore
├── CONTRIBUTING.md               # Convenciones Git y flujo de trabajo
└── README.md