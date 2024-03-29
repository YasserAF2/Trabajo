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
                            <th>Solicitud</th>
                            <th>Fecha</th>
                            <th>Documentación</th>
                            <th>Estado de la Solicitud</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solicitudes as $solicitud) : ?>
                            <tr>
                                <td><?= $solicitud->getTipoSolicitud() ?></td>
                                <td><?= $solicitud->getFecha(); ?> </td>
                                <td><a href="<?= $solicitud->getDocumentoSolicitud() ?>">Abrir documentación</a></td>
                                <td>
                                    <?php
                                    $estado = $solicitud->getEstadoSolicitud();
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
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a class="btn btn-primary" href="index.php">Volver atrás</a>
            <?php endif; ?>
        </article>
    </section>
</main>