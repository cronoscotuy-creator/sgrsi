<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="mb-1">Gestión de usuarios</h1>
        <p class="text-muted mb-0">Alta · Baja · Modificación · Búsqueda (RF-06)</p>
    </div>
    <button class="btn btn-cronos" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
        <i class="bi bi-person-plus"></i> Nuevo usuario
    </button>
</div>

<!-- Buscador -->
<div class="card-cronos mb-3">
    <form method="GET" action="/index.php" class="d-flex gap-2">
        <input type="hidden" name="pagina" value="usuarios">
        <input type="text" name="buscar" class="form-control"
               placeholder="Buscar por nombre, apellido o email..."
               value="<?= htmlspecialchars($busqueda) ?>">
        <button type="submit" class="btn btn-cronos">
            <i class="bi bi-search"></i> Buscar
        </button>
        <?php if ($busqueda): ?>
        <a href="/index.php?pagina=usuarios" class="btn btn-secondary">
            <i class="bi bi-x-lg"></i> Limpiar
        </a>
        <?php endif; ?>
    </form>
    <?php if ($busqueda): ?>
    <small class="text-muted mt-2 d-block">
        <?= count($usuarios) ?> resultado(s) para "<?= htmlspecialchars($busqueda) ?>"
    </small>
    <?php endif; ?>
</div>

<!-- Tabla de usuarios -->
<div class="card-cronos">
    <div class="table-responsive">
        <table class="table table-cronos align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Cambiar rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($usuarios): ?>
                <?php foreach ($usuarios as $u): ?>
                <tr class="<?= !$u['activo'] ? 'table-secondary text-muted' : '' ?>">
                    <td><?= $u['id_usuario'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($u['apellido'] . ', ' . $u['nombre']) ?></strong>
                        <?php if (!$u['activo']): ?>
                        <span class="badge bg-secondary ms-1">Inactivo</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <?php
                        $colores_rol = [
                            'administrador' => '#6C3483',
                            'tecnico'       => '#1A5276',
                            'solicitante'   => '#1A7A3C',
                        ];
                        ?>
                        <span class="badge-estado"
                              style="background-color:<?= $colores_rol[$u['nombre_rol']] ?? '#7F8C8D' ?>">
                            <?= htmlspecialchars($u['nombre_rol']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge <?= $u['activo'] ? 'bg-success' : 'bg-secondary' ?>">
                            <?= $u['activo'] ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($u['id_usuario'] != $_SESSION['id_usuario']): ?>
                        <form method="POST" action="/index.php?pagina=usuarios" class="d-flex gap-1">
                            <input type="hidden" name="accion" value="modificar">
                            <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
                            <select name="id_rol" class="form-select form-select-sm" style="width:auto">
                                <?php foreach ($roles as $r): ?>
                                <option value="<?= $r['id_rol'] ?>"
                                    <?= $r['id_rol'] == $u['id_rol'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($r['nombre_rol']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-sm btn-cronos-outline">
                                <i class="bi bi-check-lg"></i>
                            </button>
                        </form>
                        <?php else: ?>
                        <small class="text-muted">Tu cuenta</small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($u['id_usuario'] != $_SESSION['id_usuario']): ?>
                        <form method="POST" action="/index.php?pagina=usuarios">
                            <input type="hidden" name="accion" value="toggle">
                            <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
                            <input type="hidden" name="activo" value="<?= $u['activo'] ?>">
                            <button type="submit"
                                    class="btn btn-sm <?= $u['activo'] ? 'btn-outline-danger' : 'btn-outline-success' ?>"
                                    onclick="return confirm('<?= $u['activo'] ? '¿Desactivar' : '¿Activar' ?> a <?= htmlspecialchars($u['nombre']) ?>?')">
                                <i class="bi bi-<?= $u['activo'] ? 'person-slash' : 'person-check' ?>"></i>
                                <?= $u['activo'] ? 'Desactivar' : 'Activar' ?>
                            </button>
                        </form>
                        <?php else: ?>
                        <small class="text-muted">—</small>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <?= $busqueda ? 'No se encontraron usuarios.' : 'No hay usuarios registrados.' ?>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal nuevo usuario -->
<div class="modal fade" id="modalNuevoUsuario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/index.php?pagina=usuarios">
                <input type="hidden" name="accion" value="nuevo">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus"></i> Nuevo usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Apellido *</label>
                            <input type="text" name="apellido" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email institucional *</label>
                            <input type="email" name="email" class="form-control"
                                   placeholder="nombre@iti.edu.uy" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Contraseña *</label>
                            <input type="password" name="contrasena" class="form-control"
                                   minlength="6" required>
                            <small class="text-muted">Mínimo 6 caracteres.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Rol *</label>
                            <select name="id_rol" class="form-select" required>
                                <option value="" disabled selected>Seleccionar...</option>
                                <?php foreach ($roles as $r): ?>
                                <option value="<?= $r['id_rol'] ?>">
                                    <?= htmlspecialchars($r['nombre_rol']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-cronos">Crear usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>