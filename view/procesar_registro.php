<div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <?php
                        if(isset($_SESSION['mensaje_registro'])){
                            echo '<div class="alert alert-info" role="alert">';
                            echo $_SESSION['mensaje_registro'];
                            echo '</div>';
                            unset($_SESSION['mensaje_registro']); // Limpiar la variable de sesión después de mostrar el mensaje
                        }

                        if(isset($_SESSION['mensaje_correo'])){
                            echo '<div class="alert alert-info" role="alert">';
                            echo $_SESSION['mensaje_correo'];
                            echo '</div>';
                            unset($_SESSION['mensaje_correo']); // Limpiar la variable de sesión después de mostrar el mensaje
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>