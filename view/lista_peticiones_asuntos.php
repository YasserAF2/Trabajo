<?php
$asuntos = $dataToView['asuntos'];
?>
<main class="emp">
    <div class="empleados-lista">
        <h2>Lista de Asuntos</h2>
        <button class="volver" onclick="window.location.href='index.php?action=admin'"><i class="fas fa-arrow-left"></i>
            Volver a la página principal</button>

        <table class="tabla-empleados">
            <thead>
                <tr>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>DNI del Empleado</th>
                    <th>Aceptar</th>
                    <th>Rechazar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($asuntos as $asunto) : ?>
                    <tr>
                        <td>
                            <?php
                            $estado = $asunto->getEstado();
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
                        <td><?php echo $asunto->getFecha(); ?></td>
                        <td><?php echo $asunto->getDniEmpleado(); ?></td>
                        <td>
                            <a href="#" onclick="confirmarAccion('aceptar', '<?php echo $asunto->getIdSolicitudAsuntos(); ?>')" class="btn btn-success">Aceptar</a>
                        </td>
                        <td>
                            <a href="#" onclick="confirmarAccion('rechazar', '<?php echo $asunto->getIdSolicitudAsuntos(); ?>')" class="btn btn-danger">Rechazar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>