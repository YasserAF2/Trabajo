<?php
session_start();
?>
<main>
    <section>
        <article>
            <h2>Solicitud de días de asuntos propios</h2>
            <form enctype="multipart/form-data" action="index.php?action=procesar_asuntos" method="POST">
                <div class="form-group">
                    <label for="fecha">Fecha(s) de asuntos propios:</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                </div>
                <div class="form-group">
                    <label for="motivo">Motivo:</label>
                    <textarea class="form-control" id="motivo" name="motivo" rows="3" required></textarea>
                </div>
                <div>
                    <input type="hidden" name="correo" value="<?php echo $_SESSION['usuario']; ?>">
                </div>
                <div class="mb-3">
                    <input type="submit" class="btn btn-primary" value="Enviar solicitud">
                    <a class="btn btn-primary" href="index.php">Volver atrás</a>
                </div>
            </form>
        </article>
    </section>
</main>