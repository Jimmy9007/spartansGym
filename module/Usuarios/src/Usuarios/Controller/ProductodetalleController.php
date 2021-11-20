<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Usuarios\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Json\Json;

class ProductodetalleController extends AbstractActionController {

    private $productoDAO;

    public function getProductoDAO() {
        if (!$this->productoDAO) {
            $sm = $this->getServiceLocator();
            $this->productoDAO = $sm->get('Administracion\Modelo\DAO\ProductoDAO');
        }
        return $this->productoDAO;
    }

    public function productodetalleAction() {
        $idProducto = (int) $this->params()->fromRoute('id1', 0);
        $filtro = "producto.pk_producto_id = $idProducto";
        return new ViewModel(array(
            'producto' => $this->getProductoDAO()->getProductos($filtro)
        ));
    }

}
