<?php

namespace Administracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Administracion\Modelo\Entidades\Rutinas;
use Administracion\Modelo\Entidades\Usuario;
use Administracion\Modelo\Entidades\Clienteempleado;
use Administracion\Modelo\Entidades\Ejercicios;
use Administracion\Modelo\Entidades\RutinasEjercicios;

class RutinasDAO extends AbstractTableGateway {

    protected $table = 'rutinas';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getRutinass($filtro = '') {
        $rutinass = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_rutina_id',
            'fk_usuario_id',
            'DESCRIP_RUTINA',
            'fechaRutina',
        ));
        $select->join('usuario', 'usuario.pk_usuario_id = rutinas.fk_usuario_id', array(
            'pk_usuario_id',
            'fk_clienteempleado_id',
            'nombreApellido',
            'genero',
        ));
        $select->join('clienteempleado', 'clienteempleado.pk_clienteempleado_id = usuario.fk_clienteempleado_id', array(
            'pk_clienteempleado_id',
            'identificacion',
            'nombre',
            'apellido',
        ));
        if ($filtro != '') {
            $select->where($filtro);
        }
        //print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $rutinaUsuario = array(
                'usuarioOBJ' => new Usuario($dato),
                'clienteempleadoOBJ' => new Clienteempleado($dato),
                'rutinasOBJ' => new Rutinas($dato),
            );
            $rutinass[] = $rutinaUsuario;
        }
        return $rutinass;
    }

    public function getRutinasUsuario($filtro = '') {
        $rutinass = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_rutina_id',
            'fk_usuario_id',
            'DESCRIP_RUTINA',
            'fechaRutina',
        ));
        $select->join('usuario', 'usuario.pk_usuario_id = rutinas.fk_usuario_id', array(
            'pk_usuario_id',
            'nombreApellido',
            'genero',
        ))->where(array('pk_usuario_id' => $filtro));
        if ($filtro != '') {
            $select->where($filtro);
        }
        //print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $rutinaUsuario = array(
                'usuarioOBJ' => new Usuario($dato),
                'rutinasOBJ' => new Rutinas($dato),
            );
            $rutinass[] = $rutinaUsuario;
        }
        return $rutinass;
    }

    public function getRutinas($pk_rutina_id = 0) {
        return new Rutinas($this->select(array('pk_rutina_id' => $pk_rutina_id))->current()->getArrayCopy());
    }

    public function getEjerciciosRutina($pk_rutina_id = 0) {
        $ejerciciosRutina = array();
        $this->table = 'rutinas_ejercicios';
        $select = new Select($this->table);
        $select->columns(array(
            'DIAS',
            'REPETICION',
            'ordenEjercicio',
        ))->join('ejercicios', 'rutinas_ejercicios.pk_ejercicio_id = ejercicios.pk_ejercicio_id', array(
            'pk_ejercicio_id',
            'NOM_EJER',
            'DESC_EJER',
            'RUTA_IMG_EJER',
            'zonaMuscular',
        ))->where("rutinas_ejercicios.pk_rutina_id = $pk_rutina_id ORDER BY DIAS DESC, ordenEjercicio ASC");
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $ejerciciosRutina[] = array(
                'ejercicioOBJ' => new Ejercicios($dato),
                'rutinasEjerciciosOBJ' => new RutinasEjercicios($dato),
            );
        }
        return $ejerciciosRutina;
    }

    public function guardar(Rutinas $rutinasOBJ = null) {
        $idRutinas = (int) $rutinasOBJ->getPk_rutina_id();
        if ($idRutinas == 0) {
            return $this->insert($rutinasOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idRutinas)) {
                return $this->update($rutinasOBJ->getArrayCopy(), array('pk_rutina_id' => $idRutinas));
            } else {
                return 0;
            }
        }
    }

    public function existeID($idRutinas = 0) {
        $id = (int) $idRutinas;
        $rowset = $this->select(array('pk_rutina_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

    public function anexarEjercicio($datos = array()) {
        $this->table = 'rutinas_ejercicios';
        $dias = $_REQUEST["dias"];
        $b = implode(",", $dias);
        return $this->insert(array(
                    'pk_rutina_id' => $datos['idRutinaSelect'],
                    'pk_ejercicio_id' => $datos['idEjercicioSelect'],
                    'DIAS' => $b,
                    'REPETICION' => $datos['repeticion'],
                    'ordenEjercicio' => $datos['orden'],
        ));
    }

    public function eliminarEjercicio($idEjercicio, $idRutina) {
        $this->table = 'rutinas_ejercicios';
        $resultado = $this->delete(array("pk_ejercicio_id" => (int) $idEjercicio,
            "pk_rutina_id" => $idRutina));
        return $resultado;
    }

}
