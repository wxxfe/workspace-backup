<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Text_model extends MY_Model {
    
    public function __construct() {
        parent::__construct();
    }

    
    /**
     * 发送图文消息
     */
    public function _sendText($data, $match_id, $host_id) {

        //说明
        //
        //id - 战报ID
        //type - 消息类型
        //image     图片
        //text      消息
        
        if (!$data) {
            return false;
        }
  
        return $this->send_host_message($data, $match_id, $host_id);
    }
}
