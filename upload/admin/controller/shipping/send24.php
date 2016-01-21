<?php
class ControllerShippingSend24 extends Controller
{
    private $error = array();
    public $postcode = 1560;

    public function index()
    {
        $this->load->language('shipping/send24');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('send24', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $options_time = array(
            array(
                'id_option' => '00:00',
                'name' => '00:00',
            ),
            array(
                'id_option' => '00:30',
                'name' => '00:30',
            ),
            array(
                'id_option' => '01:00',
                'name' => '01:00',
            ),
            array(
                'id_option' => '01:30',
                'name' => '01:30',
            ),
            array(
                'id_option' => '02:00',
                'name' => '02:00',
            ),
            array(
                'id_option' => '02:30',
                'name' => '02:30',
            ),
            array(
                'id_option' => '03:00',
                'name' => '03:00',
            ),
            array(
                'id_option' => '03:30',
                'name' => '03:30',
            ),
            array(
                'id_option' => '04:00',
                'name' => '04:00',
            ),
            array(
                'id_option' => '04:30',
                'name' => '04:30',
            ),
            array(
                'id_option' => '05:00',
                'name' => '05:00',
            ),
            array(
                'id_option' => '05:30',
                'name' => '05:30',
            ),
            array(
                'id_option' => '06:00',
                'name' => '06:00',
            ),
            array(
                'id_option' => '06:30',
                'name' => '06:30',
            ),
            array(
                'id_option' => '07:00',
                'name' => '07:00',
            ),
            array(
                'id_option' => '07:30',
                'name' => '07:30',
            ),
            array(
                'id_option' => '08:00',
                'name' => '08:00',
            ),
            array(
                'id_option' => '08:30',
                'name' => '08:30',
            ),
            array(
                'id_option' => '09:00',
                'name' => '09:00',
            ),
            array(
                'id_option' => '09:30',
                'name' => '09:30',
            ),
            array(
                'id_option' => '10:00',
                'name' => '10:00',
            ),
            array(
                'id_option' => '10:30',
                'name' => '10:30',
            ),
            array(
                'id_option' => '11:00',
                'name' => '11:00',
            ),
            array(
                'id_option' => '11:30',
                'name' => '11:30',
            ),
            array(
                'id_option' => '12:00',
                'name' => '12:00',
            ),
            array(
                'id_option' => '12:30',
                'name' => '12:30',
            ),
            array(
                'id_option' => '13:00',
                'name' => '13:00',
            ),
            array(
                'id_option' => '13:30',
                'name' => '13:30',
            ),
            array(
                'id_option' => '14:00',
                'name' => '14:00',
            ),
            array(
                'id_option' => '14:30',
                'name' => '14:30',
            ),
            array(
                'id_option' => '15:00',
                'name' => '15:00',
            ),
            array(
                'id_option' => '15:30',
                'name' => '15:30',
            ),
            array(
                'id_option' => '16:00',
                'name' => '16:00',
            ),
            array(
                'id_option' => '16:30',
                'name' => '16:30',
            ),
            array(
                'id_option' => '17:00',
                'name' => '17:00',
            ),
            array(
                'id_option' => '17:30',
                'name' => '17:30',
            ),
            array(
                'id_option' => '18:00',
                'name' => '18:00',
            ),
            array(
                'id_option' => '18:30',
                'name' => '18:30',
            ),
            array(
                'id_option' => '19:00',
                'name' => '19:00',
            ),
            array(
                'id_option' => '19:30',
                'name' => '19:30',
            ),
            array(
                'id_option' => '20:00',
                'name' => '20:00',
            ),
            array(
                'id_option' => '20:30',
                'name' => '20:30',
            ),
            array(
                'id_option' => '21:00',
                'name' => '21:00',
            ),
            array(
                'id_option' => '21:30',
                'name' => '21:30',
            ),
            array(
                'id_option' => '22:00',
                'name' => '22:00',
            ),
            array(
                'id_option' => '22:30',
                'name' => '22:30',
            ),
            array(
                'id_option' => '23:00',
                'name' => '23:00',
            ),
            array(
                'id_option' => '23:30',
                'name' => '23:30',
            ),
        );
        $data['options_time'] = $options_time;

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['entry_status'] = $this->language->get('entry_status');

        $data['entry_consumer_key'] = $this->language->get('entry_consumer_key');
        $data['entry_consumer_secret'] = $this->language->get('entry_consumer_secret');
        $data['entry_start_time'] = $this->language->get('entry_start_time');
        $data['entry_stop_time'] = $this->language->get('entry_stop_time');

        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['help_consumer_key'] = $this->language->get('help_consumer_key');
        $data['help_consumer_secret'] = $this->language->get('help_consumer_secret');
        $data['help_start_time'] = $this->language->get('help_start_time');
        $data['help_stop_time'] = $this->language->get('help_stop_time');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['key'])) {
            $data['error_consumer_key'] = $this->error['key'];
        } else {
            $data['error_consumer_key'] = '';
        }

        if (isset($this->error['secret'])) {
            $data['error_consumer_secret'] = $this->error['secret'];
        } else {
            $data['error_consumer_secret'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_shipping'),
            'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('shipping/send24', 'token=' . $this->session->data['token'], 'SSL'),
        );

        $data['action'] = $this->url->link('shipping/send24', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['send24_consumer_key'])) {
            $data['send24_consumer_key'] = $this->request->post['send24_consumer_key'];
        } else {
            $data['send24_consumer_key'] = $this->config->get('send24_consumer_key');
        }

        if (isset($this->request->post['send24_consumer_secret'])) {
            $data['send24_consumer_secret'] = $this->request->post['send24_consumer_secret'];
        } else {
            $data['send24_consumer_secret'] = $this->config->get('send24_consumer_secret');
        }

        if (isset($this->request->post['send24_start_time'])) {
            $data['send24_start_time'] = $this->request->post['send24_start_time'];
        } else {
            $data['send24_start_time'] = $this->config->get('send24_start_time');
        }

        if (isset($this->request->post['send24_stop_time'])) {
            $data['send24_stop_time'] = $this->request->post['send24_stop_time'];
        } else {
            $data['send24_stop_time'] = $this->config->get('send24_stop_time');
        }

        if (isset($this->request->post['send24_sort_order'])) {
            $data['send24_sort_order'] = $this->request->post['send24_sort_order'];
        } else {
            $data['send24_sort_order'] = $this->config->get('send24_sort_order');
        }

        if (isset($this->request->post['send24_status'])) {
            $data['send24_status'] = $this->request->post['send24_status'];
        } else {
            $data['send24_status'] = $this->config->get('send24_status');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('shipping/send24.tpl', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'shipping/send24')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['send24_consumer_key']) {
            $this->error['key'] = $this->language->get('error_consumer_key');
        }
        if (!$this->request->post['send24_consumer_secret']) {
            $this->error['secret'] = $this->language->get('error_consumer_secret');
        }

        // Check keys authorization send24.com
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://send24.com/wc-api/v3/get_service_area/" . $this->postcode);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_USERPWD, $this->request->post['send24_consumer_key'] . ":" . $this->request->post['send24_consumer_secret']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
        ));
        $zip_area = curl_exec($ch);
        $zip = json_decode($zip_area, true);

        if (!empty($zip['errors'])) {
            $this->error['key'] = $this->language->get('error_invalid_keys');
            $this->error['secret'] = $this->language->get('error_invalid_keys');
        }

        // need add check time
        return !$this->error;
    }
}
