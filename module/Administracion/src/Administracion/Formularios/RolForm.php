<?php

namespace Administracion\Formularios;

use Zend\Form\Element;
use Zend\Form\Form;

class RolForm extends Form {

    public function __construct($action = '', $onsubmit = '', $required = true) {
        parent::__construct('formRol');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);

        $this->add(array(
            'name' => 'pk_rol_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'pk_rol_id',
            )
        ));

        $this->add(array(
            'name' => 'rol',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'maxlength' => 50,
                'style' => 'text-transform: uppercase',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'rol',
            )
        ));
        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control col-md-7 col-xs-12',
                'maxlength' => 50,
                'style' => 'text-transform: uppercase',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'descripcion',
            )
        ));
        $this->add(array(
            'name' => 'estado',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    'Activo' => 'Activo',
                    'Eliminado' => 'Eliminado',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'id' => 'esatdo',
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
