<?php

class Trace
{
    private $conection;
    private array $empleados = array();
    private array $solicitudes = array();
    private array $licencias = array();
    private array $asuntos = array();


    function __construct()
    {
        $this->getConection();
    }

    public function getConection()
    {
        $dbObj = new Db();
        $this->conection = $dbObj->conection;
    }

    public function validarUsuario($usuario, $password)
    {
        // Consulta para verificar las credenciales de inicio de sesión
        $sql = "SELECT * FROM empleado WHERE EMAIL = '$usuario' AND CONTRASEÑA = '$password'";
        $result = $this->conection->query($sql);

        // Verificar si se obtuvo un resultado
        if (mysqli_num_rows($result) == 1) {
            return true;
        } else {
            return false;
        }
    }

    //Obtener empleado por su correo
    public function getEmpleadoCorreo($correo)
    {
        $sql = "SELECT * FROM empleado WHERE EMAIL = '$correo'";
        $result = $this->conection->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $empleado = new Empleado(
                $row['DNI'],
                $row['DOCUMENTO_DNI'],
                $row['N_SEG_SOC'],
                $row['FECH_NACIM'],
                $row['EMAIL'],
                $row['CONTRASEÑA'],
                $row['SEXO'],
                $row['APELLIDO_1'],
                $row['APELLIDO_2'],
                $row['NOMBRE'],
                $row['FECH_ANTIGU'],
                $row['FECH_ALTA_EMPR'],
                $row['TIPO_CONTRATO'],
                $row['DIRECCION'],
                $row['CIUDAD'],
                $row['PROVINCIA'],
                $row['CP'],
                $row['TELF_CASA'],
                $row['TLF_MOVIL'],
                $row['FAMILIA_NUM'],
                $row['DOCUMENTO_FAM_NUM'],
                $row['ULTMO_RECONOC_MED'],
                $row['OBSERVACIONES'],
                $row['NIVEL_FORMATIVO'],
                $row['PAIS'],
                $row['COD_ZKT']
            );
            return $empleado;
        }
    }

    //Obtener el empleado por su DNI
    public function getEmpleadoDni($dni)
    {
        $sql = "SELECT * FROM empleado WHERE DNI = '$dni'";
        $result = $this->conection->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $empleado = new Empleado(
                $row['DNI'],
                $row['DOCUMENTO_DNI'],
                $row['N_SEG_SOC'],
                $row['FECH_NACIM'],
                $row['EMAIL'],
                $row['CONTRASEÑA'],
                $row['SEXO'],
                $row['APELLIDO_1'],
                $row['APELLIDO_2'],
                $row['NOMBRE'],
                $row['FECH_ANTIGU'],
                $row['FECH_ALTA_EMPR'],
                $row['TIPO_CONTRATO'],
                $row['DIRECCION'],
                $row['CIUDAD'],
                $row['PROVINCIA'],
                $row['CP'],
                $row['TELF_CASA'],
                $row['TLF_MOVIL'],
                $row['FAMILIA_NUM'],
                $row['DOCUMENTO_FAM_NUM'],
                $row['ULTMO_RECONOC_MED'],
                $row['OBSERVACIONES'],
                $row['NIVEL_FORMATIVO'],
                $row['PAIS'],
                $row['COD_ZKT']
            );
            return $empleado;
        }
    }

    //Actualizar datos del empleado en la base de datos
    public function guardar_empleado($DNI, $NOMBRE, $APELLIDO_1, $APELLIDO_2, $EMAIL, $DIRECCION, $CIUDAD, $PROVINCIA, $CP, $TELF_CASA, $TLF_MOVIL, $PAIS)
    {
        $sql = "UPDATE empleado SET 
        NOMBRE = '$NOMBRE', 
        APELLIDO_1 = '$APELLIDO_1', 
        APELLIDO_2 = '$APELLIDO_2', 
        EMAIL = '$EMAIL', 
        DIRECCION = '$DIRECCION', 
        CIUDAD = '$CIUDAD', 
        PROVINCIA = '$PROVINCIA', 
        CP = '$CP', 
        TELF_CASA = '$TELF_CASA', 
        TLF_MOVIL = '$TLF_MOVIL', 
        PAIS = '$PAIS' 
        WHERE DNI = '$DNI';
        ";

        $stmt = $this->conection->prepare($sql);
        $stmt->execute();
    }

    //Obtener el tipo de empleado (Admin/Empleado)
    public function empleadoTipo($correo)
    {
        $this->getConection();
        $sql = "SELECT TIPO_EMPLEADO FROM empleado WHERE EMAIL = '$correo'";
        $resultado = $this->conection->query($sql);

        if ($resultado->num_rows == 1) {
            $tipoEmpleado = $resultado->fetch_assoc()['TIPO_EMPLEADO'];
            return $tipoEmpleado;
        } else {
            return false;
        }
    }

    //Obtener el dni del empleado
    public function empleadoDni($correo)
    {
        $this->getConection();
        $sql = "SELECT DNI FROM empleado WHERE EMAIL = '$correo'";
        $resultado = $this->conection->query($sql);

        if ($resultado->num_rows == 1) {
            $dni = $resultado->fetch_assoc()['DNI'];
            return $dni;
        } else {
            return false;
        }
    }

    //Obtener empleados
    public function getEmpleados()
    {
        $sql = "SELECT * FROM empleado";
        $result = $this->conection->query($sql);

        if ($result->num_rows > 0) {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $this->empleados[$i] = new Empleado(
                    $row['DNI'],
                    $row['DOCUMENTO_DNI'],
                    $row['N_SEG_SOC'],
                    $row['FECH_NACIM'],
                    $row['EMAIL'],
                    $row['CONTRASEÑA'],
                    $row['SEXO'],
                    $row['APELLIDO_1'],
                    $row['APELLIDO_2'],
                    $row['NOMBRE'],
                    $row['FECH_ANTIGU'],
                    $row['FECH_ALTA_EMPR'],
                    $row['TIPO_CONTRATO'],
                    $row['DIRECCION'],
                    $row['CIUDAD'],
                    $row['PROVINCIA'],
                    $row['CP'],
                    $row['TELF_CASA'],
                    $row['TLF_MOVIL'],
                    $row['FAMILIA_NUM'],
                    $row['DOCUMENTO_FAM_NUM'],
                    $row['ULTMO_RECONOC_MED'],
                    $row['OBSERVACIONES'],
                    $row['NIVEL_FORMATIVO'],
                    $row['PAIS'],
                    $row['COD_ZKT']
                );
                $i++;
            }
        }
        return $this->empleados;
    }

    //Obtener todas las peticiones de las licencias
    public function getPeticionesLicencias()
    {
        $sql = "SELECT id_solicitud, tipo, documento, estado, DATE_FORMAT(fecha_solicitud, '%d-%m-%Y') AS fecha_solicitud, dni_empleado FROM solicitud_licencias";
        $result = $this->conection->query($sql);

        if ($result->num_rows > 0) {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $this->licencias[$i] = new Licencia($row['id_solicitud'], $row['tipo'], $row['documento'], $row['estado'], $row['fecha_solicitud'], $row['dni_empleado']);
                $i++;
            }
        }
        return $this->licencias;
    }

    //Obtener todas las peticiones de los asuntos
    public function getPeticionesAsuntos()
    {
        $sql = "SELECT id_solicitud_asuntos, DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha_formateada, estado, dni_empleado FROM solicitud_asuntos";
        $result = $this->conection->query($sql);

        if ($result->num_rows > 0) {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $this->asuntos[$i] = new Asuntos($row['id_solicitud_asuntos'], $row['fecha_formateada'], $row['estado'], $row['dni_empleado']);
                $i++;
            }
        }
        return $this->asuntos;
    }



    //Guardar solicitud de licencias en la base de datos (INSERT)
    public function guardarSolicitud($tipo, $rutaArchivo, $dni)
    {
        $tipo = $this->conection->real_escape_string($tipo);
        $rutaArchivo = $this->conection->real_escape_string($rutaArchivo);
        $estado = 'En proceso';
        $fechaSolicitud = date('Y-m-d'); // Obtener la fecha actual en formato YYYY-MM-DD

        $query = "INSERT INTO solicitud_licencias (tipo, documento, estado, fecha_solicitud, dni_empleado) VALUES ('$tipo', '$rutaArchivo', '$estado', '$fechaSolicitud', '$dni')";

        if ($this->conection->query($query) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    //Guardar solicitudes de dias de asuntos propios
    public function guardarAsuntos($fecha, $dni)
    {
        $estado = 'En proceso';
        $query = "INSERT INTO solicitud_asuntos (fecha, estado, dni_empleado) VALUES ('$fecha', '$estado', '$dni')";

        if ($this->conection->query($query) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function getSolicitudesDni($dni)
    {
        $sql = "SELECT id_solicitud, tipo, documento, estado, DATE_FORMAT(fecha_solicitud, '%d-%m-%Y') AS fecha_formateada, dni_empleado FROM solicitud_licencias WHERE dni_empleado = '$dni'";
        $result = $this->conection->query($sql);

        if ($result->num_rows > 0) {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $this->solicitudes[$i] = new Licencia($row['id_solicitud'], $row['tipo'], $row['documento'], $row['estado'], $row['fecha_formateada'], $row['dni_empleado']);
                $i++;
            }
        }
        return $this->solicitudes;
    }
    public function getAsuntosDni($dni)
    {
        $sql = "SELECT id_solicitud_asuntos, DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha_formateada, estado, dni_empleado FROM solicitud_asuntos WHERE dni_empleado = '$dni'";
        $result = $this->conection->query($sql);

        if ($result->num_rows > 0) {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $this->solicitudes[$i] = new Asuntos($row['id_solicitud_asuntos'], $row['fecha_formateada'], $row['estado'], $row['dni_empleado']);
                $i++;
            }
        }
        return $this->solicitudes;
    }

    public function cambiarEstadoAsunto($id, $estado)
    {
        $estado = $this->conection->real_escape_string($estado);
        $query = "UPDATE solicitud_asuntos SET estado = '$estado' WHERE id_solicitud_asuntos = '$id'";

        if ($this->conection->query($query) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function cambiarEstadoLicencia($id, $estado)
    {
        $estado = $this->conection->real_escape_string($estado);
        $query = "UPDATE solicitud_licencias SET estado = '$estado' WHERE id_solicitud = '$id'";

        if ($this->conection->query($query) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
}
