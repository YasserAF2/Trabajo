<div class="container mt-5" id="div1">
    <!-- Contenedor principal -->
    <div class="row justify-content-center align-items-center h-100">
        <!-- Fila para centrar verticalmente -->
        <div class="col-md-6">
            <!-- Columna para el formulario -->
            <form action="index.php?action=procesar_fecha" method="post"
                class="needs-validation d-flex flex-column align-items-center" novalidate>
                <div class="px-5 mb-4">
                    <img class="logo img-fluid" src="view/template/imagenes/trace4-sin-fondo.png" alt="LOGOTIPO TRACE">
                </div>
                <div class="form-group w-75">
                    <label for="fecha">Fecha:</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                    <div class="invalid-feedback">Por favor, introduzca una fecha válida.</div>
                </div>
                <div class="d-flex flex-column flex-md-row align-items-center w-75 justify-content-center">
                    <button type="submit" class="btn btn-primary mb-3 mb-md-0 me-md-3">Confirmar Fecha</button>
                    <a href="index.php" class="btn btn-secondary">Volver al inicio</a>
                </div>
            </form>
        </div>
    </div>
</div>