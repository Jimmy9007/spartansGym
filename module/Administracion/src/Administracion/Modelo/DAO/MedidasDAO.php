<?php

namespace Administracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Administracion\Modelo\Entidades\Medidas;
use Administracion\Modelo\Entidades\Usuario;
use Administracion\Modelo\Entidades\Clienteempleado;

class MedidasDAO extends AbstractTableGateway {

    protected $table = 'medidas';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getMedidass($filtro = '') {
        $medidass = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_medida_id',
            'fk_usuario_id',
            'FECHA_MED_USU',
            'ESTATURA',
            'PESO',
            'IMC',
            'PGC',
        ));
        $select->join('usuario', 'usuario.pk_usuario_id = medidas.fk_usuario_id', array(
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
            'genero',
        ));

        if ($filtro != '') {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $medidasUsuario = array(
                'usuarioOBJ' => new Usuario($dato),
                'clienteempleadoOBJ' => new Clienteempleado($dato),
                'medidasOBJ' => new Medidas($dato),
            );
            $medidass[] = $medidasUsuario;
        }
        return $medidass;
    }

    public function getMedidasUsuario($filtro = '') {
        $medidass = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_medida_id',
            'ESTATURA',
            'PESO',
            'PECHO',
            'BICEPS',
            'HOMBRO',
            'ANTEBRAZO',
            'CINTURA',
            'CADERA',
            'PIERNA',
            'piernaB',
            'PANTORRILLA',
            'IMC',
            'tricipital',
            'subescapular',
            'supraliaco',
            'plieAbdominal',
            'cuadricipital',
            'peroneal',
            'porGrasa',
            'PGC',
            'PGK',
            'PMK',
            'fk_usuario_id',
            'FECHA_MED_USU',
            'fechaMedias' => new Expression('DATE_FORMAT(FECHA_MED_USU, "%Y%m%d")'),
        ));
        $select->join('usuario', 'usuario.pk_usuario_id = medidas.fk_usuario_id', array(
            'pk_usuario_id',
            'nombreApellido',
            'genero',
        ))->where(array('pk_usuario_id' => $filtro));

        if ($filtro != '') {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $medidasUsuario = array(
                'usuarioOBJ' => new Usuario($dato),
                'medidasOBJ' => new Medidas($dato),
                'fechaMedias' => $dato['fechaMedias'],
            );
            $medidass[] = $medidasUsuario;
        }
        return $medidass;
    }

    public function getMedidas($pk_medida_id = 0) {
        return new Medidas($this->select(array('pk_medida_id' => $pk_medida_id))->current()->getArrayCopy());
    }

    public function guardar(Medidas $medidasOBJ = null) {
        $idMedidas = (int) $medidasOBJ->getPk_medida_id();
        if ($idMedidas == 0) {
            return $this->insert($medidasOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idMedidas)) {
                return $this->update($medidasOBJ->getArrayCopy(), array('pk_medida_id' => $idMedidas));
            } else {
                return 0;
            }
        }
    }

    public function existeID($idMedidas = 0) {
        $id = (int) $idMedidas;
        $rowset = $this->select(array('pk_medida_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

}
