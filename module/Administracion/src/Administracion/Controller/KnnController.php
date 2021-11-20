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
use Administracion\Formularios\KnnForm;
use Administracion\Modelo\Entidades\Knn;

class KnnController extends AbstractActionController {

    private $knnDAO;

    public function getKnnDAO() {
        if (!$this->knnDAO) {
            $sm = $this->getServiceLocator();
            $this->knnDAO = $sm->get('Administracion\Modelo\DAO\KnnDAO');
        }
        return $this->knnDAO;
    }

    function getFormulario($action = '', $onsubmit = '', $idKnn = 0) {
        $required = true;
        if ($action == 'detail') {
            $required = false;
        }
        $form = new KnnForm($action, $onsubmit, $required);
        if ($action == 'edit') {
            
        }
        if ($idKnn != 0) {
            $knnOBJ = $this->getKnnDAO()->getKnn($idKnn);
            $form->bind($knnOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {         
        return new ViewModel(array(
            'knns' => $this->getKnnDAO()->getKnns()            
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
                $knnOBJ = new Knn($form->getData());
                $this->getKnnDAO()->guardar($knnOBJ);
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
        $idKnn = (int) $this->params()->fromQuery('idKnn', 0);
        $action = 'edit';
        $onsubmit = 'return confirm("¿ DESEA GUARDAR ESTE USUARIO ?")';
        $form = $this->getFormulario($action, $onsubmit, $idKnn);
        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setData($request->getPost());
            if ($form->isValid()) {
                $knnOBJ = new Knn($form->getData());


                $this->getKnnDAO()->guardar($knnOBJ);
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
        $idKnn = (int) $this->params()->fromQuery('idKnn', 0);
        $action = 'detail';
        $onsubmit = 'return false';
        $form = $this->getFormulario($action, $onsubmit, $idKnn);
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
            $idKnn = (int) $this->params()->fromPost('idKnn', 0);
            $resultado = $this->getKnnDAO()->eliminar($idKnn);
            $response->setContent(Json::encode(array('eliminado' => $resultado)));
        }
        return $response;
    }
    
    public function KnnAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
        
        $dias = (int) $this->params()->fromPost('dias', 0);
        $lluviosas = (int) $this->params()->fromPost('lluviosos', 0);  
        $datos = array();
        $sqlKnn = $this->getKnnDAO()->getKnnKnn();               
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
