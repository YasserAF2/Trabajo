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

?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center px-5 ms-xl-4 mb-2 mt-2">
            <img class="logo" src="view/template/imagenes/trace4-sin-fondo.png" alt="LOGOTIPO TRACE">
            <div class="perfil-titulo text-end">
                <h1 class="mb-0">Cambiar roles empleados</h1>
            </div>
        </div>
    <div class="container mt-5">
        <form id="searchForm" method="get" action="index.php?action=buscar_empleado_rol">
            <div class="form-group">
                <label for="buscador">Buscar por DNI o Nombre:</label>
                <input type="text" id="buscador" name="buscador" class="form-control" placeholder="Introduce DNI o Nombre">
            </div>
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a href="index.php?action=admin" class="btn btn-secondary">Volver atrás</a>
        </form>
        <div id="resultados">
            <!-- Aquí se mostrarán los resultados de la búsqueda -->
        </div>
    </div>
</div>
<div class="container mt-5">
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
                    <td class="emp_nif"><?php echo $empleado['EMP_NIF']; ?></td>
                    <td><?php echo $empleado['EMP_NOMBRE'] . ' ' . $empleado['EMP_APE_1'] . ' ' . $empleado['EMP_APE_2']; ?>
                    </td>
                    <td>
                        <form action="index.php?action=cambiar_tipo" method="post" class="ajax-form" onsubmit="return confirm('¿Está seguro de querer modificar a <?php echo $empleado['EMP_NOMBRE'] . ' ' . $empleado['EMP_APE_1']; ?> al rol ' + this.nuevo_tipo.value + '?');">
                            <input type="hidden" name="dni" value="<?php echo $empleado['EMP_NIF']; ?>">
                            <div class="form-group">
                                <select name="nuevo_tipo" class="form-control">
                                    <option value="ADMINISTRADOR" <?php echo $empleado['EMP_TIPO'] == 'ADMINISTRADOR' ? 'selected' : ''; ?>>
                                        Administrador</option>
                                    <option value="USUARIO" <?php echo $empleado['EMP_TIPO'] == 'USUARIO' ? 'selected' : ''; ?>>Usuario</option>
                                    <option value="BASICO" <?php echo $empleado['EMP_TIPO'] == 'BASICO' ? 'selected' : ''; ?>>Básico</option>
                                    <option value="SUPERUSUARIO" <?php echo $empleado['EMP_TIPO'] == 'SUPERUSUARIO' ? 'selected' : ''; ?>>
                                        Superusuario</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Cambiar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="resultadosModal" tabindex="-1" role="dialog" aria-labelledby="resultadosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resultadosModalLabel">Resultados de búsqueda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="resultadosModalBody">
                <!--  -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const buscador = document.querySelector("#buscador");
    const searchForm = document.querySelector("#searchForm");
    const resultadosModal = new bootstrap.Modal(document.getElementById('resultadosModal'));
    const resultadosModalBody = document.getElementById('resultadosModalBody');

    console.log("Documento cargado y listo");

    // Función para manejar el envío del formulario
    searchForm.addEventListener("submit", e => {
        e.preventDefault();
        const valorBusqueda = buscador.value;

        fetch(`index.php?action=buscar&buscador=${encodeURIComponent(valorBusqueda)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Generar el contenido de la tabla con los resultados
                    let tablaResultados = '<table class="table table-bordered"><thead><tr><th>DNI</th><th>Nombre Completo</th><th>Tipo</th></tr></thead><tbody>';
                    data.data.forEach(empleado => {
                        tablaResultados += `
                            <tr>
                                <td>${empleado.EMP_NIF}</td>
                                <td>${empleado.EMP_NOMBRE} ${empleado.EMP_APE_1} ${empleado.EMP_APE_2}</td>
                                <td>
                                    <form action="index.php?action=cambiar_tipo" method="post" class="ajax-form" onsubmit="return confirm('¿Está seguro de querer modificar a ${empleado.EMP_NOMBRE} ${empleado.EMP_APE_1} al rol ' + this.nuevo_tipo.value + '?');">
                                        <input type="hidden" name="dni" value="${empleado.EMP_NIF}">
                                        <div class="form-group">
                                            <select name="nuevo_tipo" class="form-control">
                                                <option value="ADMINISTRADOR" ${empleado.EMP_TIPO == 'ADMINISTRADOR' ? 'selected' : ''}>Administrador</option>
                                                <option value="USUARIO" ${empleado.EMP_TIPO == 'USUARIO' ? 'selected' : ''}>Usuario</option>
                                                <option value="BASICO" ${empleado.EMP_TIPO == 'BASICO' ? 'selected' : ''}>Básico</option>
                                                <option value="SUPERUSUARIO" ${empleado.EMP_TIPO == 'SUPERUSUARIO' ? 'selected' : ''}>Superusuario</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Cambiar</button>
                                    </form>
                                </td>
                            </tr>`;
                    });
                    tablaResultados += '</tbody></table>';

                    // Insertar la tabla en el cuerpo del modal
                    resultadosModalBody.innerHTML = tablaResultados;

                    // Mostrar el modal
                    resultadosModal.show();
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

//////////////////////////////

document.addEventListener("DOMContentLoaded", function() {
    const buscador = document.querySelector("#buscador");
    const searchForm = document.querySelector("#searchForm");
    const resultadosModal = new bootstrap.Modal(document.getElementById('resultadosModal'));
    const resultadosModalBody = document.getElementById('resultadosModalBody');

    console.log("Documento cargado y listo");

    // Función para manejar el envío del formulario
    searchForm.addEventListener("submit", e => {
        e.preventDefault();
        const valorBusqueda = buscador.value;

        fetch(`index.php?action=buscar&buscador=${encodeURIComponent(valorBusqueda)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Generar el contenido de la tabla con los resultados
                    let tablaResultados = '<table class="table table-bordered"><thead><tr><th>DNI</th><th>Nombre Completo</th><th>Tipo</th></tr></thead><tbody>';
                    data.data.forEach(empleado => {
                        tablaResultados += `
                            <tr>
                                <td>${empleado.EMP_NIF}</td>
                                <td>${empleado.EMP_NOMBRE} ${empleado.EMP_APE_1} ${empleado.EMP_APE_2}</td>
                                <td>
                                    <form action="index.php?action=cambiar_tipo" method="post" class="ajax-form" onsubmit="return confirm('¿Está seguro de querer modificar a ${empleado.EMP_NOMBRE} ${empleado.EMP_APE_1} al rol ' + this.nuevo_tipo.value + '?');">
                                        <input type="hidden" name="dni" value="${empleado.EMP_NIF}">
                                        <div class="form-group">
                                            <select name="nuevo_tipo" class="form-control">
                                                <option value="ADMINISTRADOR" ${empleado.EMP_TIPO == 'ADMINISTRADOR' ? 'selected' : ''}>Administrador</option>
                                                <option value="USUARIO" ${empleado.EMP_TIPO == 'USUARIO' ? 'selected' : ''}>Usuario</option>
                                                <option value="BASICO" ${empleado.EMP_TIPO == 'BASICO' ? 'selected' : ''}>Básico</option>
                                                <option value="SUPERUSUARIO" ${empleado.EMP_TIPO == 'SUPERUSUARIO' ? 'selected' : ''}>Superusuario</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Cambiar</button>
                                    </form>
                                </td>
                            </tr>`;
                    });
                    tablaResultados += '</tbody></table>';

                    // Insertar la tabla en el cuerpo del modal
                    resultadosModalBody.innerHTML = tablaResultados;

                    // Mostrar el modal
                    resultadosModal.show();

                    // Agregar el manejador de eventos para los nuevos formularios
                    document.querySelectorAll("form.ajax-form").forEach(form => {
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




////////////////////////////////

    document.addEventListener("DOMContentLoaded", function() {
        // Escucha el evento submit del formulario
        document.querySelectorAll("form.ajax-form").forEach(form => {
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