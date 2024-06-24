<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$correo  = $_SESSION['user_id'];
$trace = new Trace();
$empleado = $trace->getEmpleadoCorreo($correo);
$_SESSION['dni'] = $empleado['EMP_NIF'];
$_SESSION['correo'] = $correo;

// Verificar si el usuario pertenece al sindicato
$perteneceSindicato = $trace->pertenece_sindicato();

//verifica el tipo de usuario
$tipo = $trace->tipo_empleado();

?>

<main>
    <div>
        <div class="header">
            <img class="logo" src="view/template/imagenes/trace4-sin-fondo.png" alt="LOGOTIPO TRACE">
            <div class="perfil-titulo">
                <h1 class="text-end">
                    <?php
                    echo $empleado['EMP_NOMBRE'] . ' ' . $empleado['EMP_APE_1'] . ' ' . $empleado['EMP_APE_2'];
                    ?>
                </h1>
            </div>
        </div>
        <div class="perfil-usuario">
            <nav class="opciones">
                <ul>
                    <li><a href="index.php?action=ver_solicitudes">VER SOLICITUDES</a></li>
                    <li><a href="index.php?action=solicitud_asuntos_propios">SOLICITUD ASUNTOS PROPIOS</a></li>
                    <li><a href="index.php?action=solicitud_asuntos_propios_no_remunerados">SOLICITUD ASUNTOS PROPIOS NO
                            REMUNERADOS</a></li>
                    <li><a href="index.php?action=solicitud_licencia_lactancia_maternidad_paternidad">SOLICITUD LICENCIA
                            POR LACTANCIA/MATERNIDAD/PATERNIDAD</a></li>
                    <?php if ($perteneceSindicato) : ?>
                        <li><a href="index.php?action=solicitud_hora_sindical">SOLICITUD HORA SINDICAL</a></li>
                    <?php endif; ?>
                    <li><a href="index.php?action=solicitud_licencia">SOLICITUD LICENCIA</a></li>
                    <li><a href="index.php?action=documentacion_baja_accidente">DOCUMENTACION BAJA ACCIDENTE</a></li>
                    <li><a href="index.php?action=documentacion_baja_enfermedad">DOCUMENTACIÓN BAJA ENFERMEDAD</a></li>

                    <br />
                    <li><a href="index.php?action=mensaje_direccion">MENSAJE A DIRECCIÓN</a></li>
                    <li><a href="index.php?action=mensaje_encargado_general">MENSAJE A ENCARGADO GENERAL</a></li>
                    <li><a href="index.php?action=mensaje_dpto_produccion">MENSAJE DPTO PRODUCCIÓN</a></li>
                    <li><a href="index.php?action=mensaje_nominas">MENSAJE NOMINAS</a></li>
                    <li><a href="index.php?action=consultas_uniformes_calzado">CONSULTAS UNIFORMES Y CALZADO</a></li>
                </ul>
            </nav>
            <div class="container pl-0">
                <div>
                    <?php if ($tipo !== 'BASICO') : ?>
                        <a href="index.php?action=admin" class="btn btn-primary mb-2">VISTA ADMINISTRADOR</a>
                    <?php endif; ?>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Fecha de Nacimiento:</strong>
                            <?php
                            $fecha_nacimiento = $empleado['EMP_FEC_NAC'];
                            $fecha_formateada = date("d/m/Y", strtotime($fecha_nacimiento));
                            echo $fecha_formateada;
                            ?>
                        </li>
                        <li class="list-group-item"><strong>Categoría:</strong>
                            <?php echo $empleado['EMP_CATEGORIA']; ?>
                        </li>
                        <li class="list-group-item"><strong>Turno:</strong> <?php echo $empleado['TURNO']; ?>
                        </li>
                        <li class="list-group-item"><strong>Tipo de Contrato:</strong>
                            <?php echo $empleado['EMP_TIPO_CONT']; ?>
                        </li>
                        <li class="list-group-item"><strong>Fecha de Antigüedad:</strong>
                            <?php
                            $fecha_antiguedad = $empleado['EMP_FEC_ANTIGUEDAD'];
                            $fecha_formateada_antiguedad = date("d/m/Y", strtotime($fecha_antiguedad));
                            echo $fecha_formateada_antiguedad;
                            ?>
                        </li>
                        <li class="d-flex justify-content-end">
                            <a href="index.php?action=logout" class="logout btn btn-danger">
                                Cerrar Sesión <i class="fas fa-sign-out-alt"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>