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
use Administracion\Formularios\UsuarioForm;
use Administracion\Formularios\CambiocontrasenaForm;
use Administracion\Modelo\Entidades\Usuario;

class UsuarioController extends AbstractActionController {

    private $usuarioDAO;
    private $clienteempleadoDAO;
    private $rolDAO;
    private $medidasDAO;
    private $rutinasDAO;

    //------------------------------------------------------------------------------    

    public function getInfoSesionUsuario() {
        if ($sesionUsuario = $this->identity()) {
            $infoSession = array(
                'pk_usuario_id' => $sesionUsuario->pk_usuario_id,
                'nombreApellido' => substr(trim($sesionUsuario->nombreApellido), 0, 20),
            );
        } else {
            $infoSession = array(
                'pk_usuario_id' => 0,
                'nombreApellido' => '',
            );
        }
        return $infoSession;
    }

//------------------------------------------------------------------------------ 

    public function getUsuarioDAO() {
        if (!$this->usuarioDAO) {
            $sm = $this->getServiceLocator();
            $this->usuarioDAO = $sm->get('Administracion\Modelo\DAO\UsuarioDAO');
        }
        return $this->usuarioDAO;
    }

    public function getRolDAO() {
        if (!$this->rolDAO) {
            $sm = $this->getServiceLocator();
            $this->rolDAO = $sm->get('Administracion\Modelo\DAO\RolDAO');
        }
        return $this->rolDAO;
    }

    public function getClienteempleadoDAO() {
        if (!$this->clienteempleadoDAO) {
            $sm = $this->getServiceLocator();
            $this->clienteempleadoDAO = $sm->get('Administracion\Modelo\DAO\ClienteempleadoDAO');
        }
        return $this->clienteempleadoDAO;
    }

    public function getMedidasDAO() {
        if (!$this->medidasDAO) {
            $sm = $this->getServiceLocator();
            $this->medidasDAO = $sm->get('Administracion\Modelo\DAO\MedidasDAO');
        }
        return $this->medidasDAO;
    }

    public function getRutinasDAO() {
        if (!$this->rutinasDAO) {
            $sm = $this->getServiceLocator();
            $this->rutinasDAO = $sm->get('Administracion\Modelo\DAO\RutinasDAO');
        }
        return $this->rutinasDAO;
    }

