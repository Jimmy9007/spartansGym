<?php

namespace Administracion\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class VentasProductos implements InputFilterAwareInterface {

    private $pk_venta_id;
    private $pk_producto_id;
    private $cantidadVenta;
    private $monto;

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

    function getPk_producto_id() {
        return $this->pk_producto_id;
    }

    function getCantidadVenta() {
        return $this->cantidadVenta;
    }

    function getMonto() {
        return $this->monto;
    }

    function setPk_venta_id($pk_venta_id) {
        $this->pk_venta_id = $pk_venta_id;
    }

    function setPk_producto_id($pk_producto_id) {
        $this->pk_producto_id = $pk_producto_id;
    }

    function setCantidadVenta($cantidadVenta) {
        $this->cantidadVenta = $cantidadVenta;
    }

    function setMonto($monto) {
        $this->monto = $monto;
    }

}
