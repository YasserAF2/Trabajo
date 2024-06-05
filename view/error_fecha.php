<body>
    <h1>Error - Fecha de nacimiento no registrada</h1>
    <p>La fecha de nacimiento proporcionada no está registrada en la base de datos.
        Por favor, verifica que has ingresado la fecha correcta o contacta con el administrador.</p>

    <!-- Mostrar la fecha de nacimiento almacenada en la sesión -->
    <p>Fecha de nacimiento almacenada en sesión:
        <?php $fecha = $_SESSION['fecha'];
        echo $fecha;
        ?>
    </p>

    <!-- Botón para volver al index.php -->
    <a href="index.php" class="btn btn-primary">Volver al inicio</a>
</body>