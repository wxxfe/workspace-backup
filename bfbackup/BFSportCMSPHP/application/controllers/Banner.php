<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Banner_model', 'BM');
    }
    
    public function index() {
        if ($_POST) {
            $info = $_POST;
            
            $info['visible'] = @intval($info['visible']);
            if (!@$info['publish_tm']) {
                $info['publish_tm'] = time();
            }
            if ($info['type'] == 'news') {
                $info['ref_id'] = intval($info['id_data']);
            } else {
                $info['data']   = trim($info['id_data']);
            }
            unset($info['id_data']);
            
            $this->BM->db('sports')->insert('banner', $info);
        }
        
        $this->config->load('sports');
        $data = array();
        $data['banners'] = $this->BM->getBanners();
        $data['banner_types'] = $this->config->item('banner_types');
        $this->load->view('banner/list', $data);
    }
    
}