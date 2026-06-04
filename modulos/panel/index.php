<?php
// modulos/panel/index.php
// Dashboard principal - Adaptable por rol (Coordinador / Asistente / Docente)
// RF-10: Métricas + Acceso rápido + Actividad reciente
// Nota: En producción, $_SESSION['rol_usuario'] y $_SESSION['nombre_usuario'] vienen del login
?>

<?php
// Mock de sesión para demo (reemplazar por $_SESSION real cuando el auth esté listo)
$rol_actual = $_SESSION['rol_usuario'] ?? 'coordinador'; 
$nombre_usuario = $_SESSION['nombre_usuario'] ?? 'Equipo Cronos';
$hora_actual = date('H');
$saludo = ($hora_actual < 12) ? 'Buenos días' : (($hora_actual < 18) ? 'Buenas tardes' : 'Buenas noches');
?>

<section class="panel-header mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
            <h2 class="fw-bold mb-1 text-dark"><?= $saludo ?>, <?= htmlspecialchars($nombre_usuario) ?></h2>
            <p class="text-muted mb-0">Panel de Control | SGRSI <span class="badge bg-info-ceibal ms-2"><?= ucfirst($rol_actual) ?></span></p>
        </div>
        <div class="text-md-end">
            <small class="text-muted">Última sincronización: <span id="fecha-actualizacion"><?= date('d/m/Y H:i') ?></span></small>
        </div>
    </div>
</section>

<!-- Métricas Rápidas -->
<section class="mb-4">
    <h5 class="fw-semibold mb-3">Indicadores Clave</h5>
    <div class="row g-3">
        <div class="col-6 col-md-3">
            <div class="card h-100 border-start border-4 border-primary-ceibal shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Laboratorios Activos</p>
                            <h3 class="fw-bold mb-0">12</h3>
                        </div>
                        <span class="fs-2 text-primary-ceibal">🏫</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-start border-4 border-success-ceibal shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Equipos Correctos</p>
                            <h3 class="fw-bold mb-0">84%</h3>
                        </div>
                        <span class="fs-2 text-exito">✅</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-start border-4 border-warning-ceibal shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Tickets Abiertos</p>
                            <h3 class="fw-bold mb-0">7</h3>
                        </div>
                        <span class="fs-2 text-advertencia">🎫</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-start border-4 border-info-ceibal shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Solicitudes Software</p>
                            <h3 class="fw-bold mb-0">3</h3>
                        </div>
                        <span class="fs-2 text-info-ceibal">💻</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Acceso rápido por Módulo -->
<section class="mb-4">
    <h5 class="fw-semibold mb-3">Acceso Rápido</h5>
    <div class="row g-3">
        <?php if ($rol_actual !== 'docente'): ?>
        <div class="col-md-6 col-lg-4">
            <a href="index.php?pagina=laboratorios" class="text-decoration-none">
                <div class="card h-100 hover-card p-3 d-flex flex-row align-items-center gap-3">
                    <div class="bg-primary-ceibal bg-opacity-10 p-2 rounded-3">
                        <span class="fs-4 text-primary-ceibal">📅</span>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1">Gestión de Laboratorios</h6>
                        <p class="small text-muted mb-0">RF-01 | Registro de turnos y uso</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="index.php?pagina=equipos" class="text-decoration-none">
                <div class="card h-100 hover-card p-3 d-flex flex-row align-items-center gap-3">
                    <div class="bg-info-ceibal bg-opacity-10 p-2 rounded-3">
                        <span class="fs-4 text-info-ceibal">🖥️</span>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1">Inventario de Equipos</h6>
                        <p class="small text-muted mb-0">RF-02 & RF-04 | ABM + Historial</p>
                    </div>
                </div>
            </a>
        </div>
        <?php endif; ?>

        <div class="col-md-6 col-lg-4">
            <a href="index.php?pagina=tickets" class="text-decoration-none">
                <div class="card h-100 hover-card p-3 d-flex flex-row align-items-center gap-3">
                    <div class="bg-warning-ceibal bg-opacity-10 p-2 rounded-3">
                        <span class="fs-4 text-advertencia">📝</span>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1">Mesa de Ayuda</h6>
                        <p class="small text-muted mb-0">RF-03 & RF-05 | Tickets + Solicitudes</p>
                    </div>
                </div>
            </a>
        </div>

        <?php if ($rol_actual === 'coordinador'): ?>
        <div class="col-md-6 col-lg-4">
            <a href="#" class="text-decoration-none">
                <div class="card h-100 hover-card p-3 d-flex flex-row align-items-center gap-3 border-2 border-dashed">
                    <div class="bg-secondary-ceibal bg-opacity-10 p-2 rounded-3">
                        <span class="fs-4 text-muted">⚙️</span>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1">Configuración</h6>
                        <p class="small text-muted mb-0">Roles, usuarios y parámetros</p>
                    </div>
                </div>
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Actividad Reciente -->
<section class="mb-4">
    <h5 class="fw-semibold mb-3">Actividad Reciente</h5>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light-ceibal">
                        <tr>
                            <th class="ps-3">Fecha</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-actividad">
                        <!-- Mock inicial. En producción se llena desde DB -->
                        <tr>
                            <td class="ps-3">Hoy, 09:15</td>
                            <td>Prof. Martínez</td>
                            <td>Reportó falla en proyector Lab 3</td>
                            <td><span class="badge bg-warning-ceibal">Pendiente</span></td>
                        </tr>
                        <tr>
                            <td class="ps-3">Hoy, 08:42</td>
                            <td>Téc. López</td>
                            <td>Cerró ticket #142 (Teclado Lab 1)</td>
                            <td><span class="badge bg-exito">Resuelto</span></td>
                        </tr>
                        <tr>
                            <td class="ps-3">Ayer, 16:30</td>
                            <td>Coord. García</td>
                            <td>Actualizó inventario de RAM</td>
                            <td><span class="badge bg-info-ceibal">Registrado</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- ⚡ jQuery para interactividad y fecha dinámica -->
<script>
$(document).ready(function() {
    // Actualizar hora cada minuto
    setInterval(function() {
        const ahora = new Date();
        $('#fecha-actualizacion').text(ahora.toLocaleString('es-UY'));
    }, 60000);

    // Efecto hover en tarjetas de acceso rápido
    $('.hover-card').hover(
        function() { $(this).addClass('shadow'); },
        function() { $(this).removeClass('shadow'); }
    );

    // Lógica por rol (para demo)
    const rol = '<?= $rol_actual ?>';
    if (rol === 'docente') {
        // Ocultar métricas internas o mostrar vista simplificada
        console.log('Vista adaptada para Docente: enfoque en reportes y estado de aulas.');
    }
});
</script>