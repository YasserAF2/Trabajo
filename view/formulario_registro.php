<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 mb-5">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title text-center mb-4">Registro de Usuario</h1>
                    <form action="index.php?action=registro" method="post">
                        <div class="form-group">
                            <label for="email">Correo electrónico:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>