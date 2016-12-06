<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Column extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('column_model', 'CM');
        $this->limit = 10;
    }
    
    public function index() {
        $this->getList();
    }
    
    public function getList($offset=0) {
        $list = $this->CM->getList($this->limit, $offset);
        $total = $this->CM->getTotal('column');
        
        $data = array();
        $data['page'] = $this->_pagination($total, $this->limit, '/column/getlist');
        $data['list'] = $list;
        $this->load->view('column/list', $data);
    }
    
    public function add() {
        $data = array();
        
        if (!empty($_POST)) {
            $auther_id = $this->_addColumn();
        
            $redirect = $this->input->get('redirect');
            if (!empty($redirect)) {
                redirect($redirect);
            } else {
                redirect(site_url('column/getlist'));
            }
        }
        $this->load->view('column/add', $data);
    }
    
    public function edit($id) {
        $column_info = $this->CM->getInfo($id);
        
        if (!empty($_POST)) {
            $this->_editColumn($id);
        
            $redirect = $this->input->get('redirect');
            if (!empty($redirect)) {
                redirect($redirect);
            } else {
                redirect(site_url('column/getlist'));
            }
        }
        
        // 专栏新闻列表
        $column_news = $this->CM->getRelNews($id);
        
        $data = array();
        $data['cid']  = $id;
        $data['info'] = $column_info;
        $data['news'] = $column_news;
        $this->load->view('column/edit', $data);
    }
    
    public function remove() {
    
        $id = $_POST['id'];
        if(strpos($id,',').'' != ''){
            $ids = explode(',',$id);
            try{
                $this->CM->db('sports')->remove_batch('column', $ids);
                echo 'success';
            }catch(Exception $e){
                echo 'fail';
            }
        }else{
            try{
                $this->CM->db('sports')->remove('column', $id);
                echo 'success';
            }catch(Exception $e){
                echo 'fail';
            }
        }
    }
    
    public function addNews($column_id, $news_id) {
        $id = $this->CM->addRelInfo($column_id, $news_id);
        $result = $this->CM->db('sports')->setTbSort('column_content', $id, array('priority' => 1), array('column_id'=>$column_id));
        redirect(site_url('column/edit/').$column_id);
    }
    
    public function removeNews($column_id, $news_id) {
        $result = $this->CM->removeRelInfo($column_id, $news_id);
        echo 'success';
        return;
    }
    
    public function newsSearch($column_id, $type='news', $offset=0) {
        // 专栏新闻列表
        $column_news = $this->CM->getRelNews($column_id);
        $rel_info = array();
        foreach ($column_news as $item) {
            array_push($rel_info, $item['id']);
        }
        
        // add|edit
        $type = isset($_GET['type']) ? $_GET['type'] : 'edit';
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $condition = array();
    
        $this->load->model('news_model', 'NM');
        if (!empty($keyword) && !is_numeric($keyword)) {
            $this->load->model('search_model', 'SM');
            $searchResult = $this->SM->itemsQuery('news', $keyword, $offset, $this->limit);
            $total = $searchResult['total'];
            $list = $searchResult['result'];
        } else if (is_numeric($keyword)) {
            $news = $this->NM->db('sports')->get_where('news', array('id' => intval($keyword)))->result_array();
            $total = empty($news) ? 0 : 1;
            $list = $news;
        } else {
            $news = $this->NM->db('sports')->get('news', $this->limit, $offset)->result_array();
            $total = $this->NM->db('sports')->getTotal('news');
            $list = $news;
        }
    
        $data = array();
        $data['page'] = $this->_pagination($total, $this->limit, "/column/newsSearch/{$column_id}/news");
        $data['list'] = $list;
        $data['keyword'] = $keyword;
        $data['type'] = $type;
        $data['cid'] = $column_id;
        $data['rel_info'] = $rel_info;
        $this->load->view('column/search_news', $data);
    }
    
    public function update() {
        $data = $_POST;
        $column_id = $data['pk'];
        $key = $data['name'];
        $value = $data['value'];
        $result = $this->CM->updateInfo($column_id, array($key => $value));
        echo $result ? 'success' : 'fail';
    }
    
    public function updateSort() {
        $data = $_POST;
        $column_id = $data['column_id'];
        $tid = $data['pk'];
        $sort = intval($data['value']);
        $result = $this->CM->db('sports')->setTbSort('column_content', $tid, array('priority' => $sort), array('column_id'=>$column_id));
        echo $result ? 'success' : 'fail';
    }
    
    private function _addColumn() {
        $title = $this->input->post('title');
        $image = $this->input->post('cover');
        
        if (empty($title) || empty($image)) {
            return false;
        }
        
        $data = array(
                'title' => $title,
                'image' => $image
        );
        
        
        $column_id = $this->CM->addInfo($data);
        
        return $column_id;
    }
    
    private function _editColumn($id) {
        $title = $this->input->post('title');
        $image = $this->input->post('cover');
        
        $data = array(
                'title' => $title,
                'image' => $image
        );
        
        $this->CM->updateInfo($id, $data);
        
        return true;
    }
}