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
        <a href="#top" class="btn-volver-arriba">Volver arriba</a>
    </div>
    <form class="cerrar" method="post" action="index.php?action=logout">
        <input type="submit" value="Cerrar sesión">
    </form>
</main>