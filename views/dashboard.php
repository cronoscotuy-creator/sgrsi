<h1 class="mb-1">Panel principal</h1>
<p class="text-muted mb-4">Resumen general del estado del SGRSI</p>

<!-- Métricas rápidas -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="metric-card">
            <div class="metric-valor"><?= $total_equipos ?></div>
            <div class="metric-label">Equipos registrados</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card metric-naranja">
            <div class="metric-valor"><?= $total_tickets_abiertos ?></div>
            <div class="metric-label">Tickets abiertos</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card metric-verde">
            <div class="metric-valor"><?= $total_prestamos_activos ?></div>
            <div class="metric-label">Préstamos activos</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Estado de equipos -->
    <div class="col-md-4">
        <div class="card-cronos h-100">
            <h3><i class="bi bi-pc-display"></i> Estado de equipos</h3>
            <?php if ($estados_equipos): ?>
                <?php foreach ($estados_equipos as $e): ?>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge-estado" style="background-color:<?= $e['color_hex'] ?>">
                        <?= htmlspecialchars($e['nombre_estado']) ?>
                    </span>
                    <strong><?= $e['cantidad'] ?></strong>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">No hay equipos registrados.</p>
            <?php endif; ?>
            <a href="/index.php?pagina=inventario" class="btn btn-cronos-outline btn-sm mt-2">
                Ver inventario completo
            </a>
        </div>
    </div>

    <!-- Tickets activos -->
    <div class="col-md-4">
        <div class="card-cronos h-100">
            <h3><i class="bi bi-life-preserver"></i> Tickets prioritarios</h3>
            <?php if ($tickets_activos): ?>
                <?php foreach ($tickets_activos as $t): ?>
                <div class="mb-2 pb-2" style="border-bottom:1px solid var(--borde)">
                    <div class="d-flex justify-content-between">
                        <strong><?= htmlspecialchars($t['incidente']) ?></strong>
                        <span class="badge-estado" style="background-color:<?= $t['color_prioridad'] ?>">
                            <?= htmlspecialchars($t['prioridad']) ?>
                        </span>
                    </div>
                    <small class="text-muted">
                        <?= htmlspecialchars($t['laboratorio'] ?? 'Sin laboratorio') ?>
                        &middot; <?= htmlspecialchars($t['estado']) ?>
                    </small>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">No hay tickets pendientes.</p>
            <?php endif; ?>
            <a href="/index.php?pagina=tickets" class="btn btn-cronos-outline btn-sm mt-2">
                Ir a Mesa de Ayuda
            </a>
        </div>
    </div>

    <!-- Solicitudes pendientes -->
    <div class="col-md-4">
        <div class="card-cronos h-100">
            <h3><i class="bi bi-envelope-paper"></i> Solicitudes pendientes</h3>
            <?php if ($solicitudes_pendientes): ?>
                <?php foreach ($solicitudes_pendientes as $s): ?>
                <div class="mb-2 pb-2" style="border-bottom:1px solid var(--borde)">
                    <strong><?= htmlspecialchars($s['tipo']) ?></strong>
                    <p class="mb-0 text-muted" style="font-size:.85rem">
                        <?= htmlspecialchars(substr($s['descripcion'], 0, 60)) ?>
                        <?= strlen($s['descripcion']) > 60 ? '...' : '' ?>
                    </p>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">No hay solicitudes pendientes.</p>
            <?php endif; ?>
            <a href="/index.php?pagina=solicitudes" class="btn btn-cronos-outline btn-sm mt-2">
                Ver solicitudes
            </a>
        </div>
    </div>
</div>