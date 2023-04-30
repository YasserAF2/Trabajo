<main>
    <div class="cont">
        <div class="list">
            <h2>Lista de opciones:</h2>
            <ul>
                <li><a href="index.php?action=lista_usuarios" class="opciones-lista">Lista de usuarios</a></li>
                <li><a href="index.php?action=ver_productos" class="opciones-lista">Gestionar Productos</a></li>
            </ul>
        </div>
        <div class="main">
            <div id="vista-dinamica">
                <h2>Zona de administración:</h2>
            </div>
            <a href="#top" class="btn-volver-arriba">Volver arriba</a>
        </div>
    </div>
    <form method="post" action="index.php?action=logout">
        <input type="submit" value="Cerrar sesión">
    </form>
</main>