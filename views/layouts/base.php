<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'SGRSI' ?> | Cronos - ITI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-cronos">
    <div class="container-fluid" style="max-width:1200px;margin:0 auto;">
        <a class="navbar-brand" href="/index.php?pagina=dashboard">
            <i class="bi bi-hourglass-split"></i>
            <span>
                CRONOS
                <span class="badge-slogan d-block">Change On Time</span>
            </span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navCronos">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navCronos">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= ($pagina_actual ?? '') === 'dashboard' ? 'active' : '' ?>"
                       href="/index.php?pagina=dashboard">
                        <i class="bi bi-speedometer2"></i> Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($pagina_actual ?? '') === 'inventario' ? 'active' : '' ?>"
                       href="/index.php?pagina=inventario">
                        <i class="bi bi-pc-display"></i> Inventario
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($pagina_actual ?? '') === 'tickets' ? 'active' : '' ?>"
                       href="/index.php?pagina=tickets">
                        <i class="bi bi-life-preserver"></i> Mesa de Ayuda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($pagina_actual ?? '') === 'solicitudes' ? 'active' : '' ?>"
                       href="/index.php?pagina=solicitudes">
                        <i class="bi bi-envelope-paper"></i> Solicitudes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($pagina_actual ?? '') === 'prestamos' ? 'active' : '' ?>"
                       href="/index.php?pagina=prestamos">
                        <i class="bi bi-arrow-left-right"></i> Préstamos
                    </a>
                </li>
                <?php if (($_SESSION['rol'] ?? '') !== 'solicitante'): ?>
                <li class="nav-item">
                    <a class="nav-link <?= ($pagina_actual ?? '') === 'metricas' ? 'active' : '' ?>"
                       href="/index.php?pagina=metricas">
                        <i class="bi bi-graph-up"></i> Métricas
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item d-flex align-items-center text-white-50 me-2" style="font-size:.85rem;">
                    <i class="bi bi-person-circle me-1"></i>
                    <?= htmlspecialchars($_SESSION['nombre'] ?? '') ?>
                    <span class="badge bg-light text-dark ms-2" style="font-size:.7rem;">
                        <?= htmlspecialchars($_SESSION['rol'] ?? '') ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/index.php?pagina=logout">
                        <i class="bi bi-box-arrow-right"></i> Salir
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="contenido">
    <?php
    $flash = flash_get();
    if ($flash):
    ?>
    <div class="alert alert-<?= $flash['tipo'] ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($flash['mensaje']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?= $contenido ?? '' ?>
</main>

<footer class="footer-cronos">
    SGRSI &mdash; Sistema de Gestión de Recursos y Soporte de Informática
    &middot; Grupo Cronos &middot; ITI - CETP 2026
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/main.js"></script>
</body>
</html>