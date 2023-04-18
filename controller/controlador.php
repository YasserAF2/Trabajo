<?php

class controlador
{
    public $view;
    public $header;
    private $trace;

    public function __construct()
    {
        $this->view = 'inicio';
        $this->trace = new Trace();
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

            if ($this->trace->validarUsuario($usuario, $password)) {
                session_start();
                $_SESSION['usuario'] = $usuario;
            } else {
                $_SESSION['mensaje_error'] = 'Usuario o contraseña incorrectos';
            }
        }

        // Comprobamos si el usuario está logeado
        if (isset($_SESSION['usuario'])) {
            // Si el usuario está logeado, cambiamos la vista a "logeado.php"
            $this->view = 'logeado';
        } else {
            // Si el usuario no está logeado, mostramos la vista de inicio de sesión
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
}
