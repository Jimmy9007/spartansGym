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
use Administracion\Formularios\AsistenciaForm;
use Administracion\Modelo\Entidades\Asistencia;

class AsistenciaController extends AbstractActionController {

    private $asistenciaDAO;
    private $usuarioDAO;
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

    public function getUsuarioDAO() {
        if (!$this->usuarioDAO) {
            $sm = $this->getServiceLocator();
            $this->usuarioDAO = $sm->get('Administracion\Modelo\DAO\UsuarioDAO');
        }
        return $this->usuarioDAO;
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

    public function indexAction() {
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
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'asistencia',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'asistencia',
                            'action' => 'index',
                ));
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

    public function editAction() {
        $idAsistencia = (int) $this->params()->fromQuery('idAsistencia', 0);
        $action = 'edit';
        $onsubmit = 'return confirm("¿ DESEA GUARDAR ESTE USUARIO ?")';
        $form = $this->getFormulario($action, $onsubmit, $idAsistencia);
        $request = $this->getRequest();
        if ($request->isPost()) {
//            $nombreAsistencia = '';
//            if ($sesionAsistencia = $this->identity()) {
//                $nombreAsistencia = $sesionAsistencia->login;
//            }
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $asistenciaOBJ = new Asistencia($form->getData());
//                $nombreAsistencia = '';
//                if ($sesionAsistencia = $this->identity()) {
//                    $nombreAsistencia = $sesionAsistencia->login;
//                }
//                $asistenciaOBJ->setModificadoPor($nombreAsistencia);
//                $asistenciaOBJ->setFechaHoraMod(date('Y-m-d H:i:s'));

                $this->getAsistenciaDAO()->guardar($asistenciaOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'asistencia',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'asistencia',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/asistencia/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function detailAction() {
        $idAsistencia = (int) $this->params()->fromQuery('idAsistencia', 0);
        $action = 'detail';
        $onsubmit = 'return false';
        $form = $this->getFormulario($action, $onsubmit, $idAsistencia);
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/asistencia/formulario');
        $view->setTerminal(true);
        return $view;
    }

//    public function seleccionarAsistenciaAction() {
////        $filtro = "cliente.estado = 'Registrado'";
//        $idPregunta = (int) $this->params()->fromQuery('idPregunta', 0);
//        $filtro = "opcion.pk_opcion_id NOT IN (SELECT pregunta_opcion.fk_opcion_id FROM pregunta_opcion WHERE pregunta_opcion.fk_pregunta_id = $idPregunta)";
//        $view = new ViewModel(array(
//            'opciones' => $this->getOpcionDAO()->getOpciones($filtro),
//            'idPregunta' => $idPregunta
//        ));
//        $view->setTerminal(true);
//        return $view;
//    }

    public function getAsistenciaSeleccionadaAction() {
        $idAsistencia = (int) $this->params()->fromQuery('idAsistencia', 0);
        if (!$idAsistencia) {
            return 0;
        }
        try {
            $asistenciaOBJ = $this->getOpcionDAO()->getOpcion($idAsistencia);
        } catch (\Exception $ex) {
            return 0;
        }
        $formOpcion = new OpcionFormm();
        $formOpcion->bind($asistenciaOBJ);
        $view = new ViewModel(array(
            'formAsistencia' => $formAsistencia,
        ));
        $view->setTerminal(true);
        return $view;
    }

}
