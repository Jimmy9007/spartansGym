<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Administracion\Controller;

require_once 'vendor/dompdf/autoload.inc.php';

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Administracion\Formularios\TurnoForm;
use Administracion\Modelo\Entidades\Turno;
use Dompdf\Dompdf;

class TurnoController extends AbstractActionController {

    private $turnoDAO;
    private $usuarioDAO;

    public function getTurnoDAO() {
        if (!$this->turnoDAO) {
            $sm = $this->getServiceLocator();
            $this->turnoDAO = $sm->get('Administracion\Modelo\DAO\TurnoDAO');
        }
        return $this->turnoDAO;
    }

    public function getUsuarioDAO() {
        if (!$this->usuarioDAO) {
            $sm = $this->getServiceLocator();
            $this->usuarioDAO = $sm->get('Administracion\Modelo\DAO\UsuarioDAO');
        }
        return $this->usuarioDAO;
    }

    function getFormulario($action = '', $onsubmit = '', $idTurno = 0) {
        $required = true;
        $filtroEmpleado = 'usuario.fk_rol_id = 4 OR usuario.fk_rol_id = 3';
        if ($action == 'detail' || $action == 'buscar') {
            $required = false;
        }
        $Usuarios = $this->getUsuarioDAO()->getUsuarios($filtroEmpleado);
        $UsuariosSelect = array();
        foreach ($Usuarios as $Inst) {
            $UsuariosSelect[$Inst['usuarioOBJ']->getPk_usuario_id()] = $Inst['usuarioOBJ']->getNombreApellido();
        }
        $form = new TurnoForm($action, $onsubmit, $required, $UsuariosSelect);
        if ($action == 'detail') {
            $form->get('fechaInicio')->setAttribute('type', 'text');
            $form->get('fechaFinal')->setAttribute('type', 'text');
        }
        if ($idTurno != 0) {
            $turnoOBJ = $this->getTurnoDAO()->getTurno($idTurno);
            $form->bind($turnoOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        return new ViewModel(array(
            'turnos' => $this->getTurnoDAO()->getTurnos()
        ));
    }

    public function indexReporteAction() {
        $filtroEmpleado = 'usuario.fk_rol_id = 4 OR usuario.fk_rol_id = 3';
        $form = $this->getFormulario($action = '', $onsubmit = '', $idTurno = 0);
        $Usuarios = $this->getUsuarioDAO()->getUsuarios($filtroEmpleado);
        $UsuariosSelect = array();
        foreach ($Usuarios as $Inst) {
            $UsuariosSelect[$Inst['usuarioOBJ']->getPk_usuario_id()] = $Inst['usuarioOBJ']->getNombreApellido();
        }
        $form = new TurnoForm($action, $onsubmit, $required = true, $UsuariosSelect);
        $fecha = date('Y-m-d');
        if ($_GET) {
            $fechaI = $_GET['fechaReporteInicial'];
            $fechaF = $_GET['fechaReporteFinal'];
            $inst = $_GET['inst'];

            $reporteTurno = $this->getTurnoDAO()->getTurnos($where = "DATE(turno.fechaInicio) BETWEEN '" . $fechaI . "' AND '" . $fechaF . "' AND turno.fk_usuario_id =$inst");

            $plantilla = new ViewModel(array(
                'reporteTurno' => $reporteTurno,
            ));
            $plantilla->setTerminal(true);
            $plantilla->setTemplate('administracion/turno/turnoReporte');
            $marcadores['turnosPDF'] = $this->getServiceLocator()->get('viewrenderer')->render($plantilla);

            $this->crearPDF($marcadores);
        }




        $form->get('fechaReporteInicial')->setValue($fecha);
        $form->get('fechaReporteFinal')->setValue($fecha);
        return new ViewModel(array(
            'formTurno' => $form,
        ));
    }

    public function addAction() {
        $action = 'add';
        $onsubmit = 'return confirm("¿ DESEA REGISTRAR ESTE TURNO ?")';
        $form = $this->getFormulario($action, $onsubmit);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $turnoOBJ = new Turno($form->getData());
                $this->getTurnoDAO()->guardar($turnoOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'turno',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'turno',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/turno/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function editAction() {
        $idTurno = (int) $this->params()->fromQuery('idTurno', 0);
        $action = 'edit';
        $onsubmit = 'return confirm("¿ DESEA GUARDAR ESTE USUARIO ?")';
        $form = $this->getFormulario($action, $onsubmit, $idTurno);
        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setData($request->getPost());
            if ($form->isValid()) {
                $turnoOBJ = new Turno($form->getData());

                $this->getTurnoDAO()->guardar($turnoOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'turno',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'turno',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/turno/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function detailAction() {
        $idTurno = (int) $this->params()->fromQuery('idTurno', 0);
        $action = 'detail';
        $onsubmit = 'return false';
        $form = $this->getFormulario($action, $onsubmit, $idTurno);
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/turno/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function crearPDF($marcadores = array()) {
        $plantilla = file_get_contents('module/Administracion/view/administracion/turno/imprimir.phtml');
        $html = $this->setMarcadores($plantilla, $marcadores);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('Reportes Turnos', array('Attachment' => 0));
    }

    public function setMarcadores($plantilla = '', $marcadores = array()) {
        foreach ($marcadores as $campo => $vlr) {
            $plantilla = str_replace('{' . $campo . '}', $vlr, $plantilla);
        }
        return $plantilla;
    }

}
