<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Layout;

return array(
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
//            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'reporte/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'login/layout' => __DIR__ . '/../view/layout/login.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'module_layouts' => array(
        'Usuarios' => 'login/layout'
    ),
    'controller_layouts' => array(
        'Usuarios/Login' => 'login/layout',
    ),
    'action_layouts' => array(
    ),
// Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
