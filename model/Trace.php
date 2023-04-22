<?php

class Trace
{
    private $conection;

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
    public function guardar_empleado($DNI, $NOMBRE, $APELLIDO_1, $APELLIDO_2, $EMAIL, $DIRECCION, $CIUDAD, $PROVINCIA, $CP, $TELF_CASA, $TLF_MOVIL, $PAIS)
    {
        $this->getConection();
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

        if ($this->conection->query($sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $this->conection->error;
        }
    }
}
