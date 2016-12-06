<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Program extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('program_model', 'PM');
        $this->limit = 10;
    }
    
    public function index() {
        return $this->getList();
    }
    
    public function getList($offset=0) {
        $condition = array(
        );
        
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        if (!empty($keyword) && !is_numeric($keyword)) {
            $result = $this->PM->searchList($keyword, $this->limit, $offset);
            $list = $result['result'];
            $total = $result['total'];
        } else {
            if (is_numeric($keyword)) {
                $condition['id'] = $keyword;
            }
            $list = $this->PM->getList($condition, $this->limit, $offset);
            $total = $this->PM->getTotal('program');
        }
        
        $this->load->model('tag_model', 'TagModel');
        // 获取标签
        foreach ($list as & $program) {
            $program_tag_ids = $this->PM->getProgramTags($program['id']);
            if (!empty($program_tag_ids)) {
                $program_tag = $this->TagModel->db('sports')->getTagsByFakeId(join(',', $program_tag_ids));
                $tag_str = '';
                foreach ($program_tag as $item) {
                    $tag_str .= $item['name'].',';
                }
            }
            $program['tag_str'] = isset($tag_str) ? trim($tag_str, ',') : '';

            unset($tag_str);
        }
        
        $data = array();
        $data['page'] = $this->_pagination($total, $this->limit, '/program/getlist');
        $data['list'] = $list;
        $this->load->view('program/list', $data);
    }
    
    public function addStep1() {
        
        $data = array();
        if (!empty($_POST)) {
            $program_id = $this->addProgram($_POST);
            redirect(site_url('program/edit/').$program_id);
        }
        $this->load->view('program/add_step1', $data);
    }
    
    public function addVideo($program_id, $video_id) {
        $this->PM->addVideo($program_id, $video_id);
        redirect(site_url('program/edit/').$program_id);
    }
    
    public function removeVideo($program_id, $video_id) {
        $result = $this->PM->removeVideo($program_id, $video_id);
        echo 'success';
        return;
    }
    
    public function videoSearch($program_id, $type='video', $offset=0) {
        // add|edit
        $type = isset($_GET['type']) ? $_GET['type'] : 'edit';
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $condition = array();
        
        $this->load->model('video_model', 'VM');
        if (!empty($keyword) && !is_numeric($keyword)) {
            $result = $this->VM->searchList($keyword, $this->limit, $offset);
            $list = $result['result'];
            $total = $result['total'];
        } else {
            if (is_numeric($keyword)) {
                $condition['id'] = $keyword;
            }
            $list = $this->VM->getList($condition, $this->limit, $offset);
            $total = $this->VM->getTotal('video', $condition);
        }
        
        $this->load->model('tag_model', 'TagModel');
        
        // 获取视频的标签
        foreach ($list as & $video) {
            $video_tag_ids = $this->VM->getVideoTags($video['id']);
            if (!empty($video_tag_ids)) {
                $video_tag = $this->TagModel->db('sports')->getTagsByFakeId(join(',', $video_tag_ids));
                $tag_str = '';
                foreach ($video_tag as $item) {
                    $tag_str .= $item['name'].',';
                }
            }
            $video['tag_str'] = isset($tag_str) ? trim($tag_str, ',') : '';
            unset($tag_str);
        }

        $data = array();
        $data['page'] = $this->_pagination($total, $this->limit, "/program/videoSearch/{$program_id}/video");
        $data['list'] = $list;
        $data['keyword'] = $keyword;
        $data['type'] = $type;
        $data['program_id'] = $program_id;
        $this->load->view('program/search_video', $data);
    }
    
    public function edit($id) {
        $program_info = $this->PM->getInfo($id);
        
        if (!empty($_POST)) {
            $this->editProgram($id, $_POST);
            $redirect = $this->input->get('redirect');
            if (!empty($redirect)) {
                redirect($redirect);
            } else {
                redirect(site_url('program/getlist'));
            }
        }
        
        $program_tags = $this->PM->getProgramTags($id);
        $program_tags = join(',', $program_tags);
        

        // 合集视频列表
        $program_videos = $this->PM->getVideos($id);
        
        $data = array();
        $data['info'] = $program_info;
        $data['selected_tags'] = $program_tags;
        $data['videos'] = $program_videos;
        $this->load->view('program/edit', $data);
    }
    
    public function remove() {
        $id = $_POST['id'];
        if(strpos($id,',').'' != ''){
            $ids = explode(',',$id);
            try{
                $this->PM->db('sports')->remove_batch('program',$ids);
                echo 'success';
            }catch(Exception $e){
                echo 'fail';
            }
        }else{
            try{
                $this->PM->db('sports')->remove('program',$id);
                echo 'success';
            }catch(Exception $e){
                echo 'fail';
            }
        }
    }
    
    public function update() {
        $program_id = $_POST['pk'];
        $info = array(
                $_POST['name'] => $_POST['value']
        );
        $this->PM->updateInfo($program_id, $info);
        
        //9月30日，节目下线需要同时删除节目相关标签，关联关系。删除比赛关联的视频关系
        if ($_POST['name'] == 'visible' && $_POST['value'] == 0) {
            $this->_deleteRelated('program', $program_id);
        }
        
        return true;
    }
    
    public function updateContent() {
        $program_id = $_POST['program_id'];
        
        $video_id = $_POST['pk'];
        $info = array(
                $_POST['name'] => $_POST['value']
        );
        $this->PM->updateContentInfo($program_id, $video_id, $info);
        echo 'success';
        
        return true;
    }
    
    public function updateSort() {
        $data = $_POST;
        $program_id = $data['program_id'];
        $tid = $data['pk'];
        $sort = intval($data['value']);
        $result = $this->PM->db('sports')->setTbSort('program_content', $tid, array('priority' => $sort), array('program_id'=>$program_id));
        echo $result ? 'success' : 'fail';
    }
    
    protected function addProgram($post_info) {
        $title = $post_info['title'];
        $image = isset($post_info['cover_image']) ? $post_info['cover_image'] : '';
        $large_image = isset($post_info['large_image']) ? $post_info['large_image'] : '';
        $tags = isset($post_info['tags']) ? $post_info['tags'] : '';
        $brief = isset($post_info['brief']) ? $post_info['brief'] : '';
        $visible = isset($post_info['visible']) ? 1 : 0;
        
        $data = array(
            'title' => $title,
            'brief' => $brief,
            'image' => $image,
            'large_image' => $large_image,
            'visible' => $visible
        );
        
        $program_id = $this->PM->addInfo($data);
        
        $tags_arr = explode(',', $tags);
        if (!empty($tags_arr)) {
            $this->PM->setProgramTag($program_id, $tags_arr);
        }
        
        return $program_id;
    }
    
    protected function editProgram($id, $post_info) {
        $title = $post_info['title'];
        $image = isset($post_info['cover_image']) ? $post_info['cover_image'] : '';
        $large_image = isset($post_info['large_image']) ? $post_info['large_image'] : '';
        $tags = isset($post_info['tags']) ? $post_info['tags'] : '';
        $brief = isset($post_info['brief']) ? $post_info['brief'] : '';
        $visible = isset($post_info['visible']) ? 1 : 0;
        
        $data = array(
            'title' => $title,
            'brief' => $brief,
            'image' => $image,
            'large_image' => $large_image,
            'visible' => $visible,
        );
        
        $this->PM->updateInfo($id, $data);
        
        $tags_arr = explode(',', $tags);
        if (!empty($tags_arr)) {
            $this->PM->setProgramTag($id, $tags_arr);
        }
        // 9月30日，节目下线需要同时删除节目相关标签，关联关系。删除比赛关联的视频关系
        if ($visible == 0) {
            $this->_deleteRelated('program', $id);
        }
        
        return true;
    }
}