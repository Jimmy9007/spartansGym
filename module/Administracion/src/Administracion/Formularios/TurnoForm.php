<?php

namespace Administracion\Formularios;

use Zend\Form\Element;
use Zend\Form\Form;

class TurnoForm extends Form {

    public function __construct($action = '', $onsubmit = '', $required = true, $Instructores = array()) {
        parent::__construct('formTurno');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);

        $this->add(array(
            'name' => 'pk_turno_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'pk_turno_id',
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
            'name' => 'fechaInicio',
            'attributes' => array(
                'type' => 'datetime-local',
                'onchange' => 'operacionHoras()',
                'class' => 'form-control col-md-7 col-xs-12',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'fechaInicio',
            )
        ));
        $this->add(array(
            'name' => 'fechaFinal',
            'attributes' => array(
                'type' => 'datetime-local',
                'onchange' => 'operacionHoras()',
                'class' => 'form-control col-md-7 col-xs-12',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'fechaFinal',
            )
        ));
        $this->add(array(
            'name' => 'fechaReporteInicial',
            'attributes' => array(
                'type' => 'date',
                'required' => true,
                'class' => 'form-control',
                'id' => 'fechaReporteInicial',
            )
        ));
        $this->add(array(
            'name' => 'fechaReporteFinal',
            'attributes' => array(
                'type' => 'date',
                'required' => true,
                'class' => 'form-control',
                'id' => 'fechaReporteFinal',
            )
        ));
        $this->add(array(
            'name' => 'valorHora',
            'attributes' => array(
                'type' => 'text',
                'onchange' => 'operacionHoras()',
                'class' => 'form-control col-md-7 col-xs-12',
                'placeholder' => 'Ingrese solo numeros',
                'pattern' => '[0-9]{1,20}',
                'maxlength' => 50,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'valorHora',
            )
        ));
        $this->add(array(
            'name' => 'horasTurno',
            'attributes' => array(
                'type' => 'text',
                'onchange' => 'operacionHoras()',
                'class' => 'form-control col-md-7 col-xs-12',
                'placeholder' => 'Ingrese solo numeros',
                'pattern' => '[0-9]{1,20}',
                'maxlength' => 50,
                'required' => $required,
                'readonly' => $required,
                'id' => 'horasTurno',
            )
        ));
        $this->add(array(
            'name' => 'pagoTotal',
            'attributes' => array(
                'type' => 'text',
                'onchange' => 'operacionHoras()',
                'class' => 'form-control col-md-7 col-xs-12',
                'placeholder' => 'Ingrese solo numeros',
                'pattern' => '[0-9]{1,20}',
                'maxlength' => 50,
                'required' => $required,
                'readonly' => $required,
                'id' => 'pagoTotal',
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
