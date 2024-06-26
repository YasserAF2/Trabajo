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
<div class="container main-container" id="div1">
    <div class="form-container">
        <div class="d-flex flex-column align-items-center text-center">
            <img class="logo mb-4" src="view/template/imagenes/trace4-sin-fondo.png" alt="LOGOTIPO TRACE">
            <h2 class="mb-4 text-center">Solicitud de Licencia</h2>
        </div>
        <form action="index.php?action=submit_licencia" method="post" enctype="multipart/form-data"
            class="needs-validation" novalidate>
            <div class="form-group">
                <label for="archivo">Subir Archivo (máximo 10MB):</label>
                <input type="file" class="form-control-file" id="archivo" name="archivo" required>
                <div class="invalid-feedback">Por favor, suba un archivo.</div>
            </div>
            <input type="hidden" name="fecha" value="<?php echo $fechaActual; ?>">
            <input type="hidden" name="hora" value="<?php echo $horaActual; ?>">
            <div class="d-flex ">
                <button type="submit" class="btn btn-primary mr-1">Enviar Solicitud</button>
                <a href="index.php?action=logeado" class="btn btn-secondary">Volver atrás</a>
            </div>
        </form>
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
                    // Usar SweetAlert para mostrar el mensaje de error
                    Swal.fire({
                        title: 'Error',
                        text: 'El archivo no debe exceder los 10MB.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                } else {
                    fileInput.setCustomValidity('');
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    } else {
                        event
                    .preventDefault(); // Prevenir el envío del formulario para mostrar el diálogo
                        Swal.fire({
                            title: '¿Estás seguro de enviar esta solicitud?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Sí, enviar',
                            cancelButtonText: 'No, cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form
                            .submit(); // Enviar el formulario si el usuario confirma
                            }
                        });
                    }
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>