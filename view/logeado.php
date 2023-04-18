<main>
    <div>
        <p>
            ¡Bienvenido/a, <?php echo $_SESSION['usuario']; ?>!
        </p>
        <form method="post" action="index.php?action=logout">
            <input type="submit" value="Cerrar sesión">
        </form>
    </div>

</main>