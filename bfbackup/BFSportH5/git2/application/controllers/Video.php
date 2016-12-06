<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends MY_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model('Video_model', 'VM');
    }


    public function play($video_id, $page_type = 'share') {
        $video_info = $this->VM->getVideoById($video_id);

        if (!$video_info) {
            $this->load->view('errors/not-found', array('page_type' => $page_type));
            return;
        }

        if (!empty($video_info) && isset($video_info['image'])) {
            $video_info['image_url'] = getImageUrl($video_info['image']);
        } else {
            $video_info['image_url'] = "";
        }

        //相关推荐
        $video_list = $this->VM->getVideoRelated($video_id);
        $related_count = isset($video_list['video']) ? count($video_list['video']) : 0;

        $data = array();
        $data['top_video'] = $video_info;
        $data['related_video'] = $video_list;
        $data['related_count'] = $related_count;

        $title = $video_info['title'];
        if (preg_match("/^(\s*【)/", $video_info['title']) == 0) {
            $title = '【视频】' . $video_info['title'];
        }

        $data['page_type'] = $page_type;
        $data['header_footer_data'] = getHeaderFooterData('videoshare', $title . '_暴风体育', $page_type);
        $data['download_data'] = array(
            'info' => getShareOrAppJson('video', $video_info, $page_type),
            'count' => 1
        );

        $this->load->view('video/play', $data);
    }
}
