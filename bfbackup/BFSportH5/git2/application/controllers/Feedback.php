<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function ios() {
        $result_data = array();
        $result_data['result'] = 0;
        if ($this->input->post()) {
            $data = array();
            $data['uuid'] = strval(trim($this->input->post('uuid')));
            $data['text'] = strval(trim($this->input->post('text')));
            $data['contcat'] = strval(trim($this->input->post('contcat')));
            $data['app_version'] = strval(trim($this->input->post('app_version')));
            $data['phone_model'] = strval(trim($this->input->post('phone_model')));
            $data['phone_system'] = strval(trim($this->input->post('phone_system')));
            $data['network'] = strval(trim($this->input->post('network')));

            if ($data['text'] && $data['app_version']) {
                $this->load->model('Feedback_model', 'FM');
                $this->FM->db('feedback')->insert('ios_feedback', $data);
                if ($this->FM->db('feedback')->insert_id()) {
                    $result_data['result'] = 1;
                } else {
                    $result_data['result'] = 2;
                    // $result_data['input_data'] = $data;
                }
            }
        }
        $result_data['header_footer_data'] = getHeaderFooterData('feedback');
        $this->load->view('feedback/index', $result_data);
    }

}
