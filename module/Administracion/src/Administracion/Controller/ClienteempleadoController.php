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
use Zend\View\Model\JsonModel;
use Administracion\Formularios\ClienteempleadoForm;
use Administracion\Modelo\Entidades\Clienteempleado;

class ClienteempleadoController extends AbstractActionController {

    private $clienteempleadoDAO;

    public function getClienteempleadoDAO() {
        if (!$this->clienteempleadoDAO) {
            $sm = $this->getServiceLocator();
            $this->clienteempleadoDAO = $sm->get('Administracion\Modelo\DAO\ClienteempleadoDAO');
        }
        return $this->clienteempleadoDAO;
    }

    function getFormulario($action = '', $onsubmit = '', $idClienteempleado = 0) {
        $required = true;
        if ($action == 'detail' || $action == 'buscar') {
            $required = false;
        }
        $form = new ClienteempleadoForm($action, $onsubmit, $required);
        if ($action == 'edit') {
            
        }
        if ($idClienteempleado != 0) {
            $clienteempleadoOBJ = $this->getClienteempleadoDAO()->getClienteempleado($idClienteempleado);
            $form->bind($clienteempleadoOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
//        $filtro = "clienteempleado.estado = 'Eliminado'";
        return new ViewModel(array(
            'clienteempleados' => $this->getClienteempleadoDAO()->getClienteempleados()
        ));
    }

    public function addAction() {
        $action = 'add';
        $onsubmit = 'return confirmAdd()';
        $form = $this->getFormulario($action, $onsubmit);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $clienteempleadoOBJ = new Clienteempleado($form->getData());
                if ($clienteempleadoOBJ->getGenero() == 'Masculino') {
                    $clienteempleadoOBJ->setRutaFotoPerfil('perfilHombre.png');
                } else {
                    $clienteempleadoOBJ->setRutaFotoPerfil('perfilMujer.png');
                }
                $this->getClienteempleadoDAO()->guardar($clienteempleadoOBJ);
                $this->flashMessenger()->addSuccessMessage('EL USUARIO FUE REGISTRADO EN POPGYM');
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'clienteempleado',
                            'action' => 'index',
                ));
            } else {
                $this->flashMessenger()->addErrorMessage('SE HA PRESENTADO UN INCONVENIENTE, EL USUARIO NO FUE REGISTRADO EN POPGYM');
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'clienteempleado',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/clienteempleado/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function editAction() {
        $idClienteempleado = (int) $this->params()->fromQuery('idClienteempleado', 0);
        $action = 'edit';
        $onsubmit = 'return confirm("¿ DESEA EDITAR ESTE USUARIO ?")';
        $form = $this->getFormulario($action, $onsubmit, $idClienteempleado);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $clienteempleadoOBJ = new Clienteempleado($form->getData());
                $this->getClienteempleadoDAO()->guardar($clienteempleadoOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'clienteempleado',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'clienteempleado',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/clienteempleado/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function detailAction() {
        $idClienteempleado = (int) $this->params()->fromQuery('idClienteempleado', 0);
        $action = 'detail';
        $onsubmit = 'return false';
        $form = $this->getFormulario($action, $onsubmit, $idClienteempleado);
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/clienteempleado/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function seleccionarClienteempleadoAction() {
        $view = new ViewModel(array(
            'clienteempleados' => $this->getClienteempleadoDAO()->getClienteempleados()
        ));
        $view->setTerminal(true);
        return $view;
    }

    public function getClienteempleadoAction() {
        $idClienteempleado = (int) $this->params()->fromQuery('idClienteempleado', 0);
        if (!$idClienteempleado) {
            return 0;
        }
        $form = $this->getFormulario('buscar', '', $idClienteempleado);
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/clienteempleado/formulario');
        $view->setTerminal(true);
        return $view;
    }
//------------------------------------------------------------------------------    

    public function existeidentificacionAction() {
        $error = 0;
        $existe = 1;
        $identificacion = $this->params()->fromQuery('identificacion', '');
        if ($identificacion != '') {
            $existe = $this->getClienteempleadoDAO()->existeIdentificacion($identificacion);
        } else {
            $error = 1;
        }
        return new JsonModel(array(
            'error' => $error,
            'existe' => $existe,
            'identificacion' => $identificacion,
        ));
    }

//------------------------------------------------------------------------------  

}
