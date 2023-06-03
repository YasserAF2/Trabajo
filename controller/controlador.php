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

    public function procesar_licencias()
    {
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
                        $administradorEmail = 'yas.123af@gmail.com';
                        $this->enviarAvisoCorreo($administradorEmail, $tipo);

                        header('Location: index.php');
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

    public function enviarAvisoCorreo($administradorEmail, $tipoLicencia)
    {
        // Dirección de correo del remitente
        $remitente = 'yas.123af@gmail.com';

        // Asunto del correo
        $asunto = 'Nueva solicitud de licencia';

        // Cuerpo del correo
        $mensaje = 'Se ha realizado una nueva solicitud de licencia del tipo: ' . $tipoLicencia;

        // Cabeceras del correo
        $cabeceras = 'From: ' . $remitente . "\r\n" .
            'Reply-To: ' . $remitente . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        // Envío del correo
        $enviado = mail($administradorEmail, $asunto, $mensaje, $cabeceras);

        // Verificar si el correo se ha enviado correctamente
        if ($enviado) {
            echo "Se ha enviado un aviso al administrador.";
        } else {
            echo "Error al enviar el aviso al administrador.";
        }
    }

    public function solicitud_asuntos()
    {
        $this->view = 'asuntos';
    }

    public function procesar_asuntos()
    {
    }

    public function lista_solicitudes()
    {
        session_start();
        $this->view = 'solicitudes';
        $dni_empleado = $this->trace->empleadoDni($_SESSION['usuario']);
        $solicitudes = $this->trace->getSolicitudesDni($dni_empleado);
        $datos = array(
            'solicitudes' => $solicitudes,
        );

        return $datos;
    }
}
