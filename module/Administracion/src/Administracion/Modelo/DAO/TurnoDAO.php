<?php

namespace Administracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Administracion\Modelo\Entidades\Turno;
use Administracion\Modelo\Entidades\Usuario;
use Administracion\Modelo\Entidades\Clienteempleado;

class TurnoDAO extends AbstractTableGateway {

    protected $table = 'turno';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getTurnos($filtro = '') {
        $turno = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_turno_id',
            'fk_usuario_id',
            'valorHora',
            'fechaInicio',
            'fechaFinal',
            'horasTurno',
            'pagoTotal',
        ));
        $select->join('usuario', 'usuario.pk_usuario_id = turno.fk_usuario_id', array(
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
        ));
        $select->join('clienteempleado', 'clienteempleado.pk_clienteempleado_id = usuario.fk_clienteempleado_id', array(
            'pk_clienteempleado_id',
            'identificacion',
            'nombre',
            'apellido',
            'genero',
        ));
        if ($filtro != '') {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $turnoInstructor = array(
                'turnoOBJ' => new Turno($dato),
                'usuarioOBJ' => new Usuario($dato),
                'clienteempleadoOBJ' => new Clienteempleado($dato),
            );
            $turno[] = $turnoInstructor;
        }
        return $turno;
    }

    public function getTurno($idTurno = 0) {
        return new Turno($this->select(array('pk_turno_id' => $idTurno))->current()->getArrayCopy());
    }

    public function guardar(Turno $turnoOBJ = null) {
        $idTurno = (int) $turnoOBJ->getPk_turno_id();
        if ($idTurno == 0) {
            return $this->insert($turnoOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idTurno)) {
                return $this->update($turnoOBJ->getArrayCopy(), array('pk_turno_id' => $idTurno));
            } else {
                return 0;
            }
        }
    }

    public function existeID($idTurno = 0) {
        $id = (int) $idTurno;
        $rowset = $this->select(array('pk_turno_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

}
