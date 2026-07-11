<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | Cronos - SGRSI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="login-wrapper">
    <div class="login-box">
        <h1><i class="bi bi-hourglass-split"></i> CRONOS</h1>
        <div class="slogan">Change On Time</div>

        <?php
        $flash = flash_get();
        if ($flash):
        ?>
        <div class="alert alert-<?= $flash['tipo'] ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($flash['mensaje']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <form method="POST" action="/index.php?pagina=login">
            <div class="mb-3">
                <label for="email" class="form-label">Correo institucional</label>
                <input type="email" class="form-control" id="email" name="email"
                       placeholder="nombre@iti.edu.uy" required autofocus>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" required>
            </div>
            <button type="submit" class="btn btn-cronos w-100">
                <i class="bi bi-box-arrow-in-right"></i> Ingresar
            </button>
        </form>

        <p class="text-center text-muted mt-4" style="font-size:.8rem;">
            SGRSI &mdash; ITI - CETP 2026
        </p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>