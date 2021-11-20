<?php

namespace Administracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Administracion\Modelo\Entidades\ProbaLluvia;

class ProbaLluviaDAO extends AbstractTableGateway {

    protected $table = 'proballuvia';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getProbaLluvias($filtro = '') {
        $probaLluvia = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_proballuvia_id',
            'dias',
            'lluviosos',
            'probabilidad',
        ));
        if ($filtro != '') {
            $select->where($filtro);
        }
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $probaLluvia[] = new ProbaLluvia($dato);
        }
        return $probaLluvia;
    }

    public function getKnnProbaLluvia() {
        $probaLluvia = array();
        $select = new Select($this->table);
        $select->columns(array(
                    'pk_proballuvia_id',
                    'dias',
                    'lluviosos',
                    'probabilidad',
                ))
                ->order('pk_proballuvia_id', 'asc');

        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $probaLluvia[] = new ProbaLluvia($dato);
        }
        return $probaLluvia;
    }

    public function getProbaLluvia($pk_proballuvia_id = 0) {
        return new ProbaLluvia($this->select(array('pk_proballuvia_id' => $pk_proballuvia_id))->current()->getArrayCopy());
    }

    public function guardar(ProbaLluvia $probaLluviaOBJ = null) {
        $idProbaLluvia = (int) $probaLluviaOBJ->getPk_proballuvia_id();
        if ($idProbaLluvia == 0) {
            return $this->insert($probaLluviaOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idProbaLluvia)) {
                return $this->update($probaLluviaOBJ->getArrayCopy(), array('pk_proballuvia_id' => $idProbaLluvia));
            } else {
                return 0;
            }
        }
    }

    public function existeID($idProbaLluvia = 0) {
        $id = (int) $idProbaLluvia;
        $rowset = $this->select(array('pk_proballuvia_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

    public function eliminar($idProbaLluvia) {
        $resultado = $this->delete(array("pk_proballuvia_id" => (int) $idProbaLluvia));
        return $resultado;
    }

}
