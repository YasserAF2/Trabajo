<?php

class Trace
{
    private $conection;
    private array $empleados = array();
    private array $peticiones = array();

    function __construct()
    {
        $this->getConection();

        if ($this->conection->connect_error) {
            die("Error en la conexión: " . $this->conection->connect_error);
        }
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
            $sql = "SELECT * FROM t_empleados WHERE EMP_NIF = ?";
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
            $sql = "SELECT * FROM t_empleados WHERE EMP_FEC_NAC = ?";
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
        $stmt = $this->conection->prepare("UPDATE t_empleados SET EMP_CORREO = ?, EMP_CONTRASEÑA = ?, TOKEN = ? WHERE EMP_NIF = ?");
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
        $stmt = $this->conection->prepare("SELECT * FROM t_empleados WHERE TOKEN = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Token válido, proceder con la actualización
            $stmt = $this->conection->prepare("UPDATE t_empleados SET CONFIRMADO = 1 WHERE TOKEN = ?");
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
        $stmt = $this->conection->prepare("SELECT * FROM t_empleados WHERE EMP_CORREO = ?");
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
        $query = $this->conection->prepare("SELECT * FROM t_empleados WHERE EMP_CORREO = ?");
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
        $sql = "SELECT * FROM t_peticiones WHERE PET_DNI = ?";
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
        $sql = "SELECT NUM_TOTAL_DIAS_AP FROM t_empleados WHERE EMP_CORREO = ?";
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


    public function asuntos_propios()
    {
        // Recoger la fecha y hora del POST
        $fechaHora = $_POST['selected_date'];
        $turno = $_POST['turno'];

        // Separar la fecha y la hora
        list($fecha, $hora) = explode(' ', $fechaHora);

        // Convertir la fecha al formato YYYY-MM-DD
        $fechaPartes = explode('-', $fecha);
        if (count($fechaPartes) == 3) {
            // Si el array tiene tres elementos (día, mes, año), el formato es correcto
            $fechaFormateada = sprintf('%04d-%02d-%02d', $fechaPartes[2], $fechaPartes[1], $fechaPartes[0]);
        }

        $num_dias_ap = 0;
        $num_total_dias_ap = 0;

        // Preparar la consulta para obtener los días AP del empleado
        $stmt_ap = $this->conection->prepare("SELECT NUM_DIAS_AP, NUM_TOTAL_DIAS_AP FROM t_empleados WHERE EMP_NIF = ?");
        $stmt_ap->bind_param("s", $_SESSION['dni']);
        $stmt_ap->execute();
        $stmt_ap->store_result();
        $stmt_ap->bind_result($num_dias_ap, $num_total_dias_ap);
        $stmt_ap->fetch();

        // Verificar si el número de días AP no supera el total
        if ($num_dias_ap < $num_total_dias_ap) {
            // Inicializar la variable count
            $count = 0;

            // Verificar si la fecha ya ha sido solicitada con PET_TIPO AP
            $stmt_check_date = $this->conection->prepare("SELECT COUNT(*) FROM t_peticiones WHERE PET_DNI = ? AND PET_FECHA = ? AND PET_TIPO = 'AP'");
            $stmt_check_date->bind_param("ss", $_SESSION['dni'], $fechaFormateada);
            $stmt_check_date->execute();
            $stmt_check_date->bind_result($count);
            $stmt_check_date->fetch();
            $stmt_check_date->close();

            if ($count > 0) {
                return "Ya has solicitado un día de asuntos propios para esta fecha.";
            }

            // Verificar el cupo para el turno específico
            $cupo_maximo = 0;
            switch ($turno) {
                case 'T_MAÑANA':
                    $cupo_maximo = 25;
                    break;
                case 'T_TARDE':
                    $cupo_maximo = 15;
                    break;
                case 'T_NOCHE':
                    $cupo_maximo = 10;
                    break;
                default:
                    return "Turno no válido.";
            }

            // Consultar el número actual de solicitudes para el turno específico
            $turno_columna = '';
            switch ($turno) {
                case 'T_MAÑANA':
                    $turno_columna = 'AP_MAÑANA';
                    break;
                case 'T_TARDE':
                    $turno_columna = 'AP_TARDE';
                    break;
                case 'T_NOCHE':
                    $turno_columna = 'AP_NOCHE';
                    break;
            }

            if (!empty($turno_columna)) {
                $cupo_actual = 0;
                $stmt_check_cupo = $this->conection->prepare("SELECT $turno_columna FROM t_ocupacion WHERE FECHA = ?");
                $stmt_check_cupo->bind_param("s", $fechaFormateada);
                $stmt_check_cupo->execute();
                $stmt_check_cupo->bind_result($cupo_actual);
                $stmt_check_cupo->fetch();
                $stmt_check_cupo->close();

                // Verificar si el cupo está lleno
                if ($cupo_actual >= $cupo_maximo) {
                    return "El cupo para el turno $turno en la fecha $fechaFormateada está lleno.";
                }

                // Incrementar el número de días AP
                $num_dias_ap++;

                // Actualizar el número de días AP en la base de datos
                $stmt_update_ap = $this->conection->prepare("UPDATE t_empleados SET NUM_DIAS_AP = ? WHERE EMP_NIF = ?");
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

                // Crear la fecha y hora completa en formato compatible con la fecha actual
                $fechaHoraSolicitud = date('Y-m-d H:i:s');

                // Vincular parámetros
                $stmt_insert->bind_param("sssssss", $dni, $fechaFormateada, $tipo, $fechaHoraSolicitud, $aceptado, $supervisor, $doc);

                // Ejecutar la consulta
                if ($stmt_insert->execute()) {
                    // Incrementar el cupo en t_ocupacion
                    $cupo_actual++;
                    $stmt_update_cupo = $this->conection->prepare("UPDATE t_ocupacion SET $turno_columna = ? WHERE FECHA = ?");
                    $stmt_update_cupo->bind_param("is", $cupo_actual, $fechaFormateada);
                    $stmt_update_cupo->execute();
                    $stmt_update_cupo->close();

                    $resultado = "Solicitud enviada correctamente.";
                } else {
                    $resultado = "Error al enviar la solicitud: " . $stmt_insert->error;
                }

                // Cerrar la declaración y la conexión
                $stmt_insert->close();
            } else {
                return "Turno no válido.";
            }
        } else {
            $resultado = "No puede solicitar más días de asuntos propios, ha alcanzado el límite.";
        }

        $stmt_ap->close();
        $this->conection->close();
        return $resultado;
    }

    function solicitud_asuntos_propios_norm()
    {
        // Recoger la fecha y hora del POST
        $fechaHora = $_POST['selected_date'];
        $turno = $_POST['turno'];

        // Separar la fecha y la hora
        list($fecha, $hora) = explode(' ', $fechaHora);

        // Convertir la fecha al formato YYYY-MM-DD
        $fechaPartes = explode('-', $fecha);
        $fechaFormateada = sprintf('%04d-%02d-%02d', $fechaPartes[2], $fechaPartes[1], $fechaPartes[0]);

        $num_dias_as = 0;
        $num_total_dias_as = 0;

        // Preparar la consulta para obtener los días AS del empleado
        $stmt_as = $this->conection->prepare("SELECT NUM_DIAS_AS, NUM_TOTAL_DIAS_AS FROM t_empleados WHERE EMP_NIF = ?");
        $stmt_as->bind_param("s", $_SESSION['dni']);
        $stmt_as->execute();
        $stmt_as->store_result();
        $stmt_as->bind_result($num_dias_as, $num_total_dias_as);
        $stmt_as->fetch();

        // Verificar si el número de días AS no supera el total
        if ($num_dias_as < $num_total_dias_as) {
            // Inicializar la variable count
            $count = 0;

            // Verificar si la fecha ya ha sido solicitada con PET_TIPO AS
            $stmt_check_date = $this->conection->prepare("SELECT COUNT(*) FROM t_peticiones WHERE PET_DNI = ? AND PET_FECHA = ? AND PET_TIPO = 'AS'");
            $stmt_check_date->bind_param("ss", $_SESSION['dni'], $fechaFormateada);
            $stmt_check_date->execute();
            $stmt_check_date->bind_result($count);
            $stmt_check_date->fetch();
            $stmt_check_date->close();

            if ($count > 0) {
                return "Ya has solicitado un día de asuntos propios para esta fecha.";
            }

            // Verificar el cupo para el turno específico
            $cupo_maximo = 0;
            switch ($turno) {
                case 'T_MAÑANA':
                    $cupo_maximo = 25;
                    break;
                case 'T_TARDE':
                    $cupo_maximo = 15;
                    break;
                case 'T_NOCHE':
                    $cupo_maximo = 10;
                    break;
                default:
                    return "Turno no válido.";
            }

            // Consultar el número actual de solicitudes para el turno específico
            $turno_columna = '';
            switch ($turno) {
                case 'T_MAÑANA':
                    $turno_columna = 'AP_MAÑANA';
                    break;
                case 'T_TARDE':
                    $turno_columna = 'AP_TARDE';
                    break;
                case 'T_NOCHE':
                    $turno_columna = 'AP_NOCHE';
                    break;
            }

            if (!empty($turno_columna)) {
                $cupo_actual = 0;
                $stmt_check_cupo = $this->conection->prepare("SELECT $turno_columna FROM t_ocupacion WHERE FECHA = ?");
                $stmt_check_cupo->bind_param("s", $fechaFormateada);
                $stmt_check_cupo->execute();
                $stmt_check_cupo->bind_result($cupo_actual);
                $stmt_check_cupo->fetch();
                $stmt_check_cupo->close();

                // Verificar si el cupo está lleno
                if ($cupo_actual >= $cupo_maximo) {
                    return "El cupo para el turno $turno en la fecha $fechaFormateada está lleno.";
                }

                // Incrementar el número de días AS
                $num_dias_as++;

                // Actualizar el número de días AS en la base de datos
                $stmt_update_as = $this->conection->prepare("UPDATE t_empleados SET NUM_DIAS_AS = ? WHERE EMP_NIF = ?");
                $stmt_update_as->bind_param("is", $num_dias_as, $_SESSION['dni']);
                $stmt_update_as->execute();
                $stmt_update_as->close();

                // Preparar la consulta para insertar la solicitud
                $stmt = $this->conection->prepare("INSERT INTO t_peticiones (PET_DNI, PET_FECHA, PET_TIPO, PET_FECHA_HORA_SOLICITUD, PET_ACEPTADO, PET_SUPERVISOR, PET_DOC) VALUES (?, ?, ?, ?, ?, ?, ?)");

                // Definir valores
                $dni = $_SESSION['dni'];
                $tipo = 'AS';
                $aceptado = 'En espera';
                $supervisor = NULL;
                $doc = NULL;

                // Crear la fecha y hora completa en formato compatible con la fecha actual
                $fechaHoraSolicitud = date('Y-m-d H:i:s');

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
            } else {
                $resultado = "No puede solicitar más días de asuntos propios, ha alcanzado el límite.";
            }

            $stmt_as->close();
            $this->conection->close();
            return $resultado;
        }
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
    public function submit_baja_accidente()
    {
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

    public function submit_baja_enfermedad()
    {
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
                $tipo = 'E';
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

    public function submit_lmp()
    {
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
                $tipo = 'CMP';
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

    public function submit_licencia()
    {
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
                $tipo = 'L';
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

    public function submit_hora_sindical()
    {
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
                $tipo = 'HS';
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

    public function tipo_empleado()
    {
        $dni = $_SESSION['dni'];
        $sql = "SELECT EMP_TIPO FROM t_empleados WHERE EMP_NIF = ?";
        $stmt = $this->conection->prepare($sql);
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $tipo = null; // Inicializar la variable $tipo como nula

        // Verificar si se encontró un resultado
        if ($resultado->num_rows > 0) {
            // Obtener el valor del campo EMP_TIPO
            $row = $resultado->fetch_assoc();
            $tipo = $row['EMP_TIPO'];
        }

        $stmt->close();
        return $tipo;
    }

    public function turno_empleado()
    {
        $dni = $_SESSION['dni'];
        $sql = "SELECT TURNO FROM t_empleados WHERE EMP_NIF = ?";
        $stmt = $this->conection->prepare($sql);
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $turno = null; // Inicializar la variable $tipo como nula

        // Verificar si se encontró un resultado
        if ($resultado->num_rows > 0) {
            // Obtener el valor del campo EMP_TIPO
            $row = $resultado->fetch_assoc();
            $turno = $row['TURNO'];
        }

        $stmt->close();
        return $turno;
    }

    public function ver_solicitudes_ap()
    {
        $tipo = "AP";  // Tipo de petición que queremos filtrar
        $sql = "SELECT p.*, e.* 
            FROM t_peticiones p
            JOIN t_empleados e ON e.EMP_NIF = p.PET_DNI
            WHERE p.PET_TIPO = ? ORDER BY p.PET_ID DESC";
        $stmt = $this->conection->prepare($sql);
        $stmt->bind_param("s", $tipo);
        $stmt->execute();

        // Obtener el resultado y cerrar la sentencia
        $resultado = $stmt->get_result();
        $peticiones = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $peticiones;
    }

    public function ver_solicitudes_as()
    {
        $tipo = "AS";
        $sql = "SELECT p.*, e.* 
                FROM t_peticiones p
                JOIN t_empleados e ON e.EMP_NIF = p.PET_DNI
                WHERE p.PET_TIPO = ? ORDER BY p.PET_ID DESC";
        $stmt = $this->conection->prepare($sql);
        $stmt->bind_param("s", $tipo);
        $stmt->execute();

        // Obtener el resultado y cerrar la sentencia
        $resultado = $stmt->get_result();
        $peticiones = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $peticiones;
    }

    public function ver_bajas_licencias()
    {
        // Tipos de peticiones que queremos excluir
        $tipo_excluir_1 = "AP";
        $tipo_excluir_2 = "AS";
        $sql = "SELECT p.*, e.* 
                FROM t_peticiones p
                JOIN t_empleados e ON e.EMP_NIF = p.PET_DNI
                WHERE p.PET_TIPO NOT IN (?, ?)";
        $stmt = $this->conection->prepare($sql);
        $stmt->bind_param("ss", $tipo_excluir_1, $tipo_excluir_2);
        $stmt->execute();

        // Obtener el resultado y cerrar la sentencia
        $resultado = $stmt->get_result();
        $peticiones = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $peticiones;
    }


    //obtener los datos del empleado por el dni
    public function datos_empleado($dni)
    {
        $sql = "SELECT * FROM t_empleados WHERE EMP_NIF = ?";
        $stmt = $this->conection->prepare($sql);
        $stmt->bind_param("s", $dni);
        $stmt->execute();

        // Obtener el resultado y cerrar la sentencia
        $resultado = $stmt->get_result();
        $empleado = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $empleado;
    }


    //parte admin aceptar y rechazar solicitudes
    function aceptar_ap()
    {
        session_start(); // Iniciar sesión

        $peticion_id = $_GET['peticion_id'];

        // Obtener la petición y el turno del empleado
        $query = $this->conection->prepare("SELECT PET_FECHA, TURNO FROM t_peticiones JOIN t_empleados ON t_peticiones.PET_DNI = t_empleados.EMP_NIF WHERE PET_ID = ?");
        if (!$query) {
            die('Error en la preparación de la consulta SQL: ' . mysqli_error($this->conection));
        }
        $query->bind_param("i", $peticion_id);
        $query->execute();
        $peticion_result = $query->get_result();
        if (!$peticion_result) {
            die('Error al obtener el resultado de la consulta: ' . mysqli_error($this->conection));
        }
        $peticion = $peticion_result->fetch_assoc();
        $query->close();

        $fecha = $peticion['PET_FECHA'];
        $turno = $peticion['TURNO'];

        // Verificar la ocupación actual
        $query = $this->conection->prepare("SELECT * FROM t_ocupacion WHERE FECHA = ?");
        if (!$query) {
            die('Error en la preparación de la consulta SQL: ' . mysqli_error($this->conection));
        }
        $query->bind_param("s", $fecha);
        $query->execute();
        $ocupacion_result = $query->get_result();
        if (!$ocupacion_result) {
            die('Error al obtener el resultado de la consulta: ' . mysqli_error($this->conection));
        }
        $ocupacion = $ocupacion_result->fetch_assoc();
        $query->close();

        // Límites de ocupación para AP
        $limites_ap = [
            'AP_MAÑANA' => 25,
            'AP_TARDE' => 15,
            'AP_NOCHE' => 10
        ];

        // Verificar si se puede aceptar la petición de AP
        if (strpos($turno, 'T_') !== false) {
            $turno_ocupacion = str_replace('T_', 'AP_', $turno);
            if ($ocupacion[$turno_ocupacion] >= $limites_ap[$turno_ocupacion]) {
                throw new Exception("No se puede aceptar la petición. El turno de $turno ya está completo.");
            }
        }

        // Incrementar la ocupación para AP
        $ocupacion[$turno_ocupacion] += 1;

        // Actualizar la tabla t_ocupacion
        $query = $this->conection->prepare("UPDATE t_ocupacion SET AP_MAÑANA = ?, AP_TARDE = ?, AP_NOCHE = ? WHERE FECHA = ?");
        if (!$query) {
            die('Error en la preparación de la consulta SQL: ' . mysqli_error($this->conection));
        }
        $query->bind_param("iiis", $ocupacion['AP_MAÑANA'], $ocupacion['AP_TARDE'], $ocupacion['AP_NOCHE'], $fecha);
        $query->execute();
        if ($query->errno) {
            die('Error al ejecutar la consulta de actualización: ' . $query->error);
        }
        $query->close();

        // Obtener el nombre y apellidos del supervisor a partir del correo
        $supervisor_correo = $_SESSION['correo'];
        $supervisor = $this->getEmpleadoCorreo($supervisor_correo);
        $supervisor_nombre = $supervisor['EMP_NOMBRE'] . ' ' . $supervisor['EMP_APE_1'] . ' ' . $supervisor['EMP_APE_2'];

        // Actualizar el estado de la petición a aceptada y asignar supervisor
        $query = $this->conection->prepare("UPDATE t_peticiones SET PET_ACEPTADO = 'SI', PET_SUPERVISOR = ? WHERE PET_ID = ?");
        if (!$query) {
            die('Error en la preparación de la consulta SQL: ' . mysqli_error($this->conection));
        }
        $query->bind_param("si", $supervisor_nombre, $peticion_id);
        $query->execute();
        if ($query->errno) {
            die('Error al ejecutar la consulta de actualización: ' . $query->error);
        }
        $query->close();

        // Agregar variables de sesión
        $_SESSION['mensaje'] = "Petición aceptada exitosamente.";
        $_SESSION['peticion_id_aceptada'] = $peticion_id;
    }

    public function rechazar_ap()
    {
        session_start();
        $peticion_id = $_GET['peticion_id'];

        // Obtener el nombre y apellidos del supervisor a partir del correo
        $supervisor_correo = $_SESSION['correo'];
        $supervisor = $this->getEmpleadoCorreo($supervisor_correo);
        $supervisor_nombre = $supervisor['EMP_NOMBRE'] . ' ' . $supervisor['EMP_APE_1'] . ' ' . $supervisor['EMP_APE_2'];

        // Actualizar la petición para marcarla como rechazada y asignar supervisor
        $query = $this->conection->prepare("UPDATE t_peticiones SET PET_ACEPTADO = 'NO', PET_SUPERVISOR = ? WHERE PET_ID = ?");
        $query->bind_param("si", $supervisor_nombre, $peticion_id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function aceptar_as()
    {
        session_start();
        $peticion_id = $_GET['peticion_id'];

        // Obtener la petición y el turno del empleado
        $query = $this->conection->prepare("SELECT PET_FECHA, TURNO FROM t_peticiones JOIN t_empleados ON t_peticiones.PET_DNI = t_empleados.EMP_NIF WHERE PET_ID = ?");
        if (!$query) {
            die('Error en la preparación de la consulta SQL: ' . mysqli_error($this->conection));
        }
        $query->bind_param("i", $peticion_id);
        $query->execute();
        $peticion_result = $query->get_result();
        if (!$peticion_result) {
            die('Error al obtener el resultado de la consulta: ' . mysqli_error($this->conection));
        }
        $peticion = $peticion_result->fetch_assoc();
        $query->close();

        $fecha = $peticion['PET_FECHA'];
        $turno = $peticion['TURNO'];

        // Verificar la ocupación actual
        $query = $this->conection->prepare("SELECT * FROM t_ocupacion WHERE FECHA = ?");
        if (!$query) {
            die('Error en la preparación de la consulta SQL: ' . mysqli_error($this->conection));
        }
        $query->bind_param("s", $fecha);
        $query->execute();
        $ocupacion_result = $query->get_result();
        if (!$ocupacion_result) {
            die('Error al obtener el resultado de la consulta: ' . mysqli_error($this->conection));
        }
        $ocupacion = $ocupacion_result->fetch_assoc();
        $query->close();

        // Límites de ocupación para AS
        $limites_as = [
            'AS_MAÑANA' => 25,
            'AS_TARDE' => 15,
            'AS_NOCHE' => 10
        ];

        // Verificar si se puede aceptar la petición de AS
        if (strpos($turno, 'T_') !== false) {
            $turno_ocupacion = str_replace('T_', 'AS_', $turno);
            if ($ocupacion[$turno_ocupacion] >= $limites_as[$turno_ocupacion]) {
                throw new Exception("No se puede aceptar la petición. El turno de $turno ya está completo.");
            }
        }

        // Incrementar la ocupación para AS
        $ocupacion[$turno_ocupacion] += 1;

        // Actualizar la tabla t_ocupacion
        $query = $this->conection->prepare("UPDATE t_ocupacion SET AS_MAÑANA = ?, AS_TARDE = ?, AS_NOCHE = ? WHERE FECHA = ?");
        if (!$query) {
            die('Error en la preparación de la consulta SQL: ' . mysqli_error($this->conection));
        }
        $query->bind_param("iiis", $ocupacion['AS_MAÑANA'], $ocupacion['AS_TARDE'], $ocupacion['AS_NOCHE'], $fecha);
        $query->execute();
        if ($query->errno) {
            die('Error al ejecutar la consulta de actualización: ' . $query->error);
        }
        $query->close();

        // Obtener el nombre y apellidos del supervisor a partir del correo
        $supervisor_correo = $_SESSION['correo'];
        $supervisor = $this->getEmpleadoCorreo($supervisor_correo);
        $supervisor_nombre = $supervisor['EMP_NOMBRE'] . ' ' . $supervisor['EMP_APE_1'] . ' ' . $supervisor['EMP_APE_2'];

        // Actualizar el estado de la petición a aceptada y asignar supervisor
        $query = $this->conection->prepare("UPDATE t_peticiones SET PET_ACEPTADO = 'SI', PET_SUPERVISOR = ? WHERE PET_ID = ?");
        if (!$query) {
            die('Error en la preparación de la consulta SQL: ' . mysqli_error($this->conection));
        }
        $query->bind_param("si", $supervisor_nombre, $peticion_id);
        $query->execute();
        if ($query->errno) {
            die('Error al ejecutar la consulta de actualización: ' . $query->error);
        }
        $query->close();

        // Agregar variables de sesión
        $_SESSION['mensaje'] = "Petición aceptada exitosamente.";
        $_SESSION['peticion_id_aceptada'] = $peticion_id;
    }

    public function rechazar_as()
    {
        session_start();
        $peticion_id = $_GET['peticion_id'];

        // Obtener el nombre y apellidos del supervisor a partir del correo
        $supervisor_correo = $_SESSION['correo'];
        $supervisor = $this->getEmpleadoCorreo($supervisor_correo);
        $supervisor_nombre = $supervisor['EMP_NOMBRE'] . ' ' . $supervisor['EMP_APE_1'] . ' ' . $supervisor['EMP_APE_2'];

        // Actualizar la petición para marcarla como rechazada y asignar supervisor
        $query = $this->conection->prepare("UPDATE t_peticiones SET PET_ACEPTADO = 'NO', PET_SUPERVISOR = ? WHERE PET_ID = ?");
        $query->bind_param("si", $supervisor_nombre, $peticion_id);

        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function aceptar_baja()
    {
        session_start();
        $peticion_id = $_GET['peticion_id'];
        $supervisor_correo = $_SESSION['correo'];
        $supervisor = $this->getEmpleadoCorreo($supervisor_correo);
        $supervisor_nombre = $supervisor['EMP_NOMBRE'] . ' ' . $supervisor['EMP_APE_1'] . ' ' . $supervisor['EMP_APE_2'];
        $query = "UPDATE t_peticiones SET PET_ACEPTADO = 'SI', PET_SUPERVISOR = ? WHERE PET_ID = ?";
        $stmt = $this->conection->prepare($query);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt === false) {
            // Manejar el error de preparación de consulta
            return false;
        }

        // Bind de parámetros
        $stmt->bind_param("si", $supervisor_nombre, $peticion_id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true; // Actualización exitosa
        } else {
            return false; // Error al ejecutar la consulta
        }
    }


    public function rechazar_baja()
    {
        session_start();
        $peticion_id = $_GET['peticion_id'];
        $supervisor_correo = $_SESSION['correo'];
        $supervisor = $this->getEmpleadoCorreo($supervisor_correo);
        $supervisor_nombre = $supervisor['EMP_NOMBRE'] . ' ' . $supervisor['EMP_APE_1'] . ' ' . $supervisor['EMP_APE_2'];
        $query = "UPDATE t_peticiones SET PET_ACEPTADO = 'NO', PET_SUPERVISOR = ? WHERE PET_ID = ?";
        $stmt = $this->conection->prepare($query);

        // Verificar si la preparación de la consulta fue exitosa
        if ($stmt === false) {
            // Manejar el error de preparación de consulta
            return false;
        }

        $stmt->bind_param("si", $supervisor_nombre, $peticion_id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true; // Actualización exitosa
        } else {
            return false; // Error al ejecutar la consulta
        }
    }



    //peticiones aceptadas
    public function obtenerPeticionesAceptadas()
    {
        $query = $this->conection->prepare("
            SELECT t_peticiones.*, t_empleados.TURNO, t_empleados.EMP_NOMBRE, t_empleados.EMP_APE_1, t_empleados.EMP_APE_2
            FROM t_peticiones 
            JOIN t_empleados ON t_peticiones.PET_DNI = t_empleados.EMP_NIF 
            WHERE t_peticiones.PET_ACEPTADO = 'SI'
        ");
        $query->execute();
        $result = $query->get_result();
        $peticiones = [];

        while ($row = $result->fetch_assoc()) {
            $peticiones[] = $row;
        }

        $query->close();
        return $peticiones;
    }


    public function ver_festivos()
    {
        try {
            // Verificar la conexión antes de preparar la consulta
            if ($this->conection->connect_error) {
                throw new Exception("Error de conexión a la base de datos: " . $this->conection->connect_error);
            }

            // Consulta SQL para obtener los festivos
            $sql = "SELECT FEST_FECHA, FEST_DESCRIPCION, FEST_JORNADA FROM t_festivos";

            // Preparar la consulta
            $stmt = $this->conection->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $this->conection->error);
            }

            // Ejecutar la consulta
            $executed = $stmt->execute();
            if (!$executed) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            // Inicializar las variables antes de vincularlas
            $FEST_FECHA = '';
            $FEST_DESCRIPCION = '';
            $FEST_JORNADA = '';

            // Vinculamos las variables de resultado
            $stmt->bind_result($FEST_FECHA, $FEST_DESCRIPCION, $FEST_JORNADA);

            $result = [];
            while ($stmt->fetch()) {
                $result[] = [
                    'FEST_FECHA' => $FEST_FECHA,
                    'FEST_DESCRIPCION' => $FEST_DESCRIPCION,
                    'FEST_JORNADA' => $FEST_JORNADA
                ];
            }

            $stmt->close();
            return $result;
        } catch (Exception $e) {
            // Manejar cualquier excepción que se haya lanzado
            error_log("Error en ver_festivos(): " . $e->getMessage());
            // Puedes devolver un valor por defecto o lanzar la excepción nuevamente según tus necesidades
            return []; // Devuelve un array vacío en caso de error
        }
    }


    public function lista_empleados()
    {
        // Preparar la consulta SQL para obtener todos los datos de los empleados
        $sql = "SELECT EMP_NIF, EMP_APE_1, EMP_APE_2, EMP_NOMBRE, EMP_TIPO FROM t_empleados";
        $stmt = $this->conection->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $empleados = [];

        while ($row = $resultado->fetch_assoc()) {
            $empleados[] = $row;
        }

        $stmt->close();

        return $empleados;
    }

    public function cambiar_tipo()
    {
        $response = array(); // Array para almacenar la respuesta

        if (isset($_POST['dni'], $_POST['nuevo_tipo'])) {
            $dni = $_POST['dni'];
            $nuevo_tipo = $_POST['nuevo_tipo'];

            // Escapamos las variables para evitar SQL Injection
            $dni_escapado = $this->conection->real_escape_string($dni);
            $nuevo_tipo_escapado = $this->conection->real_escape_string($nuevo_tipo);

            $sql = "UPDATE t_empleados SET EMP_TIPO = '$nuevo_tipo_escapado' WHERE EMP_NIF = '$dni_escapado'";
            if ($this->conection->query($sql) === TRUE) {
                $response['success'] = true;
                $response['message'] = "Tipo de empleado actualizado correctamente.";
            } else {
                $response['success'] = false;
                $response['message'] = "Error al actualizar el tipo de empleado: " . $this->conection->error;
            }
        } else {
            $response['success'] = false;
            $response['message'] = "Error: Datos incompletos al intentar actualizar el tipo de empleado.";
        }

        ob_clean(); // Limpiar el buffer de salida
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }


    //BUSCADOR
    public function buscar_empleado_rol($buscador)
    {
        $stmt = $this->conection->prepare("SELECT * FROM t_empleados WHERE EMP_NIF LIKE ? OR EMP_NOMBRE LIKE ? OR EMP_APE_1 LIKE ? OR EMP_APE_2 LIKE ?");
        $likeBuscador = "%$buscador%";
        $stmt->bind_param('ssss', $likeBuscador, $likeBuscador, $likeBuscador, $likeBuscador);
        $stmt->execute();
        $result = $stmt->get_result();
        $resultados = [];
        while ($fila = $result->fetch_assoc()) {
            $resultados[] = $fila;
        }
        $stmt->close();
        return $resultados;
    }

    public function ver_cupo_peticion($fecha)
    {
        $stmt = $this->conection->prepare("SELECT * FROM t_ocupacion WHERE FECHA = ?");
        $stmt->bind_param('s', $fecha);
        $stmt->execute();
        $result = $stmt->get_result();
        $cupos = $result->fetch_assoc();
        $stmt->close();
        return $cupos;
    }


    //buscador del calendario
    public function buscar_peticion()
    {
        $buscador = isset($_GET['buscador']) ? $_GET['buscador'] : '';
        $trace = new Trace();
        $peticiones = $trace->obtenerPeticionesAceptadas();

        $response = [
            'success' => true,
            'peticiones' => $peticiones
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    public function obtenerBusqueda($buscador = '')
    {
        $query = "SELECT 
                    pa.PET_FECHA, 
                    pa.PET_DNI, 
                    emp.EMP_NOMBRE, 
                    emp.EMP_APE_1, 
                    pa.PET_TIPO, 
                    pa.PET_FECHA_HORA_SOLICITUD, 
                    pa.PET_SUPERVISOR 
                  FROM t_peticiones pa
                  INNER JOIN t_empleados emp ON pa.PET_DNI = emp.EMP_NIF
                  WHERE pa.PET_ACEPTADO = 'SI'"; // Añade esta condición para peticiones aceptadas

        if ($buscador) {
            $query .= " AND (
                        pa.PET_DNI LIKE ? OR 
                        emp.EMP_NOMBRE LIKE ? OR 
                        emp.EMP_APE_1 LIKE ? OR 
                        emp.EMP_APE_2 LIKE ?
                       )";
        }

        $stmt = $this->conection->prepare($query);

        if ($buscador) {
            $likeBuscador = "%$buscador%";
            $stmt->bind_param('ssss', $likeBuscador, $likeBuscador, $likeBuscador, $likeBuscador);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $resultados = [];

        while ($fila = $result->fetch_assoc()) {
            $resultados[] = $fila;
        }

        $stmt->close();
        return $resultados;
    }
}
