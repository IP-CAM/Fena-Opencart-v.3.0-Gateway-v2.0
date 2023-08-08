<?php
/**
 * ControllerCheckoutWebhook File Doc Comment
 * php version 7.2.10
 *
 * @category Class
 * @package  Fena
 * @author   A N Other <support@fena.co>
 * @license  https://www.fena.co General Public License
 * @link     https://www.fena.co/
 */
/**
 * ControllerCheckoutWebhook Class Doc Comment
 *
 * @category Class
 * @package  Fena
 * @author   A N Other <support@fena.co>
 * @license  https://www.fena.co General Public License
 * @link     https://www.fena.co/
 */
class ControllerCheckoutWebhook extends Controller
{


    /**
     * Index the build template
     *
     * @return void
     */
    public function index()
    {

        $data = json_decode(file_get_contents('php://input'), true);

        // //some other code goes here
        $log = new Log('fena.log');
        $log->write(print_r($data, true));

        $orderId = $data['reference'];

        $this->load->model('checkout/order');
        $status = $data['status'];

        $message = 'Transaction ID: '.$data['transaction']."\n";

        if ($status == 'paid') {
                $statusId = $this->config->get('payment_fena_paid_status_id');
                $this->model_checkout_order->addOrderHistory($orderId, $statusId, $message);
        }

    }//end index()


}//end class
