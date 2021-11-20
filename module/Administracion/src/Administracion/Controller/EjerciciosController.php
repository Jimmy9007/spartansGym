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
use Administracion\Formularios\EjerciciosForm;
use Administracion\Modelo\Entidades\Ejercicios;

class EjerciciosController extends AbstractActionController {

    private $ejerciciosDAO;
    private $rutaArchivos = '../gimnasio/public/img/ejercicios';

    public function getEjerciciosDAO() {
        if (!$this->ejerciciosDAO) {
            $sm = $this->getServiceLocator();
            $this->ejerciciosDAO = $sm->get('Administracion\Modelo\DAO\EjerciciosDAO');
        }
        return $this->ejerciciosDAO;
    }

    function getFormulario($action = '', $onsubmit = '', $idEjercicios = 0) {
        $required = true;
        if ($action == 'detail') {
            $required = false;
        }
        $form = new EjerciciosForm($action, $onsubmit, $required);
        if ($action == 'edit') {
        }
        if ($idEjercicios != 0) {
            $ejerciciosOBJ = $this->getEjerciciosDAO()->getEjercicios($idEjercicios);
            $form->bind($ejerciciosOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
//        $filtro = "ejercicios.estado = 'Eliminado'";
        return new ViewModel(array(
            'ejercicioss' => $this->getEjerciciosDAO()->getEjercicioss()
        ));
    }

    public function addAction() {
        $action = 'add';
        $onsubmit = 'return confirm("DESEA REGISTRAR ESTE EJERCICIO ?")';
        $form = $this->getFormulario($action, $onsubmit);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $files = $request->getFiles()->toArray();
                $httpadapter = new \Zend\File\Transfer\Adapter\Http();
                $filesize = new \Zend\Validator\File\Size(array('max' => 5000000)); //  5 MB
                $extension = new \Zend\Validator\File\Extension(array('extension' => array('gif', 'jpg', 'png', 'jpeg')));
                $httpadapter->setValidators(array($filesize, $extension), $files['RUTA_IMG_EJER']['name']);
                if ($httpadapter->isValid()) {
                    $httpadapter->setDestination($this->rutaArchivos);
                    $ext = pathinfo($files['RUTA_IMG_EJER']['name'], PATHINFO_EXTENSION);
                    $archivo = strtoupper(md5(rand() . $files['RUTA_IMG_EJER']['name'])) . '.' . $ext;
                    $httpadapter->addFilter('File\Rename', array(
                        'target' => $this->rutaArchivos . '/' . $archivo,
                    ));
                    if ($httpadapter->receive($files['documento']['name'])) {
                        $ejerciciosOBJ = new Ejercicios($form->getData());
                        $ejerciciosOBJ->setRUTA_IMG_EJER($archivo);
                        if ($this->getEjerciciosDAO()->guardar($ejerciciosOBJ) == 0) {
                            unlink($this->rutaArchivos . '/' . $archivo);
                        }
                        $this->flashMessenger()->addSuccessMessage('EL EJERCICIO FUE REGISTRADO EN SPARTANS');
                        return $this->redirect()->toUrl('index');
                    }
                } else {
                    $this->flashMessenger()->addErrorMessage('SE HA PRESENTADO UN INCONVENIENTE, LA FOTO EXCEDE EL TAMANO REQUERIDO DE 2MB');
                    return $this->redirect()->toUrl('index');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('SE HA PRESENTADO UN INCONVENIENTE, LA FOTO NO CUMPLE CON LOS FORMATOS REQUERIDOS gif, jpg, png y jpeg.');
                return $this->redirect()->toUrl('index');
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/ejercicios/formulario');
        return $view->setTerminal(true);
    }

    public function editAction() {
        $idEjercicios = (int) $this->params()->fromQuery('idEjercicios', 0);
        $action = 'edit';
        $onsubmit = 'return confirm("DESEA GUARDAR ESTE EJERCICIO ?")';
        $form = $this->getFormulario($action, $onsubmit, $idEjercicios);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $files = $request->getFiles()->toArray();
                $httpadapter = new \Zend\File\Transfer\Adapter\Http();
                $filesize = new \Zend\Validator\File\Size(array('max' => 5000000)); //  5 MB
                $extension = new \Zend\Validator\File\Extension(array('extension' => array('gif', 'jpg', 'png', 'jpeg')));
                $httpadapter->setValidators(array($filesize, $extension), $files['RUTA_IMG_EJER']['name']);
                if ($httpadapter->isValid()) {
                    $httpadapter->setDestination($this->rutaArchivos);
                    $ext = pathinfo($files['RUTA_IMG_EJER']['name'], PATHINFO_EXTENSION);
                    $archivo = strtoupper(md5(rand() . $files['RUTA_IMG_EJER']['name'])) . '.' . $ext;
                    $httpadapter->addFilter('File\Rename', array(
                        'target' => $this->rutaArchivos . '/' . $archivo,
                    ));
                    if ($httpadapter->receive($files['RUTA_IMG_EJER']['name'])) {
                        $ejerciciosOBJ = new Ejercicios($form->getData());
                        $ejerciciosOBJ->setRUTA_IMG_EJER($archivo);
                        if ($this->getEjerciciosDAO()->guardar($ejerciciosOBJ) == 0) {
                            unlink($this->rutaArchivos . '/' . $archivo);
                        }
                        $this->flashMessenger()->addSuccessMessage('EL EJERCICIO FUE REGISTRADO EN SPARTANS');
                        return $this->redirect()->toUrl('index');
                    }
                } else {
                    $this->flashMessenger()->addErrorMessage('SE HA PRESENTADO UN INCONVENIENTE, LA FOTO EXCEDE EL TAMANO REQUERIDO DE 2MB');
                    return $this->redirect()->toUrl('index');
                }
            } else {
                $this->flashMessenger()->addErrorMessage('SE HA PRESENTADO UN INCONVENIENTE, LA FOTO NO CUMPLE CON LOS FORMATOS REQUERIDOS gif, jpg, png y jpeg.');
                return $this->redirect()->toUrl('index');
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/ejercicios/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function detailAction() {
        $idEjercicios = (int) $this->params()->fromQuery('idEjercicios', 0);
        $action = 'detail';
        $onsubmit = 'return false';
        $form = $this->getFormulario($action, $onsubmit, $idEjercicios);
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/ejercicios/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function seleccionarEjerciciosAction() {
        $idRutina = (int) $this->params()->fromQuery('idRutina', 0);
        $filtro = "ejercicios.pk_ejercicio_id NOT IN (SELECT rutinas_ejercicios.pk_ejercicio_id FROM rutinas_ejercicios WHERE rutinas_ejercicios.pk_rutina_id = $idRutina)";
        $view = new ViewModel(array(
            'ejercicios' => $this->getEjerciciosDAO()->getEjercicioss($filtro)
        ));
        $view->setTerminal(true);
        return $view;
    }

    public function getEjerciciosSeleccionadaAction() {
        $idEjercicios = (int) $this->params()->fromQuery('idEjercicios', 0);
        if (!$idEjercicios) {
            return 0;
        }
        try {
            $ejerciciosOBJ = $this->getOpcionDAO()->getOpcion($idEjercicios);
        } catch (\Exception $ex) {
            return 0;
        }
        $formOpcion = new OpcionFormm();
        $formOpcion->bind($ejerciciosOBJ);
        $view = new ViewModel(array(
            'formEjercicios' => $formEjercicios,
        ));
        $view->setTerminal(true);
        return $view;
    }

    public function getDetalleEjercicioAction() {
        $idEjercicio = (int) $this->params()->fromPost('idEjercicio', 0);
        $idEjercicio;
        $sl = $this->getEjerciciosDAO()->getDetalleEjercicio($idEjercicio);
        $view = new ViewModel(array(
            'ejercicio' => $this->getEjerciciosDAO()->getDetalleEjercicio($idEjercicio)
        ));
        $view->setTerminal(true);
        return $view;
    }

}
