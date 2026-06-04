<?php
// modulos/auth/login.php
// Prototipo de login para SGRSI
// Roles: coordinador (admin), asistente (intermedio), docente (básico)
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-5">
        
        <!-- Tarjeta de login con estilo institucional Ceibal -->
        <div class="card shadow-lg border-0">
            
            <!-- Header con logo y título -->
            <div class="card-header text-center bg-primary-ceibal py-4">
                <h3 class="text-white mb-0 fw-bold"> SGRSI</h3>
                <small class="text-white-50">Sistema de Gestión de Recursos ITI</small>
            </div>
            
            <div class="card-body p-4">
                
                <!-- Mensaje de error (se muestra con JS si hay fallo) -->
                <div id="mensaje-error" class="alert alert-danger d-none" role="alert">
                    Credenciales inválidas. Verificá tu usuario y contraseña.
                </div>
                
                <!-- Formulario de login -->
                <form id="formulario-login" method="POST" action="index.php?pagina=panel">
                    
                    <!-- Selector de rol (para prototipo - en producción va oculto o por sesión) -->
                    <div class="mb-4">
                        <label for="rol" class="form-label fw-semibold">Tipo de usuario</label>
                        <select class="form-select" id="rol" name="rol" required>
                            <option value="" selected disabled>Seleccioná tu rol...</option>
                            <option value="coordinador">Coordinador (Administrador)</option>
                            <option value="asistente">Asistente Técnico</option>
                            <option value="docente">Docente / Solicitante</option>
                        </select>
                        <small class="text-muted d-block mt-1">
                            <span class="badge bg-info-ceibal">Coordinador:</span> Acceso total
                            <span class="badge bg-warning-ceibal ms-1">Asistente:</span> Gestión técnica
                            <span class="badge bg-secondary-ceibal ms-1">Docente:</span> Reportes y consultas
                        </small>
                    </div>
                    
                    <!-- Campo correo -->
                    <div class="mb-3">
                        <label for="correo" class="form-label fw-semibold">Correo institucional</label>
                        <input type="email" 
                               class="form-control form-control-lg" 
                               id="correo" 
                               name="correo" 
                               placeholder="ej: nombre@iti.edu.uy"
                               required
                               autocomplete="email">
                    </div>
                    
                    <!-- Campo contraseña -->
                    <div class="mb-4">
                        <label for="contrasena" class="form-label fw-semibold">Contraseña</label>
                        <input type="password" 
                               class="form-control form-control-lg" 
                               id="contrasena" 
                               name="contrasena" 
                               placeholder="••••••••"
                               required
                               autocomplete="current-password">
                        <div class="form-text">Mínimo 6 caracteres</div>
                    </div>
                    
                    <!-- Checkbox "Recordarme" y enlace "¿Olvidaste tu contraseña?" -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="recordar" name="recordar">
                            <label class="form-check-label small" for="recordar">Recordarme</label>
                        </div>
                        <a href="#" class="small text-primary-ceibal text-decoration-none">¿Olvidaste tu contraseña?</a>
                    </div>
                    
                    <!-- Botón de ingreso -->
                    <button type="submit" class="btn btn-primary-ceibal btn-lg w-100 fw-semibold py-2">
                        Ingresar al sistema →
                    </button>
                    
                </form>
                
            </div>
            
            <!-- Footer con información institucional -->
            <div class="card-footer text-center bg-light-ceibal py-3">
                <small class="text-muted">
                    Equipo Cronos | ITI 2026<br>
                    <span class="text-primary-ceibal fw-semibold">"Change On Time"</span>
                </small>
            </div>
            
        </div>
        
        <!-- Leyenda de roles -->
        <div class="mt-4 p-3 bg-light-ceibal rounded border small">
            <h6 class="fw-semibold mb-2">📋 Roles del sistema (prototipo):</h6>
            <ul class="list-unstyled mb-0">
                <li><span class="badge bg-info-ceibal">Coordinador</span> → ABM usuarios, reportes globales, configuración</li>
                <li><span class="badge bg-warning-ceibal">Asistente</span> → Gestión de equipos, tickets, inventario</li>
                <li><span class="badge bg-secondary-ceibal">Docente</span> → Reportar fallas, consultar estado de aulas</li>
            </ul>
        </div>
        
    </div>
</div>

<!-- Script de validación y simulación de login (para prototipo) -->
<script>
$(document).ready(function() {
    
    // Interceptamos el envío del formulario
    $('#formulario-login').on('submit', function(evento) {
        
        // Limpiamos mensajes previos
        $('#mensaje-error').addClass('d-none');
        $('.form-control').removeClass('is-invalid');
        
        // Validaciones básicas del lado del cliente
        let hayErrores = false;
        const correo = $('#correo').val().trim();
        const contrasena = $('#contrasena').val().trim();
        const rol = $('#rol').val();
        
        // Validar correo institucional (dominio @iti.edu.uy o similar)
        if (!correo.includes('@') || correo.length < 8) {
            $('#correo').addClass('is-invalid');
            hayErrores = true;
        }
        
        // Validar longitud de contraseña
        if (contrasena.length < 6) {
            $('#contrasena').addClass('is-invalid');
            hayErrores = true;
        }
        
        // Validar que se haya seleccionado un rol
        if (!rol) {
            $('#rol').addClass('is-invalid');
            hayErrores = true;
        }
        
        // Si hay errores, bloqueamos el envío y mostramos mensaje
        if (hayErrores) {
            evento.preventDefault();
            $('#mensaje-error')
                .removeClass('d-none')
                .text('⚠️ Revisá los campos marcados en rojo.');
            return false;
        }
        
        // === SIMULACIÓN DE LOGIN (SOLO PARA PROTOTIPO) ===
        // En producción, esto va en un controlador PHP con password_verify()
        evento.preventDefault(); // Bloqueamos envío real para demostrar
        
        // Simulamos redirección según rol seleccionado
        const rolSeleccionado = $('#rol').find(':selected').text();
        const nombreUsuario = correo.split('@')[0];
        
        // Mostramos feedback visual
        const btn = $(this).find('button[type="submit"]');
        const textoOriginal = btn.html();
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Verificando...');
        
        // Simulamos delay de red (1.5 segundos)
        setTimeout(function() {
            // Mensaje de éxito (solo demo)
            alert(`¡Bienvenido, ${nombreUsuario}!\n\nRol: ${rolSeleccionado}\n\n[En producción: redirección al panel correspondiente]`);
            
            // Restauramos botón (para que puedan probar de nuevo)
            btn.prop('disabled', false).html(textoOriginal);
            
            // En producción, acá iría: window.location.href = 'index.php?pagina=panel&rol=' + rol;
            
        }, 1500);
        
    });
    
    // Efecto visual: quitar error al escribir
    $('.form-control, #rol').on('input change', function() {
        $(this).removeClass('is-invalid');
        $('#mensaje-error').addClass('d-none');
    });
    
});
</script>
