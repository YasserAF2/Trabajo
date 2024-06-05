<?php
class Peticiones
{
    private $PET_ID;
    private $PET_DNI;
    private $PET_FECHA;
    private $PET_TIPO;
    private $PET_FECHA_HORA_SOLICITUD;
    private $PET_ACEPTADO;
    private $PET_SUPERVISOR;

    public function getPetId()
    {
        return $this->PET_ID;
    }

    public function setPetId($petId)
    {
        $this->PET_ID = $petId;
    }

    public function getPetDni()
    {
        return $this->PET_DNI;
    }

    public function setPetDni($petDni)
    {
        $this->PET_DNI = $petDni;
    }

    public function getPetFecha()
    {
        return $this->PET_FECHA;
    }

    public function setPetFecha($petFecha)
    {
        $this->PET_FECHA = $petFecha;
    }

    public function getPetTipo()
    {
        return $this->PET_TIPO;
    }

    public function setPetTipo($petTipo)
    {
        $this->PET_TIPO = $petTipo;
    }

    public function getPetFechaHoraSolicitud()
    {
        return $this->PET_FECHA_HORA_SOLICITUD;
    }

    public function setPetFechaHoraSolicitud($petFechaHoraSolicitud)
    {
        $this->PET_FECHA_HORA_SOLICITUD = $petFechaHoraSolicitud;
    }

    public function getPetAceptado()
    {
        return $this->PET_ACEPTADO;
    }

    public function setPetAceptado($petAceptado)
    {
        $this->PET_ACEPTADO = $petAceptado;
    }

    public function getPetSupervisor()
    {
        return $this->PET_SUPERVISOR;
    }

    public function setPetSupervisor($petSupervisor)
    {
        $this->PET_SUPERVISOR = $petSupervisor;
    }
}