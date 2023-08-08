<?php
/**
 * ModelExtensionPaymentFena File Doc Comment
 * php version 7.2.10
 *
 * @category Class
 * @package  Fena
 * @author   A N Other <support@fena.co>
 * @license  https://www.fena.co/ General Public License
 * @link     https://www.fena.co/
 */
/**
 * ModelExtensionPaymentFena Class Doc Comment
 *
 * @category Class
 * @package  Fena
 * @author   A N Other <support@fena.co>
 * @license  https://www.fena.co/ General Public License
 * @link     https://www.fena.co/
 */
class ModelExtensionPaymentFena extends Model
{


    /**
     * Check API Credentials the build template
     *
     * @param request $request comment about this variable
     *
     * @return void
     */
    public function getAccessCode($request)
    {
        try {

            if (isset($this->request->post['payment_fena_mode'])) {
                $fenaMode = $this->request->post['payment_fena_mode'];
            } else {
                $fenaMode = $this->config->get('payment_fena_mode');
            }

            if ($fenaMode == 1) {
                $url = 'https://business.api.staging.fena.co/public/invoices/list';
            } else {
                $url = 'https://business.api.fena.co/public/invoices/list';
            }

            $response = $this->sendCurl($url, $request);
            $response = json_decode($response);
            return $response;

        } catch (Exception $e) {

            $this->log($e->getMessage());

            return false;
        }//end try

    }//end getAccessCode()


    /**
     * Send Curl the build template
     *
     * @param url    $url    comment about this variable
     * @param data   $data   comment about this variable
     * @param isPost $isPost comment about this variable
     *
     * @return void
     */
    public function sendCurl($url, $data, $isPost=true)
    {

        if (isset($this->request->post['payment_fena_api'])) {
            $fenaUsername = $this->request->post['payment_fena_api'];
        } else {
            $fenaUsername = $this->config->get('payment_fena_api');
        }

        if (isset($this->request->post['payment_fena_secret'])) {
            $fenaPassword = $this->request->post['payment_fena_secret'];
        } else {
            $fenaPassword = $this->config->get('payment_fena_secret');
        }

        $curlHandle = curl_init();
        $arrayCurl  = array(
            'integration-id: '.$fenaUsername.'',
            'secret-key: '.$fenaPassword.'',
            'Content-Type: application/json',
        );
        // Set the curl URL option.
        curl_setopt($curlHandle, CURLOPT_URL, $url);

        // This option will return data as a string instead of direct output.
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $arrayCurl);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 60);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curlHandle, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($curlHandle, CURLOPT_FRESH_CONNECT, 1);

        $response = curl_exec($curlHandle);

        if (curl_errno($curlHandle) != CURLE_OK) {
            $response         = new stdClass();
            $response->Errors = "POST Error: ".curl_error($curlHandle)." URL: $url";
            $this->log->write(array('error' => curl_error($curlHandle), 'errno' => curl_errno($curlHandle)), 'cURL failed');
            $response = json_encode($response);
        } else {
            $info = curl_getinfo($curlHandle);
            if ($info['http_code'] != 200) {
                $response = new stdClass();
                if ($info['http_code'] == 401 || $info['http_code'] == 404 || $info['http_code'] == 403) {
                    $response->Errors = "Please check ID and Secret Key";
                } else {
                    $response->Errors = 'Error connecting to Fena: '.$info['http_code'];
                }

                $response = json_encode($response);
            }
        }

        curl_close($curlHandle);

        return $response;

    }//end sendCurl()


}//end class
