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
use Administracion\Formularios\AsistenciaForm;
use Administracion\Modelo\Entidades\Asistencia;

class AsistenciaController extends AbstractActionController {

    private $asistenciaDAO;
    private $mensualidadDAO;

    public function getAsistenciaDAO() {
        if (!$this->asistenciaDAO) {
            $sm = $this->getServiceLocator();
            $this->asistenciaDAO = $sm->get('Administracion\Modelo\DAO\AsistenciaDAO');
        }
        return $this->asistenciaDAO;
    }

    public function getMensualidadDAO() {
        if (!$this->mensualidadDAO) {
            $sm = $this->getServiceLocator();
            $this->mensualidadDAO = $sm->get('Administracion\Modelo\DAO\MensualidadDAO');
        }
        return $this->mensualidadDAO;
    }

    function getFormulario($action = '', $onsubmit = '', $idAsistencia = 0) {
        $required = true;
        if ($action == 'detail') {
            $required = false;
        }
        $form = new AsistenciaForm($action, $onsubmit, $required);
        if ($action == 'edit') {
            
        }
        if ($idAsistencia != 0) {
            $asistenciaOBJ = $this->getAsistenciaDAO()->getAsistencia($idAsistencia);
            $form->bind($asistenciaOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------
    public function asistenciaAction() {
        //        $filtro = "asistencia.estado = 'Eliminado'";
        return new ViewModel(array(
            'asistencias' => $this->getAsistenciaDAO()->getAsistencias()
        ));
    }

    public function mensajeAction() {
        $listaUsuario = array();
        $idUsuario = (int) $this->params()->fromQuery('idUsuario', 0);
        $consulta = $this->getMensualidadDAO()->getMensualidadeUsuario($idUsuario);
        foreach ($consulta as $usuario) {
            $nombres = $usuario['usuarioOBJ']->getNombreApellido();
            $diasFaltantes = $usuario['diasPreaviso'];
        }
        if ($diasFaltantes >= 4) {
            $this->flashMessenger()->addSuccessMessage('(' . $diasFaltantes . ') DIAS FALTANTES');
        } elseif ($diasFaltantes <= 3 && $diasFaltantes >= 0) {
            $this->flashMessenger()->addWarningMessage('(' . $diasFaltantes . ') DIAS FALTANTES');
        } elseif ($diasFaltantes < 0) {
            $this->flashMessenger()->addErrorMessage('MENSUALIDAD VENCIDA: (' . $diasFaltantes . ')');
        }
    }

    public function addAction() {
        $action = 'add';
        $onsubmit = 'return confirm("¿ DESEA REGISTRAR ESTA ASISTENCIA ?")';
        $form = $this->getFormulario($action, $onsubmit);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $asistenciaOBJ = new Asistencia($form->getData());
                $asistenciaOBJ->setFECHA_ASIS(date('Y-m-d H:i:s'));
                $this->getAsistenciaDAO()->guardar($asistenciaOBJ);
                return $this->redirect()->toUrl('asistencia');
            } else {
                return $this->redirect()->toUrl('asistencia');
            }
        }
        $view = new ViewModel(array(
            'form' => $form,
            'usuarios' => $this->getMensualidadDAO()->getMensualidades(),
        ));
        $view->setTemplate('administracion/asistencia/formulario');
        $view->setTerminal(true);
        return $view;
    }

}
