<?php

include_once 'vendor/autoload.php';
// Importación de clases PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
error_reporting(E_ALL); 
ini_set('display_errors', 1);

class controlador
{
    public $view;
    public $header;
    private $trace;

    public function __construct()
    {
        $this->view = 'login';
        $this->trace = new Trace();
    }

    public function login()
    {
        $this->view = 'login';
    }

    public function logeado()
    {
        $resultado = $this->trace->logeado();
        $correo = $_SESSION['user_id'];

        if ($resultado === true) {
            $this->view = 'logeado';
            $_SESSION['user_id'] = $correo;
        } else {
            $this->view = 'login';
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        setcookie('usuario', '', time() - 3600, '/');
        header('Location: index.php');
    }

    public function ver_solicitudes()
    {
        session_start();
        if (!isset($_SESSION['dni']) || !isset($_SESSION['correo'])) {
            header("Location: index.php?accion=login");
            exit();
        }
    
        $dni = $_SESSION['dni'];
        $correo = $_SESSION['correo'];
    
        $peticiones = $this->trace->peticiones_dni($dni);
        $datos = array(
            'peticiones' => $peticiones,
        );
    
        $this->view = 'ver_solicitudes'; // Esto indica cuál vista cargar
        return $datos;
    }
    

    //ASUNTOS PROPIOS VISTA
    public function solicitud_asuntos_propios() {
        session_start();
        if (isset($_SESSION['correo'])) {
            $correo = $_SESSION['correo'];
            $dias = $this->trace->obtenerDiasAsuntosPropios($correo);
            $datos = array(
                'dias' => $dias,
            );
            $this->view = 'solicitud_asuntos_propios';
            return $datos;
        } else {
            $this->view = "login";
        }
    }

    // Función para procesar la solicitud de días de asuntos propios
    public function asuntos_propios(){
        session_start();
        $mensaje = $this->trace->asuntos_propios();
        $_SESSION['resultado_solicitud'] = $mensaje;

        $this->view = "resultado_asuntos_propios";
    }
    
    public function documentacion_baja_accidente()
    {
        $this->view = 'documentacion_baja_accidente';
    }

    public function submit_baja_accidente(){
        session_start();
        $mensaje = $this->trace->submit_baja_accidente();
        $_SESSION['resultado_solicitud'] = $mensaje;

        $this->view = "resultado_asuntos_propios";
    }

    public function solicitud_asuntos_propios_no_remunerados()
    {
        $this->view = 'solicitud_asuntos_propios_no_rm';
    }

    public function solicitud_licencia_lactancia_maternidad_paternidad()
    {
        $this->view = 'solicitud_maternidad';
    }

    public function documentacion_baja_enfermedad()
    {
        $this->view = 'documentacion_baja_enfermedad';
    }

    public function solicitud_hora_sindical()
    {
        $this->view = 'solicitud_hora_sindical';
    }

    public function solicitud_licencia()
    {
        $this->view = 'solicitud_licencia';
    }

    public function mensaje_direccion()
    {
        $this->view = 'mensaje_direccion';
    }

    //Funciones enviar_mensaje y correo para enviar correos electronicos
    public function enviar_mensaje()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mensaje'])) {
            // Obtener los datos del formulario
            $mensaje = $_POST['mensaje'];
            $destinatario = $_POST['destinatario'];
            // Instanciar el objeto de PHPMailer
            $mail = new PHPMailer(true);
            
            try {
                // Configurar el servidor SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = "ceutaportalempleados@gmail.com"; 
                $mail->Password = 'cfgs zaum nfkn rsaj'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
    
                // Configurar el correo
                $mail->setFrom('ceutaportalempleados@gmail.com', 'Trace');
                $mail->addAddress($destinatario);
                $mail->Subject = "Mensaje desde la aplicación";
                $mail->Body = "Has recibido un nuevo mensaje:\n\n" . $mensaje;
    
                // Enviar el correo
                $mail->send();
    
                // Guardar el mensaje de éxito en la sesión
                $_SESSION['success_mensaje'] = "Correo enviado correctamente.";
                // Redireccionar a la vista resultado_envio.php
                $this->view = "resultado_envio";
            } catch (Exception $e) {
                // Guardar el mensaje de error en la sesión
                $_SESSION['error_mensaje'] = "Hubo un error al enviar el correo.";
                // Redireccionar a la vista resultado_envio.php
                $this->view = "resultado_envio";
            }
        }
    }
    
    
    public function mensaje_encargado_general()
    {
        $this->view = 'mensaje_encargado_general';
    }

    public function mensaje_dpto_produccion()
    {
        $this->view = 'mensaje_dpto_produccion';
    }

    public function mensaje_nominas()
    {
        $this->view = 'mensaje_nominas';
    }

    public function consultas_uniformes_calzado()
    {
        $this->view = 'consultas_uniformes';
    }


    // Agrega el método registro_dni()
    public function registro_dni()
    {
        $this->view = 'registro_dni';
    }

    public function registro_fecha()
    {
        $this->view = 'registro_fecha';
    }

    public function registro()
    {
        session_start();
        $dni = $_SESSION['dni'];
        $this->view = 'procesar_registro';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $token = $this->trace->procesar_registro();

            if ($token) {
                $this->enviar_correo_confirmacion($email, $token);
                echo "Revise su correo para confirmar el registro.";
            } else {
                echo "Hubo un error en el registro. Por favor, intente nuevamente.";
            }
        } else {
            echo "Solicitud no válida.";
        }
    }


    public function procesar_dni()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['dni'])) {
            $dni = $_POST['dni'];
            if ($this->trace->procesar_dni()) {
                $_SESSION['dni'] = $dni;
                $this->view = 'registro_fecha'; // Si el DNI está en la base de datos, mostrar el formulario de fecha de nacimiento
            } else {
                $_SESSION['mensaje_error'] = 'El DNI proporcionado no está registrado.';
                $this->view = 'error_dni'; // Si el DNI no está en la base de datos, mostrar un mensaje de error
            }
        }
    }

    public function procesar_fecha()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fecha'])) {
            $fecha = $_POST['fecha'];
            if ($this->trace->procesar_fecha()) { // Verificar si la fecha de nacimiento está en la base de datos
                $_SESSION['fecha'] = $fecha;
                $this->view = 'formulario_registro'; // Si la fecha de nacimiento está en la base de datos, mostrar el formulario de registro
            } else {
                $_SESSION['fecha'] = $fecha;
                $_SESSION['mensaje_error'] = 'La fecha de nacimiento proporcionada no es válida.';
                $this->view = 'error_fecha'; // Si la fecha de nacimiento no está en la base de datos, mostrar un mensaje de error
            }
        }
    }

    private function enviar_correo_confirmacion($email, $token)
    {
        // Configuración del correo
        $mail = new PHPMailer(true); // Establece el modo de excepciones en true para que PHPMailer arroje excepciones en caso de error

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'ceutaportalempleados@gmail.com'; // Tu dirección de correo electrónico
            $mail->Password = 'cfgs zaum nfkn rsaj'; // Tu contraseña de correo electrónico
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('ceutaportalempleados@gmail.com', 'Trace');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Confirmación de registro';
            $mail->Body = "Haga clic en el siguiente enlace para confirmar su registro: <a href='http://localhost/php/Trabajo/index.php?action=confirmar_registro&token=$token'>Confirmar Registro</a>";

            // Envío del correo
            $mail->send();
            echo 'El mensaje ha sido enviado.';
        } catch (Exception $e) {
            echo "El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function confirmar_registro()
    {
        $this->view = 'registro_exitoso';
        if (isset($_GET['token'])) {
            $token = $_GET['token'];

            $this->trace->confirmar_registro($token);
        } else {
            echo "No se proporcionó token.";
        }
    }

    public function solicitud_asuntos_propios_norm(){
        session_start();
        if (isset($_GET['action']) && $_GET['action'] === 'solicitud_asuntos_propios_norm') {
            $resultado = $this->trace->solicitud_asuntos_propios_norm();
            $_SESSION['resultado_solicitud'] = $resultado;
            $this->view = 'asuntos_propios_norm_resultado';
        }
    }
}