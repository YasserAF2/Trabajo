<?php

class Empleado
{
    // Atributos privados
    private $EMP_NIF;
    private $EMP_APE_1;
    private $EMP_APE_2;
    private $EMP_NOMBRE;
    private $EMP_FEC_ANTIGUEDAD;
    private $EMP_TIPO_CONT;
    private $EMP_CATEGORIA;
    private $EMP_FEC_NAC;
    private $TURNO;
    private $NUM_DIAS_AP;
    private $NUM_DIAS_AS;

    // Constructor
    public function __construct($nif, $ape1, $ape2, $nombre, $fecAntiguedad, $tipoCont, $categoria, $fecNac, $turno, $numDiasAp, $numDiasAs)
    {
        $this->EMP_NIF = $nif;
        $this->EMP_APE_1 = $ape1;
        $this->EMP_APE_2 = $ape2;
        $this->EMP_NOMBRE = $nombre;
        $this->EMP_FEC_ANTIGUEDAD = $fecAntiguedad;
        $this->EMP_TIPO_CONT = $tipoCont;
        $this->EMP_CATEGORIA = $categoria;
        $this->EMP_FEC_NAC = $fecNac;
        $this->TURNO = $turno;
        $this->NUM_DIAS_AP = $numDiasAp;
        $this->NUM_DIAS_AS = $numDiasAs;
    }

    // Métodos getter
    public function getNIF()
    {
        return $this->EMP_NIF;
    }

    public function getApe1()
    {
        return $this->EMP_APE_1;
    }

    public function getApe2()
    {
        return $this->EMP_APE_2;
    }

    public function getNombre()
    {
        return $this->EMP_NOMBRE;
    }

    public function getFecAntiguedad()
    {
        return $this->EMP_FEC_ANTIGUEDAD;
    }

    public function getTipoCont()
    {
        return $this->EMP_TIPO_CONT;
    }

    public function getCategoria()
    {
        return $this->EMP_CATEGORIA;
    }

    public function getFecNac()
    {
        return $this->EMP_FEC_NAC;
    }

    public function getTurno()
    {
        return $this->TURNO;
    }

    public function getNumDiasAp()
    {
        return $this->NUM_DIAS_AP;
    }

    public function getNumDiasAs()
    {
        return $this->NUM_DIAS_AS;
    }

    // Métodos setter
    public function setNIF($nif)
    {
        $this->EMP_NIF = $nif;
    }

    public function setApe1($ape1)
    {
        $this->EMP_APE_1 = $ape1;
    }

    public function setApe2($ape2)
    {
        $this->EMP_APE_2 = $ape2;
    }

    public function setNombre($nombre)
    {
        $this->EMP_NOMBRE = $nombre;
    }

    public function setFecAntiguedad($fecAntiguedad)
    {
        $this->EMP_FEC_ANTIGUEDAD = $fecAntiguedad;
    }

    public function setTipoCont($tipoCont)
    {
        $this->EMP_TIPO_CONT = $tipoCont;
    }

    public function setCategoria($categoria)
    {
        $this->EMP_CATEGORIA = $categoria;
    }

    public function setFecNac($fecNac)
    {
        $this->EMP_FEC_NAC = $fecNac;
    }

    public function setTurno($turno)
    {
        $this->TURNO = $turno;
    }

    public function setNumDiasAp($numDiasAp)
    {
        $this->NUM_DIAS_AP = $numDiasAp;
    }

    public function setNumDiasAs($numDiasAs)
    {
        $this->NUM_DIAS_AS = $numDiasAs;
    }

    // Método para mostrar información del empleado
    public function mostrarInfo()
    {
        echo "NIF: " . $this->getNIF() . "\n";
        echo "Apellido 1: " . $this->getApe1() . "\n";
        echo "Apellido 2: " . $this->getApe2() . "\n";
        echo "Nombre: " . $this->getNombre() . "\n";
        echo "Fecha de Antigüedad: " . $this->getFecAntiguedad() . "\n";
        echo "Tipo de Contrato: " . $this->getTipoCont() . "\n";
        echo "Categoría: " . $this->getCategoria() . "\n";
        echo "Fecha de Nacimiento: " . $this->getFecNac() . "\n";
        echo "Turno: " . $this->getTurno() . "\n";
        echo "Número de Días AP: " . $this->getNumDiasAp() . "\n";
        echo "Número de Días AS: " . $this->getNumDiasAs() . "\n";
    }
}