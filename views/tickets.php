<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="mb-1">Mesa de ayuda</h1>
        <p class="text-muted mb-0">Registro y seguimiento de incidencias (RF-03)</p>
    </div>
    <button class="btn btn-cronos" data-bs-toggle="modal" data-bs-target="#modalNuevoTicket">
        <i class="bi bi-plus-lg"></i> Nuevo ticket
    </button>
</div>

<div class="card-cronos">
    <div class="table-responsive">
        <table class="table table-cronos align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Salón</th>
                    <th>Equipo</th>
                    <th>Incidente</th>
                    <th>Prioridad</th>
                    <th>Estado</th>
                    <th>Solicitante</th>
                    <?php if (in_array($_SESSION['rol'], ['administrador','tecnico'])): ?>
                    <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if ($tickets): ?>
                <?php foreach ($tickets as $t): ?>
                <?php
                $colores_estado = [
                    'Pendiente'  => '#F7941E',
                    'En proceso' => '#0089B8',
                    'Resuelto'   => '#43B02A',
                ];
                ?>
                <tr>
                    <td>#<?= $t['id_ticket'] ?></td>
                    <td><?= htmlspecialchars($t['laboratorio'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($t['equipo'] ?? '-') ?></td>
                    <td>
                        <?= htmlspecialchars($t['incidente']) ?>
                        <?php if ($t['descripcion']): ?>
                        <br><small class="text-muted">
                            <?= htmlspecialchars(substr($t['descripcion'], 0, 50)) ?>
                            <?= strlen($t['descripcion']) > 50 ? '...' : '' ?>
                        </small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge-estado" style="background-color:<?= $t['color_prioridad'] ?>">
                            <?= htmlspecialchars($t['prioridad']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge-estado"
                              style="background-color:<?= $colores_estado[$t['estado']] ?? '#7F8C8D' ?>">
                            <?= htmlspecialchars($t['estado']) ?>
                        </span>
                    </td>
                    <td>
                        <small><?= htmlspecialchars($t['solicitante_nombre'] . ' ' . $t['solicitante_apellido']) ?></small>
                    </td>
                    <?php if (in_array($_SESSION['rol'], ['administrador','tecnico'])): ?>
                    <td>
                        <button class="btn btn-sm btn-cronos-outline"
                                data-bs-toggle="modal"
                                data-bs-target="#modalTicket<?= $t['id_ticket'] ?>">
                            <i class="bi bi-pencil-square"></i> Gestionar
                        </button>
                    </td>
                    <?php endif; ?>
                </tr>

                <?php if (in_array($_SESSION['rol'], ['administrador','tecnico'])): ?>
                <div class="modal fade" id="modalTicket<?= $t['id_ticket'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="/index.php?pagina=tickets">
                                <input type="hidden" name="accion" value="estado">
                                <input type="hidden" name="id_ticket" value="<?= $t['id_ticket'] ?>">
                                <div class="modal-header">
                                    <h5 class="modal-title">Ticket #<?= $t['id_ticket'] ?> — <?= htmlspecialchars($t['incidente']) ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Descripción:</strong> <?= htmlspecialchars($t['descripcion'] ?? 'Sin descripción') ?></p>
                                    <p>
                                        <strong>Salón:</strong> <?= htmlspecialchars($t['laboratorio'] ?? '-') ?>
                                        &middot; <strong>Equipo:</strong> <?= htmlspecialchars($t['equipo'] ?? '-') ?>
                                    </p>
                                    <div class="mb-3">
                                        <label class="form-label">Estado</label>
                                        <select name="id_estado_ticket" class="form-select" required>
                                            <?php foreach ($estados_ticket as $est): ?>
                                            <option value="<?= $est['id_estado_ticket'] ?>"
                                                <?= $est['id_estado_ticket'] == $t['id_estado_ticket'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($est['nombre_estado']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox"
                                               id="resuelto<?= $t['id_ticket'] ?>"
                                               onchange="if(this.checked){this.closest('form').querySelector('select[name=id_estado_ticket]').value='3'}">
                                        <label class="form-check-label" for="resuelto<?= $t['id_ticket'] ?>">
                                            Marcar como resuelto rápidamente
                                        </label>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nota de resolución</label>
                                        <textarea name="nota_resolucion" class="form-control" rows="3"
                                                  placeholder="Ej: se reconectó el cable, problema solucionado."
                                        ><?= htmlspecialchars($t['nota_resolucion'] ?? '') ?></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-cronos">Guardar cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No hay tickets registrados.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal nuevo ticket -->
<div class="modal fade" id="modalNuevoTicket" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/index.php?pagina=tickets">
                <input type="hidden" name="accion" value="nuevo">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-life-preserver"></i> Nuevo ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Salón / Laboratorio</label>
                            <select name="id_laboratorio" class="form-select">
                                <option value="">Sin especificar</option>
                                <?php foreach ($laboratorios as $lab): ?>
                                <option value="<?= $lab['id_laboratorio'] ?>">
                                    <?= htmlspecialchars($lab['nombre']) ?> (<?= htmlspecialchars($lab['numero_salon']) ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">N° de equipo</label>
                            <select name="id_equipo" class="form-select">
                                <option value="">Sin especificar</option>
                                <?php foreach ($equipos as $eq): ?>
                                <option value="<?= $eq['id_equipo'] ?>"><?= htmlspecialchars($eq['identificador']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Tipo de incidente *</label>
                            <select name="incidente" id="selectIncidente" class="form-select" required>
                                <option value="" disabled selected>Seleccionar...</option>
                                <?php foreach ($incidentes_comunes as $inc): ?>
                                <option value="<?= $inc ?>"><?= $inc ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" name="incidente_otro" id="incidenteOtro"
                                   class="form-control mt-2 d-none"
                                   placeholder="Describir el incidente...">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Prioridad *</label>
                            <select name="id_prioridad" class="form-select" required>
                                <?php foreach ($prioridades as $p): ?>
                                <option value="<?= $p['id_prioridad'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción adicional</label>
                            <textarea name="descripcion" class="form-control" rows="3"
                                      placeholder="Detalles del problema..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-cronos">Crear ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('selectIncidente').addEventListener('change', function() {
    const otro = document.getElementById('incidenteOtro');
    if (this.value === 'Otro') {
        otro.classList.remove('d-none');
        otro.required = true;
    } else {
        otro.classList.add('d-none');
        otro.required = false;
    }
});
</script>