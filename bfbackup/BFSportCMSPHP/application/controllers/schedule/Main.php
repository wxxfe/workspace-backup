<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//赛程
class Main extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Match_model', 'MM');
        $this->load->model('Video_model', 'VM');
    }
    
    public function index($event_id = 0, $date = '-') {
        $data['event_id'] = $event_id;
        $data['date'] = $date;
        $this->load->view('schedule/list', $data);
    }

}