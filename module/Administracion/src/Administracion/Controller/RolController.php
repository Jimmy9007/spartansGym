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
use Administracion\Formularios\RolForm;
use Administracion\Modelo\Entidades\Rol;

class RolController extends AbstractActionController {

    private $rolDAO;

    public function getRolDAO() {
        if (!$this->rolDAO) {
            $sm = $this->getServiceLocator();
            $this->rolDAO = $sm->get('Administracion\Modelo\DAO\RolDAO');
        }
        return $this->rolDAO;
    }

    function getFormulario($action = '', $onsubmit = '', $idRol = 0) {
        $required = true;
        if ($action == 'detail' || $action == 'buscar') {
            $required = false;
        }
        $form = new RolForm($action, $onsubmit, $required);
        if ($idRol != 0) {
            $rolOBJ = $this->getRolDAO()->getRol($idRol);
            $form->bind($rolOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        return new ViewModel(array(
            'rol' => $this->getRolDAO()->getRols()
        ));
    }

    public function addAction() {
        $action = 'add';
        $onsubmit = 'return confirm("¿ DESEA REGISTRAR ESTE ROL ?")';
        $form = $this->getFormulario($action, $onsubmit);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $rolOBJ = new Rol($form->getData());
                $this->getRolDAO()->guardar($rolOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'rol',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'rol',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/rol/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function editAction() {
        $idRol = (int) $this->params()->fromQuery('idRol', 0);
        $action = 'edit';
        $onsubmit = 'return confirm("¿ DESEA EDITAR ESTE ROL ?")';
        $form = $this->getFormulario($action, $onsubmit, $idRol);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $rolOBJ = new Rol($form->getData());
                $this->getRolDAO()->guardar($rolOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'rol',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'rol',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/rol/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function detailAction() {
        $idRol = (int) $this->params()->fromQuery('idRol', 0);
        $action = 'detail';
        $onsubmit = 'return false';
        $form = $this->getFormulario($action, $onsubmit, $idRol);
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/rol/formulario');
        $view->setTerminal(true);
        return $view;
    }

}
