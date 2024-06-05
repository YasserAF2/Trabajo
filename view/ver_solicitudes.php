<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['correo'])) {
    header("Location: index.php");
    exit();
}

$correo = $_SESSION['correo'];
echo $correo;
$peticiones = $dataToView['peticiones'];
?>

<div class="container">
    <div class="formulario">
        <div class="d-flex justify-content-between align-items-center px-5 ms-xl-4 mb-2 mt-2">
            <img class="logo" src="view/template/imagenes/trace4-sin-fondo.png" alt="LOGOTIPO TRACE">
        <div class="perfil-titulo text-end">
            <h1 class="mb-0">Lista de Peticiones</h1>
        </div>
        </div>    
        <div class="mt-4">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>DNI</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Fecha y hora de solicitud</th>
                        <th>Aceptado</th>
                        <th>Supervisor</th>
                        <!-- Puedes agregar más columnas según necesites -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($peticiones as $peticion): ?>
                        <tr>
                            <td><?= $peticion['PET_DNI'] ?></td>
                            <td><?= $peticion['PET_FECHA'] ?></td>
                            <td><?= $peticion['PET_TIPO'] ?></td>
                            <td><?= $peticion['PET_FECHA_HORA_SOLICITUD'] ?></td>
                            <td><?= $peticion['PET_ACEPTADO'] ?></td>
                            <td><?= $peticion['PET_SUPERVISOR'] ?></td>
                            <!-- Asegúrate de ajustar los nombres de los campos según corresponda -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-end">
            <a href="index.php?action=logeado" class="btn btn-secondary">Volver atrás</a>
        </div>
    </div>
</div>
