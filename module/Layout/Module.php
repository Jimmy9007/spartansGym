<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Layout;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\I18n\Translator\Translator;
use Zend\Validator\AbstractValidator;
use Zend\View\Model\ViewModel;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $this->iniLayout($e);
//        $translator = new Translator();
//        $translator->addTranslationFile('phpArray', 'vendor\zendframework\zend-i18n-resources\languages\es\Zend_Validate.php');
//        AbstractValidator::setDefaultTranslator(new \Zend\Mvc\I18n\Translator($translator));
//        $eventManager->attach('route', array($this, 'mvcPreDispatch'), 100);
    }

//    public function mvcPreDispatch($event) {
//        $di = $event->getTarget()->getServiceManager();
//        $auth = $di->get('Usuarios\Event\Autenticacion');
//        return $auth->preDispatch($event);
//    }

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

    protected function iniLayout($e) {

        $layout = $e->getViewModel();

        $header = new ViewModel();
        $header->setTemplate('layout/header');
        $layout->addChild($header, 'header');

        $header2 = new ViewModel();
        $header2->setTemplate('layout/navlogin/header2');
        $layout->addChild($header2, 'header2');

        $footer = new ViewModel();
        $footer->setTemplate('layout/footer');
        $layout->addChild($footer, 'footer');

        $footer2 = new ViewModel();
        $footer2->setTemplate('layout/navlogin/footer2');
        $layout->addChild($footer2, 'footer2');

        $headerlogin = new ViewModel();
        $headerlogin->setTemplate('layout/headerlogin');
        $layout->addChild($headerlogin, 'headerlogin');

        $menu = new ViewModel();
        $menu->setTemplate('layout/menu');
        $layout->addChild($menu, 'menu');

        $menuAdmin = new ViewModel();
        $menuAdmin->setTemplate('layout/menuadmin');
        $layout->addChild($menuAdmin, 'menuadmin');

        $menuInstructor = new ViewModel();
        $menuInstructor->setTemplate('layout/menuinstructor');
        $layout->addChild($menuInstructor, 'menuinstructor');
        
        $menuEmpleado = new ViewModel();
        $menuEmpleado->setTemplate('layout/menuempleado');
        $layout->addChild($menuEmpleado, 'menuempleado');

        $global = new ViewModel();
        $global->setTemplate('layout/global');
        $layout->addChild($global, 'global');
    }

}
