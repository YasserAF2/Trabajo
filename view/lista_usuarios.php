<?php
$empleados = $dataToView['empleados'];
?>
<main class="emp">
    <div class="empleados-lista">
        <h2>Lista y administración de los empleados.</h2>
        <button class="volver" onclick="window.location.href='index.php?action=admin'"><i class="fas fa-arrow-left"></i>
            Volver a la
            página principal</button>

        <table class="tabla-empleados">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Primer Apellido</th>
                    <th>Segundo Apellido</th>
                    <th>DNI</th>
                    <th>Código ZKT</th>
                    <th>Dirección</th>
                    <th>Email</th>
                    <th>País</th>
                    <th>Ciudad</th>
                    <th>Provincia</th>
                    <th>Código Postal</th>
                    <th>Teléfono Fijo</th>
                    <th>Teléfono Móvil</th>

                    <th>Editar Usuario</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($empleados as $empleado) : ?>
                    <tr>
                        <td><?php echo $empleado->getNOMBRE(); ?></td>
                        <td><?php echo $empleado->getAPELLIDO_1(); ?></td>
                        <td><?php echo $empleado->getAPELLIDO_2(); ?></td>
                        <td><?php echo $empleado->getDNI(); ?></td>
                        <td><?php echo $empleado->getCodZkt(); ?></td>
                        <td><?php echo $empleado->getDireccion(); ?></td>
                        <td><?php echo $empleado->getEMAIL(); ?></td>
                        <td><?php echo $empleado->getPais(); ?></td>
                        <td><?php echo $empleado->getCiudad(); ?></td>
                        <td><?php echo $empleado->getProvincia(); ?></td>
                        <td><?php echo $empleado->getCp(); ?></td>
                        <td><?php echo $empleado->getTelfCasa(); ?></td>
                        <td><?php echo $empleado->getTlfMovil(); ?></td>
                        <td>
                            <?php $dni = $empleado->getDNI(); ?>
                            <form class="editar2" method="post" action="index.php?action=editar_empleado">
                                <input type="hidden" name="dni" value="<?php echo $dni; ?>">
                                <input type="submit" value="Editar">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>