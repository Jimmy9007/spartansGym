<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Usuarios;

return array(
    'di' => array(
        'instance' => array(
            'alias' => array(
                'usuarios' => 'Usuarios\Controller\LoginController'
            ),
            'usuarios' => array(
                'parameters' => array(
                    'broker' => 'Zend\Mvc\Controller\PluginBroker'
                )
            ),
            'Usuarios\Event\Autenticacion' => array(
                'parameters' => array(
                    'autenticarUsuarioPlugin' => 'Usuarios\Controller\Plugin\AutenticarUsuario',
                    'aclClass' => 'Usuarios\Acl\Acl'
                )
            ),
            'Usuarios\Acl\Acl' => array(
                'parameters' => array(
                    'config' => include __DIR__ . '/acl.config.php'
                )
            ),
            'Usuarios\Controller\Plugin\AutenticarUsuario' => array(
                'parameters' => array(
                    'authAdapter' => 'Zend\Authentication\Adapter\DbTable'
                )
            ),
            'Zend\Authentication\Adapter\DbTable' => array(
                'parameters' => array(
                    'zendDb' => 'Zend\Db\Adapter\Mysqli',
                    'tableName' => 'users',
                    'identityColumn' => 'email',
                    'credentialColumn' => 'password',
                    'credentialTreatment' => 'SHA1(CONCAT(?, "secretKey"))'
                )
            ),
            'Zend\Db\Adapter\Mysqli' => array(
                'parameters' => array(
                    'config' => array(
                        'host' => 'localhost',
                        'username' => 'username',
                        'password' => 'password',
                        'dbname' => 'dbname',
                        'charset' => 'utf-8'
                    )
                )
            ),
            'Zend\Mvc\Controller\PluginLoader' => array(
                'parameters' => array(
                    'map' => array(
                        'AutenticarUsuario' => 'Usuarios\Controller\Plugin\AutenticarUsuario'
                    )
                )
            ),
            'Zend\View\PhpRenderer' => array(
                'parameters' => array(
                    'options' => array(
                        'script_paths' => array(
                            'user' => __DIR__ . '/../views'
                        )
                    )
                )
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'login' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Administracion\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'login' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Usuarios\Controller',
                        'controller' => 'Login',
                        'action' => 'login',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action][/:id1][/:id2]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id1' => '[a-zA-Z0-9_-]*',
                                'id2' => '[a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Usuarios\Controller\Login' => Controller\LoginController::class,
            'Usuarios\Controller\Inicio' => Controller\InicioController::class,
            'Usuarios\Controller\Producto' => Controller\ProductoController::class,
            'Usuarios\Controller\Productodetalle' => Controller\ProductodetalleController::class,
            'Usuarios\Controller\Oferta' => Controller\OfertaController::class,
            'Usuarios\Controller\Asistencia' => Controller\AsistenciaController::class,
            'Usuarios\Controller\Contacto' => Controller\ContactoController::class,
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
        'aliases' => array(// !!! aliases not alias
            'Zend\Authentication\AuthenticationService' => 'servicioAutenticacion',
        ),
        'invokables' => array(
            'servicioAutenticacion' => 'Zend\Authentication\AuthenticationService',
        ),
    ),
);