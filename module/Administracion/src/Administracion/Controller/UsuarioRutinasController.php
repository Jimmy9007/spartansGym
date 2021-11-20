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
use Administracion\Formularios\UsuarioRutinasForm;
use Administracion\Modelo\Entidades\UsuarioRutinas;
use Dompdf\Dompdf;

class UsuarioRutinasController extends AbstractActionController {

    private $usuarioRutinasDAO;
    private $rutinasDAO;
    private $usuarioDAO;

    public function getUsuarioRutinasDAO() {
        if (!$this->usuarioRutinasDAO) {
            $sm = $this->getServiceLocator();
            $this->usuarioRutinasDAO = $sm->get('Administracion\Modelo\DAO\UsuarioRutinasDAO');
        }
        return $this->usuarioRutinasDAO;
    }

    public function getRutinaDAO() {
        if (!$this->rutinasDAO) {
            $sm = $this->getServiceLocator();
            $this->rutinasDAO = $sm->get('Administracion\Modelo\DAO\RutinasDAO');
        }
        return $this->rutinasDAO;
    }

    public function getUsuarioDAO() {
        if (!$this->usuarioDAO) {
            $sm = $this->getServiceLocator();
            $this->usuarioDAO = $sm->get('Administracion\Modelo\DAO\UsuarioDAO');
        }
        return $this->usuarioDAO;
    }

