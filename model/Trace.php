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
}
