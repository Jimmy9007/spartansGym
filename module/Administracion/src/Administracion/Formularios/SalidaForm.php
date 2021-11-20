<?php

namespace Administracion\Formularios;

use Zend\Form\Element;
use Zend\Form\Form;

class SalidaForm extends Form {

    public function __construct($action = '', $onsubmit = '', $required = true) {
        parent::__construct('formSalida');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
//------------------------------------------------------------------------------               
        $this->add(array(
            'name' => 'tipo_reporte',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione...',
                'value_options' => array(
                    'Mensualidades' => 'Mensualidades',
                    'Ventas' => 'Ventas',
                    'Entrenos' => 'Entrenos',
                ),
                'disable_inarray_validator' => true,
                'label' => 'TIPO DE REPORTE',
            ),
            'attributes' => array(
                'id' => 'tipo_reporte',
                'required' => true,
                'class' => 'form-control',
            )
        ));



        $this->add(array(
            'name' => 'fechaInicio',
            'options' => array('label' => 'FEHCA INICIO'),
            'attributes' => array(
                'type' => 'date',
                'required' => true,
                'class' => 'form-control',
                'id' => 'fechaInicio',
            )
        ));

        $this->add(array(
            'name' => 'fechaFin',
            'options' => array('label' => 'FECHA FIN'),
            'attributes' => array(
                'type' => 'date',
                'required' => true,
                'class' => 'form-control',
                'id' => 'fechaFin',
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
                'value' => 'Cerrar',
                'class' => 'btn btn-danger',
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
