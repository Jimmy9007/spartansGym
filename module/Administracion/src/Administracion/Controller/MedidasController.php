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
use Administracion\Formularios\MedidasForm;
use Administracion\Modelo\Entidades\Medidas;

class MedidasController extends AbstractActionController {

    private $medidasDAO;
    private $usuarioDAO;

    public function getMedidasDAO() {
        if (!$this->medidasDAO) {
            $sm = $this->getServiceLocator();
            $this->medidasDAO = $sm->get('Administracion\Modelo\DAO\MedidasDAO');
        }
        return $this->medidasDAO;
    }

    public function getUsuarioDAO() {
        if (!$this->usuarioDAO) {
            $sm = $this->getServiceLocator();
            $this->usuarioDAO = $sm->get('Administracion\Modelo\DAO\UsuarioDAO');
        }
        return $this->usuarioDAO;
    }

    function getFormulario($action = '', $onsubmit = '', $idMedidas = 0) {
        $required = true;
        if ($action == 'detail') {
            $required = false;
        }
        $usuarios = $this->getUsuarioDAO()->getUsuarios();
        $usuariosSelect = array();
        foreach ($usuarios as $usu) {
            $usuariosSelect[$usu['usuarioOBJ']->getPk_usuario_id()] = $usu['usuarioOBJ']->getNombreApellido();
        }
        $form = new MedidasForm($action, $onsubmit, $required, $usuariosSelect);
        if ($idMedidas != 0) {
            $medidasOBJ = $this->getMedidasDAO()->getMedidas($idMedidas);
            $form->bind($medidasOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
//        $filtro = "medidas.estado = 'Eliminado'";
        return new ViewModel(array(
            'medidas' => $this->getMedidasDAO()->getMedidass()
        ));
    }

    public function indexmedidasusuarioAction() {
        $nombreUsuario = '';
        if ($sesionUsuario = $this->identity()) {
            $nombreUsuario = $sesionUsuario->pk_usuario_id;
        }
//        $filtro = "medidas.estado = 'Eliminado'";
        return new ViewModel(array(
            'medidas' => $this->getMedidasDAO()->getMedidasUsuario($nombreUsuario)
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
                $medidasOBJ = new Medidas($form->getData());
                $this->getMedidasDAO()->guardar($medidasOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'medidas',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'medidas',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('administracion/medidas/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function editAction() {
        $idMedidas = (int) $this->params()->fromQuery('idMedidas', 0);
        $action = 'edit';
        $onsubmit = 'return confirm("¿ DESEA GUARDAR ESTA MEDIDA ?")';
        $form = $this->getFormulario($action, $onsubmit, $idMedidas);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $medidasOBJ = new Medidas($form->getData());
                $this->getMedidasDAO()->guardar($medidasOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'medidas',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'medidas',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/medidas/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function detailAction() {
        $idMedidas = (int) $this->params()->fromQuery('idMedidas', 0);
        $action = 'detail';
        $onsubmit = 'return false';
        $form = $this->getFormulario($action, $onsubmit, $idMedidas);
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/medidas/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function seleccionarMedidasAction() {
//        $filtro = "cliente.estado = 'Registrado'";
        $filtro = "medidas.estado = 'Activo'";
        $view = new ViewModel(array(
            'medidass' => $this->getMedidasDAO()->getMedidass($filtro),
        ));
        $view->setTerminal(true);
        return $view;
    }

    public function getMedidasAction() {
        $idMedidas = (int) $this->params()->fromQuery('idMedidas', 0);
        if (!$idMedidas) {
            return 0;
        }
        $form = $this->getFormulario('buscar', '', $idMedidas);
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/medidas/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function anexarUsuarioAction() {
        $idUsuario = (int) $this->params()->fromQuery('idUsuario', 0);
        $usuarioOBJ = $this->getUsuarioDAO()->getUsuarios('pk_usuario_id=' . $idUsuario);

        $form = new medidasForm();
        $form->get('fk_usuario_id')->setValue($usuarioOBJ[0]->getPk_usuario_id());


        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTerminal(TRUE);
        return $view;
    }

}
