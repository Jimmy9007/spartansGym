<?php

namespace Administracion\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Producto implements InputFilterAwareInterface {

    private $pk_producto_id;
    private $codigoBarras;
    private $nombreProducto;
    private $descripcion;
    private $cantidad;
    private $precioCosto;
    private $precioVenta;
    private $fechaadquisicion;
    private $proveedor;
    private $numfactura;
    private $imagenProducto;
    private $estado;
    private $fechahorareg;
    private $fechahoramod;

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
    function getPk_producto_id() {
        return $this->pk_producto_id;
    }

    function getCodigoBarras() {
        return $this->codigoBarras;
    }

    function getNombreProducto() {
        return $this->nombreProducto;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getCantidad() {
        return $this->cantidad;
    }

    function getPrecioCosto() {
        return $this->precioCosto;
    }

    function getPrecioVenta() {
        return $this->precioVenta;
    }

    function getFechaadquisicion() {
        return $this->fechaadquisicion;
    }

    function getProveedor() {
        return $this->proveedor;
    }

    function getNumfactura() {
        return $this->numfactura;
    }

    function getImagenProducto() {
        return $this->imagenProducto;
    }

    function getEstado() {
        return $this->estado;
    }

    function getFechahorareg() {
        return $this->fechahorareg;
    }

    function getFechahoramod() {
        return $this->fechahoramod;
    }

    function setPk_producto_id($pk_producto_id) {
        $this->pk_producto_id = $pk_producto_id;
    }

    function setCodigoBarras($codigoBarras) {
        $this->codigoBarras = $codigoBarras;
    }

    function setNombreProducto($nombreProducto) {
        $this->nombreProducto = $nombreProducto;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    function setPrecioCosto($precioCosto) {
        $this->precioCosto = $precioCosto;
    }

    function setPrecioVenta($precioVenta) {
        $this->precioVenta = $precioVenta;
    }

    function setFechaadquisicion($fechaadquisicion) {
        $this->fechaadquisicion = $fechaadquisicion;
    }

    function setProveedor($proveedor) {
        $this->proveedor = $proveedor;
    }

    function setNumfactura($numfactura) {
        $this->numfactura = $numfactura;
    }

    function setImagenProducto($imagenProducto) {
        $this->imagenProducto = $imagenProducto;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setFechahorareg($fechahorareg) {
        $this->fechahorareg = $fechahorareg;
    }

    function setFechahoramod($fechahoramod) {
        $this->fechahoramod = $fechahoramod;
    }



}
