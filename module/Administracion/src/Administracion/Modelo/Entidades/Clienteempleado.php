<?php

namespace Administracion\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Clienteempleado implements InputFilterAwareInterface {

    private $pk_clienteempleado_id;
    private $nombre;
    private $apellido;
    private $tipoIdentificacion;
    private $identificacion;
    private $fechaNacimiento;
    private $ocupacion;
    private $email;
    private $telefono;
    private $direccion;
    private $estado;
    private $genero;
    private $condicionFisica;
    private $OBJETIVOS;
    private $rutaFotoPerfil;

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
    function getPk_clienteempleado_id() {
        return $this->pk_clienteempleado_id;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getApellido() {
        return $this->apellido;
    }

    function getTipoIdentificacion() {
        return $this->tipoIdentificacion;
    }

    function getIdentificacion() {
        return $this->identificacion;
    }

    function getFechaNacimiento() {
        return $this->fechaNacimiento;
    }

    function getOcupacion() {
        return $this->ocupacion;
    }

    function getEmail() {
        return $this->email;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function getDireccion() {
        return $this->direccion;
    }

    function getEstado() {
        return $this->estado;
    }

    function getGenero() {
        return $this->genero;
    }

    function getCondicionFisica() {
        return $this->condicionFisica;
    }

    function getOBJETIVOS() {
        return $this->OBJETIVOS;
    }

    function getRutaFotoPerfil() {
        return $this->rutaFotoPerfil;
    }

    function setPk_clienteempleado_id($pk_clienteempleado_id) {
        $this->pk_clienteempleado_id = $pk_clienteempleado_id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    function setTipoIdentificacion($tipoIdentificacion) {
        $this->tipoIdentificacion = $tipoIdentificacion;
    }

    function setIdentificacion($identificacion) {
        $this->identificacion = $identificacion;
    }

    function setFechaNacimiento($fechaNacimiento) {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    function setOcupacion($ocupacion) {
        $this->ocupacion = $ocupacion;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setGenero($genero) {
        $this->genero = $genero;
    }

    function setCondicionFisica($condicionFisica) {
        $this->condicionFisica = $condicionFisica;
    }

    function setOBJETIVOS($OBJETIVOS) {
        $this->OBJETIVOS = $OBJETIVOS;
    }

    function setRutaFotoPerfil($rutaFotoPerfil) {
        $this->rutaFotoPerfil = $rutaFotoPerfil;
    }

}
