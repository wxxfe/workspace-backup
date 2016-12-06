<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Columncm extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('community/Columncm_model','CM');
        $this->limit = 10;
    }
    
    public function index() {
        $this->columnList();
    }
    
    public function columnList($offset=0) {
        $condition = array();
        $list = $this->CM->getList($condition, $this->limit, $offset);
        $total = $this->CM->getTotal('column');
        
        $data = array();
        $data['page'] = $this->_pagination($total, $this->limit, '/community/column/columnList');
        $data['list'] = $list;
        $this->load->view('community/column/list', $data);
    }
    
    public function columnAdd() {
        if (!empty($_POST)) {
            $this->_addColumn($_POST);
            redirect(site_url('community/columncm/columnList'));
        }
        
        $this->load->model('community/Community_model','Community_model');
        $community_list = $this->Community_model->getAll();
        
        $data = array();
        $data['type'] = 'add';
        $data['community_list'] = $community_list;
        $this->load->view('community/column/add', $data);
    }
    
    public function columnEdit($column_id) {
        if (!empty($_POST)) {
            $this->_editColumn($column_id, $_POST);
            redirect(site_url('community/columncm/columnList'));
        }
        
        $column_info = $this->CM->getInfo($column_id);
        
        $this->load->model('community/Community_model','Community_model');
        $community_list = $this->Community_model->getAll();
        
        $data = array();
        $data['type'] = 'edit';
        $data['community_list'] = $community_list;
        $data['info'] = $column_info;
        $this->load->view('community/column/add', $data);
    }
    
    public function columnRemove() {
        $id = $_POST['id'];
        
        $this->CM->deleteInfo($id);
        echo 'success';
    }
    
    public function columnSort() {
        $column_id = $_POST['pk'];
        $sort = $_POST['value'];
        
        $this->CM->sortInfo($column_id, $sort);
        echo 'success';
        return;
    }
    
    public function columnUpdate() {
        $column_id = $_POST['pk'];
        $name  = $_POST['name'];
        $value = $_POST['value'];
        
        $data = array($name => $value);
        $this->CM->updateInfo($column_id, $data);
        
        echo 'success';
        return true;
    }
    
    public function columnHasList($column_id, $type = 'column', $offset=0) {
        $column_info = $this->CM->getInfo($column_id);
        $list = $this->CM->getHas($column_id, $this->limit, $offset);
        $total = $this->CM->getTotal('column_has', array('column_id'=>$column_id));
        
        $data = array();
        $data['page'] = $this->_pagination($total, $this->limit, '/community/columncm/columnHasList/'.$column_id.'/column');
        $data['list'] = $list;
        $data['cinfo'] = $column_info;
        $this->load->view('community/column/column_has', $data);
    }
    
    public function columnHasAdd($column_id) {
        $thread_id = isset($_POST['thread_id']) ? $_POST['thread_id'] : '';
        if (!empty($thread_id)) {
            $this->CM->addHas($column_id, $thread_id);
        }
        
        redirect(site_url('/community/columncm/columnHasList/').$column_id);
        return;
    }
    
    public function columnHasRemove() {
        $column_id = $_POST['id'];
        $thread_id = $_POST['cid'];
        
        $this->CM->deleteHas($column_id, $thread_id);
        echo 'success';
    }
    
    public function columnHasSort() {
        $column_id = $_POST['column_id'];
        $id = $_POST['pk'];
        $sort = $_POST['value'];
        
        $this->CM->sortHas($column_id, $id, $sort);
        echo 'success';
        return;
    }
    
    protected function _addColumn($post_info) {
        $title = $post_info['title'];
        $community_id = isset($post_info['community_id']) ? intval($post_info['community_id']) : 0;
        
        $data = array(
            'title' => $title,
            'community_id' => $community_id
        );
        
        $id = $this->CM->addInfo($data);
        return $id;
    }
    
    protected function _editColumn($id, $post_info) {
        $title = $post_info['title'];
        $community_id = isset($post_info['community_id']) ? intval($post_info['community_id']) : 0;
        
        $data = array(
                'title' => $title,
                'community_id' => $community_id
        );
        
        $this->CM->updateInfo($id, $data);
        return TRUE;
    }
}