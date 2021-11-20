<?php

namespace Administracion\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Asistencia implements InputFilterAwareInterface {

    private $pk_asistencia_id;
    private $FECHA_ASIS;
    private $fk_usuario_id;

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
    function getPk_asistencia_id() {
        return $this->pk_asistencia_id;
    }

    function getFECHA_ASIS() {
        return $this->FECHA_ASIS;
    }

    function getFk_usuario_id() {
        return $this->fk_usuario_id;
    }

    function setPk_asistencia_id($pk_asistencia_id) {
        $this->pk_asistencia_id = $pk_asistencia_id;
    }

    function setFECHA_ASIS($FECHA_ASIS) {
        $this->FECHA_ASIS = $FECHA_ASIS;
    }

    function setFk_usuario_id($fk_usuario_id) {
        $this->fk_usuario_id = $fk_usuario_id;
    }


   
}
