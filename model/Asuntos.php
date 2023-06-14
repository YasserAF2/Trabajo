<?php

class Asuntos
{
    private $id_solicitud_asuntos;
    private $fecha;
    private $estado;
    private $dni_empleado;

    public function __construct($id_solicitud_asuntos, $fecha, $estado, $dni_empleado)
    {
        $this->id_solicitud_asuntos = $id_solicitud_asuntos;
        $this->fecha = $fecha;
        $this->estado = $estado;
        $this->dni_empleado = $dni_empleado;
    }

    public function getIdSolicitudAsuntos()
    {
        return $this->id_solicitud_asuntos;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getDniEmpleado()
    {
        return $this->dni_empleado;
    }
}
