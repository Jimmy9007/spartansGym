<?php

namespace Administracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Administracion\Modelo\Entidades\Asistencia;
use Administracion\Modelo\Entidades\Usuario;
use Administracion\Modelo\Entidades\Clienteempleado;
use Administracion\Modelo\Entidades\Mensualidad;

class AsistenciaDAO extends AbstractTableGateway {

    protected $table = 'asistencia';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getAsistencias($filtro = '') {
        $asistencias = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_asistencia_id',
            'fk_usuario_id',
            'FECHA_ASIS',
        ));
        $select->join('usuario', 'usuario.pk_usuario_id = asistencia.fk_usuario_id', array(
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
        $select->join('mensualidad', 'usuario.pk_usuario_id = mensualidad.fk_usuario_id', array(
            'pk_mensualidad_id',
            'FECHA_MENS',
            'FECHA_MENS_FIN',
            'diasPreaviso' => new Expression('DATEDIFF(mensualidad.FECHA_MENS_FIN, CURDATE())'),
            'fk_usuario_id',
        ));
        if ($filtro != '') {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $asistenciaUsuario = array(
                'usuarioOBJ' => new Usuario($dato),
                'asistenciaOBJ' => new Asistencia($dato),
                'clienteempleadoOBJ' => new Clienteempleado($dato),
                'mensualidadOBJ' => new Mensualidad($dato),
                'diasPreaviso' => $dato['diasPreaviso'],
            );
            $asistencias[] = $asistenciaUsuario;
        }
        return $asistencias;
    }

    public function getAsistencia($pk_asistencia_id = 0) {
        return new Asistencia($this->select(array('pk_asistencia_id' => $pk_asistencia_id))->current()->getArrayCopy());
    }

    public function guardar(Asistencia $asistenciaOBJ = null) {
        $idAsistencia = (int) $asistenciaOBJ->getpk_asistencia_id();
        if ($idAsistencia == 0) {
            return $this->insert($asistenciaOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idAsistencia)) {
                return $this->update($asistenciaOBJ->getArrayCopy(), array('pk_asistencia_id' => $idAsistencia));
            } else {
                return 0;
            }
        }
    }

    public function existeID($idAsistencia = 0) {
        $id = (int) $idAsistencia;
        $rowset = $this->select(array('pk_asistencia_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

//    public function getPreguntasFormularioSeleccionar($filtro = '') {
//        $asistencias = array();
//        $select = new Select($this->table);
//        $select->columns(array(
//            'pk_pregunta_id',
//            'pregunta',
//            'tipoPregunta',
//            'clasificacion',
//            'registradoPor',
//            'fechaHoraReg',
//            'estado',
//        ));
//        if ($filtro != '') {
//            $select->where($filtro);
//        }
////        print $select->getSqlString();
//        $datos = $this->selectWith($select)->toArray();
//        foreach ($datos as $dato) {
//            $asistencias[] = new Pregunta($dato);
//        }
//        return $asistencias;
//    }
//    public function getOpcionPregunta($idPregunta = 0) {
//        $asistencias = array();
//        $select = new Select($this->table);
//        $select->columns(array(
//                    'pk_opcion_id',
//                    'opcion',
//                    'puntos',
//                    'estado',
//                    'registradoPor',
//                    'fechaHoraReg',
//                ))
//                ->join('pregunta_opcion', 'pregunta_opcion.fk_opcion_id = opcion.pk_opcion_id', array())
//                ->where('pregunta_opcion.fk_pregunta_id = ' . $idPregunta);
////        print $select->getSqlString();
//        $datos = $this->selectWith($select)->toArray();
//        foreach ($datos as $dato) {
//            $asistencias[] = new Opcion($dato);
//        }
//        return $asistencias;
//    }
}
