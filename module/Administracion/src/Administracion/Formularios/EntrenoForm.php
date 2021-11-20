<?php

namespace Administracion\Formularios;

use Zend\Form\Element;
use Zend\Form\Form;

class EntrenoForm extends Form {

    public function __construct($action = '', $onsubmit = '', $required = true) {
        parent::__construct('formEntreno');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);

        $this->add(array(
            'name' => 'pk_entreno_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'pk_entreno_id',
            )
        ));

        $this->add(array(
            'name' => 'nombreUsuario',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'maxlength' => 50,
                'style' => 'text-transform: uppercase',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'nombreUsuario',
            )
        ));
        $this->add(array(
            'name' => 'fechaHoraEntreno',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'fechaHoraEntreno',
            )
        ));
        $this->add(array(
            'name' => 'valor',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'placeholder' => 'Solo Numeros',
                'pattern' => '[0-9]{1,20}',
                'maxlength' => 20,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'valor',
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
