<?php

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
require_once MODX_CORE_PATH.'model/modx/modx.class.php';
$modx = new modX();
$modx->initialize('web');
$modx->getService('error','error.modError', '', '');

/**
 * @var miniShop2 $miniShop2
 * @var mspPaybox $mspPaybox
 */

$miniShop2 = $modx->getService('miniShop2');
$miniShop2->loadCustomClasses('payment');

if(!$orderId = $_GET['pg_order_id']){
    exit('Нету id покупки');
}

$pgOrder = str_replace('Order #', '', $orderId);
$order = $modx->getObject('msOrder', array('num' => $pgOrder));

$miniShop2->changeOrderStatus($order->get('id'), 2);
