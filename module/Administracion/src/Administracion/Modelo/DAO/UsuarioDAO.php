<?php

namespace Administracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Update;
use Administracion\Modelo\Entidades\Usuario;
use Administracion\Modelo\Entidades\Clienteempleado;
use Administracion\Modelo\Entidades\Rol;
use Administracion\Modelo\Entidades\Medidas;

class UsuarioDAO extends AbstractTableGateway {

    protected $table = 'usuario';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getUsuarios($filtro = '') {
        $usuarios = array();
        $select = new Select($this->table);
        $select->columns(array(
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
            'nombre',
            'apellido',
            'tipoIdentificacion',
            'identificacion',
            'fechaNacimiento',
            'ocupacion',
            'email',
            'telefono',
            'direccion',
            'estado',
            'genero',
            'condicionFisica',
            'OBJETIVOS',
            'rutaFotoPerfil',
        ));
        $select->join('rol', 'rol.pk_rol_id = usuario.fk_rol_id', array(
            'pk_rol_id',
            'rol',
            'descripcion',
        ));
        if ($filtro != '') {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $clienteempleado = array(
                'usuarioOBJ' => new Usuario($dato),
                'clienteempleadoOBJ' => new Clienteempleado($dato),
                'rolOBJ' => new Rol($dato),
            );
            $usuarios[] = $clienteempleado;
        }
        return $usuarios;
    }

    public function getCountUsuarios() {
        $select = array();
        $select = new Select($this->table);
        $select->columns(array(
            'totalUser' => new Expression('count(pk_usuario_id)'),
        ))->where("estado = 'Activo'");
        $datos = $this->selectWith($select)->toArray();
        return $datos[0]['totalUser'];
    }

    public function getCountMujeres() {
        $select = array();
        $select = new Select($this->table);
        $select->columns(array(
            'genero',
            'totalMujeres' => new Expression('COUNT(genero)'),
        ))->where("genero = 'Femenino' AND estado = 'Activo'");
        $datos = $this->selectWith($select)->toArray();
        return $datos[0]['totalMujeres'];
    }

    public function getCountHombres() {
        $select = array();
        $select = new Select($this->table);
        $select->columns(array(
            'genero',
            'totalHombres' => new Expression('COUNT(genero)'),
        ))->where("genero = 'Masculino' AND estado = 'Activo'");
        $datos = $this->selectWith($select)->toArray();
        return $datos[0]['totalHombres'];
    }

    public function getDetalleUsuario($id = 0) {
        $Usuarios = null;
        $select = new Select($this->table);
        $select->columns(array(
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
                ))
                ->where(array('pk_usuario_id' => $id))
                ->limit(1);
        // print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $Usuarios = new Usuario($dato);
        }
        return $Usuarios;
    }

    public function getMedidasUsuario($idUsuario = 0) {
        $select = new Select($this->table);
        $select->columns(array(
            'pk_usuario_id',
            'nombreApellido',
            'genero',
        ))->join('medidas', 'medidas.fk_usuario_id = usuario.pk_usuario_id', array(
            'pk_medida_id',
            'fechaMedias' => new Expression('DATE_FORMAT(FECHA_MED_USU, "%Y%m%d")'),
            'PGC',
            'IMC',
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
            'porGrasa',
            'PGK',
            'PMK',
        ))->where("usuario.pk_usuario_id = $idUsuario");
        //print $select->getSqlString();
        return $this->selectWith($select)->toArray();
    }

    public function getUsuario($pk_usuario_id = 0) {
        return new Usuario($this->select(array('pk_usuario_id' => $pk_usuario_id))->current()->getArrayCopy());
    }

    public function existeLogin($login = '') {
        $select = new Select($this->table);
        $select->columns(array('existe' => new Expression('COUNT(pk_usuario_id)')))
                ->where(array('login' => $login));
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        if ($datos[0]['existe'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function guardar(Usuario $usuarioOBJ = null) {
        $idUsuario = (int) $usuarioOBJ->getPk_usuario_id();
        if ($idUsuario == 0) {
            return $this->insert($usuarioOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idUsuario)) {
                return $this->update($usuarioOBJ->getArrayCopy(), array('pk_usuario_id' => $idUsuario));
            } else {
                return 0;
            }
        }
    }

    public function existeID($idUsuario = 0) {
        $id = (int) $idUsuario;
        $rowset = $this->select(array('pk_usuario_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

    public function cambiarcontrasena($idUsuario = 0, $newpassword = '', $passwordseguro = '') {
        $update = new Update($this->table);
        $update->set(array(
            'password' => $newpassword,
            'passwordseguro' => $passwordseguro,
        ))->where(array('pk_usuario_id' => $idUsuario));
        return $this->updateWith($update);
    }
    
    public function usuarioHuella($idUsuario = 0) {
        $this->table = 'usuario_huella';
        $select = new Select($this->table);
        $select->columns(array(
            'imgHuella',       
            'nombreUsuario',
        ))->where("usuario_huella.idClienteEmpleado = $idUsuario")->limit(1);
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        if (count($datos) == 1) {
            return $datos[0];
        } else {
            return null;
        }
    }
    public function eliminarUsuario($idUsuario = 0) {
        try {
            $this->table = 'usuario';
            $update = new Update($this->table);
            $update->set(array(
                'estado' => 'Eliminado',
            ))->where("usuario.pk_usuario_id = $idUsuario");
            $this->updateWith($update);
            return true;
        } catch (Exception $exc) {
            return false;
        }
    }
    public function activarUsuario($idUsuario = 0) {
        try {
            $this->table = 'usuario';
            $update = new Update($this->table);
            $update->set(array(
                'estado' => 'Activo',
            ))->where("usuario.pk_usuario_id = $idUsuario");
            $this->updateWith($update);
            return true;
        } catch (Exception $exc) {
            return false;
        }
    }

}
