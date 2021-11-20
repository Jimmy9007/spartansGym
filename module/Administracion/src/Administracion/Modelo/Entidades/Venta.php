<?php

namespace Administracion\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Venta implements InputFilterAwareInterface {

    private $pk_venta_id;    
    private $cantidadVenta;
    private $valorTotal;
    private $ganancia;
    private $fechaVenta;
   

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
    function getPk_venta_id() {
        return $this->pk_venta_id;
    }

    function getCantidadVenta() {
        return $this->cantidadVenta;
    }

    function getValorTotal() {
        return $this->valorTotal;
    }

    function getGanancia() {
        return $this->ganancia;
    }

    function getFechaVenta() {
        return $this->fechaVenta;
    }

    function setPk_venta_id($pk_venta_id) {
        $this->pk_venta_id = $pk_venta_id;
    }

    function setCantidadVenta($cantidadVenta) {
        $this->cantidadVenta = $cantidadVenta;
    }

    function setValorTotal($valorTotal) {
        $this->valorTotal = $valorTotal;
    }

    function setGanancia($ganancia) {
        $this->ganancia = $ganancia;
    }

    function setFechaVenta($fechaVenta) {
        $this->fechaVenta = $fechaVenta;
    }


}
