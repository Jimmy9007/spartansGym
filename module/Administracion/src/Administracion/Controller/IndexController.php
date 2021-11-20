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
use Administracion\Formularios\EventoForm;
use Administracion\Modelo\Entidades\Evento;

class IndexController extends AbstractActionController {

    private $eventoDAO;
    private $usuarioDAO;
    private $clienteempleadoDAO;

    public function getEventoDAO() {
        if (!$this->eventoDAO) {
            $sm = $this->getServiceLocator();
            $this->eventoDAO = $sm->get('Administracion\Modelo\DAO\EventoDAO');
        }
        return $this->eventoDAO;
    }

    public function getUsuarioDAO() {
        if (!$this->usuarioDAO) {
            $sm = $this->getServiceLocator();
            $this->usuarioDAO = $sm->get('Administracion\Modelo\DAO\UsuarioDAO');
        }
        return $this->usuarioDAO;
    }

    public function getClienteempleadoDAO() {
        if (!$this->clienteempleadoDAO) {
            $sm = $this->getServiceLocator();
            $this->clienteempleadoDAO = $sm->get('Administracion\Modelo\DAO\ClienteempleadoDAO');
        }
        return $this->clienteempleadoDAO;
    }

    function getFormulario($action = '', $onsubmit = '', $idEvento = 0) {
        $required = true;
        if ($action == 'detail' || $action == 'buscar') {
            $required = false;
        }
        $form = new EventoForm($action, $onsubmit, $required);
        if ($idEvento != 0) {
            $eventoOBJ = $this->getEventoDAO()->getEvento($idEvento);
            $form->bind($eventoOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        return new ViewModel(array(
            'evento' => $this->getEventoDAO()->getEventos(),
            'usuarioCumple' => $this->getClienteempleadoDAO()->getClienteempleados(),
            'usuariosTotal' => $this->getUsuarioDAO()->getCountUsuarios(),
            'MujeresTotal' => $this->getUsuarioDAO()->getCountMujeres(),
            'HombresTotal' => $this->getUsuarioDAO()->getCountHombres()
        ));
    }

    public function addAction() {
        $action = 'add';
        $onsubmit = 'return confirm("¿ DESEA REGISTRAR ESTE EVENTO ?")';
        $form = $this->getFormulario($action, $onsubmit);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $eventoOBJ = new Evento($form->getData());
                $this->getEventoDAO()->guardar($eventoOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'index',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'index',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/index/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function editAction() {
        $idEvento = (int) $this->params()->fromQuery('id', 0);
        $action = 'edit';
        $onsubmit = 'return confirm("¿ DESEA GUARDAR ESTE EVENTO ?")';
        $form = $this->getFormulario($action, $onsubmit, $idEvento);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $eventoOBJ = new Evento($form->getData());
                $this->getEventoDAO()->guardar($eventoOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'index',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'index',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/index/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function deleteAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $idEvento = (int) $this->params()->fromPost('id', 0);
            $resultado = $this->getEventoDAO()->eliminar($idEvento);
            $response->setContent(Json::encode(array('eliminado' => $resultado)));
        }
        return $response;
    }

    public function moverEventoAction() {
        $idEvento = (int) $this->params()->fromQuery('id', 0);
        $fStart = $this->params()->fromQuery('fStart', '');
        $fEnd = $this->params()->fromQuery('fEnd', '');
        $titulo = $this->params()->fromQuery('titulo', '');
        $des = $this->params()->fromQuery('des', '');
        $cColor = $this->params()->fromQuery('cColor', '');
        $tColor = $this->params()->fromQuery('tColor', '');
        $eventoOBJ = new Evento();
        $eventoOBJ->setPk_evento_id($idEvento);
        $eventoOBJ->setStart($fStart);
        $eventoOBJ->setEnd($fEnd);
        $eventoOBJ->setTitle($titulo);
        $eventoOBJ->setDescripcion($des);
        $eventoOBJ->setColor($cColor);
        $eventoOBJ->setTextColor($tColor);
        $this->getEventoDAO()->moverEvento($eventoOBJ);
    }

}
