<?php
if (!isset($_SESSION['correo'])) {
    header("Location: index.php");
    exit();
}

$correo = $_SESSION['correo'];
$peticiones = $dataToView['peticiones'];
// verifica el tipo de usuario
$trace = new Trace();
$tipo = $trace->tipo_empleado();

?>

<div class="container">
    <div class="formulario">
        <div class="d-flex justify-content-between align-items-center px-5 ms-xl-4 mb-2 mt-2">
            <img class="logo" src="view/template/imagenes/trace4-sin-fondo.png" alt="LOGOTIPO TRACE">
            <div class="perfil-titulo text-end">
                <h1 class="mb-0">Lista de Peticiones</h1>
            </div>
        </div>

        <?php if (!empty($peticiones)) : ?>
            <div class="mt-4">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>DNI</th>
                            <th>Nombre y Apellidos</th>
                            <th>Tipo</th>
                            <th>Fecha y hora de solicitud</th>
                            <th>Estado</th>
                            <th>Supervisor</th>
                            <th>Acciones</th> <!-- Nueva columna para los botones -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($peticiones as $peticion) : ?>
                            <tr>
                                <td><?= $peticion['PET_DNI'] ?></td>
                                <td><?= $peticion['EMP_NOMBRE'] . ' ' . $peticion['EMP_APE_1'] . ' ' . $peticion['EMP_APE_2'] ?></td>
                                <td><?= $peticion['PET_TIPO'] ?></td>
                                <td><?= $peticion['PET_FECHA_HORA_SOLICITUD'] ?></td>
                                <td><?= $peticion['PET_ACEPTADO'] ?></td>
                                <td><?= $peticion['PET_SUPERVISOR'] ?></td>
                                <td>
                                    <?php if ($tipo == 'ADMINISTRADOR' || $tipo == 'SUPERUSUARIO') : ?>
                                        <a href="index.php?action=aceptar&peticion_id=<?= $peticion['PET_ID'] ?>" class="btn btn-success" onclick="return confirm('¿Estás seguro de que quieres aceptar esta petición?')">Aceptar</a>
                                        <a href="index.php?action=rechazar&peticion_id=<?= $peticion['PET_ID'] ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres rechazar esta petición?')">Rechazar</a>
                                    <?php else : ?>
                                        <button class="btn btn-success" disabled>Aceptar</button>
                                        <button class="btn btn-danger" disabled>Rechazar</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <p>No hay peticiones disponibles.</p>
        <?php endif; ?>

        <div class="mt-2 mb-2 text-end">
            <a href="index.php?action=admin" class="btn btn-secondary">Volver atrás</a>
        </div>
    </div>
</div>