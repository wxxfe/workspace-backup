<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Channel extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('channel_model', 'CM');
        $this->limit = 20;
        $this->_shortcut_target_list = array(
                'schedule'          => '赛程',
                'rankingpoint'      => '积分',
                'rankingscorer'     => '射手榜',
                'rankingassist'     => '助攻榜',
                'rankingteam'       => '球队榜',
                'rankingplayer'     => '球员榜',
        );
    }
    
    public function getList($offset=0) {
        $condition = array();
        $list = $this->CM->getList($condition, $this->limit, $offset);
        $total = $this->CM->getTotal('channel');
        
        $data['page'] = $this->_pagination($total, $this->limit, '/channel/getList');
        $data['list'] = $list;
        $this->load->view('channel/list', $data);
    }
    
    public function add() {
        if (!empty($_POST)) {
            $this->addChannel($_POST);
            redirect(site_url('channel/getList'));
        }
        
        // 获取events赛事列表
        $this->load->model('Event_model', 'EM');
        $event_list = $this->EM->getEvents();
        $data = array();
        $data['type'] = 'add';
        $data['event_list'] = $event_list;
        $this->load->view('channel/add', $data);
    }
    
    public function edit($id) {
        $channel_info = $this->CM->getById($id);
        
        if (!empty($_POST)) {
            $this->editChannel($id, $_POST);
            redirect(site_url('channel/getList'));
        }
        
        // 获取events赛事列表
        $this->load->model('Event_model', 'EM');
        $event_list = $this->EM->getEvents();
        $data = array();
        $data['type'] = 'edit';
        $data['event_list'] = $event_list;
        $data['info'] = $channel_info;
        $this->load->view('channel/add', $data);
    }
    
    public function updateSort() {
        $data = $_POST;
        $channel_id = $data['pk'];
        $sort = intval($data['value']);
        $result = $this->CM->db('sports')->setTbSort('channel', $channel_id, array('priority' => $sort), array());
        echo $result ? 'success' : 'fail';
    }
    
    public function remove() {
        $channel_id = $_POST['id'];
        
        $this->CM->delete($channel_id);
        $result = array('');
        echo 'success';
    }
    
    // 修改启用/禁用状态的ajax调用
    public function update() {
        $channel_id = $_POST['pk'];
        $info = array(
                $_POST['name'] => $_POST['value']
        );
        $this->CM->edit($channel_id, $info);
    
        return true;
    }
    
    protected function addChannel($post_info) {
        $name = $post_info['name'];
        $ref_id = isset($post_info['ref_id']) ? intval($post_info['ref_id']) : 0;
        $visibility = isset($post_info['visibility']) ? 'default' : 'hidden';
        $visible = isset($post_info['visible']) ? 1 : 0;
        $ishot = isset($post_info['ishot']) ? 1 : 0;
        $max_prioryty = $this->CM->db('sports')->select('priority')->order_by('priority','DESC')->limit(1)->get('channel')->row_array();
        $priority = intval($max_prioryty['priority'])+1;
        $channle_data = array(
                'name' => $name,
                'ref_id' => $ref_id,
                'visibility' => $visibility,
                'visible' => $visible,
                'ishot' => $ishot,
                'priority' => $priority,
        );
        $this->CM->add($channle_data);

       // $this->CM->addPriority($priority);
        // $this->LM->uadd('pending_video_edit', json_encode($this->userInfo['current_menu']), $video_data);
        
        return true;
    }
    
    protected function editChannel($id, $post_info) {
        $name = $post_info['name'];
        $ref_id = isset($post_info['ref_id']) ? intval($post_info['ref_id']) : 0;
        $visibility = isset($post_info['visibility']) ? 'default' : 'hidden';
        $visible = isset($post_info['visible']) ? 1 : 0;
        $ishot = isset($post_info['ishot']) ? 1 : 0;
        
        $channle_data = array(
                'name' => $name,
                'ref_id' => $ref_id,
                'visibility' => $visibility,
                'visible' => $visible,
                'ishot' => $ishot,
        );
        $this->CM->edit($id, $channle_data);
        
        return true;
    }

    //////////////////////////////////////////////////////////////////
    // 单独一个channel的各个menu配置
    //////////////////////////////////////////////////////////////////
    
    // 快捷入口
    public function shortcut($channel_id) {
        $channel_info = $this->CM->getById($channel_id);
        $list = $this->CM->getShortcut($channel_info['ref_id']);
        
        $data['list'] = $list;
        $data['channel'] = $channel_info;
        $data['shortcut_target'] = $this->_shortcut_target_list;
        $this->load->view('channel/shortcut_list', $data);
    }
    
    // 添加快捷入口
    public function shortcutAdd($channel_id) {
        $channel_info = $this->CM->getById($channel_id);
        
        if (!empty($_POST)) {
            $this->addShortcut($channel_info['ref_id'], $_POST);

            // 写入schedule_entry表
            if($_POST['target'] == 'schedule' && isset($_POST['entry_title']) && !empty($_POST['entry_title'])) {
                $this->load->model('schedule_entry_model', 'SEM');
                $data_entry = array('event_id'=>$channel_info['ref_id'], 'title'=>$_POST['entry_title']);
                $this->SEM->add($data_entry);
            }
            redirect(site_url("channel/shortcut/{$channel_id}"));
        }
        
        $data = array();
        $data['target_list'] = $this->_shortcut_target_list;
        $this->load->view('channel/shortcut_add', $data);
    }
    
    public function updateShortcutSort() {
        $data = $_POST;
        $event_id = $data['event_id'];
        $shortcut_id = $data['pk'];
        $sort = intval($data['value']);
        $result = $this->CM->db('sports')->setTbSort('shortcut', $shortcut_id, array('priority' => $sort), array('event_id'=>$event_id));
        echo $result ? 'success' : 'fail';
    }
    
    public function shortcutEdit($channel_id, $shortcut_id) {
        $channel_info = $this->CM->getById($channel_id);
        $shortcut_info = $this->CM->getShortcutInfo($shortcut_id);

        $this->load->model('schedule_entry_model', 'SEM');
        $entry_ifno = $this->SEM->getInfo(array('event_id'=>intval($shortcut_info['event_id'])));
        if ($entry_ifno) {
            $shortcut_info['entry_title'] = $entry_ifno['title'];
        }

        if (!empty($_POST)) {
            $this->editShortcut($shortcut_id, $_POST);
            // 写入schedule_entry表
            if($_POST['target'] == 'schedule' && isset($_POST['entry_title']) && !empty($_POST['entry_title'])) {
                $data_entry = array('event_id'=>$channel_info['ref_id'], 'title'=>$_POST['entry_title']);
                $this->SEM->add($data_entry);
            }
            redirect(site_url("channel/shortcut/{$channel_id}"));
        }
        
        $data = array();
        $data['target_list'] = $this->_shortcut_target_list;
        $data['shortcut_id'] = $shortcut_id;
        $data['shortcut'] = $shortcut_info;
        $this->load->view('channel/shortcut_add', $data);
    }
    
    public function shortcutRemove() {
        $shortcut_id = $_POST['id'];
        $shortcut_info = $this->CM->getShortcutInfo($shortcut_id);
        
        $result = $this->CM->deleteShortcut($shortcut_id);

        //删除achedule_entery
        if ($result && isset($shortcut_info['event_id']) && $shortcut_info['event_id']) {
            $event_id = $shortcut_info['event_id'];
            $this->load->model('schedule_entry_model', 'SEM');
            $entry_info = $this->SEM->getInfo(array('event_id'=>$event_id));
            if ($entry_info) {
                $this->SEM->remove('schedule_entry', $entry_info['id']);
            }
        }
        echo 'success';
    }
    
    public function shortcutSort() {
        $table = 'shortcut';
        $id = $_GET['id'];
        $sort_v = $_GET['sort_v'];
        $sort = array('priority' => $sort_v);
        $sort_condition = array('event_id'=>7);
        
        $this->CM->setTbSort($table, $id, $sort, $sort_condition);
    }
    
    // 热门直播
    public function hotLive($channel_id=0) {
        $this->load->model('Match_model', 'MM');
        
        // 获取当前日期
        $now_date = date('Y-m-d');
        $now_week = date('W');
        
        $use_event_id = isset($_GET['use_event_id']) ? $_GET['use_event_id'] : 0;
        $use_date = isset($_GET['use_date']) ? $_GET['use_date'] : '';
        
        if (!empty($channel_id)) {
            $channel_info = $this->CM->getById($channel_id);
            $event_id = $channel_info['ref_id'];
        } else {
            $event_id = 0;
        }
        
        if (empty($use_event_id)) {
            $use_event_id = 'all';
        }
        if (empty($use_date)) {
            $use_date = $now_date;
        }
        
        // 组建日期插件条的数据
        $date_slide = $this->MM->getMatchCountByWeek($use_date, $use_event_id);
        
        // 获取当前频道的推荐的热门直播
        $selectd_match = $this->MM->getSelectedMatch($event_id);
        
        // 获取event列表
        $this->load->model('Event_model', 'EM');
        $event_list = $this->EM->getEvents();
        
        $data = array();
        $data['selected_match'] = $selectd_match;
        $data['event_list'] = $event_list;
        $data['channel_id'] = $channel_id;
        $data['date_slide'] = $date_slide;
        $data['use_event_id'] = $use_event_id;
        $data['use_date']   = $use_date;
        $this->load->view('channel/hot_live', $data);
    }
    
    public function hotLiveRecommend($channel_id) {
        $this->load->model('Match_model', 'MM');
        
        if (!empty($channel_id)) {
            $channel_info = $this->CM->getById($channel_id);
            $event_id = $channel_info['ref_id'];
        } else {
            $event_id = null;
        }
        $match_id = $_GET['match_id'];
        $use_date = $_GET['use_date'];
        $use_event_id = $_GET['use_event_id'];
        
        // 检查是否已经在推荐赛事表里
        $selected_matchlist = $this->MM->getSelectedMatchSimple($event_id);
        $max_priority = 0;
        $exist_match  = false;
        foreach ($selected_matchlist as $selected_match) {
            if ($max_priority < $selected_match['priority']) {
                $max_priority = $selected_match['priority'];
            }
            if ($selected_match['match_id'] == $match_id) {
                $exist_match = true;
            }
        }
        
        if (!$exist_match) {
            $this->MM->addSelectedMatch($event_id, $match_id, $max_priority+1);
        }
        
        redirect(site_url('channel/hotLive').'/'.$channel_id.'?use_date='.$use_date.'&use_event_id='.$use_event_id);
        return ;
    }
    
    public function hotLiveRecommendCancel($channel_id) {
        $this->load->model('Match_model', 'MM');
        
        $selected_match_id = $_POST['id'];
        $this->MM->removeSelectedMatch($selected_match_id);
        
        echo 'success';
        return ;
    }
    
    // 资讯列表
    public function tagsInfoList($channel_id) {
        // 根据频道拼出tag信息
        
//         $channel_id = $this->
    }
    
    protected function addShortcut($event_id, $post_info) {
        $name = $post_info['name'];
        $target = isset($post_info['target']) ? $post_info['target'] : 'schedule';
        
        $shortcut_data = array(
                'name'      => $name,
                'event_id'  => $event_id,
                'target'    => $target,
        );
        $this->CM->addShortcut($shortcut_data);
        
        return true;
    }
    
    protected function editShortcut($shortcut_id, $post_info) {
        $name = $post_info['name'];
        $target = isset($post_info['target']) ? $post_info['target'] : 'schedule';
    
        $shortcut_data = array(
                'name'      => $name,
                'target'    => $target,
        );
        $this->CM->editShortcut($shortcut_id, $shortcut_data);
    
        return true;
    }
}