<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Special extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Api_model', 'AM');
    }

    public function detail($id, $page_type = 'share') {
        $result = @json_decode($this->AM->requestapi('special_topic', array($id)), true);
        $special = isset($result['data']) ? $result['data'] : array();

        if (!$special) {
            $this->load->view('errors/not-found', array('page_type' => $page_type));
            return;
        }

        $special['id'] = $id;

        $data = array();
        $data['special'] = $special;

        $data['page_type'] = $page_type;
        $data['header_footer_data'] = getHeaderFooterData('special-topic', '【专题】' . ($special['title'] ? $special['title'] : "") . '_暴风体育', $page_type);
        $data['download_data'] = array(
                'info' => getShareOrAppJson('special', $special, $page_type)
        );

        $this->load->view('special/detail', $data);
    }

}
