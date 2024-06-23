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
// verifica el tipo de usuario
$trace = new Trace();
$tipo = $trace->tipo_empleado();

?>

<div class="container">
    <div class="formulario">
        <div class="d-flex justify-content-between align-items-center px-5 ms-xl-4 mb-2 mt-2">
            <img class="logo" src="view/template/imagenes/trace4-sin-fondo.png" alt="LOGOTIPO TRACE">
            <div class="perfil-titulo text-end">
                <h1 class="mb-0">Lista de Peticiones Asuntos Propios</h1>
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
                            <th>Fecha y hora</th>
                            <th>Fecha de solicitud</th>
                            <th>Estado</th>
                            <th>Supervisor</th>
                            <th>Acciones</th>
                            <th>Mañana</th>
                            <th>Tarde</th>
                            <th>Noche</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($peticiones as $peticion) : ?>
                    <?php
                    // Obtener la fecha de la petición para consultar el cupo
                    $fecha_peticion = $peticion['PET_FECHA'];
                    $cuposAP = $trace->ver_cupo_peticion($fecha_peticion);

                    // Verificar si se encontraron resultados para la fecha
                    $cupo_manana = isset($cuposAP['AP_MAÑANA']) ? $cuposAP['AP_MAÑANA'] : 'No disponible';
                    $cupo_tarde = isset($cuposAP['AP_TARDE']) ? $cuposAP['AP_TARDE'] : 'No disponible';
                    $cupo_noche = isset($cuposAP['AP_NOCHE']) ? $cuposAP['AP_NOCHE'] : 'No disponible';

                    // Determinar si el botón Aceptar debe estar deshabilitado
                    $deshabilitar_aceptar = ($peticion['PET_ACEPTADO'] == 'SI');
                    ?>
                    <tr>
                        <td><?= $peticion['PET_DNI'] ?></td>
                        <td><?= $peticion['EMP_NOMBRE'] . ' ' . $peticion['EMP_APE_1'] . ' ' . $peticion['EMP_APE_2'] ?></td>
                        <td><?= $peticion['PET_TIPO'] ?></td>
                        <td>
                            <?php
                            $fecha_hora_solicitud = $peticion['PET_FECHA_HORA_SOLICITUD'];
                            $fecha_formateada = date("d/m/Y H:i:s", strtotime($fecha_hora_solicitud));
                            echo $fecha_formateada;
                            ?>
                        </td>
                        <td>
                            <?php
                            $fecha_peticion = $peticion['PET_FECHA'];
                            $fecha_formateada = date("d/m/Y", strtotime($fecha_peticion));
                            echo $fecha_formateada;
                            ?>
                        </td>
                        <td><?= $peticion['PET_ACEPTADO'] ?></td>
                        <td><?= $peticion['PET_SUPERVISOR'] ?></td>
                        <td>
                            <?php if ($tipo == 'ADMINISTRADOR' || $tipo == 'SUPERUSUARIO') : ?>
                                <?php if (!$deshabilitar_aceptar) : ?>
                                    <a href="index.php?action=aceptar_ap&peticion_id=<?= $peticion['PET_ID'] ?>" class="btn btn-success" onclick="return confirm('¿Estás seguro de que quieres aceptar esta petición?')">Aceptar</a>
                                <?php else : ?>
                                    <button class="btn btn-success" disabled>Aceptar</button>
                                <?php endif; ?>
                                <a href="index.php?action=rechazar_ap&peticion_id=<?= $peticion['PET_ID'] ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres rechazar esta petición?')">Rechazar</a>
                            <?php else : ?>
                                <div class="row">
                                    <div class="col-auto">
                                        <button class="btn btn-success btn-custom mb-2" disabled>Aceptar</button>
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-danger btn-custom mb-2" disabled>Rechazar</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><?= $cupo_manana ?></td>
                        <td><?= $cupo_tarde ?></td>
                        <td><?= $cupo_noche ?></td>
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
