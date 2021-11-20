<?php

namespace Administracion\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Rol implements InputFilterAwareInterface {

    private $pk_rol_id;
    private $rol;
    private $descripcion;
    private $estado;

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
    function getPk_rol_id() {
        return $this->pk_rol_id;
    }

    function getRol() {
        return $this->rol;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getEstado() {
        return $this->estado;
    }

    function setPk_rol_id($pk_rol_id) {
        $this->pk_rol_id = $pk_rol_id;
    }

    function setRol($rol) {
        $this->rol = $rol;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

}
