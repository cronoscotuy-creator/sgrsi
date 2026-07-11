<h1 class="mb-1">Métricas y reportes</h1>
<p class="text-muted mb-4">Resumen estadístico del sistema (RF-10)</p>

<div class="row g-3">

    <div class="col-md-6">
        <div class="card-cronos h-100">
            <h3><i class="bi bi-pc-display"></i> Equipos por estado</h3>
            <?php foreach ($equipos_por_estado as $e): ?>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge-estado" style="background-color:<?= $e['color_hex'] ?>">
                    <?= htmlspecialchars($e['nombre_estado']) ?>
                </span>
                <strong><?= $e['cantidad'] ?></strong>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card-cronos h-100">
            <h3><i class="bi bi-life-preserver"></i> Tickets por estado</h3>
            <?php
            $colores_t = ['Pendiente'=>'#F7941E','En proceso'=>'#0089B8','Resuelto'=>'#43B02A'];
            foreach ($tickets_por_estado as $t):
            ?>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge-estado"
                      style="background-color:<?= $colores_t[$t['nombre_estado']] ?? '#7F8C8D' ?>">
                    <?= htmlspecialchars($t['nombre_estado']) ?>
                </span>
                <strong><?= $t['cantidad'] ?></strong>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card-cronos h-100">
            <h3><i class="bi bi-exclamation-triangle"></i> Tickets por prioridad</h3>
            <?php foreach ($tickets_por_prioridad as $p): ?>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge-estado" style="background-color:<?= $p['color_hex'] ?>">
                    <?= htmlspecialchars($p['nombre']) ?>
                </span>
                <strong><?= $p['cantidad'] ?></strong>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card-cronos h-100">
            <h3><i class="bi bi-bar-chart"></i> Equipos con más incidencias</h3>
            <?php if ($equipos_mas_fallados): ?>
            <?php foreach ($equipos_mas_fallados as $e): ?>
            <div class="d-flex justify-content-between mb-2">
                <strong><?= htmlspecialchars($e['identificador']) ?></strong>
                <span><?= $e['cantidad_tickets'] ?> tickets</span>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <p class="text-muted">Sin datos.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-12">
        <div class="card-cronos">
            <h3><i class="bi bi-envelope-paper"></i> Solicitudes por tipo</h3>
            <div class="row g-2 mt-1">
                <?php foreach ($solicitudes_por_tipo as $s): ?>
                <div class="col-6 col-md-3">
                    <div class="metric-card">
                        <div class="metric-valor" style="font-size:1.8rem"><?= $s['cantidad'] ?></div>
                        <div class="metric-label"><?= htmlspecialchars($s['nombre']) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</div>