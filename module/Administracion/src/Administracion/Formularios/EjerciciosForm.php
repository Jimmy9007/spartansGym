<?php

namespace Administracion\Formularios;

use Zend\Form\Element;
use Zend\Form\Form;

class EjerciciosForm extends Form {

    public function __construct($action = '', $onsubmit = '', $required = true) {
        parent::__construct('formEjercicios');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);

        $this->add(array(
            'name' => 'pk_ejercicio_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'pk_ejercicio_id',
            )
        ));

        $this->add(array(
            'name' => 'NOM_EJER',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'maxlength' => 50,
                'style' => 'text-transform: uppercase',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'NOM_EJER',
            )
        ));
        $this->add(array(
            'name' => 'DESC_EJER',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control col-md-7 col-xs-12',
                'maxlength' => 500,
                'style' => 'text-transform: uppercase',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'DESC_EJER',
            )
        ));
        $this->add(array(
            'name' => 'RUTA_IMG_EJER',
            'attributes' => array(
                'type' => 'file',
                'class' => 'form-control col-md-7 col-xs-12',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'RUTA_IMG_EJER',
            )
        ));
        $this->add(array(
            'name' => 'zonaMuscular',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    'Abdominales' => 'Abdominales',
                    'Pierna' => 'Pierna',
                    'Gemelos' => 'Gemelos',
                    'Sóleos' => 'Sóleos',
                    'Gluteo' => 'Gluteo',
                    'Gluteo Medio' => 'Gluteo Medio',
                    'Muslo' => 'Muslo',
                    'Femoral' => 'Femoral',
                    'Cuádriceps' => 'Cuádriceps',
                    'Aductores' => 'Aductores',
                    'Antebrazo' => 'Antebrazo',
                    'Brazo' => 'Brazo',
                    'Bíceps' => 'Bíceps',
                    'Tríceps' => 'Tríceps',
                    'Espalda' => 'Espalda',
                    'Lats Dorsales' => 'Lats Dorsales',
                    'Erectores Espinales' => 'Erectores Espinales',
                    'Trapecio' => 'Trapecio',
                    'Pecho' => 'Pecho',
                    'Pecho Superior' => 'Pecho Superior',
                    'Pecho Inferior' => 'Pecho Inferior',
                    'Pecho Interno' => 'Pecho Interno',
                    'Pecho Externo' => 'Pecho Externo',
                    'Pecho Caja Torácica' => 'Pecho Caja Torácica',
                    'Pecho Serrato' => 'Pecho Serrato',
                    'Hombro' => 'Hombro',
                    'Deltoides Anterior' => 'Deltoides Anterior',
                    'Deltoides Medio' => 'Deltoides Medio',
                    'Deltoides Posterior' => 'Deltoides Posterior',
                    'Cardio' => 'Cardio',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'id' => 'zonaMuscular',
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
