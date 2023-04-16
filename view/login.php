<?php
session_start();

// verificar si el usuario ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // obtener las credenciales enviadas desde el formulario
    $usuario = $_POST["usuario"];
    $contraseña = $_POST["contraseña"];

    //$trace = new Trace();

    // Conectar a la base de datos
    $conexion = mysqli_connect("localhost", "root", "", "trace");

    // Verificar si la conexión es correcta
    if (!$conexion) {
        die('Error de conexión: ' . mysqli_connect_error());
    }

    // Crear la consulta SQL
    $sql = "SELECT * FROM empleado WHERE EMAIL = '$usuario' AND CONTRASEÑA = '$contraseña'";

    // Ejecutar la consulta SQL
    $resultado = mysqli_query($conexion, $sql);

    // Verificar si la consulta devolvió algún resultado
    if (mysqli_num_rows($resultado) == 1) {
        // Los datos son correctos, guardar información de sesión
        $_SESSION['usuario'] = $usuario;

        // Redirigir a la página principal
        header('Location: inicio.php');
    } else {
        // Los datos son incorrectos, mostrar mensaje de error
        echo 'Usuario o contraseña incorrectos';
        header('Location: inicio.php');
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($conexion);
}