    function getFormulario($action = '', $onsubmit = '', $idUsuarioRutinas = 0) {
        $required = true;
        if ($action == 'detail') {
            $required = false;
        }
        $usuarios = $this->getUsuarioDAO()->getUsuarios();
        $usuariosSelect = array();
        foreach ($usuarios as $usu) {
            $usuariosSelect[$usu['usuarioOBJ']->getPk_usuario_id()] = $usu['usuarioOBJ']->getNombreApellido();
        }
        $form = new UsuarioRutinasForm($action, $onsubmit, $required, $usuariosSelect);
        if ($idUsuarioRutinas != 0) {
            $rutinasOBJ = $this->getUsuarioRutinasDAO()->getUsuarioRutinas($idUsuarioRutinas);
            $form->bind($rutinasOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
//        $filtro = "rutinas.estado = 'Eliminado'";
        return new ViewModel(array(
            'usuariorutinas' => $this->getUsuarioRutinasDAO()->getUsuariosRutinas()
        ));
    }

    public function indexrutinausuarioAction() {
        $idUsuario = '';
        if ($sesionUsuario = $this->identity()) {
            $idUsuario = $sesionUsuario->pk_usuario_id;
        }
//        $filtro = "medidas.estado = 'Eliminado'";
        return new ViewModel(array(
            'rutinass' => $this->getUsuarioRutinasDAO()->getUsuarioRutinasUsuario($idUsuario)
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
                $rutinasOBJ = new UsuarioRutinas($form->getData());
                $this->getUsuarioRutinasDAO()->guardarUsuarioRutina($rutinasOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'usuariorutinas',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'usuariorutinas',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('administracion/usuario-rutinas/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function editAction() {
        $idUsuarioRutinas = (int) $this->params()->fromQuery('idUsuarioRutinas', 0);
        $action = 'edit';
        $onsubmit = 'return confirm("¿ DESEA GUARDAR ESTA RUTINA ?")';
        $form = $this->getFormulario($action, $onsubmit, $idUsuarioRutinas);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $rutinasOBJ = new UsuarioRutinas($form->getData());
                $this->getUsuarioRutinasDAO()->guardar($rutinasOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'rutinas',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'rutinas',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/rutinas/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function detailAction() {
        $idUsuarioRutinas = (int) $this->params()->fromQuery('idUsuarioRutinas', 0);
        $action = 'detail';
        $onsubmit = 'return false';
        $form = $this->getFormulario($action, $onsubmit, $idUsuarioRutinas);
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/rutinas/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function getUsuarioRutinasSeleccionadaAction() {
        $idUsuarioRutinas = (int) $this->params()->fromQuery('idUsuarioRutinas', 0);
        if (!$idUsuarioRutinas) {
            return 0;
        }
        try {
            $rutinasOBJ = $this->getOpcionDAO()->getOpcion($idUsuarioRutinas);
        } catch (\Exception $ex) {
            return 0;
        }
        $formOpcion = new OpcionFormm();
        $formOpcion->bind($rutinasOBJ);
        $view = new ViewModel(array(
            'formUsuarioRutinas' => $formUsuarioRutinas,
        ));
        $view->setTerminal(true);
        return $view;
    }

    public function configuracionAction() {
        $idUsuarioRutinas = (int) $this->params()->fromQuery('idUsuarioRutinas', 0);
        if ($idUsuarioRutinas == 0) {
            return $this->redirect()->toRoute('administracion/default', array(
                        'controller' => 'rutinas',
                        'action' => 'index',
            ));
        }
        $action = 'configuracion';
        $onsubmit = 'return false';
        $form = $this->getFormulario($action, $onsubmit, $idUsuarioRutinas);
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/rutinas/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function anexarEjerciciosAction() {
        $idUsuarioRutinas = (int) $this->params()->fromRoute('id1', 0);
        if ($idUsuarioRutinas == 0) {
            return $this->redirect()->toRoute('administracion/default', array(
                        'controller' => 'rutinas',
                        'action' => 'index',
            ));
        }
        $action = 'detail';
        $onsubmit = 'return false';
        $form = $this->getFormulario($action, $onsubmit, $idUsuarioRutinas);
        $view = new ViewModel(array(
            'form' => $form,
            'ejercicios' => $this->getEjerciciosDAO()->getEjerciciosUsuarioRutinas($idUsuarioRutinas),
        ));
        return $view;
    }

    public function anexarUsuarioAction() {
        $idUsuario = (int) $this->params()->fromQuery('idUsuario', 0);
        $usuarioOBJ = $this->getUsuarioDAO()->getUsuarios('pk_usuario_id=' . $idUsuario);

        $form = new ContratoLaboralForm();
        $form->get('fk_usuario_id')->setValue($usuarioOBJ[0]->getPk_usuario_id());


        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTerminal(TRUE);
        return $view;
    }

    public function setEjerciciosUsuarioRutinasAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $datos = $request->getPost()->toArray();
            $this->getUsuarioRutinasDAO()->anexarEjercicio($datos);
            return $this->redirect()->toRoute('administracion/default', array(
                        'controller' => 'rutinas',
                        'action' => 'anexarEjercicios',
                        'id1' => $datos['idRutinaSelect'],
            ));
        }
        return $this->redirect()->toRoute('administracion/default', array(
                    'controller' => 'rutinas',
                    'action' => 'index',
        ));
    }

    public function add2Action() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $datos = $request->getPost()->toArray();
            $this->getUsuarioRutinasDAO()->anexarEjercicio($datos);
            return $this->redirect()->toRoute('administracion/default', array(
                        'controller' => 'rutinas',
                        'action' => 'anexarEjercicios',
                        'id1' => $datos['idRutinaSelect'],
            ));
        }
        return $this->redirect()->toRoute('administracion/default', array(
                    'controller' => 'usuariorutinas',
                    'action' => 'index',
        ));
    }

    public function imprimirAction() {
        $idRutina = (int) $this->params()->fromRoute('id1', 0);
        if ($idRutina == 0) {
            return;
        }
//        $valorLetras = new ValorEnLetras();
        $rutinasOBJ = $this->getUsuarioRutinasDAO()->getUsuarioRutinas($idRutina);
        $ejerciciosRutina = $this->getUsuarioRutinasDAO()->getEjerciciosRutina($idRutina);

        $plantilla = new ViewModel(array(
            'ejerciciosRutina' => $ejerciciosRutina
        ));
        $marcadores = $rutinasOBJ->getArrayCopy();
        $plantilla->setTerminal(true);
        $plantilla->setTemplate('administracion/rutinas/ejerciciosRutina');
        $marcadores['ejerciciosPDF'] = $this->getServiceLocator()->get('viewrenderer')->render($plantilla);

        $this->crearPDF($marcadores);
    }

    public function crearPDF($marcadores = array()) {
        $plantilla = file_get_contents('module/Administracion/view/administracion/rutinas/imprimir.phtml');
        $html = $this->setMarcadores($plantilla, $marcadores);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('Rutina de ejercicios', array('Attachment' => 0));
    }

    public function setMarcadores($plantilla = '', $marcadores = array()) {
        foreach ($marcadores as $campo => $vlr) {
            $plantilla = str_replace('{' . $campo . '}', $vlr, $plantilla);
        }
        return $plantilla;
    }

    public function rutinaAutomaticaAction() {
        $idUsuario = (int) $this->params()->fromQuery('idUsuario', 0);
        $sql = 'rutinas.fk_usuario_id = ' . $idUsuario;
        $sql = 'ruti.fk_usuario_id = ' . $idUsuario;
        $usuarioUnicoMedidas = $this->getUsuarioRutinasDAO()->getUsuarioRutinass($sql);
        $usuariosMedidas = $this->getUsuarioRutinasDAO()->getUsuarioRutinass();

        var_dump('Nombre de la persona seleccionada: ' . $usuarioUnicoMedidas[0]['medidasOBJ']->getESTATURA());

        foreach ($usuariosMedidas as $um) {

            var_dump('Nombres Usuarios: ' . $um['usuarioOBJ']->getNOM_USU());
        }
        $form = $idUsuario;
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/rutinas/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function getDetalleEjercicioAction() {
        $idEjercicio = (int) $this->params()->fromPost('idEjercicios', 0); //idEjercicios Viene de rutina.js
        $idEjercicio;
        $sl = $this->getEjerciciosDAO()->getEjerciciosDetalle($idEjercicio);
        $view = new ViewModel(array(
            'ejercicio' => $this->getEjerciciosDAO()->getEjerciciosDetalle($idEjercicio)
        ));
        $view->setTerminal(true);
        return $view;
    }

    public function deleteEjercicioAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $idEjercicio = (int) $this->params()->fromPost('idEjercicio', 0); //idEjercicio Viene de rutina.js
            $idRutina = (int) $this->params()->fromPost('idRutina', 0); //idRutina Viene de rutina.js
            $resultado = $this->getUsuarioRutinasDAO()->eliminarEjercicio($idEjercicio, $idRutina);
            $response->setContent(Json::encode(array('eliminado' => $resultado)));
        }
        return $response;
    }

}
