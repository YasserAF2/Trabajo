<?php
$empleado = $dataToView['empleado'];
?>

<main>
    <h1>Formulario para editar un usuario</h1>
    <form method="post" action="index.php?action=save">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $empleado->getNombre(); ?>" required>

        <label for="apellido1">Primer apellido:</label>
        <input type="text" id="apellido1" name="apellido1" value="<?php echo $empleado->getAPELLIDO_1(); ?>" required>

        <label for="apellido2">Segundo apellido:</label>
        <input type="text" id="apellido2" name="apellido2" value="<?php echo $empleado->getAPELLIDO_2(); ?>" required>

        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" value="<?php echo $empleado->getDNI(); ?>" required>

        <label for="codigo_zkt">Código ZKT:</label>
        <input type="text" id="codigo_zkt" name="codigo_zkt" value="<?php echo $empleado->getCodZkt(); ?>" required>

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" value="<?php echo $empleado->getDireccion(); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $empleado->getEmail(); ?>" required>

        <label for="pais">País:</label>
        <input type="text" id="pais" name="pais" value="<?php echo $empleado->getPais(); ?>" required>

        <label for="ciudad">Ciudad:</label>
        <input type="text" id="ciudad" name="ciudad" value="<?php echo $empleado->getCiudad(); ?>" required>

        <label for="provincia">Provincia:</label>
        <input type="text" id="provincia" name="provincia" value="<?php echo $empleado->getProvincia(); ?>" required>

        <label for="cp">Código Postal:</label>
        <input type="text" id="cp" name="cp" value="<?php echo $empleado->getCp(); ?>" required>

        <label for="telefono_fijo">Teléfono Fijo:</label>
        <input type="number" id="telefono_fijo" name="telefono_fijo" value="<?php echo $empleado->getTelfCasa(); ?>" required>

        <label for="telefono_movil">Teléfono Móvil:</label>
        <input type="number" id="telefono_movil" name="telefono_movil" value="<?php echo $empleado->getTlfMovil(); ?>" required>

        <input type="submit" value="Guardar datos">
    </form>


</main>