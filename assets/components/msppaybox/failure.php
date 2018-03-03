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

$modx->log(MODX_LOG_LEVEL_ERROR, "[mspPaybox] Не удалось оплатить через mspPaybox. Данные: " . print_r($_GET, 1));

$num = str_replace('Order #', '', $_GET['pg_order_id']);

$orderId = $modx->getObject('msOrder', array('num' => $num));
$redirect = $orderId->get('id');
//Если указан id куда редиректить
if($id = $modx->getOption('msppaybox_id_order_redirect')){
    $url = array(
        'msorder' => $redirect,
        'fail' => 1
    );
    $modx->sendRedirect($modx->makeUrl($id,'','','full') . '&' . http_build_query($url));
}else{
    $modx->sendRedirect($modx->makeUrl($modx->getOption('site_start'),'','','full'));
}