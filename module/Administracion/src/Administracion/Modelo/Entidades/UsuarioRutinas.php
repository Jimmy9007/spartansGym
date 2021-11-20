<?php

namespace Administracion\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class UsuarioRutinas implements InputFilterAwareInterface {

    private $pk_rutina_id;
    private $pk_usuario_id;
    private $fechaAsignacion;

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

    function getPk_usuario_id() {
        return $this->pk_usuario_id;
    }

    function getFechaAsignacion() {
        return $this->fechaAsignacion;
    }

    function setPk_rutina_id($pk_rutina_id) {
        $this->pk_rutina_id = $pk_rutina_id;
    }

    function setPk_usuario_id($pk_usuario_id) {
        $this->pk_usuario_id = $pk_usuario_id;
    }

    function setFechaAsignacion($fechaAsignacion) {
        $this->fechaAsignacion = $fechaAsignacion;
    }



}
