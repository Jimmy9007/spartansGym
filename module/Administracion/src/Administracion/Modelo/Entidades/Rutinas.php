<?php

namespace Administracion\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Rutinas implements InputFilterAwareInterface {

    private $pk_rutina_id;
    private $DESCRIP_RUTINA;
    private $fechaRutina;
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
    function getPk_rutina_id() {
        return $this->pk_rutina_id;
    }

    function getDESCRIP_RUTINA() {
        return $this->DESCRIP_RUTINA;
    }

    function getFechaRutina() {
        return $this->fechaRutina;
    }

    function getFk_usuario_id() {
        return $this->fk_usuario_id;
    }

    function setPk_rutina_id($pk_rutina_id) {
        $this->pk_rutina_id = $pk_rutina_id;
    }

    function setDESCRIP_RUTINA($DESCRIP_RUTINA) {
        $this->DESCRIP_RUTINA = $DESCRIP_RUTINA;
    }

    function setFechaRutina($fechaRutina) {
        $this->fechaRutina = $fechaRutina;
    }

    function setFk_usuario_id($fk_usuario_id) {
        $this->fk_usuario_id = $fk_usuario_id;
    }

}
