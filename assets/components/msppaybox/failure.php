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
 * @var mspPaybox $mspPaybox
 */
$mspPaybox = $modx->getService('mspPaybox','mspPaybox',$modx->getOption('msppaybox_core_path',null,$modx->getOption('core_path').'components/msppaybox/').'model/');

/**
 * Ошибка платежа. Кидаем клиента на корзину, если нет то на главную. Выводим в лог ошибку и номер заказа.
 */

$check = $modx->getCount('mspPayboxOrder', array('pg_sig' => $_GET['pg_sig']));
if(!$check){
    $modx->log(MODX_LOG_LEVEL_ERROR, "[mspPaybox] Кто то прислал не верные данные с mspPaybox. Данные: " . print_r($_GET, 1));
    ;
    //Редирект на главную
    //$modx->sendRedirect($modx->makeUrl($modx->getOption('site_start'),'','','full'));
    die();
}

$modx->log(MODX_LOG_LEVEL_ERROR, "Не удалось оплатить через mspPaybox. Данные: " . print_r($_GET, 1));

$num = str_replace('Order #', '', $_GET['pg_order_id']);

$orderId = $modx->getObject('msOrder', array('num' => $num));
$redirect = $orderId->get('id');
//Если указан id куда редиректить
if($id = $modx->getOption('msppaybox_id_order_redirect')){
    $url = array(
        'msorder' => $redirect
    );
    $modx->sendRedirect($modx->makeUrl($id,'','','full') . '&' . http_build_query($url));
}else{
    $modx->sendRedirect($modx->makeUrl($modx->getOption('site_start'),'','','full'));
}