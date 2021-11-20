<?php

namespace Facturacion\Modelo\Tablas;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Facturacion\Modelo\Entidad\Factura;
use Zend\Db\Sql\Select;

class TablaFactura extends AbstractTableGateway {

    protected $table = 'factura';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getRepoFacturado($tipo = '', $mes = 0, $anio = 0) {
        $select = new Select($this->table);
        switch ($tipo) {
            case 'Empresarial':
                $select->columns(array(new \Zend\Db\Sql\Expression('SUM(factura.totalFactura) AS totalFactura'), new \Zend\Db\Sql\Expression('COUNT(factura.idFactura) AS contFacturas')))
                        ->join('corporativo', 'corporativo.idCorporativo = factura.idCorporativo', array())
                        ->where('MONTH(factura.fechaEmision) = ' . $mes)
                        ->where('YEAR(factura.fechaEmision) = ' . $anio)
                        ->where("factura.estado != 'Anulada'")
                        ->where("corporativo.clasificacion = 1")
                        ->where("corporativo.idCorporativo != 490")
                        ->where("corporativo.idCorporativo != 553")
                        ->where("corporativo.idCorporativo != 334")
                ;
                break;
            case 'MasivoCorporativo':
                $select->columns(array(new \Zend\Db\Sql\Expression('SUM(factura.totalFactura) AS totalFactura'), new \Zend\Db\Sql\Expression('COUNT(factura.idFactura) AS contFacturas')))
                        ->join('corporativo', 'corporativo.idCorporativo = factura.idCorporativo', array())
                        ->where('MONTH(factura.fechaEmision) = ' . $mes)
                        ->where('YEAR(factura.fechaEmision) = ' . $anio)
                        ->where("factura.estado != 'Anulada'")
                        ->where("corporativo.clasificacion != 1")
                ;
                break;
            case 'MasivoResidencial':
                $select->columns(array(new \Zend\Db\Sql\Expression('SUM(factura.totalFactura) AS totalFactura'), new \Zend\Db\Sql\Expression('COUNT(factura.idFactura) AS contFacturas')))
                        ->join('residencial', 'residencial.idResidencial = factura.idResidencial', array())
                        ->where('MONTH(factura.fechaEmision) = ' . $mes)
                        ->where('YEAR(factura.fechaEmision) = ' . $anio)
                        ->where("factura.estado != 'Anulada'")
                ;
                break;
            case 'Proyectos':
                $select->columns(array(new \Zend\Db\Sql\Expression('SUM(factura.totalFactura) AS totalFactura'), new \Zend\Db\Sql\Expression('COUNT(factura.idFactura) AS contFacturas')))
                        ->join('corporativo', 'corporativo.idCorporativo = factura.idCorporativo', array())
                        ->where('MONTH(factura.fechaEmision) = ' . $mes)
                        ->where('YEAR(factura.fechaEmision) = ' . $anio)
                        ->where("factura.estado != 'Anulada'")
                        ->where("corporativo.clasificacion = 1")
                        ->where("(corporativo.idCorporativo = 490 OR corporativo.idCorporativo = 553 OR corporativo.idCorporativo = 334)")
                ;
                break;
        }
        $facturas = array();
        $datos = $this->selectWith($select);
        foreach ($datos as $dato) {
            $factura = new Factura();
            $factura->setOpciones(array('totalFactura' => $dato->totalFactura, 'contFacturas' => $dato->contFacturas));
            $facturas[] = $factura;
        }
        return $facturas;
    }

}
