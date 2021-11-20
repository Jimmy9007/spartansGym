<?php

namespace Administracion\Formularios;

use Zend\Form\Element;
use Zend\Form\Form;

class OpcionForm extends Form {

    public function __construct($action = '', $onsubmit = '', $required = true) {
        parent::__construct('formOption');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);

        $this->add(array(
            'name' => 'pk_opcion_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'pk_opcion_id',
            )
        ));

        $this->add(array(
            'name' => 'opcion',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'maxlength' => 500,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'opcion',
            )
        ));

        $this->add(array(
            'name' => 'puntos',
            'attributes' => array(
                'type' => 'number',
                'min' => 0,
                'class' => 'form-control col-md-7 col-xs-12',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'puntos',
            )
        ));


        $this->add(array(
            'name' => 'estado',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'estado',
            )
        ));

        $this->add(array(
            'name' => 'registradoPor',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'registradoPor',
            )
        ));

        $this->add(array(
            'name' => 'fechaHoraReg',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'fechaHoraReg',
            )
        ));

        $this->add(array(
            'name' => 'modificadoPor',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'modificadoPor',
            )
        ));

        $this->add(array(
            'name' => 'fechaHoraMod',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'fechaHoraMod',
            )
        ));

//------------------------------------------------------------------------------

        $this->add(array(
            'name' => 'btnCancelar',
            'type' => 'Button',
            'options' => array(
                'label' => 'Cerrar',
            ),
            'attributes' => array(
                'value' => 'Cancelar',
                'class' => 'btn btn-primary',
                'data-dismiss' => 'modal',
                'id' => 'btnCancelar',
            ),
        ));

        $this->add(array(
            'name' => 'btnEnviar',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Guardar',
                'class' => 'btn btn-success',
                'id' => 'btnEnviar',
            ),
        ));
    }

}
