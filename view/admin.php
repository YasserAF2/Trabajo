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


    <div class="container mt-5 mb-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3>Consola de Administración</h3>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="index.php?action=ver_solicitudes_ap" class="list-group-item list-group-item-action">Ver Solicitudes Pendientes AP</a>
                    <a href="index.php?action=ver_solicitudes_as" class="list-group-item list-group-item-action">Ver Solicitudes Pendientes AS</a>
                    <a href="index.php?action=ver_calendario" class="list-group-item list-group-item-action">Ver Calendario</a>
                    <a href="index.php?action=logeado" class="list-group-item list-group-item-action btn btn-secondary">Volver atrás</a>
                </div>
            </div>
        </div>
    </div>