<?php

if (!class_exists('msPaymentInterface')) {
    /** @noinspection PhpIncludeInspection */
    //require_once dirname(dirname(dirname(__FILE__))) . '/minishop2/model/minishop2/mspaymenthandler.class.php';
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
        $link = '';

        $request = [
            'pg_amount'         => (int)$order->get('cost'),
            'pg_check_url'      => $this->modx->getOption('site_url') . 'check', //url for checking status of order. It must be a real url on website, and return "OK" or "rejected"
            'pg_description'    => 'Пока просто описание', //any description of order
            'pg_encoding'       => 'UTF-8', //charset
            'pg_currency'       => $this->modx->getOption('msppaybox_currency'), //currency of payment, default is KZT
            'pg_user_ip'        => $_SERVER['REMOTE_ADDR'],
            'pg_lifetime'       => 86400, //lifetime of payment, default is 86400 seconds
            'pg_merchant_id'    => $this->modx->getOption('msppaybox_pg_merchant_id'), //id of merchant in PayBox system
            'pg_order_id'       => 'Order #'.$order->get('num'), //id of order in merchant database
            'pg_result_url'     => $this->modx->getOption('site_url') . 'result', //url to which we will send the payment result
            'pg_request_method' => 'GET', //you can use GET, POST, XML
            'pg_salt'           => rand(21, 43433), //salt that will be used in encrypting the request
            'pg_success_url'    => $this->modx->getOption('site_url') . $this->modx->getOption('msppaybox_pg_success_url'), //here we will return the customer if payment was successful. It must be a real url on website
            'pg_failure_url'    => $this->modx->getOption('site_url') . $this->modx->getOption('msppaybox_pg_failure_url'), //here we will return the customer if the payment has failed. It must be a real url on website
            'pg_user_phone'     => (int)'87777777777', //phone of the customer, which he will see in the form of payment
            'pg_user_contact_email' => 'info@modx.kz' //email of the customer, where he will receive a notification of the status of payment
        ];



        $url = 'payment.php';

        //generate a signature and add it to the array

        ksort($request);
        array_unshift($request, $url);
        array_push($request, 'p0MmGmT6yVUVvgc0'); //add your secret key (you can take it in your personal cabinet on paybox system)

        $request['pg_sig'] = md5(implode(';', $request));

        unset($request[0], $request[1]);

        // Заводим в базе запись для сравнения оплаты
        $saveArr = array(
            'order_id' => $order->get('id'),
            'pg_sig' => $request['pg_sig']
        );

        /**
         * @var xPDOSimpleObject $response
         */
        $response = $this->modx->newObject('mspPayboxOrder');
        $response->fromArray($saveArr, '', true);
        $response->save();

        $query = http_build_query($request);
        $link = 'https://paybox.kz/'.$url .'?'.$query;

        return $link;
    }

    public function changeStatus($orderNum, $check = 'fail', $status = 2, msOrder $order){

        if($orderNum){
            if($check == 'success'){
                if($order->get('status') == $status){
                    return true;
                }


            }elseif($check == 'fail'){

            }else{
                exit("Что то хуйня какая то");
            }
        }
    }


}