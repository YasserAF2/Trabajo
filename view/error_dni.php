<body>
    <h1>Error - DNI no registrado</h1>
    <p>El DNI proporcionado no está registrado en la base de datos.
        Por favor, verifica que has ingresado el DNI correcto o contacta con el administrador.</p>

    <!-- Mostrar el DNI almacenado en la sesión -->
    <p>DNI almacenado en sesión:
        <?php $dni = $_SESSION['dni'];
        echo $dni;
        ?>
    </p>

    <!-- Botón para volver al index.php -->
    <a href="index.php" class="btn btn-primary">Volver al inicio</a>
</body>