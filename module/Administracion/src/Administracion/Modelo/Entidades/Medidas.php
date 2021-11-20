<?php

namespace Administracion\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Medidas implements InputFilterAwareInterface {

    private $pk_medida_id;
    private $ESTATURA;
    private $PESO;
    private $PECHO;
    private $BICEPS;
    private $HOMBRO;
    private $ANTEBRAZO;
    private $CINTURA;
    private $CADERA;
    private $PIERNA;
    private $piernaB;
    private $PANTORRILLA;
    private $IMC;
    private $ponerPliegues;
    private $tricipital;
    private $subescapular;
    private $supraliaco;
    private $plieAbdominal;
    private $cuadricipital;
    private $peroneal;
    private $porGrasa;
    private $PGC;
    private $PGK;
    private $PMK;
    private $fk_usuario_id;
    private $FECHA_MED_USU;

    public function __construct(array $datos = null) {
        if (is_array($datos)) {
            $this->exchangeArray($datos);
        }
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }

    public function getInputFilter() {
        
    }

    public function exchangeArray($data) {
        $metodos = get_class_methods($this);
        foreach ($data as $key => $value) {
            $metodo = 'set' . ucfirst($key);
            if (in_array($metodo, $metodos)) {
                $this->$metodo($value);
            }
        }
    }

//------------------------------------------------------------------------------
  
    function getPk_medida_id() {
        return $this->pk_medida_id;
    }

    function getESTATURA() {
        return $this->ESTATURA;
    }

    function getPESO() {
        return $this->PESO;
    }

    function getPECHO() {
        return $this->PECHO;
    }

    function getBICEPS() {
        return $this->BICEPS;
    }

    function getHOMBRO() {
        return $this->HOMBRO;
    }

    function getANTEBRAZO() {
        return $this->ANTEBRAZO;
    }

    function getCINTURA() {
        return $this->CINTURA;
    }

    function getCADERA() {
        return $this->CADERA;
    }

    function getPIERNA() {
        return $this->PIERNA;
    }

    function getPiernaB() {
        return $this->piernaB;
    }

    function getPANTORRILLA() {
        return $this->PANTORRILLA;
    }

    function getIMC() {
        return $this->IMC;
    }

    function getPonerPliegues() {
        return $this->ponerPliegues;
    }

    function getTricipital() {
        return $this->tricipital;
    }

    function getSubescapular() {
        return $this->subescapular;
    }

    function getSupraliaco() {
        return $this->supraliaco;
    }

    function getPlieAbdominal() {
        return $this->plieAbdominal;
    }

    function getCuadricipital() {
        return $this->cuadricipital;
    }

    function getPeroneal() {
        return $this->peroneal;
    }

    function getPorGrasa() {
        return $this->porGrasa;
    }

    function getPGC() {
        return $this->PGC;
    }

    function getPGK() {
        return $this->PGK;
    }

    function getPMK() {
        return $this->PMK;
    }

    function getFk_usuario_id() {
        return $this->fk_usuario_id;
    }

    function getFECHA_MED_USU() {
        return $this->FECHA_MED_USU;
    }

    function setPk_medida_id($pk_medida_id) {
        $this->pk_medida_id = $pk_medida_id;
    }

    function setESTATURA($ESTATURA) {
        $this->ESTATURA = $ESTATURA;
    }

    function setPESO($PESO) {
        $this->PESO = $PESO;
    }

    function setPECHO($PECHO) {
        $this->PECHO = $PECHO;
    }

    function setBICEPS($BICEPS) {
        $this->BICEPS = $BICEPS;
    }

    function setHOMBRO($HOMBRO) {
        $this->HOMBRO = $HOMBRO;
    }

    function setANTEBRAZO($ANTEBRAZO) {
        $this->ANTEBRAZO = $ANTEBRAZO;
    }

    function setCINTURA($CINTURA) {
        $this->CINTURA = $CINTURA;
    }

    function setCADERA($CADERA) {
        $this->CADERA = $CADERA;
    }

    function setPIERNA($PIERNA) {
        $this->PIERNA = $PIERNA;
    }

    function setPiernaB($piernaB) {
        $this->piernaB = $piernaB;
    }

    function setPANTORRILLA($PANTORRILLA) {
        $this->PANTORRILLA = $PANTORRILLA;
    }

    function setIMC($IMC) {
        $this->IMC = $IMC;
    }

    function setPonerPliegues($ponerPliegues) {
        $this->ponerPliegues = $ponerPliegues;
    }

    function setTricipital($tricipital) {
        $this->tricipital = $tricipital;
    }

    function setSubescapular($subescapular) {
        $this->subescapular = $subescapular;
    }

    function setSupraliaco($supraliaco) {
        $this->supraliaco = $supraliaco;
    }

    function setPlieAbdominal($plieAbdominal) {
        $this->plieAbdominal = $plieAbdominal;
    }

    function setCuadricipital($cuadricipital) {
        $this->cuadricipital = $cuadricipital;
    }

    function setPeroneal($peroneal) {
        $this->peroneal = $peroneal;
    }

    function setPorGrasa($porGrasa) {
        $this->porGrasa = $porGrasa;
    }

    function setPGC($PGC) {
        $this->PGC = $PGC;
    }

    function setPGK($PGK) {
        $this->PGK = $PGK;
    }

    function setPMK($PMK) {
        $this->PMK = $PMK;
    }

    function setFk_usuario_id($fk_usuario_id) {
        $this->fk_usuario_id = $fk_usuario_id;
    }

    function setFECHA_MED_USU($FECHA_MED_USU) {
        $this->FECHA_MED_USU = $FECHA_MED_USU;
    }

}
