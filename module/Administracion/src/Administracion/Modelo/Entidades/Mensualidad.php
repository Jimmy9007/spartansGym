<?php

namespace Administracion\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Mensualidad implements InputFilterAwareInterface {

    private $pk_mensualidad_id;
    private $FECHA_MENS;
    private $FECHA_MENS_FIN;
    private $valor;
    private $fechaUltPreaviso;
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
    function getPk_mensualidad_id() {
        return $this->pk_mensualidad_id;
    }

    function getFECHA_MENS() {
        return $this->FECHA_MENS;
    }

    function getFECHA_MENS_FIN() {
        return $this->FECHA_MENS_FIN;
    }

    function getValor() {
        return $this->valor;
    }

    function getFechaUltPreaviso() {
        return $this->fechaUltPreaviso;
    }

    function getFk_usuario_id() {
        return $this->fk_usuario_id;
    }

    function setPk_mensualidad_id($pk_mensualidad_id) {
        $this->pk_mensualidad_id = $pk_mensualidad_id;
    }

    function setFECHA_MENS($FECHA_MENS) {
        $this->FECHA_MENS = $FECHA_MENS;
    }

    function setFECHA_MENS_FIN($FECHA_MENS_FIN) {
        $this->FECHA_MENS_FIN = $FECHA_MENS_FIN;
    }

    function setValor($valor) {
        $this->valor = $valor;
    }

    function setFechaUltPreaviso($fechaUltPreaviso) {
        $this->fechaUltPreaviso = $fechaUltPreaviso;
    }

    function setFk_usuario_id($fk_usuario_id) {
        $this->fk_usuario_id = $fk_usuario_id;
    }

}
