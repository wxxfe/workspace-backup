<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eventhd_model extends MY_Model {
    
    public function __construct() {
        parent::__construct();
        $this->static_url = APPPATH.'../static/upload/live_source/';
        $this->tmp_url = '/static/events_tools_tmp/';
    }
    
    public function getToolList($where, $limit, $offset) {
        if (!empty($where)) {
            $query = $this->db('kungfu')->get_where('live_tool', $where, $limit, $offset);
        } else {
            $query = $this->db('kungfu')->get('live_tool', $limit, $offset);
        }
        $result = array();
        foreach ($query->result_array() as $item) {
            $result[] = $item;
        }
        return $result;
    }
    
    public function getAllToolList() {
        // 只显示未下线的道具
        $query = $this->db('kungfu')->get_where('live_tool', array('visible'=>1));
        return $query->result_array();
    }
    
    public function getEventList($where, $limit=10, $offset=0) {
        if (!empty($where)) {
            $query = $this->db('kungfu')->get_where('live_event', $where, $limit, $offset);
        } else {
            $query = $this->db('kungfu')->get('live_event', $limit, $offset);
        }
        $result = array();
        foreach ($query->result_array() as $item) {
            $result[] = $item;
        }
        return $result;
    }
    
    public function getAllEvents() {
        $query = $this->db('kungfu')->get('live_event');
        return $query->result_array();
    }
    
    public function getEventTools($event_id) {
        $query = $this->db('kungfu')->select('live_event_tool.*,live_tool.title as title,
                live_tool.image as image,live_tool.type as type,live_tool.visible as visible')
            ->from('live_event_tool')
            ->join('live_tool', 'live_event_tool.live_tool_id=live_tool.id', 'left')
            ->where('live_event_tool.live_event_id', $event_id)
            ->where('live_tool.visible', 1)    // 只取道具未被下线的事件道具
            ->get();
        $result = array();
        foreach ($query->result_array() as $item) {
            $result[] = $item;
        }
        
        return $result;
    }
    
    public function getEventInfo($id) {
        $query = $this->db('kungfu')->get_where('live_event', array('id'=>$id), 1);
        return $query->row_array();
    }
    
    public function addTool($data) {
        return $this->db('kungfu')->insert('live_tool', $data);
    }
    
    public function updateTool($id, $data) {
        return $this->db('kungfu')->update('live_tool', $data, array('id'=>$id));
    }
    
    public function addEvent($data) {
        return $this->db('kungfu')->insert('live_event', $data);
    }
    
    public function updateEvent($id, $data) {
        return $this->db('kungfu')->update('live_event', $data, array('id'=>$id));
    }
    
    public function removeEvent($id) {
        return $this->db('kungfu')->remove('live_event', $id);
    }
    
    public function addEventTool($data) {
        return $this->db('kungfu')->insert('live_event_tool', $data);
    }
    
    public function removeEventTool($id) {
        return $this->db('kungfu')->remove('live_event_tool', $id);
    }
    
    public function removeEventTools($event_id) {
        return $this->db('kungfu')->delete('live_event_tool', array('live_event_id'=>$event_id));
    }
    
    public function updateEventTool($et_id, $data) {
        return $this->db('kungfu')->update('live_event_tool', $data, array('id'=>$et_id));
    }
    
    public function getEventTool($et_id) {
        return $this->db('kungfu')->get_where('live_event_tool', array('id'=>$et_id,), 1)->row_array();
    }
    
    public function getEventToolByRel($event_id, $tool_id) {
        return $this->db('kungfu')->get_where('live_event_tool', 
                array('live_event_id'=>$event_id, 'live_tool_id'=>$tool_id), 1)->row_array();
    }
    
    public function addLiveEventsRecord($data) {
        return $this->db('kungfu')->insert('live_events_record', $data);
    }
    
    public function getEventsRecord($match_id) {
        $query = $this->db('kungfu')->select('live_events_record.*,live_event.title as event_title')
            ->from('live_events_record')
            ->join('live_event', 'live_event.id=live_events_record.live_event_id', 'left')
            ->where('live_events_record.match_id', $match_id)
            ->order_by('live_events_record.id', 'DESC')
            ->get();
        
        return $query->result_array();
    }
    
    /**
     * 发送主持人互动消息
     * @param intval $match_id
     * @param intval $record_id
     * @param intval $event_id
     * @param array  $tool_ids
     * @param intval $host_id
     */
    public function sendEvents($match_id, $record_id, $event_id, $tool_ids, $host_id) {
        $et_ids = array();
        if (!empty($tool_ids)) {
            $query = $this->db('kungfu')->select('live_event_tool.*, live_tool.type')
                ->from('live_event_tool')
                ->join('live_tool', 'live_event_tool.live_tool_id=live_tool.id', 'LEFT')
                ->where('live_event_tool.live_event_id', $event_id)
                ->where_in('live_event_tool.live_tool_id', $tool_ids)
                ->get();
            foreach ($query->result_array() as $et_item) {
                array_push($et_ids, intval($et_item['id']));
                if ($et_item['type'] == 'dynamic') {
                    $et_ids = array(intval($et_item['id']), intval($et_item['id']));
                    break;
                }
            }
        }
        
        $event_data = array(
            'id'       => $record_id,
            'type'     => 2,
            'event_id' => $event_id,
            'et_ids'   => $et_ids,
        );
        
        $result = $this->send_host_message($event_data, $match_id, $host_id);
        
        return $result;
    }
    
    ////////////////////////////////////////////////////////////////////////
    /**
     * 
     * @param string $type  event|tool|et
     * @param number $event_id
     * @param number $tool_id
     * @param string $source_type  image|audio|video
     */
    public function getStaticPath($type, $event_id=0, $tool_id=0, $source_type) {
        $result = '';
        if ($type == 'tool') {
            $result = "tool_{$tool_id}_{$source_type}/";
        } else if ($type == 'event') {
            $result = "event_{$event_id}_{$source_type}/";
        } else if ($type == 'et') {
            $result = "et_{$event_id}_{$tool_id}_{$source_type}/";
        }
        
        return $result;
    }
    
    /**
     * 从sports库中，获取赛事（event_id）对应的体育项目sport_id
     * 注意，这个方法的event_id和之前方法的都不是同一个含义。
     * 
     * @param intval $event_id
     */
    public function getSportIdByEventId($event_id) {
        $query = $this->db('sports')->get_where('event', array('id'=>$event_id), 1);
        $info = $query->row_array();
        $sport_id = $info['sports_id'];
        return $sport_id;
    }
    
    /**
     * 从sports库中，获取已有的体育项目id
     */
    public function getSports() {
        $query = $this->db('sports')->select('*')
            ->from('sports')
            ->get();
        
        return $query->result_array();
    }
}