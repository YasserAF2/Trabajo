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

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2 content">
            <?php
            // Verificar si la variable $resultado está definida en la sesión
            if (isset($_SESSION['resultado_solicitud'])) {
                // Mostrar el mensaje de resultado
                echo "<h1 class='text-center'>{$_SESSION['resultado_solicitud']}</h1>";
            } else {
                // Si la variable $resultado no está definida, mostrar un mensaje de error
                echo "<h1 class='text-center'>No se ha recibido ningún resultado.</h1>";
            }
            ?>
            <div class="text-center mt-3">
                <a href="index.php?action=solicitud_asuntos_propios" class="btn btn-primary">Volver a la página de solicitud de asuntos propios</a>
            </div>
        </div>
    </div>
</div>