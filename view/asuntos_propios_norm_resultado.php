<div class="container mt-5">
    <div class="alert alert-info" role="alert">
        <?php
        if (isset($_SESSION['resultado_solicitud'])) {
            echo $_SESSION['resultado_solicitud'];
            // Limpiar el mensaje de la sesión después de mostrarlo
            unset($_SESSION['resultado_solicitud']);
        } else {
            echo "No hay ningún resultado para mostrar.";
        }
        ?>
    </div>
    <div>
        <a href="index.php?action=logeado" class="btn btn-primary">Volver a la página principal</a>
    </div>
</div>