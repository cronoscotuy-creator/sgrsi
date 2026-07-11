<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="mb-1">Préstamos de equipos</h1>
        <p class="text-muted mb-0">Control de stock y trazabilidad (RF-07)</p>
    </div>
    <?php if (in_array($_SESSION['rol'], ['administrador','tecnico'])): ?>
    <button class="btn btn-cronos" data-bs-toggle="modal" data-bs-target="#modalNuevoPrestamo">
        <i class="bi bi-plus-lg"></i> Nuevo préstamo
    </button>
    <?php endif; ?>
</div>

<?php if (empty($equipos_disponibles)): ?>
<div class="alert alert-info">
    <i class="bi bi-info-circle"></i>
    No hay equipos disponibles para préstamo actualmente.
</div>
<?php endif; ?>

<div class="card-cronos">
    <div class="table-responsive">
        <table class="table table-cronos align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Equipo</th>
                    <th>Retirado por</th>
                    <th>Fecha préstamo</th>
                    <th>Devolución estimada</th>
                    <th>Devolución real</th>
                    <th>Estado</th>
                    <?php if (in_array($_SESSION['rol'], ['administrador','tecnico'])): ?>
                    <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if ($prestamos): ?>
                <?php foreach ($prestamos as $p): ?>
                <?php
                $colores_p = [
                    'Activo'   => '#0089B8',
                    'Devuelto' => '#43B02A',
                    'Atrasado' => '#E63946',
                ];
                ?>
                <tr>
                    <td>#<?= $p['id_prestamo'] ?></td>
                    <td><strong><?= htmlspecialchars($p['equipo']) ?></strong></td>
                    <td><small><?= htmlspecialchars($p['usuario_nombre'] . ' ' . $p['usuario_apellido']) ?></small></td>
                    <td><?= $p['fecha_prestamo'] ?></td>
                    <td><?= $p['fecha_devolucion_est'] ?? '-' ?></td>
                    <td><?= $p['fecha_devolucion_real'] ?? '-' ?></td>
                    <td>
                        <span class="badge-estado"
                              style="background-color:<?= $colores_p[$p['estado']] ?? '#7F8C8D' ?>">
                            <?= htmlspecialchars($p['estado']) ?>
                        </span>
                    </td>
                    <?php if (in_array($_SESSION['rol'], ['administrador','tecnico'])): ?>
                    <td>
                        <?php if ($p['estado'] === 'Activo'): ?>
                        <form method="POST" action="/index.php?pagina=prestamos">
                            <input type="hidden" name="accion" value="devolver">
                            <input type="hidden" name="id_prestamo" value="<?= $p['id_prestamo'] ?>">
                            <button type="submit" class="btn btn-sm btn-cronos-outline">
                                <i class="bi bi-arrow-return-left"></i> Devolver
                            </button>
                        </form>
                        <?php else: ?>
                        <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No hay préstamos registrados.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (in_array($_SESSION['rol'], ['administrador','tecnico'])): ?>
<div class="modal fade" id="modalNuevoPrestamo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/index.php?pagina=prestamos">
                <input type="hidden" name="accion" value="nuevo">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-arrow-left-right"></i> Nuevo préstamo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Equipo *</label>
                        <select name="id_equipo" class="form-select" required
                                <?= empty($equipos_disponibles) ? 'disabled' : '' ?>>
                            <option value="" disabled selected>Seleccionar...</option>
                            <?php foreach ($equipos_disponibles as $eq): ?>
                            <option value="<?= $eq['id_equipo'] ?>">
                                <?= htmlspecialchars($eq['identificador']) ?> (<?= htmlspecialchars($eq['nombre_tipo']) ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha de devolución estimada</label>
                        <input type="date" name="fecha_devolucion_est" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2"
                                  placeholder="Ej: préstamo para presentación en clase"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-cronos"
                            <?= empty($equipos_disponibles) ? 'disabled' : '' ?>>
                        Registrar préstamo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>