<?php

namespace Administracion\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class RutinasEjercicios implements InputFilterAwareInterface {

    private $pk_rutina_id;
    private $pk_ejercicio_id;
    private $DIAS;
    private $REPETICION;
    private $ordenEjercicio;

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
    function getPk_rutina_id() {
        return $this->pk_rutina_id;
    }

    function getPk_ejercicio_id() {
        return $this->pk_ejercicio_id;
    }

    function getDIAS() {
        return $this->DIAS;
    }

    function getREPETICION() {
        return $this->REPETICION;
    }

    function getOrdenEjercicio() {
        return $this->ordenEjercicio;
    }

    function setPk_rutina_id($pk_rutina_id) {
        $this->pk_rutina_id = $pk_rutina_id;
    }

    function setPk_ejercicio_id($pk_ejercicio_id) {
        $this->pk_ejercicio_id = $pk_ejercicio_id;
    }

    function setDIAS($DIAS) {
        $this->DIAS = $DIAS;
    }

    function setREPETICION($REPETICION) {
        $this->REPETICION = $REPETICION;
    }

    function setOrdenEjercicio($ordenEjercicio) {
        $this->ordenEjercicio = $ordenEjercicio;
    }



}
