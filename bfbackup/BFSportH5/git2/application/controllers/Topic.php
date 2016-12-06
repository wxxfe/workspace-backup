<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Topic extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Api_model', 'APIM');
    }

    public function detail($id, $page_type = 'share') {

        $topic = array();

        $resp = @json_decode($this->APIM->requestapi('topic_detail', array($id)), true);
        if (isset($resp['data']['body'])) {
            $topic = $resp['data']['body'];
        }

        if (!$topic) {
            $this->load->view('errors/not-found', array('page_type' => $page_type));
            return;
        }

        $data = array();

        $topic['thread']['id'] = $id;
        $data['topic'] = $topic;
        $data['posts'] = ($topic['thread']['count'] > 0 ? $this->posts($id) : array());
        $data['hots'] = ($topic['thread']['hot_count'] > 0 ? $this->hots($id) : array());
        $data['people'] = $this->people($id);

        $data['page_type'] = $page_type;
        $data['header_footer_data'] = getHeaderFooterData('topic_share', '【话题】' . $topic['thread']['title'] . '_暴风体育', $page_type);
        $data['download_data'] = array(
                'info' => getShareOrAppJson('topic', array('id' => $topic['thread']['id'], 'title' => $topic['thread']['title']), $page_type)
        );

        $this->load->view('topic/detail', $data);
    }

    public function posts($id) {
        $resp = @json_decode($this->APIM->requestapi('topic_posts', array($id)), true);
        if (isset($resp['data']['body'])) {
            return $resp['data']['body'];
        }
        return array();
    }

    public function hots($id) {
        $resp = @json_decode($this->APIM->requestapi('topic_hots', array($id)), true);
        if (isset($resp['data']['body'])) {
            return $resp['data']['body'];
        }
        return array();
    }

    public function people($id) {
        $resp = @json_decode($this->APIM->requestapi('topic_people', array($id)), true);
        if (isset($resp['data']['list'])) {
            return $resp['data']['list'];
        }
        return array();
    }

}