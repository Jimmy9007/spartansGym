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
use Zend\Json\Json;
use Administracion\Formularios\ProbaLluviaForm;
use Administracion\Modelo\Entidades\ProbaLluvia;

class ProbaLluviaController extends AbstractActionController {

    private $probaLluviaDAO;

    public function getProbaLluviaDAO() {
        if (!$this->probaLluviaDAO) {
            $sm = $this->getServiceLocator();
            $this->probaLluviaDAO = $sm->get('Administracion\Modelo\DAO\ProbaLluviaDAO');
        }
        return $this->probaLluviaDAO;
    }

    function getFormulario($action = '', $onsubmit = '', $idProbaLluvia = 0) {
        $required = true;
        if ($action == 'detail') {
            $required = false;
        }
        $form = new ProbaLluviaForm($action, $onsubmit, $required);
        if ($action == 'edit') {
            
        }
        if ($idProbaLluvia != 0) {
            $probaLluviaOBJ = $this->getProbaLluviaDAO()->getProbaLluvia($idProbaLluvia);
            $form->bind($probaLluviaOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {         
        return new ViewModel(array(
            'probaLluvias' => $this->getProbaLluviaDAO()->getProbaLluvias()            
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
                $probaLluviaOBJ = new ProbaLluvia($form->getData());
                $this->getProbaLluviaDAO()->guardar($probaLluviaOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'proballuvia',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'proballuvia',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('administracion/proba-lluvia/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function editAction() {
        $idProbaLluvia = (int) $this->params()->fromQuery('idProbaLluvia', 0);
        $action = 'edit';
        $onsubmit = 'return confirm("¿ DESEA GUARDAR ESTE USUARIO ?")';
        $form = $this->getFormulario($action, $onsubmit, $idProbaLluvia);
        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setData($request->getPost());
            if ($form->isValid()) {
                $probaLluviaOBJ = new ProbaLluvia($form->getData());


                $this->getProbaLluviaDAO()->guardar($probaLluviaOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'proballuvia',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'proballuvia',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/proba-lluvia/formulario');
        $view->setTerminal(true);
        return $view;
    }

    

    public function detailAction() {
        $idProbaLluvia = (int) $this->params()->fromQuery('idProbaLluvia', 0);
        $action = 'detail';
        $onsubmit = 'return false';
        $form = $this->getFormulario($action, $onsubmit, $idProbaLluvia);
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/proba-lluvia/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function deleteAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $idProbaLluvia = (int) $this->params()->fromPost('idProbaLluvia', 0);
            $resultado = $this->getProbaLluviaDAO()->eliminar($idProbaLluvia);
            $response->setContent(Json::encode(array('eliminado' => $resultado)));
        }
        return $response;
    }
    
    public function knnProbaLluviaAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
        
        $dias = (int) $this->params()->fromPost('dias', 0);
        $lluviosas = (int) $this->params()->fromPost('lluviosos', 0);  
        $datos = array();
        $sqlKnn = $this->getProbaLluviaDAO()->getKnnProbaLluvia();               
        foreach ($sqlKnn as $k){
            $dificultad[] = $k->getProbabilidad();
            $rtdo = sqrt(pow($k->getDias() - $dias, 2)+pow($k->getLluviosos() - $lluviosas, 2));
            $datos[] = $rtdo;
        }
        $minimo = array_search(min($datos), $datos);
        $valoracion = $dificultad[$minimo];     
        
        $response->setContent(Json::encode(array('Probabilidad' => $valoracion)));
        
        }
        return $response;
    }

}
