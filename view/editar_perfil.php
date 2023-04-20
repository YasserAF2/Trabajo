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
        <label for="apellido1">Apellido 1:</label>
        <input type="text" name="apellido1" id="apellido1" required><br><br>
        <label for="apellido2">Apellido 2:</label>
        <input type="text" name="apellido2" id="apellido2" required><br><br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br><br>
        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" id="direccion" required><br><br>
        <label for="ciudad">Ciudad:</label>
        <input type="text" name="ciudad" id="ciudad" required><br><br>
        <label for="provincia">Provincia:</label>
        <input type="text" name="provincia" id="provincia" required><br><br>
        <label for="codigo_postal">Código Postal:</label>
        <input type="text" name="codigo_postal" id="codigo_postal" required><br><br>
        <label for="telefono_fijo">Teléfono Fijo:</label>
        <input type="text" name="telefono_fijo" id="telefono_fijo" required><br><br>
        <label for="telefono_movil">Teléfono Móvil:</label>
        <input type="text" name="telefono_movil" id="telefono_movil" required><br><br>
        <label for="pais">País:</label>
        <input type="text" name="pais" id="pais" required><br><br>
        <input type="submit" value="Guardar cambios">
    </form>
</main>