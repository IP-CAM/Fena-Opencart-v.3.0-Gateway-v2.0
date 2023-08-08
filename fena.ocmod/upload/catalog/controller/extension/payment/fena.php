<?php
/**
 * ControllerExtensionPaymentFena File Doc Comment
 * php version 7.2.10
 *
 * @category Class
 * @package  Fena
 * @author   A N Other <support@fena.co>
 * @license  https://www.fena.co General Public License
 * @link     https://www.fena.co/
 */
/**
 * ControllerExtensionPaymentFena Class Doc Comment
 *
 * @category Class
 * @package  Fena
 * @author   A N Other <support@fena.co>
 * @license  https://www.fena.co General Public License
 * @link     https://www.fena.co/
 */
class ControllerExtensionPaymentFena extends Controller
{


    /**
     * Index the build template
     *
     * @return void
     */
    public function index()
    {

        $this->load->language('extension/payment/fena');
        $this->load->model('checkout/order');

        if (!$this->config->get('payment_fena_mode')) {
            $data['text_testing'] = $this->language->get('text_testing');
            $data['Endpoint']     = 'Sandbox';
        } else {
            $data['text_testing'] = '';
            $data['Endpoint']     = 'Production';
        }

        $data['payment_fena_title']       = $this->config->get('payment_fena_title');
        $data['payment_fena_description'] = $this->config->get('payment_fena_description');
        return $this->load->view('extension/payment/fena', $data);

    }//end index()


    /**
     * Call Back the build template
     *
     * @return void
     */
    public function callback()
    {

        $this->load->model('checkout/order');
        $orderInfo    = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        try {

            $amount       = $this->currency->format($orderInfo['total'], $orderInfo['currency_code'], $orderInfo['currency_value'], false);
            $finalAmount  = str_replace(',', '', number_format($amount, 2));
            $fenaApi      = html_entity_decode($this->config->get('payment_fena_api'), ENT_QUOTES, 'UTF-8');
            $fenaSecret   = html_entity_decode($this->config->get('payment_fena_secret'), ENT_QUOTES, 'UTF-8');
            $fenaCustName = $orderInfo['firstname'];
            $fenaEmail    = $orderInfo['email'];
            $addressLine1 = $orderInfo['payment_address_1'];
            $addressLine2 = $orderInfo['payment_address_2'];
            $zipCode = $orderInfo['payment_postcode'];
            $city = $orderInfo['payment_city'];
            $country = $orderInfo['payment_country'];
            $fenaMode     = $this->config->get('payment_fena_mode');
            $redirectUrl  = $this->config->get('config_url').'/index.php?route=checkout/response&';
            
            // API URL to send data.
            if ($fenaMode == 1) {
                $url = 'https://business.api.staging.fena.co/public/payments/create-and-process';
            } else {
                $url = 'https://business.api.fena.co/public/payments/create-and-process';
            }

            $deliveryAddress = array(
            'addressLine1' => $addressLine1,
            'addressLine2' => $addressLine2,
            'zipCode' => $zipCode,
            'city' => $city,
            'country' => $country
            );
           

            $data = array(
                "invoiceRefNumber"  => $this->session->data['order_id'],
                "amount"            => $finalAmount,
                "customerName"      => $fenaCustName,
                "customerEmail"     => $fenaEmail,
                "customRedirectUrl" => $redirectUrl,
                "type"              => "link",
                "deliveryAddress"   => $deliveryAddress
            );

            // Data should be passed as json format.
            $dataJson  = json_encode($data);
            $arrayCurl = array(
                'integration-id: '.$fenaApi.'',
                'secret-key: '.$fenaSecret.'',
                'Content-Type: application/json',
            );
            // Curl initiate.
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayCurl);

            // SET Method as a POST.
            curl_setopt($ch, CURLOPT_POST, 1);

            // Pass user data in POST command.
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute curl and assign returned data.
            $response = curl_exec($ch);

            // Close curl.
            curl_close($ch);

            // See response if data is posted successfully or any error.
            $response = json_encode($response);
            $response = json_decode($response);
            $array    = json_decode($response, true);

            echo $array['result']['link'];

        } catch (Exception $e) {

            $this->log($e->getMessage());

            return false;

        }//end try

    }//end callback()


}//end class
