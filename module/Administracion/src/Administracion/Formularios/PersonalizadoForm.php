<?php

namespace Administracion\Formularios;

use Zend\Form\Element;
use Zend\Form\Form;

class PersonalizadoForm extends Form {

    public function __construct($action = '', $onsubmit = '', $required = true, $Instructores = array()) {
        parent::__construct('formPersonalizado');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);

        $this->add(array(
            'name' => 'pk_personalizado_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'pk_personalizado_id',
            )
        ));
        $this->add(array(
            'name' => 'fk_usuario_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'fk_usuario_id',
            )
        ));
        $this->add(array(
            'name' => 'fk_usuario_id',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => $Instructores,
                'disable_inrray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'id' => 'fk_usuario_id',
            )
        ));
        $this->add(array(
            'name' => 'fechaPersonalizado',
            'attributes' => array(
                'type' => 'datetime-local',
                'class' => 'form-control col-md-7 col-xs-12',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'fechaPersonalizado',
            )
        ));
        $this->add(array(
            'name' => 'valorPersonalizado',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'placeholder' => 'Solo Numeros',
                'pattern' => '[0-9]{1,20}',
                'maxlength' => 20,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'valorPersonalizado',
            )
        ));
        $this->add(array(
            'name' => 'direccionPersonalizado',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'maxlenght' => 50,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'direccionPersonalizado',
            )
        ));
        $this->add(array(
            'name' => 'latitud',
            'options' => array('label' => 'LATITUD'),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-left',
                'readonly' => 'true',
                'id' => 'latitud',
            )
        ));

        $this->add(array(
            'name' => 'longitud',
            'options' => array('label' => 'LONGITUD'),
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => 'true',
                'id' => 'longitud',
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
