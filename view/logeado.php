<?php
$correo  = $_SESSION['usuario'];
echo $correo;
$trace = new Trace();
$empleado = $trace->getEmpleadoCorreo($correo);
?>

<main>
    <div>
        <div>
            <h2>Perfil del Empleado</h2>
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

        <form method="post" action="index.php?action=logout">
            <input type="submit" value="Cerrar sesión">
        </form>
    </div>

</main>