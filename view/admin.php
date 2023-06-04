<?php
echo $_SESSION['usuario'];
?>
<main class="admin">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>Lista de opciones:</h2>
                <ul class="list-group">
                    <li class="list-group-item">
                        <a href="index.php?action=lista_usuarios" class="opciones-lista">Lista de
                            Usuarios</a>
                    </li>
                    <li class="list-group-item">
                        <a href="index.php?action=ver_peticiones" class="opciones-lista">Gestionar
                            Peticiones</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row justify-content-between align-items-center w-50">
            <div class="col-md-6">
                <div class="mb-3">
                    <a href="index.php" class="btn btn-primary">Volver al perfil de usuario</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <form class="cerrar" method="post" action="index.php?action=logout">
                        <input type="submit" class="btn btn-danger" value="Cerrar sesión">
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>