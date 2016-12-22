<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Community extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('community/Community_model','CM');
        $this->limit = 10;
    }
    
    public function index() {
        $this->getList();
    }
    
    // 分类列表
    public function getList($offset=0) {
        $condition = array();
        $list = $this->CM->getList($condition, $this->limit, $offset);
        $total = $this->CM->getTotal('community');
        $this->load->model('Match_model', 'MM');
        $data = array();
        $data['page'] = $this->_pagination($total, $this->limit, '/community/community/getList');
        foreach($list as $key => $val){
            $list[$key]['match_info'] = $this->MM->getMatchInfo($val['match_id'], '<br>');
        }
        $data['list'] = $list;
        $this->load->view('community/community/list', $data);
    }
    
    // 添加分类
    public function add() {
        if (!empty($_POST)) {
            $this->addInfo($_POST);
            redirect(site_url('community/community/getList'));
        }
        
        $this->load->view('community/community/add');
    }
    
    // 分类编辑
    public function edit($id) {
        if (!empty($_POST)) {
            $this->editInfo($id, $_POST);
            redirect(site_url('community/community/getList'));
        }
        $info = $this->CM->getInfo($id);
        
        $data = array();
        $data['info'] = $info;
        $data['info']['match_id'] = ($data['info']['match_id'] == 0) ? "" : $data['info']['match_id'];
        $this->load->view('community/community/edit', $data);
    }
    
    // 分类删除
    public function remove() {
        $id = $_POST['id'];
        
        $this->CM->deleteInfo($id);
        echo 'success';
    }
    
    // 板块列表
    public function columnList($offset=0) {
        $condition = array();
        $list = $this->CM->getColumnList($condition, $this->limit, $offset);
        $total = $this->CM->getTotal('community_column');
        
        $data = array();
        $data['page'] = $this->_pagination($total, $this->limit, '/community/community/getList');
        $data['list'] = $list;
        $this->load->view('community/community/column_list', $data);
    }
    
    public function columnHas($column_id, $type='community', $offset=0) {
        $column_info = $this->CM->getColumn($column_id);
        $list = $this->CM->getColumnHas($column_id, $this->limit, $offset);
        $total = $this->CM->getTotal('community_has', array('community_column_id'=>$column_id));
        
        $data = array();
        $data['page'] = $this->_pagination($total, $this->limit, '/community/community/columnhas/'.$column_id.'/community');
        $data['list'] = $list;
        $data['cinfo'] = $column_info;
        $this->load->view('community/community/column_has', $data);
    }
    
    // 添加板块
    public function columnAdd() {
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        if (!empty($name)) {
            $this->CM->addColumn(array('name'=>$name));
        }
        
        redirect(site_url('community/community/columnlist'));
        return;
    }
    
    public function columnHasAdd($column_id) {
        $community_id = isset($_POST['cid']) ? $_POST['cid'] : '';
        if (!empty($community_id)) {
            $this->CM->addColumnHas($column_id, $community_id);
        }
        
        redirect(site_url('community/community/columnhas/').$column_id);
        return;
    }
    
    // 板块删除
    public function columnRemove() {
        $id = $_POST['id'];
        
        $this->CM->deleteColumn($id);
        echo 'success';
    }
    
    // 板块删除
    public function columnHasRemove() {
        $column_id = $_POST['id'];
        $community_id = $_POST['cid'];
    
        $this->CM->deleteColumnHas($column_id, $community_id);
        echo 'success';
    }
    
    // 板块编辑
    public function columnEdit() {
        $column_id = $_POST['pk'];
        $name  = $_POST['name'];
        $value = $_POST['value'];
        
        $data = array($name => $value);
        $this->CM->updateColumn($column_id, $data);
        
        echo 'success';
        return true;
    }
    
    // 板块排序
    public function columnSort() {
        $column_id = $_POST['pk'];
        $sort = $_POST['value'];
        
        $this->CM->sortColumn($column_id, $sort);
        echo 'success';
        return;
    }
    
    public function columnHasSort() {
        $column_id = $_POST['column_id'];
        $id = $_POST['pk'];
        $sort = $_POST['value'];
    
        $this->CM->sortColumnHas($column_id, $id, $sort);
        echo 'success';
        return;
    }
    
    public function thread($community_id, $offset = 0) {
        $data = array();

        $this->load->model('community/Thread_model', 'TM');
        $total = $this->CM->getTotal('thread_has', array('community_id'=> $community_id));
        $list = $this->CM->getThreadList($community_id, $this->limit, $offset);

        foreach ($list as & $item) {
            $item['user_name'] = $this->TM->getUsersAPI($item['user_id'])[$item['user_id']];
        }
        $data['list'] = $list;
        $data['page'] = $this->_pagination($total, $this->limit, '/community/community/thread/'.$community_id.'/');
        $this->load->view('community/community/thread_list', $data);
    }
    
    protected function addInfo($post_info) {
        $name = $post_info['name'];
        $icon = isset($post_info['icon']) ? $post_info['icon'] : '';
        $match_id = $post_info['match_id'];

        $data = array(
                'name' => $name,
                'icon' => $icon,
                'match_id' => $match_id,
        );
        
        $this->CM->addInfo($data);
        return true;
    }
    
    protected function editInfo($id, $post_info) {
        $name = $post_info['name'];
        $icon = isset($post_info['icon']) ? $post_info['icon'] : '';
        $match_id = $post_info['match_id'];
        $data = array(
            'name' => $name,
            'icon' => $icon,
            'match_id' => $match_id,
        );
        
        $this->CM->updateInfo($id, $data);
        return true;
    }
}