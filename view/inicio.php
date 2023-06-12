<div class="formulario">
    <form action="index.php?action=logeado" method="post" class="needs-validation d-flex flex-column justify-content-center align-items-center" novalidate>
        <div class="form-group w-50">
            <label for="usuario">Usuario:</label>
            <input type="text" class="form-control" id="usuario" name="usuario" required>
            <div class="invalid-feedback">Por favor, ingrese su usuario.</div>
        </div>
        <div class="form-group w-50">
            <label for="contraseña">Contraseña:</label>
            <input type="password" class="form-control" id="contraseña" name="contraseña" required>
            <div class="invalid-feedback">Por favor, ingrese su contraseña.</div>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="recordar" name="recordar">
            <label class="form-check-label" for="recordar">Recordarme</label>
        </div>
        <button type="submit" class="btn btn-primary w-25">Iniciar Sesión</button>
    </form>
</div>