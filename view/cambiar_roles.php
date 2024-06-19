<?php
session_start();

$trace = new Trace();
$tipo = $trace->tipo_empleado();

// Asigna la variable de sesión si no está definida
if (!isset($_SESSION['correo'])) {
    $_SESSION['correo'] = $correo;
}

// Verifica si la variable de sesión 'correo' está definida
if (!isset($_SESSION['correo'])) {
    header("Location: index.php");
    exit();
}

// Verifica si la variable 'tipo' es 'SUPERUSUARIO'
if ($tipo !== 'SUPERUSUARIO') {
    header("Location: index.php");
    exit();
}

$empleados = $dataToView['empleados'];
$total_empleados = $dataToView['total_empleados'];
$empleados_por_pagina = $dataToView['empleados_por_pagina'];
$pagina_actual = $dataToView['pagina_actual'];
$total_paginas = ceil($total_empleados / $empleados_por_pagina);
?>

<div class="container mt-5">
    <h2>Lista de Empleados</h2>
    <a href="index.php?action=admin" class="btn btn-secondary">Volver atrás</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>DNI</th>
                <th>Nombre Completo</th>
                <th>Tipo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dataToView['empleados'] as $empleado) : ?>
                <tr>
                    <td><?php echo $empleado['EMP_NIF']; ?></td>
                    <td><?php echo $empleado['EMP_NOMBRE'] . ' ' . $empleado['EMP_APE_1'] . ' ' . $empleado['EMP_APE_2']; ?></td>
                    <td>
                        <form action="index.php?action=cambiar_tipo" method="post" onsubmit="return confirm('¿Está seguro de querer modificar a <?php echo $empleado['EMP_NOMBRE'] . ' ' . $empleado['EMP_APE_1']; ?> al rol ' + this.nuevo_tipo.value + '?');">
                            <input type="hidden" name="dni" value="<?php echo $empleado['EMP_NIF']; ?>">
                            <div class="form-group">
                                <select name="nuevo_tipo" class="form-control">
                                    <option value="ADMINISTRADOR" <?php echo $empleado['EMP_TIPO'] == 'ADMINISTRADOR' ? 'selected' : ''; ?>>Administrador</option>
                                    <option value="USUARIO" <?php echo $empleado['EMP_TIPO'] == 'USUARIO' ? 'selected' : ''; ?>>Usuario</option>
                                    <option value="BASICO" <?php echo $empleado['EMP_TIPO'] == 'BASICO' ? 'selected' : ''; ?>>Básico</option>
                                    <option value="SUPERUSUARIO" <?php echo $empleado['EMP_TIPO'] == 'SUPERUSUARIO' ? 'selected' : ''; ?>>Superusuario</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Cambiar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <nav>
        <ul class="pagination">
            <?php if ($pagina_actual > 1) : ?>
                <li class="page-item"><a class="page-link" href="index.php?action=cambiar_roles&pagina=<?php echo $pagina_actual - 1; ?>">Anterior</a></li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_paginas; $i++) : ?>
                <li class="page-item <?php echo $i == $pagina_actual ? 'active' : ''; ?>">
                    <a class="page-link" href="index.php?action=cambiar_roles&pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <?php if ($pagina_actual < $total_paginas) : ?>
                <li class="page-item"><a class="page-link" href="index.php?action=cambiar_roles&pagina=<?php echo $pagina_actual + 1; ?>">Siguiente</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Escucha el evento submit del formulario
        document.querySelectorAll("form").forEach(form => {
            form.addEventListener("submit", function(event) {
                event.preventDefault(); // Evita que se envíe el formulario de forma tradicional

                // Obtén los datos del formulario
                var formData = new FormData(this);

                // Realiza la petición AJAX
                fetch(this.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        // Verificar el estado de la respuesta HTTP
                        if (!response.ok) {
                            throw new Error('Error en la solicitud HTTP: ' + response.status);
                        }
                        return response.json(); // Convertir la respuesta a JSON
                    })
                    .then(data => {
                        console.log("Respuesta del servidor:", data);

                        // Muestra una alerta basada en la respuesta recibida
                        if (data.success) {
                            alert(data.message); // Mensaje de éxito
                            // Ejemplo de actualización de la vista: recargar la página
                            location.reload();
                        } else {
                            alert(data.message); // Mensaje de error
                        }
                    })
                    .catch(error => {
                        console.error('Error al procesar la solicitud:', error);
                        alert('Error al procesar la solicitud. Por favor, intenta de nuevo.');
                    });

            });
        });
    });
</script>