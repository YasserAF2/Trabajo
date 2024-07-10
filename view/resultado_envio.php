<?php

// Asigna la variable de sesión si no está definida
if (!isset($_SESSION['correo'])) {
    $_SESSION['correo'] = $correo;
}

// Verifica si la variable de sesión está definida
if (!isset($_SESSION['correo'])) {
    header("Location: index.php");
    exit();
}

?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Cambiado de col-md-6 a col-md-8 para emular la segunda vista -->
            <div class="card mt-5 mb-5">
                <!-- Agregado el div con clase "card" -->
                <div class="card-body text-center">
                    <h1 class="text-center mb-4">Mensaje</h1>
                    <?php
                    // Verifica si hay un mensaje de éxito en la sesión y lo muestra
                    if (isset($_SESSION['success_mensaje'])) {
                        echo '<h2 class="alert alert-success" role="alert">' . $_SESSION['success_mensaje'] . '</h2>';
                        unset($_SESSION['success_mensaje']); // Limpia el mensaje de la sesión después de mostrarlo
                    }
                    // Verifica si hay un mensaje de error en la sesión y lo muestra
                    if (isset($_SESSION['error_mensaje'])) {
                        echo '<h2 class="alert alert-danger" role="alert">' . $_SESSION['error_mensaje'] . '</h2>';
                        unset($_SESSION['error_mensaje']); // Limpia el mensaje de la sesión después de mostrarlo
                    }
                    ?>
                    <div class="mt-4">
                        <!-- Ajuste de margen para alinear con la segunda vista -->
                        <a href="index.php?action=volver" class="btn btn-secondary">Volver atrás</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>