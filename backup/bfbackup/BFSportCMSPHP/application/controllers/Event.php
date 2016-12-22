<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Event_model', 'EM');
    }
    
    public function index() {
        $data['events'] = $this->EM->getEvents();
        $this->load->view('event/list', $data);
    }
    
}