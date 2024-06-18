<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="text-center mb-4">Mensaje</h1>
            <?php
            // Verifica si hay un mensaje de éxito en la sesión y lo muestra
            if (isset($_SESSION['success_mensaje'])) {
                echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_mensaje'] . '</div>';
                unset($_SESSION['success_mensaje']); // Limpia el mensaje de la sesión después de mostrarlo
            }
            // Verifica si hay un mensaje de error en la sesión y lo muestra
            if (isset($_SESSION['error_mensaje'])) {
                echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_mensaje'] . '</div>';
                unset($_SESSION['error_mensaje']); // Limpia el mensaje de la sesión después de mostrarlo
            }
            ?>
            <form action="index.php" method="get" class="text-center">
                <input type="hidden" name="action" value="logeado">
                <button type="submit" class="btn btn-primary mt-3">Volver a Logeado</button>
            </form>
        </div>
    </div>
</div>