<?php

if(is_file(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php')){
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}else{
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
}
require_once MODX_CORE_PATH.'model/modx/modx.class.php';
$modx = new modX();
$modx->initialize('web');
$modx->getService('error','error.modError', '', '');

/**
 * @var miniShop2 $miniShop2
 */

$miniShop2 = $modx->getService('miniShop2');

if(!$orderId = $_GET['pg_order_id']){
    exit('Нету id покупки');
}

$pgOrder = str_replace('Order #', '', $orderId);
$order = $modx->getObject('msOrder', array('num' => $pgOrder));
$redirect = $order->get('id');

$save_order = $miniShop2->changeOrderStatus($order->get('id'), 2);

if($save_order){
    //Если указан id куда редиректить
    if($id = $modx->getOption('msppaybox_id_order_redirect')){
        $url = array(
            'msorder' => $redirect,
            'success' => 1
        );
        $modx->sendRedirect($modx->makeUrl($id,'','','full') . '&' . http_build_query($url));
    }else{
        $modx->sendRedirect($modx->makeUrl($modx->getOption('site_start'),'','','full'));
    }
}
