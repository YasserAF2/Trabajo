<?php
$correo = $_POST['correo'];
$trace = new Trace();
$empleado = $trace->getEmpleadoCorreo($correo);
?>

<main>
    <h2>Editar perfil</h2>
    <form method="post" action="index.php?action=guardar_perfil">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" value="<?php echo $empleado->getNOMBRE(); ?>" required><br><br>

        <label for="apellido1">Primer Apellido:</label>
        <input type="text" name="apellido1" id="apellido1" value="<?php echo $empleado->getAPELLIDO_1(); ?>" required><br><br>
        <label for="apellido2">Segundo Apellido:</label>
        <input type="text" name="apellido2" id="apellido2" value="<?php echo $empleado->getAPELLIDO_2(); ?>" required><br><br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo $empleado->getEMAIL(); ?>" required><br><br>
        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" id="direccion" value="<?php echo $empleado->getDireccion(); ?>" required><br><br>
        <label for="ciudad">Ciudad:</label>
        <input type="text" name="ciudad" id="ciudad" value="<?php echo $empleado->getCiudad(); ?>" required><br><br>
        <label for="provincia">Provincia:</label>
        <input type="text" name="provincia" id="provincia" value="<?php echo $empleado->getProvincia(); ?>" required><br><br>
        <label for="cp">Código Postal:</label>
        <input type="text" name="cp" id="cp" value="<?php echo $empleado->getCp(); ?>" required><br><br>
        <label for="telefono_fijo">Teléfono Fijo:</label>
        <input type="text" name="telefono_fijo" id="telefono_fijo" value="<?php echo $empleado->getTelfCasa(); ?>" required><br><br>
        <label for="telefono_movil">Teléfono Móvil:</label>
        <input type="text" name="telefono_movil" id="telefono_movil" value="<?php echo $empleado->getTlfMovil(); ?>" required><br><br>
        <label for="pais">País:</label>
        <input type="text" name="pais" id="pais" value="<?php echo $empleado->getPais(); ?>" required><br><br>
        <input type="hidden" name="dni" id="dni" value="<?php echo $empleado->getDNI(); ?>">
        <input type="submit" value="Guardar cambios">
    </form>
    <button onclick="window.location.href='index.php'">Volver a la página principal</button>

</main>