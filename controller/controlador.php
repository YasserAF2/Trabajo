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
        if (isset($_COOKIE['usuario'])) {
            session_start();
            $sessionId = preg_replace('/[^a-zA-Z0-9,-]/', '', $_COOKIE['usuario']);
            session_id($sessionId);
            header('Location: index.php?action=logeado');
        } else {
            // Mostrar el formulario de inicio de sesión
            $this->view = 'inicio';
        }
    }


    public function logeado()
    {
        // Si el usuario ya ha iniciado sesión, mostrar la página de inicio de sesión
        if (isset($_SESSION['usuario'])) {
            $this->view = 'logeado';
            return;
        }

        // Si se envía una solicitud POST, intentar iniciar sesión
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
            $password = isset($_POST['contraseña']) ? $_POST['contraseña'] : '';
            $recordar = isset($_POST['recordar']) ? $_POST['recordar'] : '';

            if ($this->trace->validarUsuario($usuario, $password)) {
                session_start();
                $_SESSION['usuario'] = $usuario;

                if ($recordar == 'on') {
                    $expire = time() + (60 * 60 * 24 * 30);
                    setcookie('usuario', $usuario, $expire, '/');
                }
                $this->view = 'logeado';
            } else {
                $_SESSION['mensaje_error'] = 'Usuario o contraseña incorrectos';
                $this->view = 'inicio';
            }
        } else {
            // Mostrar el formulario de inicio de sesión
            session_start();
            if (isset($_COOKIE['usuario'])) {
                $_SESSION['usuario'] = $_COOKIE['usuario'];
                $this->view = 'logeado';
            } else {
                $this->view = 'inicio';
            }
        }
    }


    public function logout()
    {
        session_start();
        session_destroy();
        setcookie('usuario', '', time() - 3600, '/');
        header('Location: index.php');
    }

    public function editar_perfil()
    {
        $correo = $_POST['correo'];
        $this->view = "editar_perfil";
        return $correo;
    }


    public function guardar_perfil()
    {
        session_start();

        $DNI = $_POST['dni'];
        $NOMBRE = $_POST['nombre'];
        $APELLIDO_1 = $_POST['apellido1'];
        $APELLIDO_2 = $_POST['apellido2'];
        $EMAIL = $_POST['email'];
        $DIRECCION = $_POST['direccion'];
        $CIUDAD = $_POST['ciudad'];
        $PROVINCIA = $_POST['provincia'];
        $CP = $_POST['cp'];
        $TELF_CASA = $_POST['telefono_fijo'];
        $TLF_MOVIL = $_POST['telefono_movil'];
        $PAIS = $_POST['pais'];

        $trace = new Trace();
        $trace->guardar_empleado($DNI, $NOMBRE, $APELLIDO_1, $APELLIDO_2, $EMAIL, $DIRECCION, $CIUDAD, $PROVINCIA, $CP, $TELF_CASA, $TLF_MOVIL, $PAIS);
        $this->view = 'logeado';
    }
}
