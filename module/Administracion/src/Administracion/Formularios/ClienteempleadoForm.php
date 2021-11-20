<?php

namespace Administracion\Formularios;

use Zend\Form\Element;
use Zend\Form\Form;

class ClienteempleadoForm extends Form {

    public function __construct($action = '', $onsubmit = '', $required = true) {
        parent::__construct('formClienteempleado');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);

        $this->add(array(
            'name' => 'pk_clienteempleado_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'pk_clienteempleado_id',
            )
        ));

        $this->add(array(
            'name' => 'nombre',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-left',
                'maxlength' => 50,
                'style' => 'text-transform: uppercase',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'nombre',
            )
        ));
        $this->add(array(
            'name' => 'apellido',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-left',
                'maxlength' => 50,
                'style' => 'text-transform: uppercase',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'apellido',
            )
        ));
        $this->add(array(
            'name' => 'tipoIdentificacion',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    'Cedula' => 'Cedula',
                    'Tarjeta De Identidad' => 'Tarjeta De Identidad',
                    'Pasaporte' => 'Pasaporte',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'id' => 'tipoIdentificacion',
            )
        ));
        $this->add(array(
            'name' => 'identificacion',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'placeholder' => 'Solo Numeros',
                'pattern' => '[0-9]{1,20}',
                'maxlength' => 20,
                'onchange' => 'existeIdentificacion()',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'identificacion',
            )
        ));
        $this->add(array(
            'name' => 'fechaNacimiento',
            'attributes' => array(
                'type' => 'date',
                'class' => 'form-control col-md-7 col-xs-12',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'fechaNacimiento',
            )
        ));
        $this->add(array(
            'name' => 'ocupacion',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-left',
                'maxlength' => 50,
                'style' => 'text-transform: uppercase',
                'required' => !$required,
                'readonly' => !$required,
                'id' => 'ocupacion',
            )
        ));
        $this->add(array(
            'name' => 'telefono',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-left',
                'maxlength' => 50,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'telefono',
            )
        ));
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'email',
                'class' => 'form-control has-feedback-left',
                'maxlength' => 50,
                'required' => !$required,
                'readonly' => !$required,
                'id' => 'email',
            )
        ));

        $this->add(array(
            'name' => 'direccion',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-left',
                'maxlength' => 50,
                'style' => 'text-transform: uppercase',
                'required' => !$required,
                'readonly' => !$required,
                'id' => 'direccion',
            )
        ));


        $this->add(array(
            'name' => 'genero',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    'Femenino' => 'Femenino',
                    'Masculino' => 'Masculino',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'id' => 'genero',
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
                'id' => 'estado',
            )
        ));
        $this->add(array(
            'name' => 'condicionFisica',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    'Normal' => 'Normal',
                    'Deportista' => 'Deportista',
                    'Sedentaria' => 'Sedentaria',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'id' => 'condicionFisica',
            )
        ));
        $this->add(array(
            'name' => 'OBJETIVOS',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione ...',
                'value_options' => array(
                    'Bajar de peso' => 'Bajar de peso',
                    'Ganar masa muscular' => 'Ganar masa muscular',
                    'Tonificar el musculo' => 'Tonificar el musculo',
                    'Mejorar la condición fisica' => 'Mejorar la condición fisica',
                    'Terapia' => 'Terapia',
                ),
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'class' => 'form-control',
                'required' => $required,
                'id' => 'OBJETIVOS',
            )
        ));
        $this->add(array(
            'name' => 'rutaFotoPerfil',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-left',
                'maxlength' => 50,
                'required' => FALSE,
                'readonly' => TRUE,
                'id' => 'rutaFotoPerfil',
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
