<?php

namespace Facturacion\Modelo\Tablas;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Facturacion\Modelo\Entidad\Recaudo;
use Zend\Db\Sql\Select;

class TablaRecaudo extends AbstractTableGateway {

    protected $table = 'recaudo';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getRepoRecaudado($tipo = '', $mes = 0, $anio = 0) {
        $select = new Select($this->table);
        switch ($tipo) {
            case 'Empresarial':
                $select->columns(array(new \Zend\Db\Sql\Expression('SUM(recaudo.valorRecaudo) AS totalRecaudo'), new \Zend\Db\Sql\Expression('COUNT(recaudo.idRecaudo) AS contRecaudos')))
                        ->join('corporativo', 'corporativo.idCorporativo = recaudo.idCorporativo', array())
                        ->join('factura', 'factura.idFactura = recaudo.idFactura', array())
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
                $select->columns(array(new \Zend\Db\Sql\Expression('SUM(recaudo.valorRecaudo) AS totalRecaudo'), new \Zend\Db\Sql\Expression('COUNT(recaudo.idRecaudo) AS contRecaudos')))
                        ->join('corporativo', 'corporativo.idCorporativo = recaudo.idCorporativo', array())
                        ->join('factura', 'factura.idFactura = recaudo.idFactura', array())
                        ->where('MONTH(factura.fechaEmision) = ' . $mes)
                        ->where('YEAR(factura.fechaEmision) = ' . $anio)
                        ->where("factura.estado != 'Anulada'")
                        ->where("corporativo.clasificacion != 1")
                ;
                break;
            case 'MasivoResidencial':
                $select->columns(array(new \Zend\Db\Sql\Expression('SUM(recaudo.valorRecaudo) AS totalRecaudo'), new \Zend\Db\Sql\Expression('COUNT(recaudo.idRecaudo) AS contRecaudos')))
                        ->join('residencial', 'residencial.idResidencial = recaudo.idResidencial', array())
                        ->join('factura', 'factura.idFactura = recaudo.idFactura', array())
                        ->where('MONTH(factura.fechaEmision) = ' . $mes)
                        ->where('YEAR(factura.fechaEmision) = ' . $anio)
                        ->where("factura.estado != 'Anulada'")
                ;
                break;
            case 'Proyectos':
                $select->columns(array(new \Zend\Db\Sql\Expression('SUM(recaudo.valorRecaudo) AS totalRecaudo'), new \Zend\Db\Sql\Expression('COUNT(recaudo.idRecaudo) AS contRecaudos')))
                        ->join('corporativo', 'corporativo.idCorporativo = recaudo.idCorporativo', array())
                        ->join('factura', 'factura.idFactura = recaudo.idFactura', array())
                        ->where('MONTH(factura.fechaEmision) = ' . $mes)
                        ->where('YEAR(factura.fechaEmision) = ' . $anio)
                        ->where("factura.estado != 'Anulada'")
                        ->where("corporativo.clasificacion = 1")
                        ->where("(corporativo.idCorporativo = 490 OR corporativo.idCorporativo = 553 OR corporativo.idCorporativo = 334)")
                ;
                break;
        }
        $recaudos = array();
        $datos = $this->selectWith($select);
        foreach ($datos as $dato) {
            $recaudo = new Recaudo();
            $recaudo->setOpciones(array('totalRecaudo' => $dato->totalRecaudo, 'contRecaudos' => $dato->contRecaudos));
            $recaudos[] = $recaudo;
        }
        return $recaudos;
    }

}
