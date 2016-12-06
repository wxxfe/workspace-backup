<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {

    public function __construct(){

        parent::__construct();

    }

    public function getVideoInfo($id){

        $this->load->model('Video_model','VM');

        $result = array();
        try{
            $video = $this->VM->getVideoById($id);
            $result = array(
                'status' => 1,
                'data' => $video
            );
        }catch(Exception $e){
            $result = array(
                'status' => 0,
                'data' => array()
            );
        }

        echo json_encode($result);
    }
}