    function getFormulario($action = '', $onsubmit = '', $idUsuario = 0) {
        $required = true;
        $listaClienteEmpleado = array();
        $listaRoles = array();
        $filtro = "clienteempleado.pk_clienteempleado_id NOT IN (SELECT clienteempleado.pk_clienteempleado_id FROM clienteempleado INNER JOIN usuario ON clienteempleado.pk_clienteempleado_id = usuario.fk_clienteempleado_id WHERE usuario.pk_usuario_id > 0) and clienteempleado.estado = 'Activo'";
        $clientesempleados = $this->getClienteempleadoDAO()->getClienteempleados($filtro);
        foreach ($clientesempleados as $clienteempleado) {
            $nombres = $clienteempleado->getNombre();
            $apellidos = $clienteempleado->getApellido();
            $listaClienteEmpleado[$clienteempleado->getPk_clienteempleado_id()] = trim($nombres) . ' ' . trim($apellidos);
        }
        $roles = $this->getRolDAO()->getRols();
        foreach ($roles as $rolOBJ) {
            $listaRoles[$rolOBJ->getPk_rol_id()] = $rolOBJ->getRol();
        }
        if ($action == 'detail' || $action == 'buscar') {
            $required = false;
        }
        $form = new UsuarioForm($action, $onsubmit, $required, $listaClienteEmpleado, $listaRoles);
        if ($action == 'add') {
            $form->get('rutaFotoPerfil')->setAttribute('readonly', true);
            $form->get('rutaFotoPerfil')->setAttribute('required', false);
            $form->get('rutaFotoPerfil')->setAttribute('type', 'text');
        }
        if ($action == 'edit') {
            $form->get('login')->setAttribute('readonly', true);
            $form->get('login')->setAttribute('required', false);
            $form->get('password')->setAttribute('readonly', true);
            $form->get('password')->setAttribute('required', false);
            $form->get('passwordseguro')->setAttribute('readonly', true);
            $form->get('passwordseguro')->setAttribute('required', false);
            $form->get('rutaFotoPerfil')->setAttribute('readonly', true);
            $form->get('rutaFotoPerfil')->setAttribute('required', false);
            $form->get('rutaFotoPerfil')->setAttribute('type', 'text');
        }
        if ($idUsuario != 0) {
            $usuarioOBJ = $this->getUsuarioDAO()->getUsuario($idUsuario);
            $form->bind($usuarioOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        $filtro = "usuario.estado != 'Eliminado'";
        return new ViewModel(array(
            'usuarios' => $this->getUsuarioDAO()->getUsuarios($filtro),
            'usuariosTotal' => $this->getUsuarioDAO()->getCountUsuarios(),
            'MujeresTotal' => $this->getUsuarioDAO()->getCountMujeres(),
            'HombresTotal' => $this->getUsuarioDAO()->getCountHombres()
        ));
    }

    public function addAction() {
        $action = 'add';
        $onsubmit = 'return confirmAdd()';
        $form = $this->getFormulario($action, $onsubmit);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $usuarioOBJ = new Usuario($form->getData());
                if ($usuarioOBJ->getGenero() == 'Masculino') {
                    $usuarioOBJ->setRutaFotoPerfil('perfilHombre.png');
                } else {
                    $usuarioOBJ->setRutaFotoPerfil('perfilMujer.png');
                }
                $config = $this->getServiceLocator()->get('Config');
                $passwordSeguro = $config['passwordSeguro'];

                $usuarioOBJ->setpassword(md5($passwordSeguro . $usuarioOBJ->getPassword() . $usuarioOBJ->getPasswordseguro()));
                $usuarioOBJ->setEstado('Activo');

                if ($this->getUsuarioDAO()->guardar($usuarioOBJ) > 0) {
                    $this->flashMessenger()->addSuccessMessage('EL USUARIO FUE REGISTRADO EN POPGYM');
                } else {
                    $this->flashMessenger()->addErrorMessage('SE HA PRESENTADO UN INCONVENIENTE, EL USUARIO NO FUE REGISTRADO EN SPARTANS');
                }
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'usuario',
                            'action' => 'index',
                ));
            } else {
                $this->flashMessenger()->addErrorMessage('SE HA PRESENTADO UN INCONVENIENTE, EL USUARIO NO FUE REGISTRADO EN SPARTANS');
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'usuario',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/usuario/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function editAction() {
        $idUsuario = (int) $this->params()->fromQuery('idUsuario', 0);
        $action = 'edit';
        $onsubmit = 'return confirm("¿ DESEA GUARDAR ESTE USUARIO ?")';
        $form = $this->getFormulario($action, $onsubmit, $idUsuario);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $usuarioOBJ = new Usuario($form->getData());
                try {
                    $this->getUsuarioDAO()->guardar($usuarioOBJ);
                    $this->flashMessenger()->addSuccessMessage('EL USUARIO FUE EDITADO EN POPGYM');
                } catch (\Exception $ex) {
                    $this->flashMessenger()->addErrorMessage('SE HA PRESENTADO UN INCONVENIENTE, EL USUARIO NO FUE REGISTRADO EN POPGYM');
                    $msgLog = "\n [ " . date('Y-m-d H:i:s') . " ]  -  USUARIO  - AdministradorController->addAction \n"
                            . $ex->getMessage()
                            . "\n *********************************************************************** \n";
                    $file = fopen("C:popgym.log", "a");
                    fwrite($file, $msgLog);
                    fclose($file);
                }
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'usuario',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'usuario',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form,
            'idClienteEmpleado' => $this->getUsuarioDAO()->getUsuario($idUsuario),
        ));
        $view->setTemplate('administracion/usuario/formularioEdit');
        $view->setTerminal(true);
        return $view;
    }

    public function detailAction() {
        $idUsuario = (int) $this->params()->fromQuery('idUsuario', 0);
        $action = 'detail';
        $onsubmit = 'return false';
        $form = $this->getFormulario($action, $onsubmit, $idUsuario);
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/usuario/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function seleccionarUsuarioAction() {
        $filtro = "usuario.estado = 'Activo'";
        $view = new ViewModel(array(
            'usuarios' => $this->getUsuarioDAO()->getUsuarios($filtro),
        ));
        $view->setTerminal(true);
        return $view;
    }

    public function seleccionarUsuarioMensualidadAction() {
        $filtro = "usuario.pk_usuario_id NOT IN (SELECT mensualidad.fk_usuario_id FROM mensualidad)";
        $view = new ViewModel(array(
            'usuarios' => $this->getUsuarioDAO()->getUsuarios($filtro),
        ));
        $view->setTerminal(true);
        return $view;
    }

    public function getUsuarioAction() {
        $idUsuario = (int) $this->params()->fromQuery('idUsuario', 0);
        if (!$idUsuario) {
            return 0;
        }

        $form = $this->getFormulario('buscar', '', $idUsuario);
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/usuario/formulario-usuario');
        $view->setTerminal(true);
        return $view;
    }

    public function getDetalleUsuarioAction() {
        $idUsuario = (int) $this->params()->fromPost('idUsuario', 0);
        $idUsuario;
        $sl = $this->getUsuarioDAO()->getDetalleUsuario($idUsuario);
//        var_dump("".$sl->getGenero());
        $view = new ViewModel(array(
            'usuario' => $this->getUsuarioDAO()->getDetalleUsuario($idUsuario)
        ));
        $view->setTerminal(true);
        return $view;
    }

   public function getMedidasUsuarioAction() {
        $idUsuario = (int) $this->params()->fromQuery('idUsuario', 0);
        $medidas = $this->getUsuarioDAO()->getMedidasUsuario($idUsuario);
        $view = new ViewModel(array(
            'usuario' => $medidas
        ));
        $view->setTerminal(true);
        return $view;
    }

    public function cambiarcontrasenaAction() {
        $form = new CambiocontrasenaForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $error = 1;
            $response = $this->getResponse();
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $session = $this->getInfoSesionUsuario();
                $usuarioOBJ = $this->getUsuarioDAO()->getUsuario($session['pk_usuario_id']);
                $password = $this->params()->fromPost('password', '');
                $passwordactual = $this->params()->fromPost('passwordactual', '');
                $config = $this->getServiceLocator()->get('Config');
                $passwordApp = $config['passwordSeguro'];
                if ($usuarioOBJ->getPassword() == md5($passwordApp . $passwordactual . $usuarioOBJ->getPasswordseguro())) {
                    $passwordSeguro = $password;
                    $newpassword = md5($passwordApp . $password . $passwordSeguro);
                    if ($this->getUsuarioDAO()->cambiarcontrasena($session['pk_usuario_id'], $newpassword, $passwordSeguro, $session['nombreApellido']) > 0) {
                        $error = 0;
                    }
                } else {
                    $error = 2;
                }
            }
            $response->setContent(Json::encode(array(
                        'error' => $error,
                        'actual' => $usuarioOBJ->getPassword(),
                        'digitado' => md5($passwordApp . $passwordactual . $usuarioOBJ->getPasswordseguro()),
            )));
            return $response;
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('administracion/perfil/cambiarcontrasena');
        $view->setTerminal(true);
        return $view;
    }

    public function getLoginAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $error = 1;
        $login = '';
        $nombreApellido = '';
        $genero = 'Masculino';
        if ($request->isGet()) {
            $cont = 0;
            $idClienteEmpleado = $this->params()->fromQuery('idClienteEmpleado', 0);
            $clienteEmpleadoOBJ = $this->getClienteempleadoDAO()->getClienteempleado($idClienteEmpleado);
            $genero = $clienteEmpleadoOBJ->getGenero();
            $nombres = $clienteEmpleadoOBJ->getNombre();
            $apellidos = $clienteEmpleadoOBJ->getApellido();
            $nombreApellido = trim($nombres) . ' ' . trim($apellidos);
            $partesApellidos = explode(' ', $apellidos);
            $primerApellido = $partesApellidos[0];
            $login = strtolower($nombres[0] . $primerApellido);
            while ($this->getUsuarioDAO()->existeLogin($login) && $cont < 100) {
                $login = $login . rand(1, 1000);
                $cont++;
            }
            if ($cont <= 100) {
                $error = 0;
            }
        }
        $response->setContent(Json::encode(array(
                    'error' => $error,
                    'login' => $login,
                    'nombreApellido' => $nombreApellido,
                    'genero' => $genero,
        )));
        return $response;
    }
    
    public function verhuellaAction() {
        $idUsuario = (int) $this->params()->fromQuery('idUsuario', 0);
        $huella = $this->getUsuarioDAO()->usuarioHuella($idUsuario);
        $view = new ViewModel(array(
            'imgHuella' => $huella,
        ));
        $view->setTerminal(true);
        return $view;
    }
    public function eliminarUsuarioAction() {
        $idUsuario = (int) $this->params()->fromQuery('pk_usuario_id', 0);
        if ($idUsuario != 0) {
            try {
                $this->getUsuarioDAO()->eliminarUsuario($idUsuario);
                $this->flashMessenger()->addSuccessMessage('EL USUARIO FUE ELIMINADO CON EXITO');
                return $this->redirect()->toUrl('index');
            } catch (Exception $exc) {
                $this->flashMessenger()->addErrorMessage('LA ACCION NO SE PUDO EJECUTAR');
                return $this->redirect()->toUrl('index');
            }
        } else {
            $this->flashMessenger()->addErrorMessage('LA ACCION NO SE PUDO EJECUTAR');
            return $this->redirect()->toUrl('index');
        }
    }
    public function verActivarUsuarioAction() {
        $filtro = "usuario.estado = 'Eliminado'";
        $view = new ViewModel(array(
            'usuarios' => $this->getUsuarioDAO()->getUsuarios($filtro),
        ));
        $view->setTerminal(true);
        return $view;
    }

    public function activarUsuarioNowAction() {
        $idUsuario = (int) $this->params()->fromQuery('idUsuario', 0);
        if ($idUsuario != 0) {
            try {
                $this->getUsuarioDAO()->activarUsuario($idUsuario);
                $this->flashMessenger()->addSuccessMessage('EL USUARIO FUE ACTIVADO CON EXITO');
                return $this->redirect()->toUrl('index');
            } catch (Exception $exc) {
                $this->flashMessenger()->addErrorMessage('LA ACCION NO SE PUDO EJECUTAR');
                return $this->redirect()->toUrl('index');
            }
        } else {
            $this->flashMessenger()->addErrorMessage('LA ACCION NO SE PUDO EJECUTAR');
            return $this->redirect()->toUrl('index');
        }
    }

}
