<?php
$correo  = $_SESSION['usuario'];
$tipo = $_SESSION['tipo'];
$trace = new Trace();
$empleado = $trace->getEmpleadoCorreo($correo);
?>

<main>
    <div>
        <div class="perfil-titulo">
            <h2>Perfil del Empleado</h2>
        </div>
        <div>
            <div class="float-right">
                <form class="editar" method="post" action="index.php?action=editar_perfil">
                    <input type="hidden" name="correo" value="<?php echo $correo; ?>">
                    <input type="submit" value="Editar perfil">
                </form>
                <br>
                <div>
                    <?php
                    if ($tipo == 'Administrador') {
                        echo '<a href="index.php?action=admin" class="btn btn-primary">Administrar empleados</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="perfil-usuario">
            <nav class="opciones">
                <ul>
                    <li><a href="index.php?action=solicitud_licencias">Solicitud de licencias</a></li>
                    <li><a href="index.php?action=solicitud_asuntos">Solicitud de asuntos propios</a></li>
                    <li><a href="index.php?action=lista_solicitudes">Estado de las solicitudes</a></li>
                </ul>
            </nav>
            <div class="container">
                <div>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Nombre:</strong> <?php echo $empleado->getNOMBRE(); ?></li>
                        <li class="list-group-item"><strong>Apellidos:</strong>
                            <?php echo $empleado->getAPELLIDO_1(); ?>
                            <?php echo $empleado->getAPELLIDO_2(); ?></li>
                        <li class="list-group-item"><strong>Email:</strong> <?php echo $empleado->getEMAIL(); ?></li>
                        <li class="list-group-item"><strong>Dirección:</strong> <?php echo $empleado->getDireccion(); ?>
                        </li>
                        <li class="list-group-item"><strong>Ciudad:</strong> <?php echo $empleado->getCiudad(); ?></li>
                        <li class="list-group-item"><strong>Provincia:</strong> <?php echo $empleado->getProvincia(); ?>
                        </li>
                        <li class="list-group-item"><strong>Código Postal:</strong> <?php echo $empleado->getCp(); ?>
                        </li>
                        <li class="list-group-item"><strong>Teléfono Fijo:</strong>
                            <?php echo $empleado->getTelfCasa(); ?></li>
                        <li class="list-group-item"><strong>Teléfono Móvil:</strong>
                            <?php echo $empleado->getTlfMovil(); ?></li>
                        <li class="list-group-item"><strong>País:</strong> <?php echo $empleado->getPais(); ?></li>
                    </ul>
                </div>
            </div>
            <form class="cerrar" method="post" action="index.php?action=logout">
                <input type="submit" value="Cerrar sesión">
            </form>
        </div>
    </div>
</main>