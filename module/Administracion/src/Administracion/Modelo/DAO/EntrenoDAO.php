<?php

namespace Administracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Administracion\Modelo\Entidades\Entreno;

class EntrenoDAO extends AbstractTableGateway {

    protected $table = 'entreno';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getEntrenos($filtro = '') {
        $entrenos = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_entreno_id',
            'nombreUsuario',
            'fechaHoraEntreno',
            'valor',
        ));
        if ($filtro != '') {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $entrenos[] = new Entreno($dato);
        }
        return $entrenos;
    }

    public function getEntreno($idEntreno = 0) {
        return new Entreno($this->select(array('pk_entreno_id' => $idEntreno))->current()->getArrayCopy());
    }

    public function guardar(Entreno $entrenoOBJ = null) {
        $idEntreno = (int) $entrenoOBJ->getPk_entreno_id();
        if ($idEntreno == 0) {
            return $this->insert($entrenoOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idEntreno)) {
                return $this->update($entrenoOBJ->getArrayCopy(), array('pk_entreno_id' => $idEntreno));
            } else {
                return 0;
            }
        }
    }

    public function existeID($idEntreno = 0) {
        $id = (int) $idEntreno;
        $rowset = $this->select(array('pk_entreno_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

}
