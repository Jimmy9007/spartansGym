<?php

namespace Administracion\Formularios;

use Zend\Form\Element;
use Zend\Form\Form;

class UsuarioForm extends Form {

    public function __construct($action = '', $onsubmit = '', $required = true, $listaClienteEmpleado = array(), $listaRoles = array()) {
        parent::__construct('formUsuario');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);



        $this->add(array(
            'name' => 'pk_usuario_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'pk_usuario_id',
            )
        ));
        $this->add(array(
            'name' => 'fk_clienteempleado_id',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione...',
                'value_options' => $listaClienteEmpleado,
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'disabled' => FALSE,
                'required' => TRUE,
                'class' => 'form-control',
                'onchange' => 'getLogin(this.value)',
                'id' => 'fk_clienteempleado_id',
            )
        ));
        $this->add(array(
            'name' => 'fk_rol_id',
            'type' => 'Select',
            'options' => array(
                'empty_option' => 'Seleccione...',
                'value_options' => $listaRoles,
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'disabled' => FALSE,
                'required' => TRUE,
                'class' => 'form-control',
                'id' => 'fk_rol_id',
            )
        ));

        $this->add(array(
            'name' => 'nombreApellido',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control has-feedback-left',
                'maxlength' => 50,
                'style' => 'text-transform: uppercase',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'nombreApellido',
            )
        ));
        $this->add(array(
            'name' => 'login',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => !$required,
                'required' => $required,
                'maxlength' => 20,
                'style' => 'text-transform:lowercase',
                'onchange' => 'existeLogin(this.value)',
                'id' => 'loginRegistro',
            )
        ));
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'maxlength' => 500,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'password',
            )
        ));
        $this->add(array(
            'name' => 'passwordseguro',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'maxlength' => 500,
                'onblur' => 'verificarPassword()',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'passwordseguro',
            )
        ));
        $this->add(array(
            'name' => 'estado',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'readonly' => true,
                'id' => 'estado',
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
            'name' => 'rutaFotoPerfil',
            'attributes' => array(
                'type' => 'file',
                'class' => 'form-control col-md-7 col-xs-12',
                'required' => !$required,
                'readonly' => !$required,
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
                'class' => 'btn btn-warning',
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
