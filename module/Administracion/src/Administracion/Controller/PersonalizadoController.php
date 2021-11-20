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
use Administracion\Formularios\PersonalizadoForm;
use Administracion\Modelo\Entidades\Personalizado;

class PersonalizadoController extends AbstractActionController {

    private $personalizadoDAO;
    private $usuarioDAO;

    public function getPersonalizadoDAO() {
        if (!$this->personalizadoDAO) {
            $sm = $this->getServiceLocator();
            $this->personalizadoDAO = $sm->get('Administracion\Modelo\DAO\PersonalizadoDAO');
        }
        return $this->personalizadoDAO;
    }

    public function getUsuarioDAO() {
        if (!$this->usuarioDAO) {
            $sm = $this->getServiceLocator();
            $this->usuarioDAO = $sm->get('Administracion\Modelo\DAO\UsuarioDAO');
        }
        return $this->usuarioDAO;
    }

    function getFormulario($action = '', $onsubmit = '', $idPersonalizado = 0) {
        $required = true;
        if ($action == 'detail' || $action == 'buscar') {
            $required = false;
        }
        $Usuarioes = $this->getUsuarioDAO()->getUsuarios("usuario.fk_rol_id = '4'");
        $UsuarioesSelect = array();
        foreach ($Usuarioes as $Inst) {
            $UsuarioesSelect[$Inst['usuarioOBJ']->getPk_usuario_id()] = $Inst['usuarioOBJ']->getNombreApellido();
        }
        $form = new PersonalizadoForm($action, $onsubmit, $required, $UsuarioesSelect);
        if ($action == 'detail') {
            $form->get('fechaPersonalizado')->setAttribute('type', 'text');
        }
        if ($idPersonalizado != 0) {
            $personalizadoOBJ = $this->getPersonalizadoDAO()->getPersonalizado($idPersonalizado);
            $form->bind($personalizadoOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        return new ViewModel(array(
            'personalizado' => $this->getPersonalizadoDAO()->getPersonalizados()
        ));
    }

    public function addAction() {
        $action = 'add';
        $onsubmit = 'return validarGuardar()';
        $form = $this->getFormulario($action, $onsubmit);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $personalizadoOBJ = new Personalizado($form->getData());
                $this->getPersonalizadoDAO()->guardar($personalizadoOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'personalizado',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'personalizado',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/personalizado/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function editAction() {
        $idPersonalizado = (int) $this->params()->fromQuery('idPersonalizado', 0);
        $action = 'edit';
        $onsubmit = 'return confirm("¿ DESEA GUARDAR ESTE USUARIO ?")';
        $form = $this->getFormulario($action, $onsubmit, $idPersonalizado);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $personalizadoOBJ = new Personalizado($form->getData());
                $this->getPersonalizadoDAO()->guardar($personalizadoOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'personalizado',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'personalizado',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/personalizado/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function detailAction() {
        $idPersonalizado = (int) $this->params()->fromQuery('idPersonalizado', 0);
        $action = 'detail';
        $onsubmit = 'return false';
        $form = $this->getFormulario($action, $onsubmit, $idPersonalizado);
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/personalizado/formulario');
        $view->setTerminal(true);
        return $view;
    }

}
