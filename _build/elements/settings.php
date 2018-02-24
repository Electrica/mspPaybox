<?php

return [
    'core_path' => [
        'xtype' => 'textfield',
        'value' => '{base_path}mspPaybox/core/components/msppaybox/',
        'area' => 'msppaybox_main',
    ],
    'assets_path' => [
        'xtype' => 'textfield',
        'value' => '{base_path}mspPaybox/assets/components/msppaybox/',
        'area' => 'msppaybox_main',
    ],
    'assets_url' => [
        'xtype' => 'textfield',
        'value' => '/mspPaybox/assets/components/msppaybox/',
        'area' => 'msppaybox_main',
    ],
    'id_order_redirect' => [
        'xtype' => 'textfield',
        'value' => '',
        'area' => 'msppaybox_main'
    ],
    'pg_merchant_id' => [
        'xtype' => 'textfield',
        'value' => '',
        'area' => 'msppaybox_main',
    ],
    'pg_testing_mode' => [
        'xtype' => 'combo-boolean',
        'value' => true,
        'area' => 'msppaybox_main',
    ],
    'pg_secret_key_admission' => [
        'xtype' => 'textfield',
        'value' => '',
        'area' => 'msppaybox_main',
    ],
    'pg_secret_key_payments' => [
        'xtype' => 'textfield',
        'value' => '',
        'area' => 'msppaybox_main',
    ],
    'currency' => [
        'xtype' => 'textfield',
        'value' => 'KZT',
        'area' => 'msppaybox_currency',
    ],
    'pg_success_url' => [
        'xtype' => 'textfield',
        'value' => 'assets/components/msppaybox/success.php',
        'area' => 'msppaybox_main',
    ],
    'pg_failure_url' => [
        'xtype' => 'textfield',
        'value' => 'assets/components/msppaybox/failure.php',
        'area' => 'msppaybox_main',
    ],
    'pg_language' => [
        'xtype' => 'textfield',
        'value' => 'ru',
        'area' => 'msppaybox_main',
    ]

];