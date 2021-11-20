<?php

/**
 * @namespace
 */

namespace Usuarios\Event;

/**
 * @uses Zend\Mvc\MvcEvent
 * @uses User\Controller\Plugin\UserAuthentication
 * @uses User\Acl\Acl
 */
use Zend\Mvc\MvcEvent as MvcEvent,
    Usuarios\Controller\Plugin\AutenticarUsuario as AuthPlugin,
    Usuarios\Acl\Acl as AclClass;

/**
 * Authentication Event Handler Class
 *
 * This Event Handles Authentication
 *
 * @category  User
 * @package   User_Event
 * @copyright Copyright (c) 2011, Marco Neumann
 * @license   http://binware.org/license/index/type:new-bsd New BSD License
 */
class Autenticacion {

    /**
     * @var AuthPlugin
     */
    protected $_userAuth = null;

    /**
     * @var AclClass
     */
    protected $_aclClass = null;

    /**
     * preDispatch Event Handler
     *
     * @param \Zend\Mvc\MvcEvent $event
     * @throws \Exception
     */
    public function preDispatch(MvcEvent $event) {
        //@todo - Should we really use here and Controller Plugin?
        $userAuth = $this->getUserAuthenticationPlugin();
        $acl = $this->getAclClass();
        $role = AclClass::DEFAULT_ROLE;

        if ($userAuth->hasIdentity()) {
            $user = $userAuth->getIdentity();
            $role = 'member'; //@todo - Get role from user!
        }

//        var_dump($event);
        $routeMatch = $event->getRouteMatch();
        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');
//        print_r($acl->getResources());
//        echo $role.'<br>';

        if (!$acl->hasResource($controller)) {
            throw new \Exception('RECURSO ' . $controller . ' NO DEFINIDO');
        }

        if (!$acl->isAllowed($role, $controller, $action)) {
            $url = $event->getRouter()->assemble(array(), array('name' => 'login'));
            $response = $event->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);
//            var_dump($response);
            $response->sendHeaders();
            exit;
        }
    }

    /**
     * Sets Authentication Plugin
     *
     * @param \User\Controller\Plugin\UserAuthentication $userAuthenticationPlugin
     * @return Authentication
     */
    public function setUserAuthenticationPlugin(AuthPlugin $userAuthenticationPlugin) {
        $this->_userAuth = $userAuthenticationPlugin;

        return $this;
    }

    /**
     * Gets Authentication Plugin
     *
     * @return \User\Controller\Plugin\UserAuthentication
     */
    public function getUserAuthenticationPlugin() {
        if ($this->_userAuth === null) {
            $this->_userAuth = new AuthPlugin();
        }

        return $this->_userAuth;
    }

    /**
     * Sets ACL Class
     *
     * @param \User\Acl\Acl $aclClass
     * @return Authentication
     */
    public function setAclClass(AclClass $aclClass) {
        $this->_aclClass = $aclClass;

        return $this;
    }

    /**
     * Gets ACL Class
     *
     * @return \User\Acl\Acl
     */
    public function getAclClass() {
        if ($this->_aclClass === null) {
            $this->_aclClass = new AclClass(array());
        }

        return $this->_aclClass;
    }

}
