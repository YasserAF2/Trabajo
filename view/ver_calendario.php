<?php
session_start();
if (!isset($_SESSION['correo'])) {
    header("Location: index.php");
    exit();
}
$trace = new Trace();
$buscador = isset($_GET['buscador']) ? $_GET['buscador'] : '';
$peticiones = $trace->obtenerPeticionesAceptadas();

?>

<div class="container mt-5">
    <h1 class="mb-4">Peticiones Aceptadas</h1>

    <!-- Formulario de búsqueda -->
    <form id="searchForm" method="GET" action="index.php">
        <input type="hidden" name="action" value="ver_calendario">
        <div class="input-group mb-3">
            <input type="text" name="buscador" id="buscador" class="form-control" placeholder="Buscar..." value="<?php echo $buscador; ?>">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
        </div>
    </form>

    <!-- Modal para mostrar los resultados de búsqueda -->
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

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Fecha</th>
                <th>DNI</th>
                <th>Nombre y Apellidos</th>
                <th>Tipo</th>
                <th>Fecha Solicitud</th>
                <th>Supervisor</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($peticiones as $peticion) : ?>
                <tr>
                    <td><?php echo date("d/m/Y", strtotime($peticion['PET_FECHA'])); ?></td>
                    <td><?php echo $peticion['PET_DNI']; ?></td>
                    <td><?php echo $peticion['EMP_NOMBRE'] . ' ' . $peticion['EMP_APE_1']; ?></td>
                    <td><?php echo $peticion['PET_TIPO']; ?></td>
                    <td><?php echo date("d/m/Y H:i:s", strtotime($peticion['PET_FECHA_HORA_SOLICITUD'])); ?></td>
                    <td><?php echo $peticion['PET_SUPERVISOR']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="mb-3">
        <a href="index.php?action=excel" class="btn btn-success">Descargar como Excel</a>
        <a href="index.php?action=admin" class="btn btn-secondary">Volver atrás</a>
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

            fetch(`index.php?action=buscar_peticion&buscador=${encodeURIComponent(valorBusqueda)}`)
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
</script>