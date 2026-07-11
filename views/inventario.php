<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="mb-1">Inventario de equipos</h1>
        <p class="text-muted mb-0">Gestión de laboratorios y equipos tecnológicos (RF-02)</p>
    </div>
    <?php if (in_array($_SESSION['rol'], ['administrador','tecnico'])): ?>
    <button class="btn btn-cronos" data-bs-toggle="modal" data-bs-target="#modalNuevoEquipo">
        <i class="bi bi-plus-lg"></i> Nuevo equipo
    </button>
    <?php endif; ?>
</div>

<div class="card-cronos">
    <div class="table-responsive">
        <table class="table table-cronos align-middle">
            <thead>
                <tr>
                    <th>Identificador</th>
                    <th>Tipo</th>
                    <th>Marca / Modelo</th>
                    <th>Laboratorio</th>
                    <th>Estado</th>
                    <th>Observaciones</th>
                    <?php if (in_array($_SESSION['rol'], ['administrador','tecnico'])): ?>
                    <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if ($equipos): ?>
                <?php foreach ($equipos as $e): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($e['identificador']) ?></strong></td>
                    <td><?= htmlspecialchars($e['tipo']) ?></td>
                    <td><?= htmlspecialchars(($e['marca'] ?? '') . ' ' . ($e['modelo'] ?? '')) ?></td>
                    <td><?= htmlspecialchars($e['laboratorio'] ?? 'Sin asignar') ?></td>
                    <td>
                        <span class="badge-estado" style="background-color:<?= $e['color_hex'] ?>">
                            <?= htmlspecialchars($e['estado']) ?>
                        </span>
                    </td>
                    <td><small class="text-muted"><?= htmlspecialchars($e['observaciones'] ?? '-') ?></small></td>
                    <?php if (in_array($_SESSION['rol'], ['administrador','tecnico'])): ?>
                    <td>
                        <button class="btn btn-sm btn-cronos-outline"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEstado<?= $e['id_equipo'] ?>">
                            <i class="bi bi-pencil-square"></i> Estado
                        </button>
                    </td>
                    <?php endif; ?>
                </tr>

                <?php if (in_array($_SESSION['rol'], ['administrador','tecnico'])): ?>
                <div class="modal fade" id="modalEstado<?= $e['id_equipo'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="/index.php?pagina=inventario">
                                <input type="hidden" name="accion" value="estado">
                                <input type="hidden" name="id_equipo" value="<?= $e['id_equipo'] ?>">
                                <div class="modal-header">
                                    <h5 class="modal-title">Actualizar estado — <?= htmlspecialchars($e['identificador']) ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nuevo estado</label>
                                        <select name="id_estado_equipo" class="form-select" required>
                                            <?php foreach ($estados_equipo as $est): ?>
                                            <option value="<?= $est['id_estado_equipo'] ?>"
                                                <?= $est['nombre_estado'] === $e['estado'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($est['nombre_estado']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Motivo del cambio</label>
                                        <textarea name="descripcion" class="form-control" rows="3"
                                                  placeholder="Ej: equipo reparado, se reemplazó la fuente..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-cronos">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No hay equipos registrados.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal nuevo equipo -->
<?php if (in_array($_SESSION['rol'], ['administrador','tecnico'])): ?>
<div class="modal fade" id="modalNuevoEquipo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/index.php?pagina=inventario">
                <input type="hidden" name="accion" value="nuevo">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-lg"></i> Nuevo equipo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Identificador *</label>
                            <input type="text" name="identificador" class="form-control"
                                   placeholder="Ej: PC-107" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tipo *</label>
                            <select name="id_tipo_equipo" class="form-select" required>
                                <option value="" disabled selected>Seleccionar...</option>
                                <?php foreach ($tipos_equipo as $tipo): ?>
                                <option value="<?= $tipo['id_tipo_equipo'] ?>"><?= htmlspecialchars($tipo['nombre_tipo']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Modelo</label>
                            <input type="text" name="modelo" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Estado *</label>
                            <select name="id_estado_equipo" class="form-select" required>
                                <?php foreach ($estados_equipo as $est): ?>
                                <option value="<?= $est['id_estado_equipo'] ?>"
                                    <?= $est['nombre_estado'] === 'Correcto' ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($est['nombre_estado']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Laboratorio</label>
                            <select name="id_laboratorio" class="form-select">
                                <option value="">Sin asignar</option>
                                <?php foreach ($laboratorios as $lab): ?>
                                <option value="<?= $lab['id_laboratorio'] ?>"><?= htmlspecialchars($lab['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Observaciones</label>
                            <textarea name="observaciones" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-cronos">Agregar equipo</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>