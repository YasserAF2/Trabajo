<div class="formulario">
        <form action="index.php?action=logeado" method="post"
            class="needs-validation d-flex flex-column justify-content-center align-items-center" novalidate>
            <div class="px-5 ms-xl-4">
                <img class="logo" src="view/template/imagenes/trace4-sin-fondo.png" alt="LOGOTIPO TRACE">
            </div>
            <div class="form-group w-50">
                <label for="correo">Correo:</label>
                <input type="text" class="form-control" id="correo" name="correo" required>
                <div class="invalid-feedback">Por favor, introduzca su correo.</div>
            </div>
            <div class="form-group w-50">
                <label for="contraseña">Contraseña:</label>
                <input type="password" class="form-control" id="contraseña" name="contraseña" required>
                <div class="invalid-feedback">Por favor, introduzca su contraseña.</div>
            </div>
            <button type="submit" class="btn btn-primary w-25">Iniciar Sesión</button>
            <div class="mt-3">
                <p>¿No tienes cuenta? <a href="index.php?action=registro_dni" class="btn btn-secondary">Regístrate</a></p>
            </div>
        </form>
</div>

<script>
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
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