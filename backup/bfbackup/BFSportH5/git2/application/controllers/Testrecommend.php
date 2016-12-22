<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testrecommend extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('test_model', 'TESTM');
    }

    public function index(){
        $id = $_GET['id'];
        $type = $_GET['type'];

        $method = 'get'. strtolower($type) . 'Related';
        $related = $this->TESTM->$method($id);

        echo json_encode($related , JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }
}