<?php
$solicitudes = $dataToView['solicitudes'];
?>
<main>
    <section>
        <article>
            <h2>Lista de solicitudes realizadas</h2>
            <a class="btn btn-primary" href="index.php">Volver atrás</a>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Solicitud</th>
                        <th>Documentación</th>
                        <th>Estado de la Solicitud</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($solicitudes as $solicitud) {
                        echo "<tr>";
                        echo "<td>" . $solicitud->getTipoSolicitud() . "</td>";
                        echo "<td>" . $solicitud->getDocumentoSolicitud() . "</td>";
                        echo "<td>";
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
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </article>
    </section>
</main>