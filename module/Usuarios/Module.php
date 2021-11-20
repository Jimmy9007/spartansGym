<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Usuarios;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\I18n\Translator\Translator;
use Zend\Validator\AbstractValidator;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
//        $translator = new Translator();
//        $translator->addTranslationFile('phpArray', 'vendor\zendframework\zend-i18n-resources\languages\es\Zend_Validate.php');
//        $translator->addTranslationFile('phpArray', 'resources/languages/en/Zend_Validate.php', 'default', 'es_ES');
//        AbstractValidator::setDefaultTranslator(new \Zend\Mvc\I18n\Translator($translator));
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE, array($this, 'mvcPreDispatch'));
    }

    public function mvcPreDispatch($event) {
        $routeMatch = $event->getRouteMatch();
        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');
//        if ($controller == 'Usuarios\Controller\Login' && $action == 'login') {
//            return;
//        }
        $di = $event->getTarget()->getServiceManager();
        $auth = $di->get('Usuarios\Event\Autenticacion');
        return $auth->preDispatch($event);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
