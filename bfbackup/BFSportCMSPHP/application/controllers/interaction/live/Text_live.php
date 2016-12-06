<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//图文
class Text_live extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('interaction/live/Text_model', 'TM');
    }

    public function index($matchId, $host='') {
        
        $result = $this->get_history($matchId, 104);
        
//         if ($result) {
//             $result = array_reverse($result);
//         }
        $data = array(
            'matchId' => $matchId,
            'host'    => $host,
            'list'    => $result ? $result : array()
        );
        
        // 添加比赛信息，用来显示比分板
        $this->load->model('Match_model', 'MM');
        $data['match'] = $this->MM->getMatchById($matchId);
        
        $this->load->view('interaction/live/text', $data);
    }
    
    public function save() {
        
        $content  = $this->input->post('content');
        $match_id = $this->input->post('match_id');
        $host     = $this->input->post('host');
        $image    = $this->input->post('poster');
        
        if (!$match_id || !$content) {
            redirect(base_url('/interaction/live/text_live/index/'.$match_id.'/'.$host.'/#text_board'));
            exit();
        }
        
        $data = array(
            'id'    => '',
            'type'  => 0,
            'text'  => $content,
            'image' => '',
            'date'  => date('Y-m-d H:i:s')
        );
        
        if ($image) {
            $data['image'] = getImageUrl($image);
        }
        
        $result = $this->TM->_sendText($data, $match_id, $host);
        
        redirect(base_url('/interaction/live/text_live/index/'.$match_id.'/'.$host.'/#text_board'));
    }
    
    //获取聊天记录 
    //matchId 比赛id
    //type 聊天信息类型 - 100 (普通互动消息)、104（主持人消息）
    private function get_history($matchId, $type=0) {
        $historydata = array();
        if (!$matchId) {
            return $historydata;
        }
        
        $url = 'http://r.rt.sports.baofeng.com/api/v2/history?id='.$matchId;
        
        if ($type) {
            $url .= '&type='.$type;
        }
        
        $result  = json_decode(send_get($url), true);
        //var_dump($url,$result);//exit;
        if ($result && '10000' == $result['errno']) {
            $historydata = $result['data']['history'];
        }
        
        return $historydata;
    }

}
