<?php

namespace Administracion\Formularios;

use Zend\Form\Element;
use Zend\Form\Form;

class VentaForm extends Form {

    public function __construct($action = '', $onsubmit = '', $required = true) {
        parent::__construct('formVenta');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);

        $this->add(array(
            'name' => 'pk_venta_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'pk_venta_id',
            )
        ));
        $this->add(array(
            'name' => 'cantidadVenta',
            'attributes' => array(
                'type' => 'number',
                'min' => 1,
                'max' => 100,
                'placeholder' => 'Ingrese solo numeros',
                'onchange' => 'operacionesVenta()',
                'class' => 'form-control col-md-7 col-xs-12',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'cantidadVenta',
            )
        ));

        $this->add(array(
            'name' => 'valorTotal',
            'attributes' => array(
                'type' => 'text',
                'onchange' => 'operacionesVenta()',
                'class' => 'form-control col-md-7 col-xs-12',
                'placeholder' => 'Ingrese solo numeros',
                'pattern' => '[0-9]{1,20}',
                'maxlength' => 50,
                'required' => $required,
                'readonly' => True,
                'id' => 'valorTotal',
            )
        ));
        $this->add(array(
            'name' => 'ganancia',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'placeholder' => 'Ingrese solo numeros',
                'pattern' => '[0-9]{1,20}',
                'maxlength' => 50,
                //'required' => $required,
                'readonly' => TRUE,
                'id' => 'ganancia',
            )
        ));
        $this->add(array(
            'name' => 'fechaVenta',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'fechaVenta',
            )
        ));
//------------------------------------------------------------------------------

        $this->add(array(
            'name' => 'btnCancelar',
            'type' => 'Button',
            'options' => array(
                'label' => 'Cancelar',
            ),
            'attributes' => array(
                'value' => 'Cancelar',
                'class' => 'btn btn-danger',
                'data-dismiss' => 'modal',
                'id' => 'btnCancelar',
            ),
        ));

        $this->add(array(
            'name' => 'btnEnviar',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Vender',
                'class' => 'btn btn-success',
                'id' => 'btnEnviar',
            ),
        ));
    }

}
