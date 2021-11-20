<?php

namespace Administracion\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Entreno implements InputFilterAwareInterface {

    private $pk_entreno_id;
    private $nombreUsuario;
    private $fechaHoraEntreno;
    private $valor;

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
    function getPk_entreno_id() {
        return $this->pk_entreno_id;
    }

    function getNombreUsuario() {
        return $this->nombreUsuario;
    }

    function getFechaHoraEntreno() {
        return $this->fechaHoraEntreno;
    }

    function getValor() {
        return $this->valor;
    }

    function setPk_entreno_id($pk_entreno_id) {
        $this->pk_entreno_id = $pk_entreno_id;
    }

    function setNombreUsuario($nombreUsuario) {
        $this->nombreUsuario = $nombreUsuario;
    }

    function setFechaHoraEntreno($fechaHoraEntreno) {
        $this->fechaHoraEntreno = $fechaHoraEntreno;
    }

    function setValor($valor) {
        $this->valor = $valor;
    }


}
