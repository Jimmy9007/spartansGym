<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Administracion\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Administracion\Formularios\ProductoForm;
use Administracion\Modelo\Entidades\Producto;

class ProductoController extends AbstractActionController {

    private $productoDAO;
    private $rutaArchivos = './public/img/productos';

    public function getProductoDAO() {
        if (!$this->productoDAO) {
            $sm = $this->getServiceLocator();
            $this->productoDAO = $sm->get('Administracion\Modelo\DAO\ProductoDAO');
        }
        return $this->productoDAO;
    }

    function getFormulario($action = '', $onsubmit = '', $idProducto = 0) {
        $required = true;
        if ($action == 'detail' || $action == 'buscar') {
            $required = false;
        }
        $form = new ProductoForm($action, $onsubmit, $required);
        if ($action == 'add') {
            $form->get('imagenProducto')->setAttribute('type', 'file');
        }
        if ($idProducto != 0) {
            $productoOBJ = $this->getProductoDAO()->getProducto($idProducto);
            $form->bind($productoOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        return new ViewModel(array(
            'producto' => $this->getProductoDAO()->getProductos()
        ));
    }

    public function addAction() {
        $action = 'add';
        $onsubmit = 'return confirm("DESEA REGISTRAR ESTE PRODUCTO ?")';
        $form = $this->getFormulario($action, $onsubmit);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $files = $request->getFiles()->toArray();
                $httpadapter = new \Zend\File\Transfer\Adapter\Http();
                $filesize = new \Zend\Validator\File\Size(array('max' => 5242880)); //  5 MB
                $extension = new \Zend\Validator\File\Extension(array('extension' => array('gif', 'jpg', 'png', 'jpeg', 'bmp')));
                $httpadapter->setValidators(array($filesize, $extension), $files['imagenProducto']['name']);
                if ($httpadapter->isValid()) {
                    $httpadapter->setDestination($this->rutaArchivos);
                    $ext = pathinfo($files['imagenProducto']['name'], PATHINFO_EXTENSION);
                    $archivo = strtoupper(md5(rand() . $files['imagenProducto']['name'])) . '.' . $ext;
                    $httpadapter->addFilter('File\Rename', array(
                        'target' => $this->rutaArchivos . '/' . $archivo,
                    ));
                    if ($httpadapter->receive($files['imagenProducto']['name'])) {
                        $productoOBJ = new Producto($form->getData());
                        $productoOBJ->setImagenProducto($archivo);
                        if ($this->getProductoDAO()->guardar($productoOBJ) == 0) {
                            unlink($this->rutaArchivos . '/' . $archivo);
                        }
                        return $this->redirect()->toRoute('administracion/default', array(
                                    'controller' => 'producto',
                                    'action' => 'index',
                        ));
                    }
                } else {
                    //                    no poner imagen 

                    $form = $this->getFormulario($action);
                    $request = $this->getRequest();
                    if ($request->isPost()) {
                        $form->setData($request->getPost());
                        if ($form->isValid()) {
                            $productoOBJ = new Producto($form->getData());
                            $productoOBJ->setImagenProducto('sin_foto.png');

                            $this->getProductoDAO()->guardar($productoOBJ);
                            return $this->redirect()->toRoute('administracion/default', array(
                                        'controller' => 'producto',
                                        'action' => 'index',
                            ));
                        } else {
                            return $this->redirect()->toRoute('administracion/default', array(
                                        'controller' => 'producto',
                                        'action' => 'index',
                            ));
                        }
                    }
                    $view = new ViewModel(array(
                        'form' => $form,
                    ));
                    $view->setTemplate('administracion/producto/formulario_1');
                    $view->setTerminal(true);
                    return $view;


//                    fin no poner imagen
                }
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'producto',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/producto/formulario_1');
        $view->setTerminal(true);
        return $view;
    }

    public function editAction() {
        $idProducto = (int) $this->params()->fromQuery('idProducto', 0);
        $action = 'edit';
        $onsubmit = 'return confirm("DESEA EDITAR ESTE PRODUCTO ?")';
        $form = $this->getFormulario($action, $onsubmit, $idProducto);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $productoOBJ = new Producto($form->getData());
                $productoOBJ->setFechahoramod(date('Y-m-d H:i:s'));
                $this->getProductoDAO()->guardar($productoOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'producto',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'producto',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form,
            'producto' => $this->getProductoDAO()->getProducto($idProducto)
        ));
        $view->setTemplate('administracion/producto/formulario_1');
        $view->setTerminal(true);
        return $view;
    }

    public function detailAction() {
        $idProducto = (int) $this->params()->fromQuery('idProducto', 0);
        $action = 'detail';
        $onsubmit = 'return false';
        $form = $this->getFormulario($action, $onsubmit, $idProducto);
        $view = new ViewModel(array(
            'form' => $form,
            'producto' => $this->getProductoDAO()->getProducto($idProducto)
        ));
        $view->setTemplate('administracion/producto/formulario_1');
        $view->setTerminal(true);
        return $view;
    }

    public function getDetalleProductoAction() {
        $idProducto = (int) $this->params()->fromPost('idProducto', 0);
        $view = new ViewModel(array(
            'producto' => $this->getProductoDAO()->getDetalleProducto($idProducto)
        ));
        $view->setTerminal(true);
        return $view;
    }

    public function deleteAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $idProducto = (int) $this->params()->fromPost('idProducto', 0);
            $resultado = $this->getProductoDAO()->eliminar($idProducto);
            $response->setContent(Json::encode(array('eliminado' => $resultado)));
        }
        return $response;
    }

    public function seleccionarProductoAction() {
        $idVenta = (int) $this->params()->fromQuery('idVenta', 0);
        $filtro = "producto.pk_producto_id NOT IN (SELECT venta_producto.pk_producto_id FROM venta_producto WHERE venta_producto.pk_venta_id = $idVenta)";
        $view = new ViewModel(array(
            'productos' => $this->getProductoDAO()->getProductos($filtro),
        ));
        $view->setTerminal(true);
        return $view;
    }
    public function seleccionarProductoEditarAction() {
        $idVenta = (int) $this->params()->fromQuery('idVenta', 0);
        $filtro = "producto.pk_producto_id NOT IN (SELECT venta_producto.pk_producto_id FROM venta_producto WHERE venta_producto.pk_venta_id = $idVenta)";
        $view = new ViewModel(array(
            'productos' => $this->getProductoDAO()->getProductos($filtro),
        ));
        $view->setTerminal(true);
        return $view;
    }

    public function getProductoAction() {
        $idProducto = (int) $this->params()->fromQuery('idProducto', 0);
        if (!$idProducto) {
            return 0;
        }
        $form = $this->getFormulario('buscar', '', $idProducto);
        $view = new ViewModel(array(
            'form' => $form,
            'producto' => $this->getProductoDAO()->getProducto($idProducto)
        ));
        $view->setTemplate('administracion/producto/formulario');
        $view->setTerminal(true);
        return $view;
    }
    public function getProductoEditarAction() {
        $idProducto = (int) $this->params()->fromQuery('idProducto', 0);
        if (!$idProducto) {
            return 0;
        }
        $form = $this->getFormulario('buscar', '', $idProducto);
        $view = new ViewModel(array(
            'form' => $form,
            'producto' => $this->getProductoDAO()->getProducto($idProducto)
        ));
        $view->setTemplate('administracion/producto/editarventa');
        $view->setTerminal(true);
        return $view;
    }

}
