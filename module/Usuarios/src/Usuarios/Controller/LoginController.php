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
use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Usuarios\Formularios\Login;
use Usuarios\Modelo\Entidad\Usuario;

class LoginController extends AbstractActionController {

    public function loginAction() {
        $user = $this->identity();
        $formLogin = new Login('IniciarSesion');
        $msg = null;

        $viewModel = new ViewModel(array('formLogin' => $formLogin, 'msg' => $msg));
        $peticion = $this->getRequest();
        if ($peticion->isPost()) {
            $authFormFilters = new Usuario();
            $formLogin->setInputFilter($authFormFilters->getInputFilter());
            $formLogin->setData($peticion->getPost());
            if ($formLogin->isValid()) {
                $datos = $formLogin->getData();
                $sm = $this->getServiceLocator();
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

                $config = $this->getServiceLocator()->get('Config');
                $passwordSeguro = $config['passwordSeguro'];
                $authAdapter = new AuthAdapter($dbAdapter, 'usuario', // there is a method setTableName to do the same
                        'login', // there is a method setIdentityColumn to do the same
                        'password', // there is a method setCredentialColumn to do the same
                        "MD5(CONCAT('$passwordSeguro', ?, passwordseguro)) AND estado = 'Activo'" // setCredentialTreatment(parametrized string) 'MD5(?)'
                );
                $authAdapter->setIdentity($datos['login'])->setCredential($datos['password']);

                $auth = new AuthenticationService();
                // or prepare in the globa.config.php and get it from there. Better to be in a module, so we can replace in another module.
                // $auth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
                // $sm->setService('Zend\Authentication\AuthenticationService', $auth); // You can set the service here but will be loaded only if this action called.
                $result = $auth->authenticate($authAdapter);

                switch ($result->getCode()) {
                    case Result::FAILURE_IDENTITY_NOT_FOUND:
                        $this->flashMessenger()->addErrorMessage("USUARIO O CONTRASEÑA INCORRECTO");
                        break;
                    case Result::FAILURE_CREDENTIAL_INVALID:
                        $this->flashMessenger()->addErrorMessage("USUARIO O CONTRASEÑA INCORRECTO");
                        break;
                    case Result::SUCCESS:
                        $storage = $auth->getStorage();
                        $storage->write($authAdapter->getResultRowObject(null, 'password'));
                        $time = 1209600; // 14 days 1209600/3600 = 336 hours => 336/24 = 14 days
//						if ($datos['rememberme']) $storage->getSession()->getManager()->rememberMe($time); // no way to get the session
                        if ($datos['recordar']) {
                            $sessionManager = new \Zend\Session\SessionManager();
                            $sessionManager->rememberMe($time);
                        }
                        $tipoUsuario = '';
                        if ($sesionUsuario = $this->identity()) {
                            $tipoUsuario = $sesionUsuario->fk_rol_id;
                        }
                        if ($tipoUsuario == '1') {
                            $viewModel = $this->redirect()->toRoute('administracion/default', array(
                                'controller' => 'index',
                                'action' => 'index',
                            ));
                        } else if ($tipoUsuario == '2') {
                            $viewModel = $this->redirect()->toRoute('administracion/default', array(
                                'controller' => 'index',
                                'action' => 'index',
                            ));
                        } else if ($tipoUsuario == '3') {
                            $viewModel = $this->redirect()->toRoute('administracion/default', array(
                                'controller' => 'index',
                                'action' => 'index',
                            ));
                        } else if ($tipoUsuario == '4') {
                            $viewModel = $this->redirect()->toRoute('administracion/default', array(
                                'controller' => 'index',
                                'action' => 'index',
                            ));
                        }
                        break;
                    default:
                        $this->flashMessenger()->addErrorMessage("SE HA PRESENTADO UN INCONVENIENTE CON EL INICIO DE SU SESION");
                        break;
                }
                foreach ($result->getMessages() as $message) {
                    $msg .= "$message\n";
                }
            }
        }
        return $viewModel;
    }

    public function cerrarSesionAction() {
        $auth = new AuthenticationService();
        // or prepare in the globa.config.php and get it from there
        // $auth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');

        if ($auth->hasIdentity()) {
            $identity = $auth->getIdentity();
        }

        $auth->clearIdentity();
//		$auth->getStorage()->session->getManager()->forgetMe(); // no way to get the sessionmanager from storage
        $sessionManager = new \Zend\Session\SessionManager();
        $sessionManager->forgetMe();
        $this->flashMessenger()->addSuccessMessage("LA SESION HA TERMINADO");

        return $this->redirect()->toRoute('login/default', array('controller' => 'login', 'action' => 'login'));
    }

}
