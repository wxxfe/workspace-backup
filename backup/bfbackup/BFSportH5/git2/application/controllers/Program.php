<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Program extends MY_Controller {


    public function __construct() {

        parent::__construct();
        $this->load->model('Video_model', 'VM');
        $this->load->model('Program_model', 'PM');
        $this->load->model('Api_model', 'AM');
    }

    public function index($id = 0, $page_type = 'share') {
        $this->detail($id, $page_type);
    }

    public function detail($program_id, $page_type = 'share') {
        $program_detail = $this->PM->getInfo($program_id);
        $api_data = $this->AM->requestapi('program', array($program_id));
        $api_data_arr = json_decode($api_data, true);
        if ($api_data_arr['errno'] == '10000') {
            $video_list = $api_data_arr['data'];
        } else {
            $video_list = array('content' => array());
        }

        $video_ids = array();
        foreach ($video_list['content'] as $video) {
            array_push($video_ids, $video['id']);
        }

        if (!$video_ids) {
            $this->load->view('errors/not-found', array('page_type' => $page_type));
            return;
        }

        $top_video = array();

        $video_extra_info = $this->VM->getVideosExtra($video_ids);
        foreach ($video_list['content'] as & $video) {
            $video['extra'] = $video_extra_info[$video['id']];
            if (empty($top_video)) {
                $top_video = $video;
            }
        }


        $data = array();
        $data['top_video'] = $top_video;
        $data['program'] = $program_detail;
        $data['video_list'] = $video_list;

        $data['page_type'] = $page_type;
        $data['header_footer_data'] = getHeaderFooterData('program', '【视频】' . $top_video['title'] . '_暴风体育', $page_type);
        $data['download_data'] = array(
                'info' => getShareOrAppJson('program', $program_detail, $page_type)
        );

        $this->load->view('program/detail', $data);
    }

}
