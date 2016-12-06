<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 互动直播间话题-互动事件
 */
class Eventhd extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('eventhd_model', 'EHM');
        $this->limit = 10;
        
        $this->upload_path = APPPATH.'../upload/live_source_tmp/';
        $this->static_path = APPPATH.'../static/upload/live_source/';
        $this->source_path = '/static/upload/live_source/';
        $this->event_type = array(
            'full'   => 1,
            'normal' => 2,
        );
        
        $this->event_tool_action = array(
            1 => '道具button上飘出叠加',
            2 => '场景动画变化',
            3 => '全屏随机飞出',
        );
    }
    
    public function index() {
        $this->toolsList();
    }
    
    /**
     * 道具列表
     */
    public function toolsList($offset=0) {
        $condition = array();
        $list = $this->EHM->getToolList($condition, $this->limit, $offset);
        $total = $this->EHM->getTotal('live_tool');
        
        $data = array();
        $data['page'] = $this->_pagination($total, $this->limit, '/interaction/event/Eventhd/toolsList');
        $data['list'] = $list;
        $data['source_path'] = $this->source_path;
        $this->load->view('interaction/event/tool_list', $data);
    }
    
    /**
     * 事件列表
     * @param 类型 $type   full,normal  全屏、普通
     * 需要get或者post获取体育类型
     */
    public function eventsList($type='full', $offset=0) {
        $type_v = $this->event_type[$type];
        $sport_id = isset($_GET['sport_id']) ? $_GET['sport_id'] : 0;
        
        $condition = array(
            'type' => $type_v,
        );
        if (!empty($sport_id)) {
            $condition['sport_id'] = $sport_id;
        }
        $list = $this->EHM->getEventList($condition, $this->limit, $offset);
        $total = $this->EHM->getTotal('live_event', $condition);
        $sports_list = $this->EHM->getSports();
        
        $data = array();
        $data['page'] = $this->_pagination($total, $this->limit, "/interaction/event/Eventhd/eventsList/{$type}");
        $data['list'] = $list;
        $data['source_path'] = $this->source_path;
        $data['type'] = $type;
        $data['sport_id'] = $sport_id;
        $data['sports_list'] = $sports_list;
        $this->load->view('interaction/event/event_list', $data);
        
    }
    
    public function toolAdd() {
        $data = array();
        if (!empty($_POST)) {
            $tool_id = $this->_addTool($_POST);
            redirect(site_url('interaction/event/Eventhd/toolsList'));
        }
        $this->load->view('interaction/event/tool_add', $data);
    }
    
    public function toolEdit() {
        
    }
    
    public function toolUpdate() {
        $tool_id = $_POST['pk'];
        $info = array(
                $_POST['name'] => $_POST['value']
        );
        $this->EHM->updateTool($tool_id, $info);
        
        return true;
    }
    
    public function eventAdd($type) {
        $data = array();
        if (!empty($_POST)) {
            $event_id = $this->_addEvent($_POST);
            redirect(site_url("interaction/event/Eventhd/eventsList/{$type}"));
        }
        $sports_list = $this->EHM->getSports();
        $data['sports_list'] = $sports_list;
        $this->load->view('interaction/event/event_add', $data);
    }
    
    public function eventRemove() {
        $event_id = $_POST['id'];
        $this->EHM->removeEventTools($event_id);
        $result = $this->EHM->removeEvent($event_id);
        echo 'success';
        return;
    }
    
    public function eventEdit($id) {
        $event_info = $this->EHM->getEventInfo($id);
        
        if (!empty($_POST)) {
            $this->_editEvent($id, $_POST);
            redirect(site_url('interaction/event/Eventhd/eventsList'));
        }
        
        // 事件相关道具列表
        $event_tools = $this->EHM->getEventTools($id);
        $sports_list = $this->EHM->getSports();
        
        $data = array();
        $data['info'] = $event_info;
        $data['et_list'] = $event_tools;
        $data['source_path'] = $this->source_path;
        $data['sports_list'] = $sports_list;
        $this->load->view('interaction/event/event_edit', $data);
    }
    
    public function eventToolAdd($event_id) {
        $tool_list = $this->EHM->getAllToolList();
        $data = array();
        if (!empty($_POST)) {
            $event_id = $this->_addEventTool($event_id, $_POST);
            redirect(site_url('interaction/event/Eventhd/eventEdit/').$event_id);
        }
        
        $data['tool_list'] = $tool_list;
        $data['event_tool_action'] = $this->event_tool_action;
        $data['type'] = 'add';
        $this->load->view('interaction/event/event_tool_add', $data);
    
    }
    
    public function eventToolRemove() {
        $et_id = $_POST['id'];
        $result = $this->EHM->removeEventTool($et_id);
        echo 'success';
        return;
    }
    
    public function eventToolEdit($event_id, $et_id) {
        $tool_list = $this->EHM->getAllToolList();
        $et_info = $this->EHM->getEventTool($et_id);
        $data = array();
        if (!empty($_POST)) {
            $this->_editEventTool($et_id, $event_id, $et_info['live_tool_id'], $_POST);
            redirect(site_url('interaction/event/Eventhd/eventEdit/').$event_id);
        }
        
        $data['tool_list'] = $tool_list;
        $data['type'] = 'edit';
        $data['et_info'] = $et_info;
        $data['event_tool_action'] = $this->event_tool_action;
        $data['source_path'] = $this->source_path;
        $this->load->view('interaction/event/event_tool_add', $data);
    }
    
    protected function _addTool($post_info) {
        $title = $post_info['title'];
        $type  = $post_info['type'];
        
        $data = array(
                'title' => $title,
                'type'  => $type,
        );
        
        $tool_id = $this->EHM->addTool($data);
        
        if (!empty($tool_id)) {
            $this->_updateSource('tool', 0, $tool_id, 'image');
        }
        
        return $tool_id;
    }
    
    protected function _addEvent($post_info) {
        $title = $post_info['title'];
        $desc  = isset($post_info['desc']) ? $post_info['desc'] : '';
        $duration  = isset($post_info['duration']) ? intval($post_info['duration']) : 0;
        $total_duration = $this->input->post('total_duration');
        $type  = isset($post_info['type']) ? intval($post_info['type']) : 1;
        $sport_id  = isset($post_info['sport_id']) ? intval($post_info['sport_id']) : 1;
        $video_repeat = isset($post_info['video_repeat']) ? intval($post_info['video_repeat']) : 1;
        
        $data = array(
                'title' => $title,
                'type'  => $type,
                'desc'  => $desc,
                'duration' => $duration,
                'total_duration' => intval($total_duration),
                'sport_id' => $sport_id,
                'video_repeat' => $video_repeat,
        );
        
        $event_id = $this->EHM->addEvent($data);
        
        if (!empty($event_id)) {
            $upload_names = array('video', 'audio');
            foreach ($upload_names as $name) {
                $this->_updateSource('event', $event_id, 0, $name);
            }
        }
        
        return $event_id;
    }

    protected function _addEventTool($event_id, $post_info) {
        $live_tool_id = isset($post_info['live_tool_id']) ? intval($post_info['live_tool_id']) : 0;
        $action = isset($post_info['action']) ? intval($post_info['action']) : 1;
        $duration  = isset($post_info['duration']) ? intval($post_info['duration']) : 0;
        $video_repeat = isset($post_info['video_repeat']) ? intval($post_info['video_repeat']) : 1;
        
        $data = array(
            'live_event_id' => $event_id,
            'live_tool_id'  => $live_tool_id,
            'action' => $action,
            'duration' => $duration,
            'video_repeat' => $video_repeat,
        );
        
        $et_id = $this->EHM->addEventTool($data);
        
        if (!empty($et_id)) {
            $upload_names = array('video', 'audio');
            foreach ($upload_names as $name) {
                $this->_updateSource('et', $event_id, $live_tool_id, $name);
            }
        }
        
        return $event_id;
    }
    
    protected function _editEvent($event_id, $post_info) {
        $title = $post_info['title'];
        $desc  = isset($post_info['desc']) ? $post_info['desc'] : '';
        $duration  = isset($post_info['duration']) ? intval($post_info['duration']) : 0;
        $total_duration = $this->input->post('total_duration');
        $video_repeat = isset($post_info['video_repeat']) ? intval($post_info['video_repeat']) : 1;
        
        $data = array(
                'title' => $title,
                'desc'  => $desc,
                'total_duration' => intval($total_duration),
                'duration' => $duration,
                'video_repeat' => $video_repeat,
        );
        
        $this->EHM->updateEvent($event_id, $data);
        
        if (!empty($event_id)) {
            $upload_names = array('video', 'audio');
            foreach ($upload_names as $name) {
                $this->_updateSource('event', $event_id, 0, $name);
            }
        }
        
        return $event_id;
    }
    
    protected function _editEventTool($et_id, $event_id, $tool_id, $post_info) {
        $action = isset($post_info['action']) ? intval($post_info['action']) : 1;
        $duration  = isset($post_info['duration']) ? intval($post_info['duration']) : 0;
        $video_repeat = isset($post_info['video_repeat']) ? intval($post_info['video_repeat']) : 1;
        
        $data = array(
                'action' => $action,
                'duration' => $duration,
                'video_repeat' => $video_repeat,
        );
        
        $this->EHM->updateEventTool($et_id, $data);
        
        if (!empty($et_id)) {
            $upload_names = array('video', 'audio');
            foreach ($upload_names as $name) {
                $this->_updateSource('et', $event_id, $tool_id, $name);
            }
        }
        
        return $event_id;
    }

    /**
     * 
     * @param string $type          tool|event|et
     * @param intval $event_id
     * @param intval $tool_id
     * @param string $source_name   image|video|audio
     */
    protected function _updateSource($type, $event_id=0, $tool_id=0, $source_name) {
        if ($type == 'et') {
            $et_info = $this->EHM->getEventToolByRel($event_id, $tool_id);
            if (empty($et_info)) {
                return FALSE;
            }
        }
        
        $upload_config = array(
                'upload_path'   => $this->upload_path,
                'allowed_types' => 'gif|jpg|png|mp3|zip',
                'encrypt_name'  => true
        );
        
        $this->load->library('upload', $upload_config);
        
        $this->upload->do_upload($source_name);
        if (!empty($this->upload->display_errors())) {
            return FALSE;
        }
        
        $data = $this->upload->data();
        $md5 = md5(file_get_contents($data['full_path']));
        $target_path = $this->static_path.$this->EHM->getStaticPath($type, $event_id, $tool_id, $source_name);
        $file_name = $md5.$data['file_ext'];
        $file_full_path = $target_path.$file_name;
        if (!is_dir($target_path)) {
            exec('mkdir '.$target_path);
        }
        exec('mv '.$data['full_path'].' '.$file_full_path);
        
        $update_content = array();
        $update_content[$source_name] = $this->EHM->getStaticPath($type, $event_id, $tool_id, $source_name).$file_name;
        
        if ($type == 'tool') {
            $this->EHM->updateTool($tool_id, $update_content);
        } else if ($type == 'event') {
            $this->EHM->updateEvent($event_id, $update_content);
        } else if ($type == 'et') {
            /////////
            $this->EHM->updateEventTool($et_info['id'], $update_content);
            /////////
        }
    }

}