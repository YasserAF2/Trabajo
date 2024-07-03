<?php
// Asigna la variable de sesión si no está definida
if (!isset($_SESSION['correo'])) {
    $_SESSION['correo'] = $correo;
}

// Verifica si la variable de sesión está definida
if (!isset($_SESSION['correo'])) {
    header("Location: index.php");
    exit();
}

$correo = $_SESSION['correo'];
$peticiones = $dataToView['peticiones'];
// verifica el tipo de usuario
$trace = new Trace();
$tipo = $trace->tipo_empleado();
$buscador = isset($_GET['buscador']) ? $_GET['buscador'] : '';

?>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>

<div class="container">
    <div class="formulario">
        <div class="header d-flex justify-content-between align-items-center px-5 ms-xl-4 mb-3 mt-3">
            <img class="logo" src="view/template/imagenes/trace4-sin-fondo.png" alt="LOGOTIPO TRACE">
            <div class="perfil-titulo text-end">
                <h1 class="mb-0">Lista de Peticiones Asuntos Propios</h1>
            </div>
        </div>

        <!-- BUSCADOR   -->
        <form id="searchForm" method="GET" action="index.php">
            <input type="hidden" name="action" value="ver_solicitudes_ap">
            <div class="input-group mb-3">
                <input type="text" name="buscador" id="buscador" class="form-control" placeholder="Buscar..." value="<?php echo $buscador; ?>">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </div>
            </div>
        </form>

        <!-- MODAL -->
        <div class="modal fade" id="resultadosModal" tabindex="-1" role="dialog" aria-labelledby="resultadosModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="resultadosModalLabel">Resultados de Búsqueda</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="resultadosModalBody">
                        <!-- Contenido de los resultados -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-2 mb-2 text-end">
            <a href="index.php?action=admin" class="btn btn-secondary">Volver atrás</a>
        </div>

        <?php if (!empty($peticiones)) : ?>
            <div class="mt-4">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>DNI</th>
                            <th>Nombre y Apellidos</th>
                            <th>Categoría</th>
                            <th>Tipo</th>
                            <th>Fecha y hora</th>
                            <th>Fecha de solicitud</th>
                            <th>Estado</th>
                            <th>Supervisor</th>
                            <th>Acciones</th>
                            <th>Mañana</th>
                            <th>Tarde</th>
                            <th>Noche</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($peticiones as $peticion) : ?>
                            <?php
                            // Obtener la fecha de la petición para consultar el cupo
                            $fecha_peticion = $peticion['PET_FECHA'];
                            $cuposAP = $trace->ver_cupo_peticion($fecha_peticion);

                            // Verificar si se encontraron resultados para la fecha
                            $cupo_manana = isset($cuposAP['AP_MAÑANA']) ? $cuposAP['AP_MAÑANA'] : 0;
                            $cupo_tarde = isset($cuposAP['AP_TARDE']) ? $cuposAP['AP_TARDE'] : 0;
                            $cupo_noche = isset($cuposAP['AP_NOCHE']) ? $cuposAP['AP_NOCHE'] : 0;

                            // Definir los límites de cupo
                            $limite_manana = 25;
                            $limite_tarde = 15;
                            $limite_noche = 10;

                            // Determinar si el botón Aceptar debe estar deshabilitado
                            $deshabilitar_aceptar = ($peticion['PET_ACEPTADO'] == 'SI') ||
                                ($cupo_manana >= $limite_manana) ||
                                ($cupo_tarde >= $limite_tarde) ||
                                ($cupo_noche >= $limite_noche);
                            ?>
                            <tr>
                                <td><?= $peticion['PET_DNI'] ?></td>
                                <td><?= $peticion['EMP_NOMBRE'] . ' ' . $peticion['EMP_APE_1'] . ' ' . $peticion['EMP_APE_2'] ?>
                                </td>
                                <td><?= $peticion['EMP_CATEGORIA'] ?></td>
                                <td><?= $peticion['PET_TIPO'] ?></td>
                                <td>
                                    <?php
                                    $fecha_hora_solicitud = $peticion['PET_FECHA_HORA_SOLICITUD'];
                                    $fecha_formateada = date("d/m/Y H:i:s", strtotime($fecha_hora_solicitud));
                                    echo $fecha_formateada;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $fecha_peticion = $peticion['PET_FECHA'];
                                    $fecha_formateada = date("d/m/Y", strtotime($fecha_peticion));
                                    echo $fecha_formateada;
                                    ?>
                                </td>
                                <td><?= $peticion['PET_ACEPTADO'] ?></td>
                                <td><?= $peticion['PET_SUPERVISOR'] ?></td>
                                <td>
                                    <div class="d-flex">
                                        <?php if ($tipo == 'ADMINISTRADOR' || $tipo == 'SUPERUSUARIO') : ?>
                                            <?php if (!$deshabilitar_aceptar) : ?>
                                                <a href="index.php?action=aceptar_ap&peticion_id=<?= $peticion['PET_ID'] ?>" class="btn btn-success me-2 mr-1" onclick="return confirmarAccion(event, '¿Estás seguro de que quieres aceptar esta petición?')">Aceptar</a>
                                            <?php else : ?>
                                                <button class="btn btn-success me-2 mr-1" disabled>Aceptar</button>
                                            <?php endif; ?>
                                            <a href="index.php?action=rechazar_ap&peticion_id=<?= $peticion['PET_ID'] ?>" class="btn btn-danger" onclick="return confirmarAccion(event, '¿Estás seguro de que quieres rechazar esta petición?')">Rechazar</a>
                                    </div>
                                <?php else : ?>
                                    <div class="d-flex">
                                        <button class="btn btn-success me-2 mr-1" disabled>Aceptar</button>
                                        <button class="btn btn-danger" disabled>Rechazar</button>
                                    </div>
                                <?php endif; ?>
                                </td>

                                <td><?= $cupo_manana ?>/25</td>
                                <td><?= $cupo_tarde ?>/15</td>
                                <td><?= $cupo_noche ?>/10</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <p>No hay peticiones disponibles.</p>
        <?php endif; ?>

        <div class="mt-2 mb-2 text-end">
            <a href="index.php?action=admin" class="btn btn-secondary">Volver atrás</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchForm = document.querySelector("#searchForm");
        const resultadosModal = new bootstrap.Modal(document.getElementById('resultadosModal'));
        const resultadosModalBody = document.getElementById('resultadosModalBody');

        searchForm.addEventListener("submit", function(e) {
            e.preventDefault();
            const valorBusqueda = document.querySelector("#buscador").value;

            fetch(`index.php?action=buscar_ap&buscador=${encodeURIComponent(valorBusqueda)}`)
                .then(response => {
                    console.log('Response:', response);
                    return response.json();
                })
                .then(data => {
                    console.log('Data:', data);
                    if (data.success) {
                        let tablaResultados =
                            '<table class="table table-bordered"><thead><tr><th>Fecha</th><th>DNI</th><th>Nombre y Apellidos</th><th>Tipo</th><th>Fecha Solicitud</th><th>Supervisor</th></tr></thead><tbody>';
                        data.peticiones.forEach(peticion => {
                            tablaResultados += `<tr>
                                <td>${new Date(peticion.PET_FECHA).toLocaleDateString()}</td>
                                <td>${peticion.PET_DNI}</td>
                                <td>${peticion.EMP_NOMBRE} ${peticion.EMP_APE_1}</td>
                                <td>${peticion.PET_TIPO}</td>
                                <td>${new Date(peticion.PET_FECHA_HORA_SOLICITUD).toLocaleString()}</td>
                                <td>${peticion.PET_SUPERVISOR}</td>
                            </tr>`;
                        });
                        tablaResultados += '</tbody></table>';

                        resultadosModalBody.innerHTML = tablaResultados;
                        resultadosModal.show();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error al procesar la solicitud:', error);
                    alert('Error al procesar la solicitud. Por favor, intenta de nuevo.');
                });
        });
    });





    function confirmarAccion(event, mensaje) {
        event.preventDefault(); // Prevenir la acción por defecto del enlace
        const url = event.currentTarget.href; // Obtener la URL del enlace

        Swal.fire({
            title: mensaje,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, confirmar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url; // Redirigir si se confirma la acción
            }
        });
    }
</script>