<div class="container-fluid">
    <!-- Contenedor principal -->
    <div class="row justify-content-center align-items-center h-100">
        <!-- Fila para centrar verticalmente -->
        <div class="col-md-6">
            <!-- Columna para el formulario -->
            <form action="index.php?action=logeado" method="post" class="needs-validation" novalidate>
                <div class="px-5 mb-4 d-flex justify-content-center">
                    <img class="logo img-fluid" src="view/template/imagenes/trace4-sin-fondo.png" alt="LOGOTIPO TRACE">
                </div>
                <div class="form-group">
                    <label for="correo">Correo:</label>
                    <input type="text" class="form-control" id="correo" name="correo" required>
                    <div class="invalid-feedback">Por favor, introduzca su correo.</div>
                </div>
                <div class="form-group">
                    <label for="contraseña">Contraseña:</label>
                    <input type="password" class="form-control" id="contraseña" name="contraseña" required>
                    <div class="invalid-feedback">Por favor, introduzca su contraseña.</div>
                </div>
                <!-- Mensaje de error -->
                <?php if (isset($_SESSION['login_error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_SESSION['login_error']; ?>
                    <?php unset($_SESSION['login_error']); // Elimina el mensaje de error después de mostrarlo ?>
                </div>
                <?php endif; ?>
                <div class="text-center d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary btn-block mb-3 w-50">Iniciar Sesión</button>
                </div>
                <div>
                    <p class="mb-0 text-center">¿No tienes cuenta? <a href="index.php?action=registro_dni"
                            class="btn btn-secondary btn-sm">Regístrate</a></p>
                </div>
            </form>
        </div>
    </div>
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