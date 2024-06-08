<?php 
session_start();
if (!isset($_SESSION['correo'])) {
    header("Location: index.php");
    exit();
}
?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>Calendario de Eventos</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action active">Eventos del Día 1</a>
                        <a href="#" class="list-group-item list-group-item-action">Evento 1</a>
                        <a href="#" class="list-group-item list-group-item-action">Evento 2</a>
                    </div>
                </div>
                <div class="col">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action active">Eventos del Día 2</a>
                        <a href="#" class="list-group-item list-group-item-action">Evento 3</a>
                        <a href="#" class="list-group-item list-group-item-action">Evento 4</a>
                    </div>
                </div>
                <div class="col">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action active">Eventos del Día 3</a>
                        <a href="#" class="list-group-item list-group-item-action">Evento 5</a>
                        <a href="#" class="list-group-item list-group-item-action">Evento 6</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="index.php?action=logeado" class="btn btn-secondary">Volver atrás</a>
        </div>
    </div>
</div>