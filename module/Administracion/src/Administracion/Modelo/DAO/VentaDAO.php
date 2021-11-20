<?php

namespace Administracion\Modelo\DAO;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Administracion\Modelo\Entidades\Venta;
use Administracion\Modelo\Entidades\Producto;
use Administracion\Modelo\Entidades\VentasProductos;

class VentaDAO extends AbstractTableGateway {

    protected $table = 'venta';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getVentas($filtro = '') {
        $ventas = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_venta_id',
            'cantidadVenta',
            'valorTotal',
            'ganancia',
            'fechaVenta',
        ));

        if ($filtro != '') {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $rutinaUsuario = array(
                'ventaOBJ' => new Venta($dato),
            );
            $ventas[] = $rutinaUsuario;
        }
        return $ventas;
    }

    public function getReporteVenta($filtro = '') {
        $ReporteVenta = array();
        $select = new Select($this->table);
        $select->columns(array(
            'pk_venta_id',
            'cantidadVenta',
            'valorTotal',
            'ganancia',
            'fechaVenta',
            'mes' => new Expression('month(fechaVenta)'),
        ));
        $select->join('venta_producto', 'venta_producto.pk_venta_id = venta.pk_venta_id', array(
            'pk_producto_id',
            'cantidadVenta',
            'monto',
        ));
        $select->join('producto', 'producto.pk_producto_id = venta_producto.pk_producto_id', array(
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
            'fechahorareg',
            'fechahoramod',
            'imagenProducto',
        ));
        if ($filtro != '') {
            $select->where($filtro);
        }
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $Reporte = array(
                'ventaOBJ' => new Venta($dato),
                'productoOBJ' => new Producto($dato),
                'productoVentaOBJ' => new VentasProductos($dato),
                'mes' => $dato['mes'],
            );
            $ReporteVenta[] = $Reporte;
        }
        return $ReporteVenta;
    }

    public function getProductosVentas($idVenta = 0) {
        $productos = array();
        $select = new Select($this->table);
        $select->columns(array(
                    'pk_venta_id',
                    'cantidadVenta',
                    'valorTotal',
                    'ganancia',
                    'fechaVenta',
                ))
                ->join('venta_producto', 'venta_producto.pk_venta_id = venta.pk_venta_id', array(
                    'pk_producto_id',
                    'cantidadVenta',
                    'monto',
                ))->join('producto', 'producto.pk_producto_id = venta_producto.pk_producto_id', array(
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
                    'fechahorareg',
                    'fechahoramod',
                    'imagenProducto',
                ))
                ->where('venta_producto.pk_venta_id = ' . $idVenta);
//        print $select->getSqlString();
        $datos = $this->selectWith($select)->toArray();
        foreach ($datos as $dato) {
            $rutinaUsuario = array(
                'ventaOBJ' => new Venta($dato),
                'productoOBJ' => new Producto($dato),
                'productoVentaOBJ' => new VentasProductos($dato),
            );
            $productos[] = $rutinaUsuario;
        }
        return $productos;
    }

    public function anexarProducto($datos = array()) {
        $this->table = 'venta_producto';

        return $this->insert(array(
                    'pk_venta_id' => $datos['idVentaSelect'],
                    'pk_producto_id' => $datos['pk_producto_id'],
                    'cantidadVenta' => $datos['cantidadVenta'],
                    'monto' => $datos['valorTotal'],
        ));
    }

    public function getVenta($idVenta = 0) {
        return new Venta($this->select(array('pk_venta_id' => $idVenta))->current()->getArrayCopy());
    }

    public function guardar(Venta $ventaOBJ = null, $productos = array()) {
        $connection = $this->getAdapter()->getDriver()->getConnection();
        $connection->beginTransaction();
        try {
            $this->table = 'venta';
            $insert = new \Zend\Db\Sql\Insert($this->table);
            $insert->values($ventaOBJ->getArrayCopy());
            $this->insertWith($insert);
            $idVentaInsert = $this->getLastInsertValue();
            //------------------------------------------------------------------
            $this->table = 'venta_producto';
            $insert2 = new \Zend\Db\Sql\Insert($this->table);
            foreach ($productos as $producto) {
                $producto['pk_venta_id'] = $idVentaInsert;
                $insert2->values($producto);
                $this->insertWith($insert2);
            }
            // ------------ EJECUCION COMMIT ------------
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollback();
            throw new \Exception($e);
        }
    }

    public function eliminar($idVenta) {
        $resultado = $this->delete(array("pk_venta_id" => (int) $idVenta));
        return $resultado;
    }

