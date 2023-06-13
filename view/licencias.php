<?php
session_start();
?>
<main>
    <section>
        <article>
            <h2>Solicitud de licencias</h2>
            <form enctype="multipart/form-data" action="index.php?action=procesar_formulario" method="POST">
                <div class="form-group">
                    <label for="tipo_licencia">Tipo de licencia:</label>
                    <select class="form-control" id="tipo_licencia" name="tipo_licencia" required>
                        <?php
                        $tiposLicencia = array(
                            'BAJA ACCTE',
                            'ASTOS.PROP.',
                            'AS.PROP.NO R',
                            'LACTANCIA',
                            'DESCANSO',
                            'BAJA ENFERM.',
                            'FALTA/SANC',
                            'HOR.SINDICAL',
                            'DESC.JORNADA',
                            'LICENCIA',
                            'MATERNIDAD',
                            'PATERNIDAD',
                            'VACACIONES',
                            'EXCEDENCIA'
                        );

                        foreach ($tiposLicencia as $tipo) {
                            echo "<option value='$tipo'>$tipo</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="documentacion">Documentación necesaria:</label>
                    <input type="file" class="form-control-file" id="documentacion" name="documentacion" accept=".pdf,.doc,.docx" required>
                </div>
                <div>
                    <input type="hidden" name="correo" value="<?php echo $_SESSION['usuario']; ?>" />
                </div>
                <div>
                    <input type="submit" class="btn btn-primary" value="Enviar solicitud">
                    <a class="btn btn-primary" href="index.php">Volver atrás</a>
                </div>
            </form>
        </article>
    </section>
</main>