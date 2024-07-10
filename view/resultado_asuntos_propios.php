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

<div class="container solicitud">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-5 mb-5">
                <div class="card-body text-center">
                    <?php
                    // Verificar si la variable $resultado está definida en la sesión
                    if (isset($_SESSION['resultado_solicitud'])) {
                        // Mostrar el mensaje de resultado
                        echo "<h1>{$_SESSION['resultado_solicitud']}</h1>";
                    } else {
                        // Si la variable $resultado no está definida, mostrar un mensaje de error
                        echo "<h1>No se ha recibido ningún resultado.</h1>";
                    }
                    ?>
                    <div class="mt-3">
                        <a href="index.php?action=solicitud_asuntos_propios" class="btn btn-primary">Volver atrás</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>