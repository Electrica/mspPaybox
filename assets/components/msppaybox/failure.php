<?php

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
require_once MODX_CORE_PATH.'model/modx/modx.class.php';
$modx = new modX();
$modx->initialize('web');
$modx->getService('error','error.modError', '', '');

/**
 * @var mspPaybox $mspPaybox
 */

$mspPaybox = $modx->getService('mspPaybox');

$mspPaybox->changeStatus($_GET['pg_order_id']);