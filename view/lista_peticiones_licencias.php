<?php
$licencias = $dataToView['licencias'];
?>
<main class="emp">
    <div class="empleados-lista">
        <h2>Lista de Licencias</h2>
        <button class="volver" onclick="window.location.href='index.php?action=admin'"><i class="fas fa-arrow-left"></i>
            Volver a la página principal</button>

        <table class="tabla-empleados">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Documento</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>DNI del Empleado</th>
                    <th>Aceptar</th>
                    <th>Denegar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($licencias as $licencia) : ?>
                    <tr>
                        <td><?php echo $licencia->getTipoSolicitud(); ?></td>
                        <td><a href="<?= $licencia->getDocumentoSolicitud() ?>">Abrir documentación</a></td>
                        <td>
                            <?php
                            $estado = $licencia->getEstadoSolicitud();
                            $color = '';
                            switch ($estado) {
                                case 'Aceptada':
                                    $color = 'text-success';
                                    break;
                                case 'Rechazada':
                                    $color = 'text-danger';
                                    break;
                                case 'En proceso':
                                    $color = 'text-warning';
                                    break;
                                default:
                                    $color = '';
                                    break;
                            }
                            echo "<span class='$color'>" . $estado . "</span>";
                            ?>
                        </td>
                        <td><?php echo $licencia->getFecha(); ?></td>
                        <td><?php echo $licencia->getDniEmpleado(); ?></td>
                        <td>Aceptar</td>
                        <td>Denegar</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>