<?php

namespace Administracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Administracion\Modelo\Entidades\Evento;

class EventoDAO extends AbstractTableGateway {

    protected $table = 'evento';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getEventos($filtro = '') {
        $eventos = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_evento_id',
            'title',
            'descripcion',
            'start',
            'color',
            'textColor',
            'end',
        ));
        if ($filtro != '') {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $eventos[] = new Evento($dato);
        }
        return $eventos;
    }

    public function getEvento($idEvento = 0) {
        return new Evento($this->select(array('pk_evento_id' => $idEvento))->current()->getArrayCopy());
    }

    public function guardar(Evento $eventoOBJ = null) {
        $idEvento = (int) $eventoOBJ->getPk_evento_id();
        if ($idEvento == 0) {
            return $this->insert($eventoOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idEvento)) {
                return $this->update($eventoOBJ->getArrayCopy(), array('pk_evento_id' => $idEvento));
            } else {
                return 0;
            }
        }
    }

    public function existeID($idEvento = 0) {
        $id = (int) $idEvento;
        $rowset = $this->select(array('pk_evento_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

    public function eliminar($idEvento) {
        $resultado = $this->delete(array("pk_evento_id" => (int) $idEvento));
        return $resultado;
    }

    public function moverEvento(Evento $eventoOBJ = null) {
        $idEvento = (int) $eventoOBJ->getPk_evento_id();
        if ($idEvento == 0) {
            return $this->insert($eventoOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idEvento)) {
                return $this->update($eventoOBJ->getArrayCopy(), array('pk_evento_id' => $idEvento));
            } else {
                return 0;
            }
        }
    }

}

?>
