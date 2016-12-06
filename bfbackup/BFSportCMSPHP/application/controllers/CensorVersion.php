<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Censorversion extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('censor_version_model', 'CVM');
    }
    
    public function index() {
        return $this->getList();
    }
    
    public function getList() {
        $list = $this->CVM->getList();

        $data = array();
        $data['list'] = $list;
        $this->load->view('censorversion/list', $data);
    }
    
    public function update() {
        $data = $_POST;
        $id = $data['pk'];
        $version = $data['value'];
        $result = $this->CVM->db('sports')->updateInfo($id, array('version' => $version));
        echo $result ? 'success' : 'fail';
    }
}