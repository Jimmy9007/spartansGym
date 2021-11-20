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
use Administracion\Formularios\EntrenoForm;
use Administracion\Modelo\Entidades\Entreno;

class EntrenoController extends AbstractActionController {

    private $entrenoDAO;

    public function getEntrenoDAO() {
        if (!$this->entrenoDAO) {
            $sm = $this->getServiceLocator();
            $this->entrenoDAO = $sm->get('Administracion\Modelo\DAO\EntrenoDAO');
        }
        return $this->entrenoDAO;
    }

    function getFormulario($action = '', $onsubmit = '', $idEntreno = 0) {
        $required = true;
        if ($action == 'detail' || $action == 'buscar') {
            $required = false;
        }
        $form = new EntrenoForm($action, $onsubmit, $required);
        if ($idEntreno != 0) {
            $entrenoOBJ = $this->getEntrenoDAO()->getEntreno($idEntreno);
            $form->bind($entrenoOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        return new ViewModel(array(
            'entreno' => $this->getEntrenoDAO()->getEntrenos()
        ));
    }

    public function addAction() {
        $action = 'add';
        $onsubmit = 'return confirm("¿ DESEA REGISTRAR ESTE ENTRENO ?")';
        $form = $this->getFormulario($action, $onsubmit);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $entrenoOBJ = new Entreno($form->getData());
                $entrenoOBJ->setFechaHoraEntreno(date('Y-m-d H:i:s'));
                $this->getEntrenoDAO()->guardar($entrenoOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'entreno',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'entreno',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/entreno/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function editAction() {
        $idEntreno = (int) $this->params()->fromQuery('idEntreno', 0);
        $action = 'edit';
        $onsubmit = 'return confirm("¿ DESEA GUARDAR ESTE USUARIO ?")';
        $form = $this->getFormulario($action, $onsubmit, $idEntreno);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $entrenoOBJ = new Entreno($form->getData());
                $this->getEntrenoDAO()->guardar($entrenoOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'entreno',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'entreno',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/entreno/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function detailAction() {
        $idEntreno = (int) $this->params()->fromQuery('idEntreno', 0);
        $action = 'detail';
        $onsubmit = 'return false';
        $form = $this->getFormulario($action, $onsubmit, $idEntreno);
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/entreno/formulario');
        $view->setTerminal(true);
        return $view;
    }

}
