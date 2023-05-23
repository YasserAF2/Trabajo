<?php

class Solicitud
{
    private $id_solicitud;
    private $tipo;
    private $documento;
    private $dni_empleado;

    public function __construct($id_solicitud, $tipo, $documento, $dni_empleado)
    {
        $this->id_solicitud = $id_solicitud;
        $this->tipo = $tipo;
        $this->documento = $documento;
        $this->dni_empleado = $dni_empleado;
    }

    public function getIdSolicitud()
    {
        return $this->id_solicitud;
    }
    public function getTipo()
    {
        return $this->tipo;
    }
    public function getDocumento()
    {
        return $this->documento;
    }
    public function getDniEmpleado()
    {
        return $this->dni_empleado;
    }
}
