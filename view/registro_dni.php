<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="index.php?action=procesar_dni" method="post" class="needs-validation d-flex flex-column align-items-center" novalidate>
                <div class="px-5 ms-xl-4">
                    <img class="logo" src="view/template/imagenes/trace4-sin-fondo.png" alt="LOGOTIPO TRACE">
                </div>
                <div class="form-group w-75">
                    <label for="dni">DNI:</label>
                    <input type="text" class="form-control" id="dni" name="dni" required>
                    <div class="invalid-feedback">Por favor, introduzca su DNI.</div>
                </div>
                <div class="d-flex flex-column flex-md-row align-items-center w-75 justify-content-center">
                    <button type="submit" class="btn btn-primary mb-3 mb-md-0 me-md-3 mr-3">Confirmar DNI</button>
                    <a href="index.php" class="btn btn-secondary">Volver al inicio</a>
                </div>
            </form>
        </div>
    </div>
</div>