<main>
    <section>
        <article>
            <form class="needs-validation" action="procesar_solicitud.php" method="POST" enctype="multipart/form-data"
                novalidate>
                <div class="form-group">
                    <label for="tipo_licencia">Tipo de licencia:</label>
                    <input type="text" class="form-control" id="tipo_licencia" name="tipo_licencia" required>
                    <div class="invalid-feedback">
                        Por favor, introduce el tipo de licencia.
                    </div>
                </div>

                <div class="form-group">
                    <label for="documentacion">Documentación necesaria:</label>
                    <input type="file" class="form-control-file" id="documentacion" name="documentacion"
                        accept=".pdf,.doc,.docx" required>
                    <div class="invalid-feedback">
                        Por favor, selecciona la documentación necesaria en formato PDF, DOC o DOCX.
                    </div>
                </div>

                <input type="submit" class="btn btn-primary" value="Enviar solicitud">
                <a class="btn btn-primary" href="index.php">Volver atrás</a>
            </form>
        </article>
    </section>
</main>