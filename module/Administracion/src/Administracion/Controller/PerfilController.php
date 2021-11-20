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
use Administracion\Modelo\Entidades\Clienteempleado;
use Administracion\Modelo\Entidades\Usuario;
use Administracion\Formularios\ClienteempleadoForm;
use Administracion\Formularios\UsuarioForm;

class PerfilController extends AbstractActionController {

    private $rutaArchivos = './public/img/profile';
    private $usuarioDAO;
    private $clienteempleadoDAO;
    private $chatDAO;

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

    public function getChatDAO() {
        if (!$this->chatDAO) {
            $sm = $this->getServiceLocator();
            $this->chatDAO = $sm->get('Administracion\Modelo\DAO\ChatDAO');
        }
        return $this->chatDAO;
    }

    function getFormulario($action = '', $onsubmit = '', $idPerfil = 0) {
        $required = true;
        if ($action == 'detail' || $action == 'buscar') {
            $required = false;
        }
        $form = new ClienteempleadoForm($action, $onsubmit, $required);
        if ($action == 'edit') {
            $form->get('estado')->setAttribute('readonly', true);
            $form->get('estado')->setAttribute('required', false);
            $form->get('estado')->setAttribute('type', 'text');
        }
        if ($idPerfil != 0) {
            $perfilOBJ = $this->getClienteempleadoDAO()->getClienteempleado($idPerfil);
            $form->bind($perfilOBJ);
        }
        return $form;
    }

    function getFormulario2($action = '', $onsubmit = '', $idPerfil = 0) {
        $required = true;
        if ($action == 'detail' || $action == 'buscar') {
            $required = false;
        }
        $form = new UsuarioForm($action, $onsubmit, $required);
        if ($action == 'editfoto') {
            $form->get('rutaFotoPerfil')->setAttribute('readonly', false);
            $form->get('rutaFotoPerfil')->setAttribute('required', true);
            $form->get('rutaFotoPerfil')->setAttribute('type', 'file');
            $form->get('fk_clienteempleado_id')->setAttribute('readonly', true);
            $form->get('fk_clienteempleado_id')->setAttribute('required', false);
            $form->get('fk_clienteempleado_id')->setAttribute('type', 'text');
            $form->get('fk_rol_id')->setAttribute('readonly', true);
            $form->get('fk_rol_id')->setAttribute('required', false);
            $form->get('fk_rol_id')->setAttribute('type', 'text');
            $form->get('genero')->setAttribute('readonly', true);
            $form->get('genero')->setAttribute('required', false);
        }
        if ($idPerfil != 0) {
            $perfilOBJ = $this->getUsuarioDAO()->getUsuario($idPerfil);
            $form->bind($perfilOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------
    public function indexAction() {
        $idUsuario = '';
        $idClienteEmpleado = '';
        if ($sesionUsuario = $this->identity()) {
            $idUsuario = $sesionUsuario->pk_usuario_id;
            $idClienteEmpleado = $sesionUsuario->fk_clienteempleado_id;
        }
        return new ViewModel(array(
            'usuario' => $this->getClienteempleadoDAO()->getPerfilUsuario($idClienteEmpleado),
        ));
    }

    public function perfilesUsuariosAction() {
        return new ViewModel(array(
            'usuarios' => $this->getClienteempleadoDAO()->getClienteempleados(),
        ));
    }

    public function chatAction() {
        $idUsuario = (int) $this->params()->fromQuery('idUsuario', 0);
    }

    public function editfotoAction() {
        $idPerfil = (int) $this->params()->fromQuery('idPerfil', 0);
        $action = 'editfoto';
        $onsubmit = 'return confirm("¿ DESEA ACTUALIZAR SU FOTO DE PERFIL ?")';
        $form = $this->getFormulario2($action, $onsubmit, $idPerfil);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $perfilOBJ = new Usuario($form->getData());
                $files = $request->getFiles()->toArray();
                $httpadapter = new \Zend\File\Transfer\Adapter\Http();
                $filesize = new \Zend\Validator\File\Size(array('max' => 5242880)); //  5 MB
                $extension = new \Zend\Validator\File\Extension(array('extension' => array('gif', 'jpg', 'png', 'jpeg', 'bmp')));
                $httpadapter->setValidators(array($filesize, $extension), $files['rutaFotoPerfil']['name']);
                if ($httpadapter->isValid()) {
                    $httpadapter->setDestination($this->rutaArchivos);
                    $ext = pathinfo($files['rutaFotoPerfil']['name'], PATHINFO_EXTENSION);
                    $archivo = strtoupper(md5(rand() . $files['rutaFotoPerfil']['name'])) . '.' . $ext;
                    $httpadapter->addFilter('File\Rename', array(
                        'target' => $this->rutaArchivos . '/' . $archivo,
                    ));
                    if ($httpadapter->receive($files['rutaFotoPerfil']['name'])) {

                        $perfilOBJ->setRutaFotoPerfil($archivo);
                        if ($this->getUsuarioDAO()->guardar($perfilOBJ) == 0) {

                            unlink($this->rutaArchivos . '/' . $archivo);
                        }
                        $this->flashMessenger()->addSuccessMessage('SU FOTO FUE ACTUALIZADO CON EXITO EN POPGYM');
                        return $this->redirect()->toRoute('administracion/default', array(
                                    'controller' => 'perfil',
                                    'action' => 'index',
                        ));
                    }
                }
            } else {
                $this->flashMessenger()->addErrorMessage('SE HA PRESENTADO UN INCONVENIENTE, SU FOTO NO FUE ACTUALIZADA EN POPGYM');
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'perfil',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('administracion/perfil/formulariofoto');
        $view->setTerminal(true);
        return $view;
    }

    public function editAction() {
        $idPerfil = (int) $this->params()->fromQuery('idPerfil', 0);
        $action = 'edit';
        $onsubmit = 'return confirm("¿ DESEA ACTUALIZAR SUS DATOS ?")';
        $form = $this->getFormulario($action, $onsubmit, $idPerfil);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $usuarioOBJ = new Clienteempleado($form->getData());
                try {
                    $this->getClienteempleadoDAO()->guardar($usuarioOBJ);
                    $this->flashMessenger()->addSuccessMessage('SUS DATOS FUERON ACTUALIZADOS CON EXITO EN POPGYM');
                } catch (\Exception $ex) {
                    $this->flashMessenger()->addErrorMessage('SE HA PRESENTADO UN INCONVENIENTE, SUS DATOS NO FUERON ACTUALIZADO CON EXITO EN POPGYM');
                    $msgLog = "\n [ " . date('Y-m-d H:i:s') . " ]  -  PERFIL  - AdministradorController->editAction \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("C:popgym.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                }


                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'perfil',
                            'action' => 'index',
                ));
            } else {
                $this->flashMessenger()->addErrorMessage('SE HA PRESENTADO UN INCONVENIENTE, SUS DATOS NO FUERON ACTUALIZADO CON EXITO EN POPGYM');
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'perfil',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/perfil/formulario');
        $view->setTerminal(true);
        return $view;
    }

}
