<?php
$correo  = $_SESSION['usuario'];
echo $correo;
$trace = new Trace();
$empleado = $trace->getEmpleadoCorreo($correo);
?>

<main>
    <h2>Perfil del Empleado</h2>
    <div class="perfil-usuario">
        <nav class="opciones">
            <ul>
                <li><a href="index.php?action=solicitud_licencias">Solicitud de licencias</a></li>
                <li><a href="index.php?action=solicitud_asuntos">Solicitud de asuntos propios</a></li>
                <li><a href="#">Estado de las solicitudes</a></li>
            </ul>
        </nav>
        <div class="container">
            <div>
                <div class="float-right">
                    <form class="editar" method="post" action="index.php?action=editar_perfil">
                        <input type="hidden" name="correo" value="<?php echo $correo; ?>">
                        <input type="submit" value="Editar perfil">
                    </form>
                </div>
                <ul>
                    <li><strong>Nombre:</strong> <?php echo $empleado->getNOMBRE(); ?></li>
                    <li><strong>Apellidos:</strong> <?php echo $empleado->getAPELLIDO_1(); ?>
                        <?php echo $empleado->getAPELLIDO_2(); ?></li>
                    <li><strong>Email:</strong> <?php echo $empleado->getEMAIL(); ?></li>
                    <li><strong>Dirección:</strong> <?php echo $empleado->getDireccion(); ?></li>
                    <li><strong>Ciudad:</strong> <?php echo $empleado->getCiudad(); ?></li>
                    <li><strong>Provincia:</strong> <?php echo $empleado->getProvincia(); ?></li>
                    <li><strong>Código Postal:</strong> <?php echo $empleado->getCp(); ?></li>
                    <li><strong>Teléfono Fijo:</strong> <?php echo $empleado->getTelfCasa(); ?></li>
                    <li><strong>Teléfono Móvil:</strong> <?php echo $empleado->getTlfMovil(); ?></li>
                    <li><strong>País:</strong> <?php echo $empleado->getPais(); ?></li>
                </ul>
            </div>
        </div>
        <form class="cerrar" method="post" action="index.php?action=logout">
            <input type="submit" value="Cerrar sesión">
        </form>
    </div>

</main>