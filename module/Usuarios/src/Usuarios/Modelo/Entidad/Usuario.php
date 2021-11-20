<?php

namespace Usuarios\Modelo\Entidad;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Usuario implements InputFilterAwareInterface {

    private $pk_usuario_id;
    private $login;
    private $password;

    protected $inputFilter;

    public function exchangeArray($datos = array()) {
        $this->pk_usuario_id = (isset($datos['pk_usuario_id'])) ? $datos['pk_usuario_id'] : null;
        $this->login = (isset($datos['login'])) ? $datos['login'] : null;
        $this->password = (isset($datos['password'])) ? $datos['password'] : null;
    }

    // Add content to these methods:
    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

//            $inputFilter->add($factory->createInput(array(
//                'name' => 'idUsuario',
//                'required' => true,
//                'filters' => array(
//                    array('name' => 'Int'),
//                ),
//            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'login',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 1,
                                    'max' => 20,
                                ),
                            ),
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'password',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 1,
                                    'max' => 20,
                                ),
                            ),
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'recordar',
                        'required' => false,
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
