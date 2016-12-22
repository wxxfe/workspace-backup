<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Collection extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('collection_model', 'CM');
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
            $result = $this->CM->searchList($keyword, $this->limit, $offset);
            $list = $result['result'];
            $total = $result['total'];
        } else {
            if (is_numeric($keyword)) {
                $condition['id'] = $keyword;
            }
            $list = $this->CM->getList($condition, $this->limit, $offset);
            $total = $this->CM->getTotal('collection');
        }
        
        $this->load->model('tag_model', 'TagModel');
        // 获取标签
        foreach ($list as & $collection) {
            $collection_tag_ids = $this->CM->getCollectionTags($collection['id']);
            if (!empty($collection_tag_ids)) {
                $collection_tag = $this->TagModel->db('sports')->getTagsByFakeId(join(',', $collection_tag_ids));
                $tag_str = '';
                foreach ($collection_tag as $item) {
                    $tag_str .= $item['name'].',';
                }
            }
            $collection['tag_str'] = isset($tag_str) ? trim($tag_str, ',') : '';

            unset($tag_str);
        }
        
        $data = array();
        $data['page'] = $this->_pagination($total, $this->limit, '/collection/getlist');
        $data['list'] = $list;
        $this->load->view('collection/list', $data);
    }
    
    public function addStep1() {
        
        $data = array();
        if (!empty($_POST)) {
            $collection_id = $this->addCollection($_POST);
            redirect(site_url('collection/edit/').$collection_id);
        }
        $this->load->view('collection/add_step1', $data);
    }
    
    public function addVideo($collection_id, $video_id) {
        $this->CM->addVideo($collection_id, $video_id);
        redirect(site_url('collection/edit/').$collection_id);
    }

    public function addManyVideo($collection_id, $video_id) {
	$video_id_array = explode("_",$video_id);
	foreach($video_id_array as $key => $val)
	{
		$this->CM->addVideo(intval($collection_id), intval($val));
	}
        redirect(site_url('collection/edit/').$collection_id);
    }

    
    public function removeVideo($collection_id, $video_id) {
        $result = $this->CM->removeVideo($collection_id, $video_id);
        echo 'success';
        return;
    }
    
    public function videoSearch($collection_id, $type='video', $offset=0) {
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
        $data['page'] = $this->_pagination($total, $this->limit, "/collection/videoSearch/{$collection_id}/video");
        $data['list'] = $list;
        $data['keyword'] = $keyword;
        $data['type'] = $type;
        $data['collection_id'] = $collection_id;
        $this->load->view('collection/search_video', $data);
    }
    
    public function edit($id) {
        $collection_info = $this->CM->getInfo($id);
        
        if (!empty($_POST)) {
            $this->editCollection($id, $_POST);
            
            $redirect = $this->input->get('redirect');
            if (!empty($redirect)) {
                redirect($redirect);
            } else {
                redirect(site_url('collection/getlist'));
            }
        }
        
        $collection_tags = $this->CM->getCollectionTags($id);
        $collection_tags = join(',', $collection_tags);
        

        // 合集视频列表
        $collection_videos = $this->CM->getVideos($id);
        
        $data = array();
        $data['info'] = $collection_info;
        $data['selected_tags'] = $collection_tags;
        $data['videos'] = $collection_videos;
        $this->load->view('collection/edit', $data);
    }
    
    public function remove() {

        $id = $_POST['id'];
        if(strpos($id,',').'' != ''){
            $ids = explode(',',$id);
            try{
                $this->CM->db('sports')->remove_batch('collection', $ids);
                echo 'success';
            }catch(Exception $e){
                echo 'fail';
            }
        }else{
            try{
                $this->CM->db('sports')->remove('collection', $id);
                echo 'success';
            }catch(Exception $e){
                echo 'fail';
            }
        }
    }
    
    public function update() {
        $collection_id = $_POST['pk'];
        $info = array(
                $_POST['name'] => $_POST['value']
        );
        $this->CM->updateInfo($collection_id, $info);
        
        //9月30日，视频合集下线需要同时删除视频合集相关标签，关联关系。删除比赛关联的视频关系
        if ($_POST['name'] == 'visible' && $_POST['value'] == 0) {
            $this->_deleteRelated('collection', $collection_id);
        }
        
        return true;
    }
    
    public function updateSort() {
        $data = $_POST;
        $collection_id = $data['collection_id'];
        $tid = $data['pk'];
        $sort = intval($data['value']);
        $result = $this->CM->db('sports')->setTbSort('collection_content', $tid, array('priority' => $sort), array('collection_id'=>$collection_id));
        echo $result ? 'success' : 'fail';
    }
    
    protected function addCollection($post_info) {
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
        
        $collection_id = $this->CM->addInfo($data);
        
        $tags_arr = explode(',', $tags);
        if (!empty($tags_arr)) {
            $this->CM->setCollectionTag($collection_id, $tags_arr);
        }
        
        return $collection_id;
    }
    
    protected function editCollection($id, $post_info) {
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
        
        $this->CM->updateInfo($id, $data);
        
        $tags_arr = explode(',', $tags);
        if (!empty($tags_arr)) {
            $this->CM->setCollectionTag($id, $tags_arr);
        }
        // 9月30日，视频合集下线需要同时删除视频合集相关标签，关联关系。删除比赛关联的视频关系
        if ($visible == 0) {
            $this->_deleteRelated('collection', $id);
        }
        
        return true;
    }
}
