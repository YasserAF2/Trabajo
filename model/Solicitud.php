<?php

class Solicitud
{
    private $id_solicitud;
    private $tipo;
    private $documento;
    private $estado;
    private $dni_empleado;

    public function __construct($id_solicitud, $tipo, $documento, $estado, $dni_empleado)
    {
        $this->id_solicitud = $id_solicitud;
        $this->tipo = $tipo;
        $this->documento = $documento;
        $this->estado = $estado;
        $this->dni_empleado = $dni_empleado;
    }

    public function getIdSolicitud()
    {
        return $this->id_solicitud;
    }
    public function getTipoSolicitud()
    {
        return $this->tipo;
    }
    public function getDocumentoSolicitud()
    {
        return $this->documento;
    }
    public function getEstadoSolicitud()
    {
        return $this->estado;
    }
    public function getDniEmpleado()
    {
        return $this->dni_empleado;
    }
}