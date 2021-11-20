<?php

namespace Administracion\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ReporteMensualidad implements InputFilterAwareInterface {

    private $pk_reporte_id;
    private $fechaReporte;
    private $fechaFinReporte;
    private $valorReporte;
    private $fk_mensualidad_id;

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
    function getPk_reporte_id() {
        return $this->pk_reporte_id;
    }

    function getFechaReporte() {
        return $this->fechaReporte;
    }

    function getFechaFinReporte() {
        return $this->fechaFinReporte;
    }

    function getValorReporte() {
        return $this->valorReporte;
    }

    function getFk_mensualidad_id() {
        return $this->fk_mensualidad_id;
    }

    function setPk_reporte_id($pk_reporte_id) {
        $this->pk_reporte_id = $pk_reporte_id;
    }

    function setFechaReporte($fechaReporte) {
        $this->fechaReporte = $fechaReporte;
    }

    function setFechaFinReporte($fechaFinReporte) {
        $this->fechaFinReporte = $fechaFinReporte;
    }

    function setValorReporte($valorReporte) {
        $this->valorReporte = $valorReporte;
    }

    function setFk_mensualidad_id($fk_mensualidad_id) {
        $this->fk_mensualidad_id = $fk_mensualidad_id;
    }
}
