<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Event_live
 * 互动直播间-事件
 */
class Event_live extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Match_model', 'MM');
        $this->load->model('Eventhd_model', 'EHM');
    }
    
    public function index($match_id, $host_id) {
        $this->pageEvents($match_id, $host_id);
    }
    
    public function pageEvents($match_id, $host_id) {
        // 获取比赛信息，体育项目id
        $match_info = $this->MM->getMatchById($match_id);
        $match_event_id = $match_info['event_id'];
        $sport_id = $this->EHM->getSportIdByEventId($match_event_id);
        
        $condition = array(
                'sport_id' => $sport_id
        );
        
        $all_events = $this->EHM->getEventList($condition, 0);
        $full_events = array();
        $normal_events = array();
        foreach ($all_events as $event) {
            if ($event['type'] == 1) {
                $event_tools = $this->EHM->getEventTools($event['id']);
                $event['tools_info'] = $event_tools;
                $full_events[] = $event;
            } else if ($event['type'] == 2) {
                $normal_events[] = $event;
            }
        }

        $event_records = $this->_eventsRecord($match_id);
        
        $data['match_id']   = $match_id;
        $data['full_event'] = $full_events;
        $data['normal_event'] = $normal_events;
        $data['records'] = $event_records;
        $data['host_id'] = $host_id;
        $this->load->view('interaction/live/event', $data);
    }
    
    // 已发送互动事件
    protected function _eventsRecord($match_id) {
        $all_tools = $this->EHM->getAllToolList();
        $tool_names = array();
        foreach ($all_tools as $tool) {
            $tool_names[$tool['id']] = $tool['title'];
        }
        $event_records = $this->EHM->getEventsRecord($match_id);
        foreach ($event_records as & $item) {
            $item_tools = json_decode($item['live_tool_ids'], true);
            if (!empty($item_tools)) {
                foreach ($item_tools as $tool_id) {
                    $item['tools_name'][] = $tool_names[$tool_id];
                }
            } else {
                $item['tools_name'] = array();
            }
        }
        
        return $event_records;
    }
    
    public function send($match_id) {
        $event_id = $_POST['id'];
        $tool_ids = isset($_POST['tool_id']) ? $_POST['tool_id'] : array();
        $host_id  = isset($_POST['host_id']) ? $_POST['host_id'] : 0;
        
        // 记录发送日志
        $record_data = array(
                'match_id' => $match_id,
                'live_event_id' => $event_id,
                'live_tool_ids' => json_encode($tool_ids),
        );
        $record_id = $this->EHM->addLiveEventsRecord($record_data);
        
        ////////////////////////
        ////////////////////////
        // 发送消息
        $this->EHM->sendEvents($match_id, $record_id, $event_id, $tool_ids, $host_id);
        ////////////////////////
        ////////////////////////
        
        echo 'success';
    }
}