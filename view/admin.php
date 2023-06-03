<?php
echo $_SESSION['usuario'];
?>
<main class="admin">
    <div>
        <div>
            <h2>Lista de opciones:</h2>
            <ul>
                <li><a href="index.php?action=lista_usuarios" class="opciones-lista">Lista de Usuarios</a></li>
                <li><a href="index.php?action=ver_peticiones" class="opciones-lista">Gestionar Peticiones</a></li>
            </ul>
        </div>
    </div>
    <div>
        <a href="index.php" class="btn btn-primary">Volver al perfil de usuario</a>
    </div>
    <div>
        <form class="cerrar" method="post" action="index.php?action=logout">
            <input type="submit" value="Cerrar sesión">
        </form>
    </div>

</main>