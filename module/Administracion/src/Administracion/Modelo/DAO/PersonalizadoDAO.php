<?php

namespace Administracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Administracion\Modelo\Entidades\Personalizado;
use Administracion\Modelo\Entidades\Usuario;

class PersonalizadoDAO extends AbstractTableGateway {

    protected $table = 'personalizado';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getPersonalizados($filtro = '') {
        $personalizados = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_personalizado_id',
            'fk_usuario_id',
            'fechaPersonalizado',
            'valorPersonalizado',
            'direccionPersonalizado',
            'latitud',
            'longitud',
        ));
        $select->join('usuario', 'usuario.pk_usuario_id = personalizado.fk_usuario_id', array(
            'pk_usuario_id',
            'nombreApellido',
            'genero',
        ));
        if ($filtro != '') {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $persoUsuario = array(
                'usuarioOBJ' => new Usuario($dato),
                'personalizadoOBJ' => new Personalizado($dato),
            );
            $personalizados[] = $persoUsuario;
        }
        return $personalizados;
    }

    public function getPersonalizado($idPersonalizado = 0) {
        return new Personalizado($this->select(array('pk_personalizado_id' => $idPersonalizado))->current()->getArrayCopy());
    }

    public function guardar(Personalizado $personalizadoOBJ = null) {
        $idPersonalizado = (int) $personalizadoOBJ->getPk_personalizado_id();
        if ($idPersonalizado == 0) {
            return $this->insert($personalizadoOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idPersonalizado)) {
                return $this->update($personalizadoOBJ->getArrayCopy(), array('pk_personalizado_id' => $idPersonalizado));
            } else {
                return 0;
            }
        }
    }

    public function existeID($idPersonalizado = 0) {
        $id = (int) $idPersonalizado;
        $rowset = $this->select(array('pk_personalizado_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

}
