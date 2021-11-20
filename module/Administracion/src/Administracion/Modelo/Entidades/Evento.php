<?php

namespace Administracion\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Evento implements InputFilterAwareInterface {

    private $pk_evento_id;
    private $title;
    private $descripcion;
    private $start;
    private $color;
    private $textColor;
    private $end;

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

    function getPk_evento_id() {
        return $this->pk_evento_id;
    }

    function getTitle() {
        return $this->title;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getStart() {
        return $this->start;
    }

    function getColor() {
        return $this->color;
    }

    function getTextColor() {
        return $this->textColor;
    }

    function getEnd() {
        return $this->end;
    }

    function setPk_evento_id($pk_evento_id) {
        $this->pk_evento_id = $pk_evento_id;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setStart($start) {
        $this->start = $start;
    }

    function setColor($color) {
        $this->color = $color;
    }

    function setTextColor($textColor) {
        $this->textColor = $textColor;
    }

    function setEnd($end) {
        $this->end = $end;
    }

}
