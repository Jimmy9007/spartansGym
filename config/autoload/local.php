<?php

/**
 * Local Configuration Override
 *
 * This configuration override file is for overriding environment-specific and
 * security-sensitive configuration information. Copy this file without the
 * .dist extension at the end and populate values as needed.
 *
 * @NOTE: This file is ignored from Git by default with the .gitignore included
 * in ZendSkeletonApplication. This is a good practice, as it prevents sensitive
 * credentials from accidentally being committed into version control.
 */
return array(
    'db' => array(
        // for primary db adapter that called
        // by $sm->get('Zend\Db\Adapter\Adapter')
        'username' => 'root',
        'password' => '',
        // to allow other adapter to be called by
        // $sm->get('db1') or $sm->get('db2') based on the adapters config.
        'adapters' => array(
            'gimnasio_bd' => array(
                'username' => 'root',
                'password' => '',
            ),
        ),
    ),
);
