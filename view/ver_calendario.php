<?php
session_start();
if (!isset($_SESSION['correo'])) {
    header("Location: index.php");
    exit();
}
$trace = new Trace();
$peticiones = $trace->obtenerPeticionesAceptadas();
?>

<div class="container mt-5 mb-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>Calendario de Eventos</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <?php foreach ($peticiones as $fecha => $eventos) : ?>
                    <div class="col-md-4">
                        <div class="list-group">
                            <div class="list-group-item list-group-item-action active">
                                Solicitud Aceptada <?php echo $fecha; ?>
                            </div>
                            <?php foreach ($eventos as $evento) : ?>
                                <div class="list-group-item list-group-item-action">
                                    Turno: <?php echo $evento['turno']; ?> <br>
                                    Empleado: <?php echo $evento['nombre'] . ' ' . $evento['apellido1'] . ' ' . $evento['apellido2']; ?> <br>
                                    Supervisor: <?php echo $evento['supervisor']; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card-footer text-end mb-">
            <a href="index.php?action=admin" class="btn btn-secondary">Volver atrás</a>
        </div>
    </div>
</div>