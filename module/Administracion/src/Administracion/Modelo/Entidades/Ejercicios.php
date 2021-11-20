<?php

namespace Administracion\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Ejercicios implements InputFilterAwareInterface {

    private $pk_ejercicio_id;
    private $NOM_EJER;
    private $DESC_EJER;
    private $RUTA_IMG_EJER;
    private $zonaMuscular;

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
    function getPk_ejercicio_id() {
        return $this->pk_ejercicio_id;
    }

    function getNOM_EJER() {
        return $this->NOM_EJER;
    }

    function getDESC_EJER() {
        return $this->DESC_EJER;
    }

    function getRUTA_IMG_EJER() {
        return $this->RUTA_IMG_EJER;
    }

    function getZonaMuscular() {
        return $this->zonaMuscular;
    }

    function setPk_ejercicio_id($pk_ejercicio_id) {
        $this->pk_ejercicio_id = $pk_ejercicio_id;
    }

    function setNOM_EJER($NOM_EJER) {
        $this->NOM_EJER = $NOM_EJER;
    }

    function setDESC_EJER($DESC_EJER) {
        $this->DESC_EJER = $DESC_EJER;
    }

    function setRUTA_IMG_EJER($RUTA_IMG_EJER) {
        $this->RUTA_IMG_EJER = $RUTA_IMG_EJER;
    }

    function setZonaMuscular($zonaMuscular) {
        $this->zonaMuscular = $zonaMuscular;
    }


}
