<?php

namespace Administracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Administracion\Modelo\Entidades\Clienteempleado;
use Administracion\Modelo\Entidades\Usuario;

class ClienteempleadoDAO extends AbstractTableGateway {

    protected $table = 'clienteempleado';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getClienteempleados($filtro = '') {
        $clienteempleados = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_clienteempleado_id',
            'nombre',
            'apellido',
            'tipoIdentificacion',
            'identificacion',
            'fechaNacimiento',
            'ocupacion',
            'email',
            'telefono',
            'direccion',
            'estado',
            'genero',
            'condicionFisica',
            'OBJETIVOS',
            'rutaFotoPerfil',
        ));
        if ($filtro != '') {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $clienteempleados[] = new Clienteempleado($dato);
        }
        return $clienteempleados;
    }

    public function getClienteempleado($idClienteempleado = 0) {
        return new Clienteempleado($this->select(array('pk_clienteempleado_id' => $idClienteempleado))->current()->getArrayCopy());
    }

    public function getPerfilUsuario($filtro = '') {
        $usuario = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_clienteempleado_id',
            'nombre',
            'apellido',
            'tipoIdentificacion',
            'identificacion',
            'fechaNacimiento',
            'ocupacion',
            'email',
            'telefono',
            'direccion',
            'estado',
            'genero',
            'condicionFisica',
            'OBJETIVOS',
            'rutaFotoPerfil',
        ));
        $select->join('usuario', 'clienteempleado.pk_clienteempleado_id = usuario.fk_clienteempleado_id', array(
                    'pk_usuario_id',
                    'fk_clienteempleado_id',
                    'fk_rol_id',
                    'nombreApellido',
                    'login',
                    'password',
                    'passwordseguro',
                    'estado',
                    'genero',
                    'rutaFotoPerfil',
                ))
                ->where(array('pk_clienteempleado_id' => $filtro));
        if ($filtro != '') {
            $select->where($filtro);
        }
        //print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $perfilUsuario = array(
                'clienteempleadoOBJ' => new Clienteempleado($dato),
                'usuarioOBJ' => new usuario($dato),
            );
            $usuario[] = $perfilUsuario;
        }
        return $usuario;
    }

    public function guardar(Clienteempleado $clienteempleadoOBJ = null) {
        $idClienteempleado = (int) $clienteempleadoOBJ->getPk_clienteempleado_id();
        if ($idClienteempleado == 0) {
            return $this->insert($clienteempleadoOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idClienteempleado)) {
                return $this->update($clienteempleadoOBJ->getArrayCopy(), array('pk_clienteempleado_id' => $idClienteempleado));
            } else {
                return 0;
            }
        }
    }

    public function existeID($idClienteempleado = 0) {
        $id = (int) $idClienteempleado;
        $rowset = $this->select(array('pk_clienteempleado_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }
//------------------------------------------------------------------------------

    public function existeIdentificacion($identificacion = '') {
        $this->table = 'clienteempleado';
        $rowset = $this->select(array('identificacion' => $identificacion));
        $row = $rowset->current();
        if (!$row) {
            return 0;
        }
        return 1;
    }

//------------------------------------------------------------------------------

}
