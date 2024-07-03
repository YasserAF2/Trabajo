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

$peticiones = $dataToView['peticiones'];
// verifica el tipo de usuario
$trace = new Trace();
$tipo = $trace->tipo_empleado();
$buscador = isset($_GET['buscador']) ? $_GET['buscador'] : '';


?>


<div class="container mt-5">
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
            <input type="text" name="buscador" id="buscador" class="form-control" placeholder="Buscar..."
                value="<?php echo $buscador; ?>">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
        </div>
    </form>

    <!-- MODAL -->
    <div class="modal fade" id="resultadosModal" tabindex="-1" role="dialog" aria-labelledby="resultadosModalLabel"
        aria-hidden="true">
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
    <div class="mt-2 mb-3 text-end">
        <a href="index.php?action=admin" class="btn btn-secondary">Volver atrás</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Fecha</th>
                    <th>Categoría</th>
                    <th>Tipo</th>
                    <th>Aceptado</th>
                    <th>Supervisor</th>
                    <th>Documento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($peticiones)) : ?>
                <?php foreach ($peticiones as $peticion) : ?>
                <tr>
                    <td><?php echo $peticion['PET_DNI']; ?></td>
                    <td><?= $peticion['EMP_NOMBRE'] . ' ' . $peticion['EMP_APE_1'] . ' ' . $peticion['EMP_APE_2'] ?>
                    <td><?php echo date("d/m/Y", strtotime($peticion['PET_FECHA'])); ?></td>
                    <td><?php echo $peticion['EMP_CATEGORIA']; ?></td>
                    <td><?php echo $peticion['PET_TIPO']; ?></td>
                    <td><?php echo $peticion['PET_ACEPTADO']; ?></td>
                    <td><?php echo $peticion['PET_SUPERVISOR']; ?></td>
                    <td>
                        <?php if (!empty($peticion['PET_DOC'])) : ?>
                        <a href="view/descargar.php?file=<?php echo urlencode($peticion['PET_DOC']); ?>"
                            class="btn btn-primary btn-sm">Descargar</a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($tipo == 'ADMINISTRADOR' || $tipo == 'SUPERUSUARIO') : ?>
                        <?php if ($peticion['PET_ACEPTADO'] != 'SI') : ?>
                        <div class="d-flex">
                            <a href="#" class="btn btn-success aceptar-btn mr-1"
                                data-id="<?= $peticion['PET_ID'] ?>">Aceptar</a>
                            <a href="#" class="btn btn-danger rechazar-btn"
                                data-id="<?= $peticion['PET_ID'] ?>">Rechazar</a>
                        </div>
                        <?php else : ?>
                        <div class="d-flex">
                            <button class="btn btn-success mr-1" disabled>Aceptar</button>
                            <a href="#" class="btn btn-danger rechazar-btn"
                                data-id="<?= $peticion['PET_ID'] ?>">Rechazar</a>
                        </div>
                        <?php endif; ?>
                        <?php else : ?>
                        <div class="d-flex">
                            <button class="btn btn-success btn-custom mb-2 mr-1" disabled>Aceptar</button>
                            <button class="btn btn-danger btn-custom mb-2" disabled>Rechazar</button>
                        </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else : ?>
                <tr>
                    <td colspan="9" class="text-center">No hay bajas registradas.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
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

        fetch(`index.php?action=buscar_bajas&buscador=${encodeURIComponent(valorBusqueda)}`)
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


// Función para manejar la confirmación con SweetAlert
document.addEventListener('DOMContentLoaded', function() {
    const aceptarButtons = document.querySelectorAll('.aceptar-btn');
    const rechazarButtons = document.querySelectorAll('.rechazar-btn');

    aceptarButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const peticionId = this.getAttribute('data-id');
            Swal.fire({
                title: '¿Estás seguro de aceptar esta petición?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, aceptar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href =
                        `index.php?action=aceptar_baja&peticion_id=${peticionId}`;
                }
            });
        });
    });

    rechazarButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const peticionId = this.getAttribute('data-id');
            Swal.fire({
                title: '¿Estás seguro de rechazar esta petición?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, rechazar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href =
                        `index.php?action=rechazar_baja&peticion_id=${peticionId}`;
                }
            });
        });
    });
});
</script>