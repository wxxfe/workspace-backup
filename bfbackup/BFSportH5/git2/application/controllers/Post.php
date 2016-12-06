<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Api_model', 'APIM');
    }

    public function detail($id, $page_type = 'share') {

        $post = array();

        $resp = @json_decode($this->APIM->requestapi('post_detail', array($id)), true);
        if (isset($resp['data']['body'])) {
            $post = $resp['data']['body'];
        }

        if (!$post) {
            $this->load->view('errors/not-found', array('page_type' => $page_type));
            return;
        }

        $data = array();

        $data['post'] = $post;
        $data['comments'] = (@$post['comment_count'] > 0 ? $this->comments($id) : array());

        $data['page_type'] = $page_type;
        $data['header_footer_data'] = getHeaderFooterData('post_share', '【图集】' . $post['thread_title'] . '_暴风体育', $page_type);
        $data['download_data'] = array(
                'info' => getShareOrAppJson('topic', array('id' => $post['thread_id'], 'title' => $post['thread_title'], 'postId' => $post['id']), $page_type)
        );

        $this->load->view('post/detail', $data);
    }

    public function comments($id) {
        $resp = @json_decode($this->APIM->requestapi('post_comments', array($id)), true);
        if (isset($resp['data']['body'])) {
            return $resp['data']['body'];
        }
        return array();
    }

}