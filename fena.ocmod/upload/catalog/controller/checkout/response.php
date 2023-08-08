<?php
/**
 * ControllerCheckoutResponse File Doc Comment
 * php version 7.2.10
 *
 * @category Class
 * @package  Fena
 * @author   A N Other <support@fena.co>
 * @license  https://www.fena.co General Public License
 * @link     https://www.fena.co/
 */
/**
 * ControllerCheckoutResponse Class Doc Comment
 *
 * @category Class
 * @package  Fena
 * @author   A N Other <support@fena.co>
 * @license  https://www.fena.co General Public License
 * @link     https://www.fena.co/
 */
class ControllerCheckoutResponse extends Controller
{


    /**
     * Index the build template
     *
     * @return void
     */
    public function index()
    {

        $this->load->language('checkout/response');

        // Get Response Data from Url.
        $url      = $_SERVER["REQUEST_URI"];
        $queryStr = parse_url($url, PHP_URL_QUERY);
        parse_str($queryStr, $queryParams);
        $resPonse       = $queryParams;
        $reserveOrderId = $resPonse['order_id'];
        $status         = $resPonse['status'];


        // Order Status Update.
        $this->load->model('checkout/order');      
        if ($status == "paid" && $status != "rejected") {
            $statusId = $this->config->get('payment_fena_paid_status_id');

            $checkOrder = "SELECT * FROM  `" . DB_PREFIX . "order_history` WHERE `order_id` = '" . (int)$reserveOrderId . "' AND `order_status_id` = '" . (int)$statusId . "'";
            $query = $this->db->query($checkOrder);
        
            if (empty($query->rows)) {
             $this->model_checkout_order->addOrderHistory($reserveOrderId, $statusId, '');
            }


            $this->response->redirect($this->url->link('checkout/success', '', true));
        }
        if ($status == "rejected") {
            $reserveOrderId = $resPonse['?order_id'];
            $statusId = $this->config->get('payment_fena_rejected_status_id');

            $checkOrder = "SELECT * FROM  `" . DB_PREFIX . "order_history` WHERE `order_id` = '" . (int)$reserveOrderId . "' AND `order_status_id` = '" . (int)$statusId . "'";
            $query = $this->db->query($checkOrder);
        
            if (empty($query->rows)) {
             $this->model_checkout_order->addOrderHistory($reserveOrderId, $statusId, '');
            }


            $this->response->redirect($this->url->link('checkout/failure', '', true));
        }
        if ($status == "refunded") {
            $statusId = $this->config->get('payment_fena_refunded_status_id');

            $checkOrder = "SELECT * FROM  `" . DB_PREFIX . "order_history` WHERE `order_id` = '" . (int)$reserveOrderId . "' AND `order_status_id` = '" . (int)$statusId . "'";
            $query = $this->db->query($checkOrder);
        
            if (empty($query->rows)) {
             $this->model_checkout_order->addOrderHistory($reserveOrderId, $statusId, '');
            }


            $this->response->redirect($this->url->link('checkout/success', '', true));
        }                        

    }//end index()


}//end class
