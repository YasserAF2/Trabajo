<?php
    session_start();
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

    <div>
        
    </div>