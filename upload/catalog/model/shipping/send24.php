<?php
class ModelShippingSend24 extends Model
{
    public function getQuote($address)
    {
        $this->load->language('shipping/send24');
        $quote_data = array();
        // Shipping value
        $shipping_address = $this->session->data['shipping_address'];
        $current_currency = 'DKK';
        $select_country = 'Ekspres';

        // Get/check Express.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://send24.com/wc-api/v3/get_products");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_USERPWD, $this->config->get('send24_consumer_key') . ":" . $this->config->get('send24_consumer_secret'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
        ));
        $send24_countries = json_decode(curl_exec($ch));
        curl_close($ch);
        $n = count($send24_countries);

        for ($i = 0; $i < $n; $i++) {
            if ($send24_countries[$i]->title == $select_country) {
                $cost = $send24_countries[$i]->price;
                $product_id = $send24_countries[$i]->product_id;
                $i = $n;
                $is_available = true;
            } else {
                $is_available = false;
            }
        }

        if($is_available == true){
            $shipping_address_1 = $shipping_address['address_1'];
            $shipping_postcode = $shipping_address['postcode'];
            $shipping_city = $shipping_address['city'];
            $shipping_country = $shipping_address['iso_code_2'];
            if($shipping_country == 'DK'){
                $shipping_country = 'Denmark';
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://send24.com/wc-api/v3/get_user_id");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_USERPWD, $this->config->get('send24_consumer_key') . ":" . $this->config->get('send24_consumer_secret'));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
            ));
            $user_meta = json_decode(curl_exec($ch));

            $billing_address_1 = $user_meta->billing_address_1['0'];
            $billing_postcode = $user_meta->billing_postcode['0'];
            $billing_city = $user_meta->billing_city['0'];
            $billing_country = $user_meta->billing_country['0'];
            if($billing_country == 'DK'){
                $billing_country = 'Denmark';
            }

            $full_billing_address = "$billing_address_1, $billing_postcode $billing_city, $billing_country";
            $full_shipping_address = "$shipping_address_1, $shipping_postcode $shipping_city, $shipping_country";
            // $full_billing_address = "вул. Перемоги, 63, Запоріжжя, Запорізька область";
            // $full_shipping_address = "вул. Перемоги, 63, Запоріжжя, Запорізька область";
            // Get billing coordinates.
            $billing_url = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=".urlencode($full_billing_address);
            $billing_latlng = get_object_vars(json_decode(file_get_contents($billing_url)));
            // Check billing address.
            if(!empty($billing_latlng['results'])){
                $billing_lat = $billing_latlng['results'][0]->geometry->location->lat;
                $billing_lng = $billing_latlng['results'][0]->geometry->location->lng;

                // Get shipping coordinates.
                $shipping_url = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=".urlencode($full_shipping_address);
                $shipping_latlng = get_object_vars(json_decode(file_get_contents($shipping_url)));
                // Check shipping address.
                if(!empty($shipping_latlng['results'])){
                    $shipping_lat = $shipping_latlng['results'][0]->geometry->location->lat;
                    $shipping_lng = $shipping_latlng['results'][0]->geometry->location->lng;

                    // get_is_driver_area_five_km
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://send24.com/wc-api/v3/get_is_driver_area_five_km");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_HEADER, FALSE);
                    curl_setopt($ch, CURLOPT_POST, TRUE);
                    curl_setopt($ch, CURLOPT_USERPWD, $this->config->get('send24_consumer_key') . ":" . $this->config->get('send24_consumer_secret'));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, '
                                                    {
                                                        "billing_lat": "'.$billing_lat.'",
                                                        "billing_lng": "'.$billing_lng.'",
                                                        "shipping_lat": "'.$shipping_lat.'",
                                                        "shipping_lng": "'.$shipping_lng.'"
                                                    }
                                                    ');
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        "Content-Type: application/json",
                    ));

                    $response = curl_exec($ch);
                    $res = json_decode($response);
                    if(!empty($res)){
                        // Get time work Express.
                        $start_work_express = $this->config->get('send24_start_time');
                        $end_work_express = $this->config->get('send24_stop_time');
                         // Check time work.
                        date_default_timezone_set('Europe/Copenhagen');
                        $today = strtotime(date("Y-m-d H:i"));
                        $start_time = strtotime(''.date("Y-m-d").' '.$start_work_express.'');
                        $end_time = strtotime(''.date("Y-m-d").' '.$end_work_express.'');
                        // Check time setting in plugin. 
                        if($start_time < $today && $end_time > $today){
                            // Check start_time.
                            if(!empty($res->start_time)){
                                $picked_up_time = strtotime(''.date("Y-m-d").' '.$res->start_time.'');
                                // Check time work from send24.com
                                if($start_time < $picked_up_time && $end_time > $picked_up_time){
                                    // Auto convert currency.
                                    if($current_currency != $this->config->get('config_currency')){
                                        $cost = $this->convert_currency($cost, $current_currency, $this->config->get('config_currency'));
                                    }

                                    $quote_data['send24'] = array(
                                        'code' => 'send24.send24',
                                        'title' => 'Send24 Sameday',
                                        'cost' => $cost,
                                        'tax_class_id' => 0,
                                        'text' => $this->currency->format($cost, $this->currency->getCode(), 1.0000000),
                                    );

                                    $title = 'Send24';
                                    $method_data = array(
                                        'code' => 'send24',
                                        'title' => $title,
                                        'quote' => $quote_data,
                                        'sort_order' => $this->config->get('flat_sort_order'),
                                        'error' => false,
                                    );
                                    return $method_data;

                                }
                            }
                        }
                    }
                    curl_close($ch);
         
                }
            }

        }
    }
       
    public function convert_currency($cost, $from, $to){
        $temp = 'https://www.google.com/finance/converter?a='.$cost.'&from='.$from.'&to='.$to;
        $get = file_get_contents($temp);
        $get = explode("<span class=bld>",$get);
        $get = explode("</span>",$get[1]);  
        $converted_amount = preg_replace("/[^0-9\.]/", null, $get[0]);
        return preg_replace("/([^0-9\\.])/i", "", $get['0']);
    }

}
