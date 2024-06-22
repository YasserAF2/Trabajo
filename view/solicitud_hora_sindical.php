<?php
session_start();

// Asigna la variable de sesión si no está definida
if (!isset($_SESSION['correo'])) {
    $_SESSION['correo'] = $correo;
}

// Verifica si la variable de sesión está definida
if (!isset($_SESSION['correo'])) {
    header("Location: index.php");
    exit();
}

// Obtiene la fecha y hora actual
$fechaActual = date('Y-m-d');
$horaActual = date('H:i:s');

?>
<div class="container mt-5">
    <h2>Solicitud de Hora Sindical</h2>
    <form action="index.php?action=submit_hora_sindical" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
        <div class="form-group">
            <label for="archivo">Subir Archivo (máximo 10MB):</label>
            <input type="file" class="form-control-file" id="archivo" name="archivo" required>
            <div class="invalid-feedback">Por favor, suba un archivo.</div>
        </div>
        <input type="hidden" name="fecha" value="<?php echo $fechaActual; ?>">
        <input type="hidden" name="hora" value="<?php echo $horaActual; ?>">
        <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
    </form>
    <div class="mt-4 text-end">
        <a href="index.php?action=logeado" class="btn btn-secondary">Volver atrás</a>
    </div>
</div>

<script>
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    var fileInput = document.getElementById('archivo');
                    var file = fileInput.files[0];
                    if (file && file.size > 10 * 1024 * 1024) { // 10 MB en bytes
                        event.preventDefault();
                        event.stopPropagation();
                        fileInput.setCustomValidity('El archivo no debe exceder los 10MB.');
                        fileInput.reportValidity();
                    } else {
                        fileInput.setCustomValidity('');
                    }

                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>