<?php
$usuario  = $_SESSION['usuario'];
echo $usuario;
?>

<main>
    <div>
        <p>
            ¡Bienvenido/a, <?php echo $usuario->getNOMBRE; ?>!
        </p>
        <form method="post" action="index.php?action=logout">
            <input type="submit" value="Cerrar sesión">
        </form>
    </div>

</main>