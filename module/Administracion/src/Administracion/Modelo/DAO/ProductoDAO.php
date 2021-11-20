<?php

namespace Administracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Administracion\Modelo\Entidades\Producto;

class ProductoDAO extends AbstractTableGateway {

    protected $table = 'producto';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getProductos($filtro = '') {
        $productos = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_producto_id',
            'codigoBarras',
            'nombreProducto',
            'descripcion',
            'cantidad',
            'precioCosto',
            'precioVenta',
            'fechaadquisicion',
            'proveedor',
            'numfactura',
            'estado',
            'imagenProducto',
            'fechahorareg',
            'fechahoramod',
        ));
        if ($filtro != '') {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $productos[] = new Producto($dato);
        }
        return $productos;
    }

    public function getDetalleProducto($id = 0) {
        $producto = null;
        $select = new Select($this->table);
        $select->columns(array(
                    'pk_producto_id',
                    'codigoBarras',
                    'nombreProducto',
                    'descripcion',
                    'cantidad',
                    'precioCosto',
                    'precioVenta',
                    'fechaadquisicion',
                    'proveedor',
                    'numfactura',
                    'estado',
                    'imagenProducto',
                    'fechahorareg',
                    'fechahoramod',
                ))
                ->where(array('pk_producto_id' => $id))
                ->limit(1);
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $producto = new Producto($dato);
        }
        return $producto;
    }

    public function getProducto($idProducto = 0) {
        return new Producto($this->select(array('pk_producto_id' => $idProducto))->current()->getArrayCopy());
    }

    public function guardar(Producto $productoOBJ = null) {
        $idProducto = (int) $productoOBJ->getPk_producto_id();
        if ($idProducto == 0) {
            return $this->insert($productoOBJ->getArrayCopy());
        } else {
            if ($this->existeID($idProducto)) {
                return $this->update($productoOBJ->getArrayCopy(), array('pk_producto_id' => $idProducto));
            } else {
                return 0;
            }
        }
    }

    public function existeID($idProducto = 0) {
        $id = (int) $idProducto;
        $rowset = $this->select(array('pk_producto_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("EL ID $id NO EXISTE");
        }
        return $row;
    }

    public function eliminar($idProducto) {
        $resultado = $this->delete(array("pk_producto_id" => (int) $idProducto));
        return $resultado;
    }

}
