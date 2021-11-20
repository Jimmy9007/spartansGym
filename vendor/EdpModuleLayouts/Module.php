<?php
namespace EdpModuleLayouts;
class Module {
    public function onBootstrap($e)
    {
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e) {
            $routeMatchParams = $e->getRouteMatch()->getParams();
            // Avoid errors on redirections, forwards, etc
            if (!isset($routeMatchParams['__NAMESPACE__']) ||!isset($routeMatchParams['controller']) ||!isset($routeMatchParams['action'])) {
                return;
            }
            
            $moduleName = substr($routeMatchParams['__NAMESPACE__'], 0, strpos($routeMatchParams['__NAMESPACE__'], '\\'));
            $controllerName = str_replace('\\Controller\\', '/', $routeMatchParams['controller']);            
            $actionName = $routeMatchParams['action'];
                     
            $config = $e->getApplication()->getServiceManager()->get('config');
            $controller = $e->getTarget();
            if (isset($config['module_layouts'][$moduleName]))
            {
                $controller->layout($config['module_layouts'][$moduleName]);   
            }
            if (isset($config['controller_layouts'][$controllerName]))
            {
                $controller->layout($config['controller_layouts'][$controllerName]);
            }
            if (isset($config['action_layouts'][$controllerName . '/' . $actionName]))
            {
                $controller->layout($config['action_layouts'][$controllerName . '/' . $actionName]);
            }            
            
        }, 100);
    }
}