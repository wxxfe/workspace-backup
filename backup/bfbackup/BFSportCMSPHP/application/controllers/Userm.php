<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//用户管理
class Userm extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Userm_model', 'UMM');
    }

    public function index($offset=0) {
        $limit = 20;

        $total     = $this->UMM->db('board')->getTotal('user');
        $userData  = $this->UMM->getAllUser($limit, $offset);

        $data = array(
            'userdata' => $userData ? $userData : array(),
            'total'     => $total
        );

        $data['page'] = $this->_pagination($total, $limit, '/userm/index');
        $data['offset'] = $offset;
        

        $this->load->view('userm/list', $data);
    }


}
