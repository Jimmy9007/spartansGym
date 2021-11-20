<?php

namespace Administracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Administracion\Modelo\Entidades\Rol;

class RolDAO extends AbstractTableGateway {

    protected $table = 'rol';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getRols($filtro = '') {
        $rols = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_rol_id',
            'rol',
            'descripcion',
            'estado',
        ));
        if ($filtro != '') {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $rols[] = new Rol($dato);
        }
        return $rols;
    }

    public function getRol($idRol = 0) {
        return new Rol($this->select(array('pk_rol_id' => $idRol))->current()->getArrayCopy());
    }

    public function guardar(Rol $rolOBJ = null) {
        $idRol = (int) $rolOBJ->getPk_rol_id();
        if ($idRol == 0) {
            return $this->insert($rolOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idRol)) {
                return $this->update($rolOBJ->getArrayCopy(), array('pk_rol_id' => $idRol));
            } else {
                return 0;
            }
        }
    }

    public function existeID($idRol = 0) {
        $id = (int) $idRol;
        $rowset = $this->select(array('pk_rol_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

}
