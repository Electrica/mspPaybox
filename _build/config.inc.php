<?php

if (!defined('MODX_CORE_PATH')) {
    define('MODX_CORE_PATH', dirname(dirname(dirname(__FILE__))) . '/core/');
}

return [
    'name' => 'mspPaybox',
    'name_lower' => 'msppaybox',
    'version' => '1.0.0',
    'release' => 'beta',
    // Install package to site right after build
    'install' => false,
    // Which elements should be updated on package upgrade
    'update' => [
        'chunks' => false,
        'menus' => false,
        'plugins' => true,
        'resources' => false,
        'settings' => true,
        'snippets' => false,
        'templates' => false,
        'widgets' => false,
    ],
    // Which elements should be static by default
    'static' => [
        'plugins' => false,
    ],
    // Log settings
    'log_level' => !empty($_REQUEST['download']) ? 0 : 3,
    'log_target' => php_sapi_name() == 'cli' ? 'ECHO' : 'HTML',
    // Download transport.zip after build
    'download' => !empty($_REQUEST['download']),
];