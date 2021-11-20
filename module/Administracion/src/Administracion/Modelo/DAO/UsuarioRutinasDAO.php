<?php

namespace Administracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Administracion\Modelo\Entidades\UsuarioRutinas;
use Administracion\Modelo\Entidades\Usuario;
use Administracion\Modelo\Entidades\Clienteempleado;
use Administracion\Modelo\Entidades\Rutinas;

class UsuarioRutinasDAO extends AbstractTableGateway {

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getUsuariosRutinas() {
        $ejerciciosRutina = array();
        $this->table = 'usuario_rutinas';
        $select = new Select($this->table);
        $select->columns(array(
            'pk_usuario_id',
            'pk_rutina_id',
        ))->join('usuario', 'usuario_rutinas.pk_usuario_id = usuario.pk_usuario_id', array(
            'pk_usuario_id',
            'fk_clienteempleado_id',
            'nombreApellido',
            'genero',
        ))->join('rutinas', 'usuario_rutinas.pk_rutina_id = rutinas.pk_rutina_id', array(
            'pk_rutina_id',
            'DESCRIP_RUTINA',
            'fechaRutina',
            'fk_usuario_id',
        ));
        $select->join('clienteempleado', 'clienteempleado.pk_clienteempleado_id = usuario.fk_clienteempleado_id', array(
            'pk_clienteempleado_id',
            'identificacion',
            'nombre',
            'apellido',
        ));
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $ejerciciosRutina[] = array(
                'usuarioRutinasOBJ' => new UsuarioRutinas($dato),
                'usuarioOBJ' => new Usuario($dato),
                'clienteempleadoOBJ' => new Clienteempleado($dato),
                'rutinasOBJ' => new Rutinas($dato),
            );
        }
        return $ejerciciosRutina;
    }

    public function getUsuarioRutinas($pk_rutina_id = 0) {
        return new UsuarioRutinas($this->select(array('pk_rutina_id' => $pk_rutina_id))->current()->getArrayCopy());
    }

    public function guardar(UsuarioRutinas $rutinasOBJ = null) {
        $idUsuarioRutinas = (int) $rutinasOBJ->getPk_rutina_id();
        if ($idUsuarioRutinas == 0) {
            return $this->insert($rutinasOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idUsuarioRutinas)) {
                return $this->update($rutinasOBJ->getArrayCopy(), array('pk_rutina_id' => $idUsuarioRutinas));
            } else {
                return 0;
            }
        }
    }

    public function guardarUsuarioRutina($usuarioRutinaOBJ) {
        $this->table = 'usuario_rutinas';
        $idUsuario = $_REQUEST["idUsuarioSelect"];
        $idRutina = $_REQUEST["idRutinaSelect"];
        return $this->insert(array(
                    'pk_usuario_id' => $idUsuario,
                    'pk_rutina_id' => $idRutina,
        ));
    }

    public function existeID($idUsuarioRutinas = 0) {
        $id = (int) $idUsuarioRutinas;
        $rowset = $this->select(array('pk_rutina_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

    public function eliminarEjercicio($idEjercicio, $idRutina) {
        $this->table = 'rutinas_ejercicios';
        $resultado = $this->delete(array("pk_ejercicio_id" => (int) $idEjercicio,
            "pk_rutina_id" => $idRutina));
        return $resultado;
    }

    public function getRutinasUsuarioId($filtro = '') {
        $rutinass = array();
        $select = new Select($this->table = 'usuario_rutinas');
        $select->columns(array(
            'pk_rutina_id',
            'pk_usuario_id',
            'fechaAsignacion',
        ));
        $select->join('rutinas', 'rutinas.pk_rutina_id = usuario_rutinas.pk_rutina_id', array(
            'pk_rutina_id',
            'DESCRIP_RUTINA',
            'fechaRutina',
            'fk_usuario_id',
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
                'usuarioRutinasOBJ' => new UsuarioRutinas($dato),
            );
            $rutinass[] = $rutinaUsuario;
        }
        return $rutinass;
    }

}
