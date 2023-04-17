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
}
