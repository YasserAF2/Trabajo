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
?>


<div class="container mt-5">
    <h1 class="mb-4">Bajas por Accidente o Enfermedad</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Aceptado</th>
                    <th>Supervisor</th>
                    <th>Documento</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($peticiones)) : ?>
                    <?php foreach ($peticiones as $peticion) : ?>
                        <tr>
                            <td><?php echo $peticion['PET_DNI']; ?></td>
                            <td><?php echo $peticion['PET_FECHA']; ?></td>
                            <td><?php echo $peticion['PET_TIPO']; ?></td>
                            <td><?php echo $peticion['PET_ACEPTADO']; ?></td>
                            <td><?php echo $peticion['PET_SUPERVISOR']; ?></td>
                            <td>
                                <?php if (!empty($peticion['PET_DOC'])) : ?>
                                    <a href="view/descargar.php?file=<?php echo urlencode($peticion['PET_DOC']); ?>" class="btn btn-primary btn-sm">Descargar</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="text-center">No hay bajas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="mt-2 mb-2 text-end">
            <a href="index.php?action=admin" class="btn btn-secondary">Volver atrás</a>
        </div>
    </div>
</div>