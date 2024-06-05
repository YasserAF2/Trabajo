<body>
    <div class="formulario">
        <form action="index.php?action=procesar_fecha" method="post" class="needs-validation d-flex flex-column justify-content-center align-items-center">
            <div class="px-5 ms-xl-4">
                <img class="logo" src="view/template/imagenes/trace4-sin-fondo.png" alt="LOGOTIPO TRACE">
            </div>
            <div class="form-group w-25">
                <label for="fecha">Fecha:</label>
                <input type="date" class="form-control" id="fecha" name="fecha" required>
                <div class="invalid-feedback">Por favor, introduzca una fecha válida.</div>
            </div>
            <button type="submit" class="btn btn-primary w-25">Enviar</button>        
            <a href="index.php" class="btn btn-secondary mt-3">Volver al inicio</a>
        </form>
    </div>
</body>
