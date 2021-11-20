<?php

namespace Administracion\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Turno implements InputFilterAwareInterface {

    private $pk_turno_id;
    private $fk_usuario_id;
    private $valorHora;
    private $fechaInicio;
    private $fechaFinal;
    private $horasTurno;
    private $pagoTotal;

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
    function getPk_turno_id() {
        return $this->pk_turno_id;
    }

    function getFk_usuario_id() {
        return $this->fk_usuario_id;
    }

    function getValorHora() {
        return $this->valorHora;
    }

    function getFechaInicio() {
        return $this->fechaInicio;
    }

    function getFechaFinal() {
        return $this->fechaFinal;
    }

    function getHorasTurno() {
        return $this->horasTurno;
    }

    function getPagoTotal() {
        return $this->pagoTotal;
    }

    function setPk_turno_id($pk_turno_id) {
        $this->pk_turno_id = $pk_turno_id;
    }

    function setFk_usuario_id($fk_usuario_id) {
        $this->fk_usuario_id = $fk_usuario_id;
    }

    function setValorHora($valorHora) {
        $this->valorHora = $valorHora;
    }

    function setFechaInicio($fechaInicio) {
        $this->fechaInicio = $fechaInicio;
    }

    function setFechaFinal($fechaFinal) {
        $this->fechaFinal = $fechaFinal;
    }

    function setHorasTurno($horasTurno) {
        $this->horasTurno = $horasTurno;
    }

    function setPagoTotal($pagoTotal) {
        $this->pagoTotal = $pagoTotal;
    }

}
