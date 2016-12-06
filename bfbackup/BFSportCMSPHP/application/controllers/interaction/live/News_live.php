<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Event_live
 * 互动直播间-新闻资讯
*/
class News_live extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Match_model', 'MM');
        $this->load->model('News_model', 'NM');
    }
    
    public function index($match_id, $host_id) {
        $this->history($match_id, $host_id);
    }
    
    public function history($match_id, $host_id) {
        $data = array();
        
        $list = $this->NM->getLivehistory($match_id);
        
        $data['list'] = $list;
        $data['match_id'] = $match_id;
        $data['host_id']  = $host_id;
        $this->load->view('interaction/live/news', $data);
    }

    public function send($match_id, $host_id, $type='news', $ref_id) {
        
        $news_info = $this->NM->getNewsById($ref_id);
        
        $record_data = array(
            'news_id' => $news_info['id'],
            'match_id' => $match_id,
            'title'   => $news_info['title'],
            'image'   => getImageUrl($news_info['image']),
            'large_image' => !empty($news_info['large_image']) ? getImageUrl($news_info['large_image']) : "",
            'publish_tm' => strtotime($news_info['publish_tm']),
        );
        
        $record_id = $this->NM->addLiveHistory($record_data);
        
        unset($record_data['match_id']);
        $record_data['id'] = $record_id;
        $record_data['type'] = 5;
        
        
        ////////////////////////
        ////////////////////////
        // 发送消息
        $this->NM->send_host_message($record_data, $match_id, $host_id);
        ////////////////////////
        ////////////////////////
        
        redirect(site_url('/interaction/live/news_live/history/').$match_id.'/'.$host_id);
    }
}