<?php
include_once 'vendor/autoload.php';
error_reporting(E_ALL); 
ini_set('display_errors', 1);
class Trace
{
    private $conection;
    private array $empleados = array();
    private array $peticiones = array();

    function __construct()
    {
        $this->getConection();
    }

    public function getConection()
    {
        $dbObj = new Db();
        $this->conection = $dbObj->conection;
    }


    public function procesar_dni()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['dni'])) {
            $dni = $_POST['dni'];

            // Sanitizar la entrada
            $dni = $this->conection->real_escape_string($dni);

            // Consulta SQL utilizando una consulta preparada
            $sql = "SELECT * FROM empleados WHERE EMP_NIF = ?";
            $stmt = $this->conection->prepare($sql);
            $stmt->bind_param("s", $dni);
            $stmt->execute();

            // Verificar si se obtuvo un resultado
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $_SESSION['dni'] = $dni;
                return true;
            } else {
                return false;
            }
        }
    }

    public function procesar_fecha()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fecha'])) {
            $fecha = $_POST['fecha'];
            $dni = $_SESSION['dni'];

            // Sanitizar la entrada (asegúrate de que la fecha esté en el formato adecuado)
            $fecha = $this->conection->real_escape_string($fecha);

            // Formatear la fecha al formato de MySQL (año-mes-día)
            $fecha_formateada = date('Y-m-d', strtotime($fecha));

            // Consulta SQL utilizando una consulta preparada
            $sql = "SELECT * FROM empleados WHERE EMP_FEC_NAC = ?";
            $stmt = $this->conection->prepare($sql);
            $stmt->bind_param("s", $fecha_formateada); // Usamos la fecha formateada
            $stmt->execute();

            // Verificar si se obtuvo un resultado
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $_SESSION['fecha'] = $fecha_formateada; // Almacenamos la fecha formateada
                $_SESSION['dni'] = $dni;
                return true;
            } else {
                return false;
            }
        }
    }

    public function procesar_registro()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $password = password_hash($password, PASSWORD_DEFAULT);

        // Obtener el NIF de la sesión
        $nif = $_SESSION['dni'];
        // Sanitización de los datos
        $email = mysqli_real_escape_string($this->conection, $email);
        $password = mysqli_real_escape_string($this->conection, $password);

        // Generar un token de confirmación
        $token = bin2hex(random_bytes(16));

        // Preparación de la consulta SQL
        $stmt = $this->conection->prepare("UPDATE empleados SET EMP_CORREO = ?, EMP_CONTRASEÑA = ?, TOKEN = ? WHERE EMP_NIF = ?");
        $stmt->bind_param("ssss", $email, $password, $token, $nif);

        // Ejecución de la consulta SQL
        $resultado = $stmt->execute();

        // Cierre de la declaración
        $stmt->close();

        // Retornar el resultado y el token
        return $resultado ? $token : false;
    }

    public function confirmar_registro($token)
    {
        // Verificar si el token es válido
        $stmt = $this->conection->prepare("SELECT * FROM empleados WHERE TOKEN = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Token válido, proceder con la actualización
            $stmt = $this->conection->prepare("UPDATE empleados SET CONFIRMADO = 1 WHERE TOKEN = ?");
            $stmt->bind_param("s", $token);
            $resultado = $stmt->execute();
            $stmt->close();
            return $resultado;
        } else {
            // Token inválido, retorno falso
            return false;
        }
    }

    public function getEmpleadoCorreo($correo)
    {
        $stmt = $this->conection->prepare("SELECT * FROM empleados WHERE EMP_CORREO = ?");
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $this->conection->error);
        }
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $empleado = $result->fetch_assoc();
            $stmt->close();
            return $empleado;
        } else {
            $stmt->close();
            return null;
        }
    }

    public function logeado()
    {
        session_start();
    
        // Verificar si ya hay una sesión iniciada
        if (isset($_SESSION['user_id']) && isset($_SESSION['contraseña'])) {
            return true; // El usuario ya está autenticado
        }
    
        // Si no hay una sesión iniciada, intentar iniciar sesión con las credenciales proporcionadas
        $correo = $_POST['correo'];
        $contraseña = $_POST['contraseña'];
        $query = $this->conection->prepare("SELECT * FROM empleados WHERE EMP_CORREO = ?");
        $query->bind_param("s", $correo);
        $query->execute();
        $result = $query->get_result()->fetch_assoc();
        $_SESSION['result'] = $result;
    
        if (!$result) {
            return '¡La combinación de nombre de usuario y contraseña es incorrecta!';
        }
    
        if (isset($result['EMP_CONTRASEÑA']) && password_verify($contraseña, $result['EMP_CONTRASEÑA'])) {
            $_SESSION['user_id'] = $result['EMP_CORREO'];
            $_SESSION['contraseña'] = $result['EMP_CONTRASEÑA'];
            return true; // Inicio de sesión exitoso
        } else {
            return false; // La contraseña no coincide
        }
    }


    /* Te devuelve las peticiones por el dni del empleado */
    public function peticiones_dni($dni)
    {
        // Preparar la consulta con un marcador de posición para el valor del DNI
        $sql = "SELECT * FROM T_PETICIONES WHERE PET_DNI = ?";
        $stmt = $this->conection->prepare($sql);
    
        // Verificar la preparación de la consulta
        if (!$stmt) {
            die('Error al preparar la consulta: ' . $this->conection->error);
        }
    
        // Vincular el parámetro DNI y ejecutar la consulta
        $stmt->bind_param("s", $dni);
        $stmt->execute();
    
        // Verificar la ejecución de la consulta
        if (!$stmt) {
            die('Error al ejecutar la consulta: ' . $stmt->error);
        }
    
        // Obtener el resultado y cerrar la sentencia
        $resultado = $stmt->get_result();
        $peticiones = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    
        return $peticiones;
    }
    

    public function obtenerDiasAsuntosPropios($correo)
    {
        // Preparar la consulta con un marcador de posición para el valor del correo
        $sql = "SELECT NUM_TOTAL_DIAS_AP FROM empleados WHERE EMP_CORREO = ?";
        $stmt = $this->conection->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $dias_ap = null;
        
        // Verificar si se encontró un resultado
        if ($row = $resultado->fetch_assoc()) {
            $dias_ap = $row['NUM_TOTAL_DIAS_AP'];
        }
    
        $stmt->close();
        return $dias_ap;
    }
    

    function asuntos_propios() {
    // Recoger la fecha y hora del POST
    $fechaHora = $_POST['selected_date'];

    // Separar la fecha y la hora
    list($fecha, $hora) = explode(' ', $fechaHora);

    // Convertir la fecha al formato YYYY-MM-DD
    $fechaPartes = explode('-', $fecha);
    if (count($fechaPartes) == 3) {
        // Si el array tiene tres elementos (día, mes, año), el formato es correcto
        $fechaFormateada = sprintf('%04d-%02d-%02d', $fechaPartes[2], $fechaPartes[1], $fechaPartes[0]);
    } else {
        // Manejar el caso en el que el formato de fecha no sea el esperado
        // Por ejemplo, mostrar un mensaje de error o establecer $fechaFormateada en un valor predeterminado
    }
    
        $num_dias_ap = 0;
        $num_total_dias_ap = 0;

        // Preparar la consulta para obtener los días AP del empleado
        $stmt_ap = $this->conection->prepare("SELECT NUM_DIAS_AP, NUM_TOTAL_DIAS_AP FROM empleados WHERE EMP_NIF = ?");
        $stmt_ap->bind_param("s", $_SESSION['dni']);
        $stmt_ap->execute();
        $stmt_ap->store_result();
        $stmt_ap->bind_result($num_dias_ap, $num_total_dias_ap);
        $stmt_ap->fetch();
    
        // Verificar si el número de días AP no supera el total
        if ($num_dias_ap < $num_total_dias_ap) {
            // Incrementar el número de días AP
            $num_dias_ap++;
    
            // Actualizar el número de días AP en la base de datos
            $stmt_update_ap = $this->conection->prepare("UPDATE empleados SET NUM_DIAS_AP = ? WHERE EMP_NIF = ?");
            $stmt_update_ap->bind_param("is", $num_dias_ap, $_SESSION['dni']);
            $stmt_update_ap->execute();
            $stmt_update_ap->close();
    
            // Preparar la consulta para insertar la solicitud
            $stmt_insert = $this->conection->prepare("INSERT INTO t_peticiones (PET_DNI, PET_FECHA, PET_TIPO, PET_FECHA_HORA_SOLICITUD, PET_ACEPTADO, PET_SUPERVISOR, PET_DOC) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
            // Definir valores
            $dni = $_SESSION['dni'];
            $tipo = 'AP';
            $aceptado = 'En espera';
            $supervisor = NULL;
            $doc = NULL;
    
            // Crear la fecha y hora completa en formato compatible
            $fechaHoraSolicitud = $fechaFormateada . ' ' . $hora;
    
            // Vincular parámetros
            $stmt_insert->bind_param("sssssss", $dni, $fechaFormateada, $tipo, $fechaHoraSolicitud, $aceptado, $supervisor, $doc);
    
            // Ejecutar la consulta
            if ($stmt_insert->execute()) {
                $resultado = "Solicitud enviada correctamente.";
            } else {
                $resultado = "Error al enviar la solicitud: " . $stmt_insert->error;
            }
    
            // Cerrar la declaración y la conexión
            $stmt_insert->close();
        } else {
            $resultado = "No puede solicitar más días de asuntos propios, ha alcanzado el límite.";
        }
    
        $stmt_ap->close();
        $this->conection->close();
        return $resultado;
    }
    

    function solicitud_asuntos_propios_norm() {
        // Recoger la fecha y hora del POST
        $fechaHora = $_POST['selected_date'];
    
        // Separar la fecha y la hora
        list($fecha, $hora) = explode(' ', $fechaHora);
    
        // Convertir la fecha al formato YYYY-MM-DD
        $fechaPartes = explode('-', $fecha);
        $fechaFormateada = sprintf('%04d-%02d-%02d', $fechaPartes[2], $fechaPartes[1], $fechaPartes[0]);
    
        // Preparar la consulta
        $stmt = $this->conection->prepare("INSERT INTO t_peticiones (PET_DNI, PET_FECHA, PET_TIPO, PET_FECHA_HORA_SOLICITUD, PET_ACEPTADO, PET_SUPERVISOR, PET_DOC) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
        // Definir valores
        $dni = $_SESSION['dni'];
        $tipo = 'AS';
        $aceptado = 'En espera';
        $supervisor = NULL;
        $doc = NULL;
    
        // Crear la fecha y hora completa en formato compatible
        $fechaHoraSolicitud = $fechaFormateada . ' ' . $hora;
    
        // Vincular parámetros
        $stmt->bind_param("sssssss", $dni, $fechaFormateada, $tipo, $fechaHoraSolicitud, $aceptado, $supervisor, $doc);
    
        // Ejecutar la consulta
        if ($stmt->execute()) {
            $resultado = "Solicitud enviada correctamente.";
        } else {
            $resultado = "Error al enviar la solicitud: " . $stmt->error;
        }
    
        // Cerrar la declaración y la conexión
        $stmt->close();
        $this->conection->close();
        return $resultado;
    }
    
    public function pertenece_sindicato()
    {
        $dni = $_SESSION['dni'];
        // Preparar la consulta con un marcador de posición para el valor del DNI
        $sql = "SELECT DNI FROM t_comite WHERE DNI = ?";
        $stmt = $this->conection->prepare($sql);
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $pertenece = false;
        
        // Verificar si se encontró un resultado
        if ($resultado->num_rows > 0) {
            $pertenece = true;
        }
        
        $stmt->close();
        return $pertenece;
    }
    

    //INSERT PETICIÓN BAJA POR ACCIDENTE
    public function submit_baja_accidente() {
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
    
        // Verificar si el archivo fue subido sin errores
        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
            // Ruta de almacenamiento del archivo
            $nombreArchivo = $_FILES['archivo']['name'];
            $rutaTemporal = $_FILES['archivo']['tmp_name'];
            $rutaDestino = 'view/documentos/' . $nombreArchivo;
    
            // Mover el archivo subido al directorio de destino
            if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
                // Preparar la consulta
                $stmt = $this->conection->prepare("INSERT INTO t_peticiones (PET_DNI, PET_FECHA, PET_TIPO, PET_FECHA_HORA_SOLICITUD, PET_ACEPTADO, PET_SUPERVISOR, PET_DOC) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
                // Definir valores
                $dni = $_SESSION['dni'];
                $tipo = 'A';
                $aceptado = 'En espera';
                $supervisor = NULL;
                $doc = $nombreArchivo;
    
                // Crear la fecha y hora completa en formato compatible
                $fechaHoraSolicitud = $fecha . ' ' . $hora;
    
                // Vincular parámetros
                $stmt->bind_param("sssssss", $dni, $fecha, $tipo, $fechaHoraSolicitud, $aceptado, $supervisor, $doc);
    
                // Ejecutar la consulta
                if ($stmt->execute()) {
                    $resultado = "Solicitud enviada correctamente.";
                } else {
                    $resultado = "Error al enviar la solicitud: " . $stmt->error;
                }
    
                // Cerrar la declaración y la conexión
                $stmt->close();
                $this->conection->close();
                return $resultado;
            } else {
                return "Error al mover el archivo subido.";
            }
        } else {
            return "Error al subir el archivo: " . $_FILES['archivo']['error'];
        }
    }
    

}