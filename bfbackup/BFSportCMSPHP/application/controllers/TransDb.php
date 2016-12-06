<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransDb extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Transdb_model', 'TDM');
    }
    
    public function index() {
        
        $data = array();
        $data = $this->TDM->page();
        
        $this->load->view('transdb/index', $data);
    }
    
    public function start() {
        $data = $this->TDM->start();
        redirect(site_url('/TransDb/index'));
    }
}