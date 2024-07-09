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

$correo = $_SESSION['correo'];
$peticiones = $dataToView['peticiones'];
?>

<div class="container" id="div1">
    <div class="formulario">
        <div class="header d-flex justify-content-between align-items-center px-5 ms-xl-4 mb-2 mt-2">
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
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Fecha y hora de solicitud</th>
                            <th>Aceptado</th>
                            <th>Supervisor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($peticiones as $peticion) : ?>
                            <tr>
                                <td><?= $peticion['PET_DNI'] ?></td>
                                <td>
                                    <?php
                                    $fecha_peticion = $peticion['PET_FECHA'];
                                    $fecha_formateada_peticion = date("d/m/Y", strtotime($fecha_peticion));
                                    echo $fecha_formateada_peticion;
                                    ?>
                                </td>
                                <td><?= $peticion['PET_TIPO'] ?></td>
                                <td>
                                    <?php
                                    $fecha_hora_solicitud = $peticion['PET_FECHA_HORA_SOLICITUD'];
                                    $fecha = date("d/m/Y", strtotime($fecha_hora_solicitud));
                                    $hora = date("H:i:s", strtotime($fecha_hora_solicitud));
                                    echo $fecha . ' ' . $hora;
                                    ?>
                                </td>
                                <td><?= $peticion['PET_ACEPTADO'] ?></td>
                                <td><?= $peticion['PET_SUPERVISOR'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <div class="mt-4 text-center">
                <div class="alert alert-warning alert-custom" role="alert">
                    <strong>No hay peticiones disponibles.</strong>
                </div>
            </div>
        <?php endif; ?>

        <div class="mt-4 mb-4 text-end">
            <a href="index.php?action=volver" class="btn btn-secondary">Volver atrás</a>
        </div>
    </div>
</div>