<?php

namespace Administracion\Formularios;

use Zend\Form\Element;
use Zend\Form\Form;

class ProductoForm extends Form {

    public function __construct($action = '', $onsubmit = '', $required = true) {
        parent::__construct('formProducto');
        $this->setAttribute('method', 'post');
        $this->setAttribute('data-toggle', 'validator');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal form-label-left');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('action', $action);
        $this->setAttribute('onsubmit', $onsubmit);

        $this->add(array(
            'name' => 'pk_producto_id',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'pk_producto_id',
            )
        ));

        $this->add(array(
            'name' => 'codigoBarras',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'maxlength' => 50,
                'style' => 'text-transform: uppercase',
                'required' => !$required,
                'readonly' => !$required,
                'id' => 'codigoBarras',
            )
        ));
        $this->add(array(
            'name' => 'nombreProducto',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'maxlength' => 50,
                'style' => 'text-transform: uppercase',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'nombreProducto',
            )
        ));
        $this->add(array(
            'name' => 'descripcion',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control col-md-7 col-xs-12',
                'maxlength' => 500,
                'required' => !$required,
                'readonly' => !$required,
                'id' => 'descripcion',
            )
        ));
        $this->add(array(
            'name' => 'cantidad',
            'attributes' => array(
                'type' => 'number',
                'min' => 1,
                'max' => 1000,
                'placeholder' => 'Ingrese solo numeros',
                'class' => 'form-control col-md-7 col-xs-12',
                'required' => $required,
                'readonly' => !$required,
                'id' => 'cantidad',
            )
        ));

        $this->add(array(
            'name' => 'precioCosto',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'placeholder' => 'Ingrese solo numeros',
                'pattern' => '[0-9]{1,20}',
                'maxlength' => 50,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'precioCosto',
            )
        ));
        $this->add(array(
            'name' => 'precioVenta',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'placeholder' => 'Ingrese solo numeros',
                'pattern' => '[0-9]{1,20}',
                'maxlength' => 50,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'precioVenta',
            )
        ));
        $this->add(array(
            'name' => 'fechaadquisicion',
            'attributes' => array(
                'type' => 'date',
                'class' => 'form-control col-md-7 col-xs-12',
                'maxlength' => 500,
                'required' => $required,
                'readonly' => !$required,
                'id' => 'fechaadquisicion',
            )
        ));
        $this->add(array(
            'name' => 'proveedor',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'maxlength' => 500,
                'required' => !$required,
                'readonly' => !$required,
                'id' => 'proveedor',
            )
        ));
        $this->add(array(
            'name' => 'numfactura',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'maxlength' => 500,
                'required' => !$required,
                'readonly' => !$required,
                'id' => 'numfactura',
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
            'name' => 'fechahorareg',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'fechahorareg',
            )
        ));
        $this->add(array(
            'name' => 'fechahoramod',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'readonly' => true,
                'id' => 'fechahoramod',
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
                'readonly' => $required,
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
                //'required' => $required,
                'readonly' => True,
                'id' => 'valorTotal',
            )
        ));

        $this->add(array(
            'name' => 'imagenProducto',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control col-md-7 col-xs-12',
                'placeholder' => 'Ingrese solo numeros',
                'pattern' => '[0-9]{1,20}',
                'maxlength' => 50,
//                'required' => $required,
                'readonly' => $required,
                'id' => 'imagenProducto',
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
