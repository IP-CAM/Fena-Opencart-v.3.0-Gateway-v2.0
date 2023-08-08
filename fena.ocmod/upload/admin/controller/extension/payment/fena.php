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
     **/
    public function index()
    {
        $extensionFena = 'extension/payment/fena';
        $this->load->language($extensionFena);
        $this->document->setTitle('Custom Payment Method Configuration');
        $this->load->model('setting/setting');
        $userToken = 'user_token=';

        // $orderStatus['order_status'][1] = array('name'=>'test');
        // $this->load->model('localisation/order_status');
        // $this->model_localisation_order_status->addOrderStatus($orderStatus);

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {

            $request = new stdClass();

            $request->Customer   = new stdClass();
            $request->CustomerIP = $this->request->server['REMOTE_ADDR'];

            $this->load->model($extensionFena);

            $result = $this->model_extension_payment_fena->getAccessCode($request);
            // Check if any error returns.
            if (isset($result->Errors)) {
                $errorArray = explode(",", $result->Errors);
                $lblError   = "";
                foreach ($errorArray as $error) {
                    $error     = $this->language->get($error);
                    $lblError .= $error."<br />\n";
                }

                $this->log->write('fena Payment error: '.$lblError);

            }

            if (isset($lblError)) {
                $data['error'] = $lblError;
            } else {

                $this->model_setting_setting->editSetting('payment_fena', $this->request->post);
                $this->session->data['success'] = 'Saved.';
                $this->response->redirect($this->url->link('marketplace/extension', $userToken.$this->session->data['user_token'].'&type=payment', true));

            }

        }//end if

          // Breadcrumbs.
          $data['breadcrumbs'] = array();

          $data['breadcrumbs'][] = array(
              'text' => $this->language->get('text_home'),
              'href' => $this->url->link('common/dashboard', $userToken.$this->session->data['user_token'], true),
          );

          $data['breadcrumbs'][] = array(
              'text' => $this->language->get('text_extensions'),
              'href' => $this->url->link('marketplace/extension', $userToken.$this->session->data['user_token'].'&type=payment', true),
          );

          $data['breadcrumbs'][] = array(
              'text' => $this->language->get('heading_title'),
              'href' => $this->url->link($extensionFena, $userToken.$this->session->data['user_token'], true),
          );
          // Set fields Titles.
          $data['text_mode']          = $this->language->get('text_mode');
          $data['text_terminal_id']   = $this->language->get('text_terminal_id');
          $data['text_secret_key']    = $this->language->get('text_secret_key');
          $data['text_title']         = $this->language->get('text_title');
          $data['text_description']   = $this->language->get('text_description');
          $data['button_save']        = $this->language->get('text_button_save');
          $data['button_cancel']      = $this->language->get('text_button_cancel');
          $data['entry_order_status'] = $this->language->get('entry_order_status');
          $data['text_enabled']       = $this->language->get('text_enabled');
          $data['text_disabled']      = $this->language->get('text_disabled');
          $data['entry_status']       = $this->language->get('entry_status');
          $data['action'] = $this->url->link($extensionFena, $userToken.$this->session->data['user_token'], true);
          $data['cancel'] = $this->url->link($extensionFena, $userToken.$this->session->data['user_token'], true);

          if (isset($this->request->post['payment_fena_api'])) {
              $data['payment_fena_api'] = $this->request->post['payment_fena_api'];
          } else {
              $data['payment_fena_api'] = $this->config->get('payment_fena_api');
          }

          if (isset($this->request->post['payment_fena_secret'])) {
              $data['payment_fena_secret'] = $this->request->post['payment_fena_secret'];
          } else {
              $data['payment_fena_secret'] = $this->config->get('payment_fena_secret');
          }

          if (isset($this->request->post['payment_fena_status'])) {
              $data['payment_fena_status'] = $this->request->post['payment_fena_status'];
          } else {
              $data['payment_fena_status'] = $this->config->get('payment_fena_status');
          }

          if (isset($this->request->post['payment_fena_mode'])) {
              $data['payment_fena_mode'] = $this->request->post['payment_fena_mode'];
          } else {
              $data['payment_fena_mode'] = $this->config->get('payment_fena_mode');
          }

          if (isset($this->request->post['payment_fena_title'])) {
              $data['payment_fena_title'] = $this->request->post['payment_fena_title'];
          } else {
              $data['payment_fena_title'] = $this->config->get('payment_fena_title');
          }

          if (isset($this->request->post['payment_fena_description'])) {
              $data['payment_fena_description'] = $this->request->post['payment_fena_description'];
          } else {
              $data['payment_fena_description'] = $this->config->get('payment_fena_description');
          }

          if (isset($this->request->post['payment_fena_paid_status_id'])) {
              $data['payment_fena_paid_status_id'] = $this->request->post['payment_fena_paid_status_id'];
          } else {
              $data['payment_fena_paid_status_id'] = $this->config->get('payment_fena_paid_status_id');
          }          

          if (isset($this->request->post['payment_fena_rejected_status_id'])) {
              $data['payment_fena_rejected_status_id'] = $this->request->post['payment_fena_rejected_status_id'];
          } else {
              $data['payment_fena_rejected_status_id'] = $this->config->get('payment_fena_rejected_status_id');
          }

          if (isset($this->request->post['payment_fena_cancelled_status_id'])) {
              $data['payment_fena_cancelled_status_id'] = $this->request->post['payment_fena_cancelled_status_id'];
          } else {
              $data['payment_fena_cancelled_status_id'] = $this->config->get('payment_fena_cancelled_status_id');
          }

          if (isset($this->request->post['payment_fena_refunded_status_id'])) {
              $data['payment_fena_refunded_status_id'] = $this->request->post['payment_fena_refunded_status_id'];
          } else {
              $data['payment_fena_refunded_status_id'] = $this->config->get('payment_fena_refunded_status_id');
          }

          $this->load->model('localisation/order_status');
          $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

          if (isset($this->error['warning'])) {
              $data['error_warning'] = $this->error['warning'];
          } else {
              $data['error_warning'] = '';
          }

          $data['redirect_url']     = HTTP_CATALOG.'index.php?route=checkout/response';
          $data['notification_url'] = HTTP_CATALOG.'index.php?route=checkout/waybook';

          $data['header']      = $this->load->controller('common/header');
          $data['column_left'] = $this->load->controller('common/column_left');
          $data['footer']      = $this->load->controller('common/footer');
          $this->response->setOutput($this->load->view($extensionFena, $data));

    }//end index()


}//end class