//------------------------------------------------------------------------------
    public function getBarChartEnero($anio = 0) {
        $date = "$anio-01-01";
        $dateFinal = date("Y-m-t", strtotime($date));
        $select = array();
        $select = new Select($this->table);
        $select->columns(array(
            'total' => new Expression('SUM(valorTotal)'),
        ))->where("fechaVenta BETWEEN '$date' AND '$dateFinal'");
        $datos = $this->selectWith($select)->toArray();
        return $datos[0]['total'];
    }

    public function getBarChartFebrero($anio = 0) {
        $date = "$anio-02-01";
        $dateFinal = date("Y-m-t", strtotime($date));
        $select = array();
        $select = new Select($this->table);
        $select->columns(array(
            'total' => new Expression('SUM(valorTotal)'),
        ))->where("fechaVenta BETWEEN '$date' AND '$dateFinal'");
        $datos = $this->selectWith($select)->toArray();
        return $datos[0]['total'];
    }

    public function getBarChartMarzo($anio = 0) {
        $date = "$anio-03-01";
        $dateFinal = date("Y-m-t", strtotime($date));
        $select = array();
        $select = new Select($this->table);
        $select->columns(array(
            'total' => new Expression('SUM(valorTotal)'),
        ))->where("fechaVenta BETWEEN '$date' AND '$dateFinal'");
        $datos = $this->selectWith($select)->toArray();
        return $datos[0]['total'];
    }

    public function getBarChartAbril($anio = 0) {
        $date = "$anio-04-01";
        $dateFinal = date("Y-m-t", strtotime($date));
        $select = array();
        $select = new Select($this->table);
        $select->columns(array(
            'total' => new Expression('SUM(valorTotal)'),
        ))->where("fechaVenta BETWEEN '$date' AND '$dateFinal'");
        $datos = $this->selectWith($select)->toArray();
        return $datos[0]['total'];
    }

    public function getBarChartMayo($anio = 0) {
        $date = "$anio-05-01";
        $dateFinal = date("Y-m-t", strtotime($date));
        $select = array();
        $select = new Select($this->table);
        $select->columns(array(
            'total' => new Expression('SUM(valorTotal)'),
        ))->where("fechaVenta BETWEEN '$date' AND '$dateFinal'");
        $datos = $this->selectWith($select)->toArray();
        return $datos[0]['total'];
    }

    public function getBarChartJunio($anio = 0) {
        $date = "$anio-06-01";
        $dateFinal = date("Y-m-t", strtotime($date));
        $select = array();
        $select = new Select($this->table);
        $select->columns(array(
            'total' => new Expression('SUM(valorTotal)'),
        ))->where("fechaVenta BETWEEN '$date' AND '$dateFinal'");
        $datos = $this->selectWith($select)->toArray();
        return $datos[0]['total'];
    }

    public function getBarChartJulio($anio = 0) {
        $date = "$anio-07-01";
        $dateFinal = date("Y-m-t", strtotime($date));
        $select = array();
        $select = new Select($this->table);
        $select->columns(array(
            'total' => new Expression('SUM(valorTotal)'),
        ))->where("fechaVenta BETWEEN '$date' AND '$dateFinal'");
        $datos = $this->selectWith($select)->toArray();
        return $datos[0]['total'];
    }

    public function getBarChartAgosto($anio = 0) {
        $date = "$anio-08-01";
        $dateFinal = date("Y-m-t", strtotime($date));
        $select = array();
        $select = new Select($this->table);
        $select->columns(array(
            'total' => new Expression('SUM(valorTotal)'),
        ))->where("fechaVenta BETWEEN '$date' AND '$dateFinal'");
        $datos = $this->selectWith($select)->toArray();
        return $datos[0]['total'];
    }

    public function getBarChartSeptiembre($anio = 0) {
        $date = "$anio-09-01";
        $dateFinal = date("Y-m-t", strtotime($date));
        $select = array();
        $select = new Select($this->table);
        $select->columns(array(
            'total' => new Expression('SUM(valorTotal)'),
        ))->where("fechaVenta BETWEEN '$date' AND '$dateFinal'");
        $datos = $this->selectWith($select)->toArray();
        return $datos[0]['total'];
    }

    public function getBarChartOctubre($anio = 0) {
        $date = "$anio-10-01";
        $dateFinal = date("Y-m-t", strtotime($date));
        $select = array();
        $select = new Select($this->table);
        $select->columns(array(
            'total' => new Expression('SUM(valorTotal)'),
        ))->where("fechaVenta BETWEEN '$date' AND '$dateFinal'");
        $datos = $this->selectWith($select)->toArray();
        return $datos[0]['total'];
    }

    public function getBarChartNoviembre($anio = 0) {
        $date = "$anio-11-01";
        $dateFinal = date("Y-m-t", strtotime($date));
        $select = array();
        $select = new Select($this->table);
        $select->columns(array(
            'total' => new Expression('SUM(valorTotal)'),
        ))->where("fechaVenta BETWEEN '$date' AND '$dateFinal'");
        $datos = $this->selectWith($select)->toArray();
        return $datos[0]['total'];
    }

    public function getBarChartDiciembre($anio = 0) {
        $date = "$anio-12-01";
        $dateFinal = date("Y-m-t", strtotime($date));
        $select = array();
        $select = new Select($this->table);
        $select->columns(array(
            'total' => new Expression('SUM(valorTotal)'),
        ))->where("fechaVenta BETWEEN '$date' AND '$dateFinal'");
        $datos = $this->selectWith($select)->toArray();
        return $datos[0]['total'];
    }

//------------------------------------------------------------------------------
    public function eliminarArticuloVenta($idVenta = 0, $idProducto = 0) {
        try {
            $this->table = 'venta_producto';
            $delete = new \Zend\Db\Sql\Delete($this->table);
            $delete->where("pk_venta_id = $idVenta AND pk_producto_id = $idProducto");
//            echo $delete->getSqlString();
            return $this->deleteWith($delete);
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

//------------------------------------------------------------------------------
}