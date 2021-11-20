<?php

namespace Administracion\Formularios;

use Zend\Form\Element;
use Zend\Form\Form;

class EventoForm extends Form {

    public function __construct($action = '', $onsubmit = '', $required = true) {
        parent::__construct('formEvento');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);

        $this->add(array(
            'name' => 'pk_evento_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'pk_evento_id',
            )
        ));

        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'maxlength' => 50,
                'style' => 'text-transform: uppercase',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'title',
            )
        ));
        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'maxlength' => 50,
                'style' => 'text-transform: uppercase',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'descripcion',
            )
        ));
        $this->add(array(
            'name' => 'color',
            'attributes' => array(
                'type' => 'color',
                'class' => 'form-control col-md-7 col-xs-12',
                'maxlength' => 50,
				'value' => "#3987ad",
                'style' => 'text-transform: uppercase',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'color',
            )
        ));
        $this->add(array(
            'name' => 'textColor',
            'attributes' => array(
                'type' => 'color',
                'class' => 'form-control col-md-7 col-xs-12',
                'maxlength' => 50,
				'value' => "#ffffff",
                'style' => 'text-transform: uppercase',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'textColor',
            )
        ));
        $this->add(array(
            'name' => 'start',
            'attributes' => array(
                'type' => 'datetime-local',
//                'onchange' => 'ponerFecha()',
                'class' => 'form-control col-md-7 col-xs-12',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'start',
            )
        ));
        $this->add(array(
            'name' => 'end',
            'attributes' => array(
                'type' => 'datetime-local',
                'class' => 'form-control col-md-7 col-xs-12',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'end',
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
            'name' => 'btnAgregarEvento',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Guardar',
                'class' => 'btn btn-success',
                'id' => 'btnAgregarEvento',
            ),
        ));
    }

}
