<?php

class trace
{
    public $view;
    public $header;
    public $trace;

    public function __construct()
    {
        $this->view = 'inicio';
    }

    public function login()
    {
        $this->view = 'inicio';
    }

    public function logeado()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
            $password = isset($_POST['contraseña']) ? $_POST['contraseña'] : '';

            if ($this->validarUsuario($usuario, $password)) {
                session_start();
                $_SESSION['usuario'] = $usuario;
                $this->view = 'logeado';
                header('Location: index.php?action=' . $this->view);
                exit;
            } else {
                $_SESSION['mensaje_error'] = 'Usuario o contraseña incorrectos';
                $this->view = 'inicio';
            }
        } else {
            $this->view = 'inicio';
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        $this->view = 'inicio';
        header('Location: index.php');
        exit;
    }

    private function validarUsuario($usuario, $password)
    {
        // Conexión a la base de datos
        $db = mysqli_connect("localhost", "root", "", "trace");

        // Verificar si hay error en la conexión
        if (mysqli_connect_errno()) {
            printf("Error de conexión a la base de datos: %s\n", mysqli_connect_error());
            exit();
        }

        // Consulta para verificar las credenciales de inicio de sesión
        $sql = "SELECT * FROM empleado WHERE EMAIL = '$usuario' AND CONTRASEÑA = '$password'";
        $result = mysqli_query($db, $sql);

        // Verificar si se obtuvo un resultado
        if (mysqli_num_rows($result) == 1) {
            return true;
        } else {
            return false;
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($db);
    }
}
