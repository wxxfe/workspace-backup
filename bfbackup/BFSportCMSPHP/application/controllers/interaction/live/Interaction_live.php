<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//互动直播间
class Interaction_live extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Match_model', 'MM');
    }
    
    public function index($event_id = 0, $date = '-') {
        $data['event_id'] = $event_id;
        $data['date'] = $date;
        $data['users'] = $this->MM->getUsersAPI('135601920097128682');
        $this->load->view('interaction/live/interaction', $data);
    }

}
