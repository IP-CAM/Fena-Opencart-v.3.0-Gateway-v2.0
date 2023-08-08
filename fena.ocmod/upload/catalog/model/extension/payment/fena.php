<?php
/**
 * ModelExtensionPaymentFena File Doc Comment
 * php version 7.2.10
 *
 * @category Class
 * @package  Fena
 * @author   A N Other <support@fena.co>
 * @license  https://www.fena.co General Public License
 * @link     https://www.fena.co/
 */
/**
 * ModelExtensionPaymentFena Class Doc Comment
 *
 * @category Class
 * @package  Fena
 * @author   A N Other <support@fena.co>
 * @license  https://www.fena.co General Public License
 * @link     https://www.fena.co/
 */
class ModelExtensionPaymentFena extends Model
{


    /**
     * GetMethod build template
     *
     * @param address $address comment about this variable
     * @param total   $total   comment about this variable
     *
     * @return void
     */
    public function getMethod($address, $total)
    {
        $this->load->language('extension/payment/fena');

        $query = $this->db->query("SELECT * FROM `".DB_PREFIX."zone_to_geo_zone` WHERE `geo_zone_id` = '".(int) $this->config->get('payment_fena_geo_zone_id')."' AND `country_id` = '".(int) $address['country_id']."' AND (`zone_id` = '".(int) $address['zone_id']."' OR `zone_id` = '0')");

        if ($this->config->get('payment_fena_total') > $total) {
            $status = false;
        } elseif (!$this->config->get('payment_fena_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }

        $methodData = array();

        if ($status) {
            $methodData = array(
                'code'       => 'fena',
                'title'      => $this->language->get('text_title'),
                'terms'      => '',
                'sort_order' => $this->config->get('payment_fena_sort_order'),
            );
        }

        return $methodData;

    }//end getMethod()


}//end class
