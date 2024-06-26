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
?>


<div class="container mt-5">
    <h1 class="mb-4">Bajas por Accidente o Enfermedad</h1>
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
                    <td colspan="7" class="text-center">No hay bajas registradas.</td>
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