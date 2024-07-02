<?php
session_start();
if (!isset($_SESSION['correo'])) {
    header("Location: index.php");
    exit();
}
$trace = new Trace();
$peticiones = $trace->obtenerPeticionesAceptadas();
?>

<div class="container mt-5">
    <h1 class="mb-4">Peticiones Aceptadas</h1>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Fecha</th>
                <th>DNI</th>
                <th>Nombre y Apellidos</th>
                <th>Tipo</th>
                <th>Fecha Solicitud</th>
                <th>Supervisor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($peticiones as $peticion) : ?>
            <tr>
                <td><?php echo date("d/m/Y", strtotime($peticion['PET_FECHA'])); ?></td>
                <td><?php echo $peticion['PET_DNI']; ?></td>
                <td><?php echo $peticion['EMP_NOMBRE'] . ' ' . $peticion['EMP_APE_1']; ?></td>
                <td><?php echo $peticion['PET_TIPO']; ?></td>
                <td><?php echo date("d/m/Y H:i:s", strtotime($peticion['PET_FECHA_HORA_SOLICITUD'])); ?></td>
                <td><?php echo $peticion['PET_SUPERVISOR']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="mb-3">
        <a href="index.php?action=excel" class="btn btn-success">Descargar como Excel</a>
        <a href="index.php?action=admin" class="btn btn-secondary">Volver atrás</a>
    </div>
</div>