<?php
$solicitudes = $dataToView['solicitudes'];
?>
<main>
    <section>
        <article>
            <?php if (empty($solicitudes)) : ?>
            <h2>No hay solicitudes realizadas</h2>
            <p>No tienes ninguna solicitud realizada en este momento.</p>
            <a class="btn btn-primary" href="index.php">Volver atrás</a>
            <?php else : ?>
            <h2>Lista de solicitudes realizadas</h2>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Solicitud</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>DNI Empleado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($solicitudes as $solicitud) : ?>
                    <tr>
                        <td><?= $solicitud->getIdSolicitudAsuntos() ?></td>
                        <td><?= $solicitud->getFecha() ?></td>
                        <td>
                            <?php
                                    $estado = $solicitud->getEstado();
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
                        <td><?= $solicitud->getDniEmpleado() ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a class="btn btn-primary" href="index.php">Volver atrás</a>
            <?php endif; ?>
        </article>
    </section>
</main>