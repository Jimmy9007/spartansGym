<?php

return array(
    'acl' => array(
        'roles' => array(
            'guest' => null,
            'member' => 'guest'
        ),
        'resources' => array(
            'allow' => array(
                'Usuarios\Controller\Login' => array(
                    'login' => 'invitado',
                    'all' => 'member'
                ),
                'Usuarios\Controller\Inicio' => array(
                    'inicio' => 'invitado',
                    'all' => 'member'
                ),
                'Usuarios\Controller\Producto' => array(
                    'producto' => 'invitado',
                    'all' => 'member'
                ),
                'Usuarios\Controller\Productodetalle' => array(
                    'productodetalle' => 'invitado',
                    'all' => 'member'
                ),
                'Usuarios\Controller\Oferta' => array(
                    'oferta' => 'invitado',
                    'all' => 'member'
                ),
                'Usuarios\Controller\Asistencia' => array(
                    'asistencia' => 'invitado',
                    'all' => 'member'
                ),
                'Usuarios\Controller\Contacto' => array(
                    'contacto' => 'invitado',
                    'all' => 'member'
                ),
                'Administracion\Controller\Index' => array(
                    'login' => 'invitado',
                    'all' => 'member'
                ),
                'Administracion\Controller\Chat' => array(
                    'login' => 'invitado',
                    'all' => 'member'
                ),
                'Administracion\Controller\Usuario' => array(
                    'login' => 'invitado',
                    'all' => 'member'
                ),
                'Administracion\Controller\Medidas' => array(
                    'login' => 'invitado',
                    'all' => 'member'
                ),
                'Administracion\Controller\Mensualidad' => array(
                    'login' => 'invitado',
                    'all' => 'member'
                ),
                'Administracion\Controller\Clienteempleado' => array(
                    'login' => 'invitado',
                    'all' => 'member'
                ),
                'Administracion\Controller\Rol' => array(
                    'login' => 'invitado',
                    'all' => 'member'
                ),
                'Administracion\Controller\Turno' => array(
                    'login' => 'invitado',
                    'all' => 'member'
                ),
                'Administracion\Controller\Rutinas' => array(
                    'login' => 'invitado',
                    'all' => 'member'
                ),
                'Administracion\Controller\Usuariorutinas' => array(
                    'login' => 'invitado',
                    'all' => 'member'
                ),
                'Administracion\Controller\Asistencia' => array(
                    'login' => 'invitado',
                    'all' => 'member'
                ),
                'Administracion\Controller\Ejercicios' => array(
                    'login' => 'invitado',
                    'all' => 'member'
                ),
                'Administracion\Controller\Entreno' => array(
                    'login' => 'invitado',
                    'all' => 'member'
                ),
                'Administracion\Controller\Personalizado' => array(
                    'login' => 'invitado',
                    'all' => 'member'
                ),
                'Administracion\Controller\Producto' => array(
                    'login' => 'invitado',
                    'all' => 'member'
                ),
                'Administracion\Controller\Venta' => array(
                    'login' => 'invitado',
                    'all' => 'member'
                ),
                'Administracion\Controller\Proballuvia' => array(
                    'login' => 'invitado',
                    'all' => 'member'
                ),
                'Administracion\Controller\Perfil' => array(
                    'login' => 'invitado',
                    'all' => 'member'
                ),
            )
        )
    )
);
