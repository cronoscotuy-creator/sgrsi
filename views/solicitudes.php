<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="mb-1">Solicitudes de servicio</h1>
        <p class="text-muted mb-0">Canal formal para docentes y funcionarios (RF-05)</p>
    </div>
    <button class="btn btn-cronos" data-bs-toggle="modal" data-bs-target="#modalNuevaSolicitud">
        <i class="bi bi-plus-lg"></i> Nueva solicitud
    </button>
</div>

<div class="card-cronos">
    <div class="table-responsive">
        <table class="table table-cronos align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>Laboratorio</th>
                    <th>Solicitante</th>
                    <th>Fecha necesaria</th>
                    <th>Estado</th>
                    <?php if (in_array($_SESSION['rol'], ['administrador','tecnico'])): ?>
                    <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if ($solicitudes): ?>
                <?php foreach ($solicitudes as $s): ?>
                <?php
                $colores_sol = [
                    'Pendiente'  => '#F7941E',
                    'En proceso' => '#0089B8',
                    'Completada' => '#43B02A',
                    'Rechazada'  => '#E63946',
                ];
                ?>
                <tr>
                    <td>#<?= $s['id_solicitud'] ?></td>
                    <td><?= htmlspecialchars($s['tipo']) ?></td>
                    <td><?= htmlspecialchars(substr($s['descripcion'], 0, 60)) . (strlen($s['descripcion']) > 60 ? '...' : '') ?></td>
                    <td><?= htmlspecialchars($s['laboratorio'] ?? '-') ?></td>
                    <td><small><?= htmlspecialchars($s['solicitante_nombre'] . ' ' . $s['solicitante_apellido']) ?></small></td>
                    <td><?= $s['fecha_necesaria'] ?? '-' ?></td>
                    <td>
                        <span class="badge-estado"
                              style="background-color:<?= $colores_sol[$s['estado']] ?? '#7F8C8D' ?>">
                            <?= htmlspecialchars($s['estado']) ?>
                        </span>
                    </td>
                    <?php if (in_array($_SESSION['rol'], ['administrador','tecnico'])): ?>
                    <td>
                        <form method="POST" action="/index.php?pagina=solicitudes" class="d-flex gap-1">
                            <input type="hidden" name="accion" value="estado">
                            <input type="hidden" name="id_solicitud" value="<?= $s['id_solicitud'] ?>">
                            <select name="id_estado_solicitud" class="form-select form-select-sm" style="width:auto">
                                <?php foreach ($estados_solicitud as $est): ?>
                                <option value="<?= $est['id_estado_solicitud'] ?>"
                                    <?= $est['id_estado_solicitud'] == $s['id_estado_solicitud'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($est['nombre_estado']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-sm btn-cronos-outline">
                                <i class="bi bi-check-lg"></i>
                            </button>
                        </form>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No hay solicitudes registradas.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal nueva solicitud -->
<div class="modal fade" id="modalNuevaSolicitud" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/index.php?pagina=solicitudes">
                <input type="hidden" name="accion" value="nuevo">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-envelope-paper"></i> Nueva solicitud</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tipo de solicitud *</label>
                        <select name="id_tipo_solicitud" class="form-select" required>
                            <option value="" disabled selected>Seleccionar...</option>
                            <?php foreach ($tipos_solicitud as $tipo): ?>
                            <option value="<?= $tipo['id_tipo_solicitud'] ?>"><?= htmlspecialchars($tipo['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Laboratorio (opcional)</label>
                        <select name="id_laboratorio" class="form-select">
                            <option value="">Sin especificar</option>
                            <?php foreach ($laboratorios as $lab): ?>
                            <option value="<?= $lab['id_laboratorio'] ?>">
                                <?= htmlspecialchars($lab['nombre']) ?> (<?= htmlspecialchars($lab['numero_salon']) ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción *</label>
                        <textarea name="descripcion" class="form-control" rows="3"
                                  placeholder="Ej: instalar Python 3.12 en todas las PC del lab 1"
                                  required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha necesaria (opcional)</label>
                        <input type="date" name="fecha_necesaria" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-cronos">Enviar solicitud</button>
                </div>
            </form>
        </div>
    </div>
</div>