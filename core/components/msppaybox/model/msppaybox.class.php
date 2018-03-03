<?php

if (!class_exists('msPaymentInterface')) {
    /** @noinspection PhpIncludeInspection */
    require_once MODX_CORE_PATH . 'components/minishop2/model/minishop2/mspaymenthandler.class.php';
}


class mspPaybox extends msPaymentHandler implements msPaymentInterface
{
    /** @var modX $modx */
    public $modx;
    /** @var miniShop2 $ms2 */
    public $ms2;
    /** @var array $config */
    public $config;

    /**
     * @param xPDOObject $object
     * @param array $config
     */
    function __construct(xPDOObject $object, array $config = [])
    {
        parent::__construct($object, $config);

        $corePath = $this->modx->getOption('msppaybox_core_path', null, MODX_CORE_PATH . 'components/msppaybox/');
        $assetsUrl = $this->modx->getOption('msppaybox_assets_path', null, MODX_ASSETS_PATH . 'components/msppaybox/');


        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',

            'assetsUrl' => $assetsUrl,
        ], $config);

        $this->modx->addPackage('msppaybox', $this->config['modelPath']);
        $this->modx->lexicon->load('msppaybox:default');
    }


    /**
     * @param msOrder $order
     *
     * @return array|string
     */
    public function send(msOrder $order)
    {
        $link = $this->sendResquest($order);

        return $this->success('', array('redirect' => $link));
    }

    public function sendResquest(msOrder $order){

        //Выбираем мыло пользователя
        /**
         * @var modUser $user
         */
        $orderUserId = $order->get('user_id');
        $user = $this->modx->getObject('modUser', $orderUserId);
        $profile = $user->Profile;
        $email = $profile->get('email');

        //Выбираем телефон
        $q = $this->modx->getObject('msOrderAddress', array('user_id' => $orderUserId));
        $phone = $q->get('phone');

        //TODO Сделать редирект на главную если не будет настройки $this->modx->getOption('mspmollie_success_id', null, $this->modx->getOption('site_start'), true)
        $request = [
            'pg_amount'         => (int)$order->get('cost'),
            'pg_check_url'      => '', //url for checking status of order. It must be a real url on website, and return "OK" or "rejected"
            'pg_description'    => 'Оплата за заказ ' . $order->get('num'), //any description of order
            'pg_encoding'       => 'UTF-8', //charset
            'pg_currency'       => $this->modx->getOption('msppaybox_currency'), //currency of payment, default is KZT
            'pg_user_ip'        => $_SERVER['REMOTE_ADDR'],
            'pg_lifetime'       => 86400, //lifetime of payment, default is 86400 seconds
            'pg_merchant_id'    => $this->modx->getOption('msppaybox_pg_merchant_id'), //id of merchant in PayBox system
            'pg_order_id'       => 'Order #'.$order->get('num'), //id of order in merchant database
            'pg_result_url'     => '', //url to which we will send the payment result
            'pg_request_method' => 'GET', //you can use GET, POST, XML
            'pg_salt'           => rand(21, 43433), //salt that will be used in encrypting the request
            'pg_success_url'    => $this->modx->getOption('site_url') . $this->modx->getOption('msppaybox_pg_success_url'), //here we will return the customer if payment was successful. It must be a real url on website
            'pg_failure_url'    => $this->modx->getOption('site_url') . $this->modx->getOption('msppaybox_pg_failure_url'), //here we will return the customer if the payment has failed. It must be a real url on website
            'pg_user_phone'     => $phone, //phone of the customer, which he will see in the form of payment
            'pg_user_contact_email' => $email, //email of the customer, where he will receive a notification of the status of payment
            'pg_testing_mode' => $this->modx->getOption('msppaybox_pg_testing_mode')
        ];



        $url = 'payment.php';

        //generate a signature and add it to the array

        ksort($request);
        array_unshift($request, $url);
        array_push($request, $this->modx->getOption('msppaybox_pg_secret_key_admission')); //add your secret key (you can take it in your personal cabinet on paybox system)

        $request['pg_sig'] = md5(implode(';', $request));

        unset($request[0], $request[1]);

        // Заводим в базе запись для сравнения оплаты

        $saveArr = array(
            'order_id' => $order->get('id'),
            'pg_sig' => $request['pg_sig']
        );
        $this->saveOrder($saveArr);

        $query = http_build_query($request);
        $link = 'https://paybox.kz/'.$url .'?'.$query;
        return $link;
    }

    public function saveOrder(array $saveArr){

        //Разбираемся, почему не пишет в базу через объекты

        $q = $this->modx->newObject('mspPayboxOrder');
        $q->fromArray($saveArr);
        $q->save();

        return true;
    }


}