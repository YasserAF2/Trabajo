<?php
$empleados = $dataToView['empleados'];
?>

<div class="empleados-lista">
    <h2>Lista y administración de los empleados.</h2>
    <button class="editar" onclick="window.location.href='index.php?action='">Volver a la página principal</button>
    <button class="volver" onclick="window.location.href='index.php'"><i class="fas fa-arrow-left"></i> Volver a la
        página principal</button>

    <table class="tabla-empleados">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Código ZKT</th>
                <th>DNI</th>
                <th>Dirección</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($empleados as $empleado) : ?>
                <tr>
                    <td><?php echo $empleado->getNOMBRE(); ?></td>
                    <td><?php echo $empleado->getCodZkt(); ?></td>
                    <td><?php echo $empleado->getDNI(); ?></td>
                    <td><?php echo $empleado->getDireccion(); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>