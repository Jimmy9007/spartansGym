<?php

namespace Administracion\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Personalizado implements InputFilterAwareInterface {

    private $pk_personalizado_id;
    private $fk_usuario_id;
    private $fechaPersonalizado;
    private $valorPersonalizado;
    private $direccionPersonalizado;
    private $latitud;
    private $longitud;

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
    function getPk_personalizado_id() {
        return $this->pk_personalizado_id;
    }

    function getFk_usuario_id() {
        return $this->fk_usuario_id;
    }

    function getFechaPersonalizado() {
        return $this->fechaPersonalizado;
    }

    function getValorPersonalizado() {
        return $this->valorPersonalizado;
    }

    function getDireccionPersonalizado() {
        return $this->direccionPersonalizado;
    }

    function getLatitud() {
        return $this->latitud;
    }

    function getLongitud() {
        return $this->longitud;
    }

    function setPk_personalizado_id($pk_personalizado_id) {
        $this->pk_personalizado_id = $pk_personalizado_id;
    }

    function setFk_usuario_id($fk_usuario_id) {
        $this->fk_usuario_id = $fk_usuario_id;
    }

    function setFechaPersonalizado($fechaPersonalizado) {
        $this->fechaPersonalizado = $fechaPersonalizado;
    }

    function setValorPersonalizado($valorPersonalizado) {
        $this->valorPersonalizado = $valorPersonalizado;
    }

    function setDireccionPersonalizado($direccionPersonalizado) {
        $this->direccionPersonalizado = $direccionPersonalizado;
    }

    function setLatitud($latitud) {
        $this->latitud = $latitud;
    }

    function setLongitud($longitud) {
        $this->longitud = $longitud;
    }

}
