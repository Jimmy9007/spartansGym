<?php

namespace Administracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Administracion\Modelo\Entidades\Ejercicios;
use Administracion\Modelo\Entidades\RutinasEjercicios;

class EjerciciosDAO extends AbstractTableGateway {

    protected $table = 'ejercicios';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getEjercicioss($filtro = '') {
        $ejercicioss = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_ejercicio_id',
            'NOM_EJER',
            'DESC_EJER',
            'RUTA_IMG_EJER',
            'zonaMuscular',
        ));
        if ($filtro != '') {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $ejercicioss[] = new Ejercicios($dato);
        }
        return $ejercicioss;
    }

    public function getEjercicios($pk_ejercicio_id = 0) {
        return new Ejercicios($this->select(array('pk_ejercicio_id' => $pk_ejercicio_id))->current()->getArrayCopy());
    }

    public function guardar(Ejercicios $ejerciciosOBJ = null) {
        $idEjercicio = (int) $ejerciciosOBJ->getpk_ejercicio_id();
        if ($idEjercicio == 0) {
            return $this->insert($ejerciciosOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idEjercicio)) {
                return $this->update($ejerciciosOBJ->getArrayCopy(), array('pk_ejercicio_id' => $idEjercicio));
            } else {
                return 0;
            }
        }
    }

    public function existeID($idEjercicios = 0) {
        $id = (int) $idEjercicios;
        $rowset = $this->select(array('pk_ejercicio_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

    public function getEjerciciosRutinas($idRutina = 0) {
        $ejercicios = array();
        $select = new Select($this->table);
        $select->columns(array(
                    'pk_ejercicio_id',
                    'NOM_EJER',
                    'DESC_EJER',
                    'RUTA_IMG_EJER',
                    'zonaMuscular',
                ))
                ->join('rutinas_ejercicios', 'rutinas_ejercicios.pk_ejercicio_id = ejercicios.pk_ejercicio_id', array(
                    'REPETICION',
                    'DIAS',
                    'ordenEjercicio',
                ))->where('rutinas_ejercicios.pk_rutina_id = ' . $idRutina);
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $rutinaUsuario = array(
                'ejercicioOBJ' => new Ejercicios($dato),
                'rutinasEjerciciosOBJ' => new RutinasEjercicios($dato),
            );
            $ejercicios[] = $rutinaUsuario;
        }
        return $ejercicios;
    }

    public function getEjerciciosDetalle($id = 0) {
        $ejercicios = null;
        $select = new Select($this->table);
        $select->columns(array(
                    'pk_ejercicio_id',
                    'NOM_EJER',
                    'DESC_EJER',
                    'RUTA_IMG_EJER',
                    'zonaMuscular',
                ))
                ->where(array('pk_ejercicio_id' => $id));
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $ejercicios = new Ejercicios($dato);
        }
        return $ejercicios;
    }

    public function getDetalleEjercicio($id = 0) {
        $Ejercicio = null;
        $select = new Select($this->table);
        $select->columns(array(
                    'pk_ejercicio_id',
                    'NOM_EJER',
                    'DESC_EJER',
                    'RUTA_IMG_EJER',
                    'zonaMuscular',
                ))
                ->where(array('pk_ejercicio_id' => $id))
                ->limit(1);
        // print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $Ejercicio = new Ejercicios($dato);
        }
        return $Ejercicio;
    }

}
