<?php

namespace Administracion\Formularios;

use Zend\Form\Element;
use Zend\Form\Form;

class MedidasForm extends Form {

    public function __construct($action = '', $onsubmit = '', $required = true) {
        parent::__construct('formMedidas');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);

        $this->add(array(
            'name' => 'pk_medida_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'pk_medida_id',
            )
        ));
        $this->add(array(
            'name' => 'ESTATURA',
            'attributes' => array(
                'type' => 'text',
                'onchange' => 'calcularIMC()',
                'class' => 'form-control has-feedback-right',
                'placeholder' => 'Estaura en metros',
                'maxlength' => 50,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'ESTATURA',
            )
        ));
        $this->add(array(
            'name' => 'PESO',
            'attributes' => array(
                'type' => 'text',
                'onchange' => 'calcularIMC()',
                'class' => 'form-control has-feedback-right',
                'placeholder' => 'Peso en kilogramos',
                'maxlength' => 50,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'PESO',
            )
        ));
        $this->add(array(
            'name' => 'PECHO',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-right',
                'placeholder' => 'Centimetros',
                'maxlength' => 20,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'PECHO',
            )
        ));
        $this->add(array(
            'name' => 'BICEPS',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-right',
                'placeholder' => 'Centimetros',
                'maxlength' => 20,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'BICEPS',
            )
        ));

        $this->add(array(
            'name' => 'HOMBRO',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-right',
                'placeholder' => 'Centimetros',
                'maxlength' => 20,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'HOMBRO',
            )
        ));
        $this->add(array(
            'name' => 'ANTEBRAZO',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-right',
                'placeholder' => 'Centimetros',
                'maxlength' => 20,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'ANTEBRAZO',
            )
        ));
        $this->add(array(
            'name' => 'CINTURA',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-right',
                'placeholder' => 'Centimetros',
                'maxlength' => 20,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'CINTURA',
            )
        ));
        $this->add(array(
            'name' => 'CADERA',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-right',
                'placeholder' => 'Centimetros',
                'maxlength' => 20,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'CADERA',
            )
        ));

        $this->add(array(
            'name' => 'PIERNA',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-right',
                'placeholder' => 'Centimetros',
                'maxlength' => 20,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'PIERNA',
            )
        ));
        $this->add(array(
            'name' => 'piernaB',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-right',
                'placeholder' => 'Centimetros',
                'maxlength' => 20,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'piernaB',
            )
        ));

        $this->add(array(
            'name' => 'PANTORRILLA',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-right',
                'placeholder' => 'Centimetros',
                'maxlength' => 20,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'PANTORRILLA',
            )
        ));
        $this->add(array(
            'name' => 'IMC',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'maxlength' => 20,
                'onchange' => 'calcularIMC()',
                'required' => $required,
                'readonly' => true,
                'id' => 'IMC',
            )
        ));
        $this->add(array(
            'name' => 'ponerPliegues',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    '1' => 'Con Plieges',
                    '2' => 'Con Bioimpedancia',
                    '0' => 'NO',
                ),
                'disable_inrray_validator' => true,
            ),
            'attributes' => array(
                'onclick' => 'ponerValorPliegues()',
                'onchange' => 'calcularPorcentajeGrasa()',
                'class' => 'form-control',
                'required' => $required,
                'id' => 'ponerPliegues',
            )
        ));
        $this->add(array(
            'name' => 'tricipital',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-right',
                'maxlenngth' => 50,
                'onchange' => 'calcularPorcentajeGrasa()',
                'placeholder' => 'Pliegues en mm',
                'readonly' => !$required,
                'id' => 'tricipital',
            )
        ));
        $this->add(array(
            'name' => 'subescapular',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-right',
                'maxlenngth' => 50,
                'onchange' => 'calcularPorcentajeGrasa()',
                'placeholder' => 'Pliegues en mm',
                'readonly' => !$required,
                'id' => 'subescapular',
            )
        ));
        $this->add(array(
            'name' => 'supraliaco',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-right',
                'maxlenngth' => 50,
                'onchange' => 'calcularPorcentajeGrasa()',
                'placeholder' => 'Pliegues en mm',
                'readonly' => !$required,
                'id' => 'supraliaco',
            )
        ));
        $this->add(array(
            'name' => 'plieAbdominal',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-right',
                'maxlenngth' => 50,
                'onchange' => 'calcularPorcentajeGrasa()',
                'placeholder' => 'Pliegues en mm',
                'readonly' => !$required,
                'id' => 'plieAbdominal',
            )
        ));
        $this->add(array(
            'name' => 'cuadricipital',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-right',
                'maxlenngth' => 50,
                'onchange' => 'calcularPorcentajeGrasa()',
                'placeholder' => 'Pliegues en mm',
                'readonly' => !$required,
                'id' => 'cuadricipital',
            )
        ));
        $this->add(array(
            'name' => 'peroneal',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-right',
                'maxlenngth' => 50,
                'onchange' => 'calcularPorcentajeGrasa()',
                'placeholder' => 'Pliegues en mm',
                'readonly' => !$required,
                'id' => 'peroneal',
            )
        ));
        $this->add(array(
            'name' => 'porGrasa',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-right',
                'maxlenngth' => 50,
                'onchange' => 'calcularPorcentajeGrasa()',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'porGrasa',
            )
        ));
        $this->add(array(
            'name' => 'PGC',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-right',
                'maxlength' => 20,
                'onchange' => 'calcularPorcentajeGrasa()',
                'required' => $required,
                'readonly' => true,
                'id' => 'PGC',
            )
        ));
        $this->add(array(
            'name' => 'PGK',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-right',
                'maxlenngth' => 50,
                'onchange' => 'calcularPorcentajeGrasa()',
                'required' => $required,
                'readonly' => true,
                'id' => 'PGK',
            )
        ));
        $this->add(array(
            'name' => 'PMK',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-right',
                'maxlenngth' => 50,
                'onchange' => 'calcularPorcentajeGrasa()',
                'required' => $required,
                'readonly' => true,
                'id' => 'PMK',
            )
        ));

        $this->add(array(
            'name' => 'FECHA_MED_USU',
            'attributes' => array(
                'type' => 'date',
                'class' => 'form-control col-md-7 col-xs-12',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'FECHA_MED_USU',
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
