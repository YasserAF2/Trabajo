<?php
// Incluir autoload.php de Composer
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

    //VISTA PARA REALIZAR EL LOGIN
    public function login()
    {
        $this->view = 'login';
    }

    //VISTA PRINCIPAL PERFIL DE USUARIO CON OPCIONES
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

    //CERRAR SESIÓN
    public function logout()
    {
        session_start();
        session_destroy();
        setcookie('usuario', '', time() - 3600, '/');
        header('Location: index.php');
    }

    //VISTA PARA VER LAS SOLICITUDES DEL EMPLEADO
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

        $this->view = 'ver_solicitudes';
        return $datos;
    }

    //VISTA EN ADMIN PARA VER LA VISTA DE SOLICITUDES ACEPTADAS
    public function ver_calendario()
    {
        $this->view = 'ver_calendario';
    }

    //VISTA DE CAMBIAR ROLES
    public function cambiar_roles()
    {
        $empleados = $this->trace->lista_empleados();
        $this->view = 'cambiar_roles';
        $datos = array(
            'empleados' => $empleados,
        );
        return $datos;
    }

    //CAMBIAR EL ROL DEL USUARIO
    public function cambiar_tipo()
    {
        $response = $this->trace->cambiar_tipo();
        ob_clean(); // Limpiar el buffer de salida
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    //ACEPTAR SOLICITUDES
    public function aceptar_ap()
    {
        if (isset($_GET['peticion_id'])) {
            $this->trace->aceptar_ap();
            header("Location: index.php?action=ver_solicitudes_ap");
            exit();
        } else {
            echo "No se ha proporcionado un ID de petición en la URL.";
        }
    }

    public function aceptar_as()
    {
        if (isset($_GET['peticion_id'])) {
            $this->trace->aceptar_as();
            header("Location: index.php?action=ver_solicitudes_as");
            exit();
        } else {
            echo "No se ha proporcionado un ID de petición en la URL.";
        }
    }

    public function aceptar_baja()
    {
        if (isset($_GET['peticion_id'])) {
            $this->trace->aceptar_baja();
            header("Location: index.php?action=ver_bajas");
            exit();
        } else {
            echo "No se ha proporcionado un ID de petición en la URL.";
        }
    }

    //RECHAZAR SOLICITUDES
    public function rechazar_baja()
    {
        if (isset($_GET['peticion_id'])) {
            $this->trace->rechazar_baja();
            header("Location: index.php?action=ver_bajas");
            exit();
        } else {
            echo "No se ha proporcionado un ID de petición en la URL.";
        }
    }

    public function rechazar_ap()
    {
        if (isset($_GET['peticion_id'])) {
            $this->trace->rechazar_ap();
            header("Location: index.php?action=ver_solicitudes_ap");
            exit();
        } else {
            echo "No se ha proporcionado un ID de petición en la URL.";
        }
    }

    public function rechazar_as()
    {
        if (isset($_GET['peticion_id'])) {
            $this->trace->rechazar_as();
            header("Location: index.php?action=ver_solicitudes_as");
            exit();
        } else {
            echo "No se ha proporcionado un ID de petición en la URL.";
        }
    }

    //ASUNTOS PROPIOS VISTA usuario
    public function solicitud_asuntos_propios()
    {
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

    //solicitudes ap en admin
    public function ver_solicitudes_ap()
    {
        session_start();
        if (isset($_SESSION['correo'])) {
            $peticiones = $this->trace->ver_solicitudes_ap();
            $datos = array(
                'peticiones' => $peticiones,
            );
            $this->view = 'ver_solicitudes_ap';
            return $datos;
        } else {
            $this->view = "login";
        }
    }

    //solicitudes as en admin
    public function ver_solicitudes_as()
    {
        session_start();
        if (isset($_SESSION['correo'])) {
            $peticiones = $this->trace->ver_solicitudes_as();
            $datos = array(
                'peticiones' => $peticiones,
            );
            $this->view = 'ver_solicitudes_as';
            return $datos;
        } else {
            $this->view = "login";
        }
    }

    // Función para procesar la solicitud de días de asuntos propios
    public function asuntos_propios()
    {
        session_start();
        $mensaje = $this->trace->asuntos_propios();
        $_SESSION['resultado_solicitud'] = $mensaje;

        $this->view = "resultado_asuntos_propios";
    }

    //VISTA PARA LICENCIAS Y BAJAS Y SUS SUBMITS
    public function documentacion_baja_accidente()
    {
        $this->view = 'documentacion_baja_accidente';
    }

    public function submit_baja_accidente()
    {
        session_start();
        $mensaje = $this->trace->submit_baja_accidente();
        $_SESSION['resultado_solicitud'] = $mensaje;
        $this->view = "resultado_baja_accidente_enfermedad";
    }

    public function documentacion_baja_enfermedad()
    {
        $this->view = 'documentacion_baja_enfermedad';
    }

    public function submit_baja_enfermedad()
    {
        session_start();
        $mensaje = $this->trace->submit_baja_enfermedad();
        $_SESSION['resultado_solicitud'] = $mensaje;
        $this->view = "resultado_baja_accidente_enfermedad";
    }

    public function solicitud_asuntos_propios_no_remunerados()
    {
        $this->view = 'solicitud_asuntos_propios_no_rm';
    }

    public function solicitud_licencia_lactancia_maternidad_paternidad()
    {
        $this->view = 'solicitud_maternidad';
    }

    public function submit_lmp()
    {
        session_start();
        $mensaje = $this->trace->submit_lmp();
        $_SESSION['resultado_solicitud'] = $mensaje;
        $this->view = "resultado_baja_accidente_enfermedad";
    }

    public function solicitud_hora_sindical()
    {
        $this->view = 'solicitud_hora_sindical';
    }

    public function submit_hora_sindical()
    {
        session_start();
        $mensaje = $this->trace->submit_hora_sindical();
        $_SESSION['resultado_solicitud'] = $mensaje;
        $this->view = "resultado_baja_accidente_enfermedad";
    }

    public function solicitud_licencia()
    {
        $this->view = 'solicitud_licencia';
    }

    public function submit_licencia()
    {
        session_start();
        $mensaje = $this->trace->submit_licencia();
        $_SESSION['resultado_solicitud'] = $mensaje;
        $this->view = "resultado_baja_accidente_enfermedad";
    }

    public function mensaje_direccion()
    {
        $this->view = 'mensaje_direccion';
    }

    //VISTA PRINCIPAL PARA EL ADMIN
    public function admin()
    {
        $this->view = 'admin';
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
                $mail->Subject = "Mensaje Portal de Empleados";
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


    //VISTAS DE LAS OPCIONES PARA ENVIAR MENSAJES
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

    //PROCESAR EL DNI DEL REGISTRO
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

    //PROCESAR LA FECHA DE NACIMIENTO DEL REGISTRO
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

    //ENVIAR UN CORREO ELECTRÓNICO EN EL REGISTRO
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
            $mail->Subject = 'TRACE - Confirmar registro';
            $mail->Body = "Haga clic en el siguiente enlace para confirmar su registro: <a href='http://40942650.servicio-online.net/index.php?action=confirmar_registro&token=$token'>Confirmar Registro</a>";

            // Envío del correo
            $mail->send();
            $_SESSION['mensaje_correo'] = 'Se ha enviado un correo electrónico de confirmación a su dirección de correo registrada. 
            Por favor, verifique su bandeja de entrada para completar el proceso de registro.';
        } catch (Exception $e) {
            $_SESSION['mensaje_correo'] = "El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    //CONFIRMAR EL REGISTRO
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
            } else {
                $_SESSION['mensaje_correo'] = "Hubo un error en el registro. Por favor, intente nuevamente.";
            }
        } else {
            $_SESSION['mensaje_correo'] = "Solicitud no válida.";
        }

        return;
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

    //SOLICITUDES AS RESULTADO
    public function solicitud_asuntos_propios_norm()
    {
        session_start();
        if (isset($_GET['action']) && $_GET['action'] === 'solicitud_asuntos_propios_norm') {
            $resultado = $this->trace->solicitud_asuntos_propios_norm();
            $_SESSION['resultado_solicitud'] = $resultado;
            $this->view = 'asuntos_propios_norm_resultado';
        }
    }

    //VISTA PARA VER LAS SOLICITUDES DE BAJAS EN ADMIN
    public function ver_bajas()
    {
        session_start();
        if (isset($_SESSION['correo'])) {
            $peticiones = $this->trace->ver_bajas_licencias();
            $datos = array(
                'peticiones' => $peticiones,
            );
            $this->view = "ver_bajas";
            return $datos;
        } else {
            $this->view = "login";
        }
    }

    //BUSCADOR DE EMPLEADOS EN CAMBIAR ROL
    public function buscar()
    {
        ob_clean();

        $buscador = isset($_GET['buscador']) ? $_GET['buscador'] : '';
        $resultados = $this->trace->buscar_empleado_rol($buscador);
        header('Content-Type: application/json');

        if ($resultados) {
            echo json_encode(['success' => true, 'data' => $resultados]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontraron resultados.']);
        }

        exit();
    }

    //vista calendario para bajar excel
    public function buscar_peticion()
    {
        $buscador = isset($_GET['buscador']) ? $_GET['buscador'] : '';
        $trace = new Trace();
        $peticiones = $trace->obtenerBusqueda($buscador);

        $response = [
            'success' => true,
            'peticiones' => $peticiones
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    public function buscar_ap()
    {
        $buscador = isset($_GET['buscador']) ? $_GET['buscador'] : '';
        $trace = new Trace();
        $peticiones = $trace->buscarPeticionesAp($buscador);
        $response = [
            'success' => true,
            'peticiones' => $peticiones
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }


    public function buscar_as()
    {
        $buscador = isset($_GET['buscador']) ? $_GET['buscador'] : '';
        $trace = new Trace();
        $peticiones = $trace->buscarPeticionesAs($buscador);
        $response = [
            'success' => true,
            'peticiones' => $peticiones
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    public function buscar_bajas()
    {
        $buscador = isset($_GET['buscador']) ? $_GET['buscador'] : '';
        $trace = new Trace();
        $peticiones = $trace->buscarPeticionesBajas($buscador);
        $response = [
            'success' => true,
            'peticiones' => $peticiones
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }


    // Función para generar y descargar Excel
    public function excel()
    {
        // Lógica para obtener los datos de la tabla que deseas exportar
        $peticiones = $this->trace->obtenerPeticionesAceptadas();

        // Crear un nuevo libro de Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Agregar encabezados
        $sheet->setCellValue('A1', 'Fecha');
        $sheet->setCellValue('B1', 'DNI');
        $sheet->setCellValue('C1', 'Nombre y Apellidos');
        $sheet->setCellValue('D1', 'Tipo');
        $sheet->setCellValue('E1', 'Fecha Solicitud');
        $sheet->setCellValue('F1', 'Supervisor');

        // Llenar datos desde la base de datos
        $fila = 2;
        foreach ($peticiones as $peticion) {
            $sheet->setCellValue('A' . $fila, date("d/m/Y", strtotime($peticion['PET_FECHA'])));
            $sheet->setCellValue('B' . $fila, $peticion['PET_DNI']);
            $sheet->setCellValue('C' . $fila, $peticion['EMP_NOMBRE'] . ' ' . $peticion['EMP_APE_1']);
            $sheet->setCellValue('D' . $fila, $peticion['PET_TIPO']);
            $sheet->setCellValue('E' . $fila, date("d/m/Y H:i:s", strtotime($peticion['PET_FECHA_HORA_SOLICITUD'])));
            $sheet->setCellValue('F' . $fila, $peticion['PET_SUPERVISOR']);
            $fila++;
        }

        // Establecer el nombre del archivo
        $filename = 'peticiones_aceptadas.xlsx';

        // Configurar los encabezados HTTP para la descarga del archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Crear el escritor y generar la salida del archivo
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
}
