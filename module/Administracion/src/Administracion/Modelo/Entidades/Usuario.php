<?php

namespace Administracion\Modelo\Entidades;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Usuario implements InputFilterAwareInterface {

    private $pk_usuario_id;
    private $fk_clienteempleado_id;
    private $fk_rol_id;
    private $nombreApellido;
    private $login;
    private $password;
    private $passwordseguro;
    private $estado;
    private $genero;
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
    function getPk_usuario_id() {
        return $this->pk_usuario_id;
    }

    function getFk_clienteempleado_id() {
        return $this->fk_clienteempleado_id;
    }

    function getFk_rol_id() {
        return $this->fk_rol_id;
    }

    function getNombreApellido() {
        return $this->nombreApellido;
    }

    function getLogin() {
        return $this->login;
    }

    function getPassword() {
        return $this->password;
    }

    function getPasswordseguro() {
        return $this->passwordseguro;
    }

    function getEstado() {
        return $this->estado;
    }

    function getGenero() {
        return $this->genero;
    }

    function getRutaFotoPerfil() {
        return $this->rutaFotoPerfil;
    }

    function setPk_usuario_id($pk_usuario_id) {
        $this->pk_usuario_id = $pk_usuario_id;
    }

    function setFk_clienteempleado_id($fk_clienteempleado_id) {
        $this->fk_clienteempleado_id = $fk_clienteempleado_id;
    }

    function setFk_rol_id($fk_rol_id) {
        $this->fk_rol_id = $fk_rol_id;
    }

    function setNombreApellido($nombreApellido) {
        $this->nombreApellido = $nombreApellido;
    }

    function setLogin($login) {
        $this->login = $login;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function setPasswordseguro($passwordseguro) {
        $this->passwordseguro = $passwordseguro;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setGenero($genero) {
        $this->genero = $genero;
    }

    function setRutaFotoPerfil($rutaFotoPerfil) {
        $this->rutaFotoPerfil = $rutaFotoPerfil;
    }

}
