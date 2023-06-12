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

    public function logeado()
    {
        session_start();
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

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
                $_SESSION['usuario'] = $usuario;
                $_SESSION['tipo'] = $this->trace->empleadoTipo($usuario);

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
            if (isset($_COOKIE['usuario'])) {
                $_SESSION['usuario'] = $_COOKIE['usuario'];
                $_SESSION['tipo'] = $this->trace->empleadoTipo($_COOKIE['usuario']);
            }

            $this->view = 'inicio';
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        setcookie('usuario', '', time() - 3600, '/');
        header('Location: index.php');
    }

    public function admin()
    {
        session_start();
        $this->view = 'admin';
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

        $this->trace->guardar_empleado($DNI, $NOMBRE, $APELLIDO_1, $APELLIDO_2, $EMAIL, $DIRECCION, $CIUDAD, $PROVINCIA, $CP, $TELF_CASA, $TLF_MOVIL, $PAIS);
        $this->view = 'logeado';
    }

    public function lista_usuarios()
    {
        $this->view = 'lista_usuarios';
        $empleados = $this->trace->getEmpleados();

        $datos = array(
            'empleados' => $empleados,
        );

        return $datos;
    }

    public function editar_empleado()
    {
        $this->view = 'editar_empleado';
        $dni = $_POST['dni'];

        $empleado = $this->trace->getEmpleadoDni($dni);

        $datos = array(
            'empleado' => $empleado,
        );

        return $datos;
    }

    public function save()
    {
        $this->view = 'lista_usuarios';
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

        $this->trace->guardar_empleado($DNI, $NOMBRE, $APELLIDO_1, $APELLIDO_2, $EMAIL, $DIRECCION, $CIUDAD, $PROVINCIA, $CP, $TELF_CASA, $TLF_MOVIL, $PAIS);
        $empleados = $this->trace->getEmpleados();

        $datos = array(
            'empleados' => $empleados,
        );

        return $datos;
    }

    public function solicitud_licencias()
    {
        $this->view = 'licencias';
    }

    public function procesar_formulario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener los datos del formulario
            $tipo = $_POST['tipo_licencia'];
            $correo = $_POST['correo'];

            // Verificar si se ha enviado el archivo
            if (isset($_FILES['documentacion']) && $_FILES['documentacion']['error'] === UPLOAD_ERR_OK) {
                $archivo = $_FILES['documentacion'];
                $nombreArchivo = $archivo['name'];
                $rutaArchivo = $archivo['tmp_name'];
                $destino = 'view/s_licencias/' . $nombreArchivo;

                // Verificar si se ha movido el archivo correctamente
                if (move_uploaded_file($rutaArchivo, $destino)) {
                    $dni = $this->trace->empleadoDni($correo);

                    // Guardar la solicitud en el modelo
                    if ($this->trace->guardarSolicitud($tipo, $destino, $dni)) {
                        // Enviar aviso al administrador

                        $this->enviarAvisoCorreo($tipo);

                        header('Location: index.php?action=lista_solicitudes');
                        exit;
                    } else {
                        // Mostrar un mensaje de error
                        echo "Error al guardar la solicitud.";
                    }
                } else {
                    // Ocurrió un error al mover el archivo
                    echo "Error al subir el archivo.";
                }
            } else {
                // No se ha enviado ningún archivo o se produjo un error
                echo "No se ha seleccionado ningún archivo o se produjo un error al subirlo.";
            }
        }
    }

    public function enviarAvisoCorreo($tipoLicencia)
    {
        $para      = '9078@cifpceuta.es';
        $titulo    = 'Nueva solicitud de licencia';
        $mensaje   = 'Se ha solicitado una nueva solicitud de Licencia de tipo: ' . $tipoLicencia;
        $cabeceras = 'From: noreply' . "\r\n" .
            'Reply-To: noreply@trace.cifpceuta.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($para, $titulo, $mensaje, $cabeceras);
    }

    public function enviarAvisoCorreoAsuntos($motivo)
    {
        $para      = '9078@cifpceuta.es';
        $titulo    = 'Nueva solicitud de días de asuntos propios';
        $mensaje   = 'Se ha realizado una nueva solicitud de días de asuntos propios. Motivo: ' . $motivo;
        $cabeceras = 'From: noreply' . "\r\n" .
            'Reply-To: noreply@trace.cifpceuta.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($para, $titulo, $mensaje, $cabeceras);
    }

    public function solicitud_asuntos()
    {
        $this->view = 'asuntos';
    }

    public function procesar_asuntos()
    {
        // Verificar si se ha enviado el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener los datos del formulario
            $fecha = $_POST['fecha'];
            $motivo = $_POST['motivo'];
            $correo = $_POST['correo'];

            // Obtener el DNI del empleado
            $dni = $this->trace->empleadoDni($correo);

            // Guardar la solicitud de días de asuntos propios
            $resultado = $this->trace->guardarAsuntos($fecha, $motivo, $dni);

            if ($resultado) {
                // Solicitud guardada correctamente
                // Enviar aviso al administrador
                $this->enviarAvisoCorreoAsuntos($motivo);

                header('Location: index.php?action=lista_asuntos');
                exit();
            } else {
                // Error al guardar la solicitud
                header('Location: index.php');
                exit();
            }
        }
    }

    public function lista_solicitudes()
    {
        session_start();
        $this->view = 'solicitudes_licencias';
        $dni_empleado = $this->trace->empleadoDni($_SESSION['usuario']);
        $solicitudes = $this->trace->getSolicitudesDni($dni_empleado);
        $datos = array(
            'solicitudes' => $solicitudes,
        );

        return $datos;
    }

    public function lista_asuntos()
    {
        session_start();
        $this->view = 'solicitudes_asuntos';
        $dni_empleado = $this->trace->empleadoDni($_SESSION['usuario']);
        $solicitudes = $this->trace->getAsuntosDni($dni_empleado);
        $datos = array(
            'solicitudes' => $solicitudes,
        );

        return $datos;
    }
}
