<?php

namespace Administracion\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ProbaLluvia implements InputFilterAwareInterface {

    private $pk_proballuvia_id;
    private $dias;
    private $lluviosos;
    private $probabilidad;
   

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
    function getPk_proballuvia_id() {
        return $this->pk_proballuvia_id;
    }

    function getDias() {
        return $this->dias;
    }

    function getLluviosos() {
        return $this->lluviosos;
    }

    function getProbabilidad() {
        return $this->probabilidad;
    }

    function setPk_proballuvia_id($pk_proballuvia_id) {
        $this->pk_proballuvia_id = $pk_proballuvia_id;
    }

    function setDias($dias) {
        $this->dias = $dias;
    }

    function setLluviosos($lluviosos) {
        $this->lluviosos = $lluviosos;
    }

    function setProbabilidad($probabilidad) {
        $this->probabilidad = $probabilidad;
    }



}
