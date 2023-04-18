<?php

class Empleado
{
    private $DNI;
    private $DOCUMENTO_DNI;
    private $N_SEG_SOC;
    private $FECH_NACIM;
    private $EMAIL;
    private $CONTRASEÑA;
    private $SEXO;
    private $APELLIDO_1;
    private $APELLIDO_2;
    private $NOMBRE;
    private $FECH_ANTIGU;
    private $FECH_ALTA_EMPR;
    private $TIPO_CONTRATO;
    private $DIRECCION;
    private $CIUDAD;
    private $PROVINCIA;
    private $CP;
    private $TELF_CASA;
    private $TLF_MOVIL;
    private $FAMILIA_NUM;
    private $DOCUMENTO_FAM_NUM;
    private $ULTMO_RECONOC_MED;
    private $OBSERVACIONES;
    private $NIVEL_FORMATIVO;
    private $PAIS;
    private $COD_ZKT;

    public function __construct(
        $DNI,
        $DOCUMENTO_DNI,
        $N_SEG_SOC,
        $FECH_NACIM,
        $EMAIL,
        $CONTRASEÑA,
        $SEXO,
        $APELLIDO_1,
        $APELLIDO_2,
        $NOMBRE,
        $FECH_ANTIGU,
        $FECH_ALTA_EMPR,
        $TIPO_CONTRATO,
        $DIRECCION,
        $CIUDAD,
        $PROVINCIA,
        $CP,
        $TELF_CASA,
        $TLF_MOVIL,
        $FAMILIA_NUM,
        $DOCUMENTO_FAM_NUM,
        $ULTMO_RECONOC_MED,
        $OBSERVACIONES,
        $NIVEL_FORMATIVO,
        $PAIS,
        $COD_ZKT
    ) {
        $this->DNI = $DNI;
        $this->DOCUMENTO_DNI = $DOCUMENTO_DNI;
        $this->N_SEG_SOC = $N_SEG_SOC;
        $this->FECH_NACIM = $FECH_NACIM;
        $this->EMAIL = $EMAIL;
        $this->CONTRASEÑA = $CONTRASEÑA;
        $this->SEXO = $SEXO;
        $this->APELLIDO_1 = $APELLIDO_1;
        $this->APELLIDO_2 = $APELLIDO_2;
        $this->NOMBRE = $NOMBRE;
        $this->FECH_ANTIGU = $FECH_ANTIGU;
        $this->FECH_ALTA_EMPR = $FECH_ALTA_EMPR;
        $this->TIPO_CONTRATO = $TIPO_CONTRATO;
        $this->DIRECCION = $DIRECCION;
        $this->CIUDAD = $CIUDAD;
        $this->PROVINCIA = $PROVINCIA;
        $this->CP = $CP;
        $this->TELF_CASA = $TELF_CASA;
        $this->TLF_MOVIL = $TLF_MOVIL;
        $this->FAMILIA_NUM = $FAMILIA_NUM;
        $this->DOCUMENTO_FAM_NUM = $DOCUMENTO_FAM_NUM;
        $this->ULTMO_RECONOC_MED = $ULTMO_RECONOC_MED;
        $this->OBSERVACIONES = $OBSERVACIONES;
        $this->$NIVEL_FORMATIVO = $NIVEL_FORMATIVO;
        $this->PAIS = $PAIS;
        $this->COD_ZKT = $COD_ZKT;
    }

    public function getDNI()
    {
        return $this->DNI;
    }

    public function getDOCUMENTO_DNI()
    {
        return $this->DOCUMENTO_DNI;
    }

    public function getN_SEG_SOC()
    {
        return $this->N_SEG_SOC;
    }

    public function getFECH_NACIM()
    {
        return $this->FECH_NACIM;
    }

    public function getEMAIL()
    {
        return $this->EMAIL;
    }

    public function getCONTRASEÑA()
    {
        return $this->CONTRASEÑA;
    }

    public function getSEXO()
    {
        return $this->SEXO;
    }

    public function getAPELLIDO_1()
    {
        return $this->APELLIDO_1;
    }

    public function getAPELLIDO_2()
    {
        return $this->APELLIDO_2;
    }

    public function getNOMBRE()
    {
        return $this->NOMBRE;
    }
}
