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

$dias_restantes = $trace->obtener_dias_restantes();
$peticiones = $trace->peticiones_dni($_SESSION['dni']);

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
                    <div class="boton-admin">
                        <?php if ($tipo !== 'BASICO') : ?>
                            <a href="index.php?action=admin" class="btn btn-primary mb-2">VISTA ADMINISTRADOR</a>
                        <?php endif; ?>
                    </div>
                    <ul class="list-group">
                        <!-- NÚMERO DE DÍAS RESTANTES AP -->
                        <li class="list-group-item">
                            <?php
                            echo "Días de Asuntos Propios (AP) Restantes:<br>";
                            echo "Año Actual: <span class='badge text-white bg-success'>" . $dias_restantes['dias_restantes_ap_act'] . "</span><br>";
                            echo "Año Siguiente: <span class='badge text-white bg-info'>" . $dias_restantes['dias_restantes_ap_sig'] . "</span>";
                            ?>
                        </li>
                        <?php
                        // Variable para verificar si hay peticiones de tipo AP aceptadas
                        $hay_peticiones_ap = false;
                        ?>

                        <?php foreach ($peticiones as $peticion) : ?>
                            <?php if ($peticion['PET_TIPO'] == 'AP' && $peticion['PET_ACEPTADO'] == 'SI') : ?>
                                <?php
                                // Si encontramos al menos una petición válida, marcamos la variable como true
                                $hay_peticiones_ap = true;
                                ?>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <?php if ($hay_peticiones_ap) : ?>
                            <li class="list-group-item">
                                <p>Fecha de los días de Asuntos Propios (AP)</p>
                                <?php foreach ($peticiones as $peticion) : ?>
                                    <?php if ($peticion['PET_TIPO'] == 'AP' && $peticion['PET_ACEPTADO'] == 'SI') : ?>
                                        <?php
                                        // Formatear la fecha de año-mes-dia a dia-mes-año
                                        $fecha_formateada = date('d-m-Y', strtotime($peticion['PET_FECHA']));
                                        ?>
                                        <div class="cubo pl-2 pr-2"><?php echo $fecha_formateada; ?></div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </li>
                        <?php endif; ?>

                        <!-- NÚMERO DE DÍAS AS RESTANTES AS -->
                        <li class="list-group-item">
                            <?php
                            echo "Días de Asuntos Propios no Remunerados (AS) Restantes:<br>";
                            echo "Año Actual: <span class='badge text-white bg-success'>" . $dias_restantes['dias_restantes_as_act'] . "</span><br>";
                            echo "Año Siguiente: <span class='badge text-white bg-info'>" . $dias_restantes['dias_restantes_as_sig'] . "</span>";
                            ?>
                        </li>

                        <!-- DÍAS -->
                        <?php
                        // Variable para verificar si hay peticiones de tipo AS aceptadas
                        $hay_peticiones_as = false;
                        ?>

                        <?php foreach ($peticiones as $peticion) : ?>
                            <?php if ($peticion['PET_TIPO'] == 'AS' && $peticion['PET_ACEPTADO'] == 'SI') : ?>
                                <?php
                                // Si encontramos al menos una petición válida, marcamos la variable como true
                                $hay_peticiones_as = true;
                                ?>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <?php if ($hay_peticiones_as) : ?>
                            <li class="list-group-item">
                                <p>Fecha de los días de Asuntos No Remunerados (AS)</p>
                                <?php foreach ($peticiones as $peticion) : ?>
                                    <?php if ($peticion['PET_TIPO'] == 'AS' && $peticion['PET_ACEPTADO'] == 'SI') : ?>
                                        <?php
                                        // Formatear la fecha de año-mes-dia a dia-mes-año
                                        $fecha_formateada = date('d-m-Y', strtotime($peticion['PET_FECHA']));
                                        ?>
                                        <div class="cubo pl-2 pr-2"><?php echo $fecha_formateada; ?></div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </li>
                        <?php endif; ?>

                        <!-- DATOS EMPLEADO -->
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