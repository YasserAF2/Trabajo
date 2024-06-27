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
?>
<div class="formulario" id="div1">
    <form action="index.php?action=enviar_mensaje" method="post" class="needs-validation d-flex flex-column justify-content-center align-items-center" novalidate>
        <div class="px-5 ms-xl-4">
            <img class="logo" src="view/template/imagenes/trace4-sin-fondo.png" alt="LOGOTIPO TRACE">
        </div>
        <h3 class="mt-4">Mensaje al departamento de producción</h3>
        <div class="form-group w-50">
            <label for="mensaje">Mensaje:</label>
            <textarea class="form-control" id="mensaje" name="mensaje" rows="4" required></textarea>
            <div class="invalid-feedback">Por favor, introduzca su mensaje.</div>
            <input type="hidden" name="destinatario" value="jlmelgar@traceserviciosurbanos.com">
        </div>
        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary">Enviar</button>
            <a href="index.php?action=logeado" class="btn btn-secondary">Volver atrás</a>
        </div>
    </form>
</div>

<script>
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    } else {
                        // Mostrar cuadro de diálogo de confirmación con SweetAlert
                        event
                            .preventDefault(); // Prevenir el envío del formulario para mostrar el diálogo
                        Swal.fire({
                            title: '¿Estás seguro de enviar este mensaje?',
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
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>