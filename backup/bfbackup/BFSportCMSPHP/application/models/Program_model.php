<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Program_model extends MY_Model {
    
    public function getInfo($id) {
        $query = $this->db('sports')->get_where('program', array('id'=>$id), 1);
        $result = array();
        $item = $query->row_array();
        $item['image_url'] = getImageUrl($item['image']);
        $item['large_image_url'] = getImageUrl($item['large_image']);
        $result = $item;
        return $result;
    }
    
    public function getList($where, $limit, $offset) {
        $this->db('sports')->select('*')
            ->from('program');
        if (!empty($where)) {
            $this->db('sports')->where($where);
        }
        $query = $this->db('sports')->order_by('id', 'desc')
            ->limit($limit)
            ->offset($offset)
            ->get();
        $result = array();
        foreach ($query->result_array() as $item) {
            $item['videos_count'] = $this->getTotal('program_content', array('program_id' => $item['id']));
            $item['image_url'] = getImageUrl($item['image']);
            $item['large_image_url'] = getImageUrl($item['large_image']);
            $result[] = $item;
        }
        return $result;
    }
    
    public function getProgramTags($id) {
        $query = $this->dbSports->get_where('program_tag', array('program_id'=>$id));
        $result = array();
        foreach ($query->result_array() as $item) {
            $result[] = $item['tag_id'];
        }
        return $result;
    }
    
    public function addInfo($data) {
        return $this->db('sports')->insert('program', $data);
    }
    
    public function updateInfo($id, $data) {
        return $this->db('sports')->update('program', $data, array('id'=>$id));
    }
    
    public function updateContentInfo($id, $video_id, $data) {
        return $this->db('sports')->update('program_content', $data, array('program_id'=>$id, 'video_id'=>$video_id));
    }
    
    public function deleteInfo($id) {
        return $this->db('sports')->remove('program', $id);
    }
    
    public function setProgramTag($id, $tag_ids) {
        $orig_tag_ids = $this->db('sports')->get_where('program_tag', array('program_id'=>$id));
        $delete_batch_ids = array();
        foreach ($orig_tag_ids->result_array() as $item) {
            if (!in_array($item['tag_id'], $tag_ids)) {
                array_push($delete_batch_ids, $item['id']);
            } else {
                $key = array_search($item['tag_id'], $tag_ids);
                unset($tag_ids[$key]);
            }
        }
    
        if (!empty($delete_batch_ids)) {
            $this->remove_batch('program_tag', $delete_batch_ids);
        }
    
        if (!empty($tag_ids)) {
            $insert_batch_data = array();
            foreach ($tag_ids as $tag_id) {
                $insert_batch_data[] = array('program_id' => $id, 'tag_id'=> $tag_id);
            }
            $this->insert_batch('program_tag', $insert_batch_data);
        }
    }
    
    
    public function getVideos($id) {
        $query = $this->db('sports')->select('video.*,program_content.priority as priority,
                program_content.issue_no as issue_no,program_content.id as tid')
            ->from('program_content')
            ->join('video', 'program_content.video_id=video.id', 'left')
            ->where('program_content.program_id', $id)
            ->order_by('priority', 'DESC')
            ->get();
        $result = array();
        foreach ($query->result_array() as $item) {
            $item['image_url'] = getImageUrl($item['image']);
            $item['duration'] = gmstrftime('%H:%M:%S', intval($item['duration']));
            $result[] = $item;
        }
        
        return $result;
    }
    
    public function addVideo($program_id, $video_id) {
        // 验证是否已经存在video
        $query = $this->db('sports')->select('*')
            ->from('program_content')
            ->where('program_id', $program_id)
            ->where('video_id', $video_id)
            ->get();
        $video_info = $query->row_array();
        if (!empty($video_info)) {
            return false;
        }
        $data = array(
            'program_id' => $program_id,
            'video_id'      => $video_id
        );
        return $this->db('sports')->insert('program_content', $data);
    }
    
    public function removeVideo($program_id, $video_id) {
        $condition = array(
            'program_id' => $program_id,
            'video_id'      => $video_id
        );
        return $this->db('sports')->delete('program_content', $condition);
    }
    
    public function searchList($key_word, $limit, $offset) {
        $this->load->model('search_model', 'SM');
        $result = $this->SM->itemsQuery('program', $key_word, $offset, $limit);
    
        $total = $result['total'];
        $list  = $result['result'];
    
        $this->db('sports');
        $result = array();
        foreach ($list as $item) {
            $item['videos_count'] = $this->getTotal('collection_content', array('collection_id' => $item['id']));
            $item['image_url'] = getImageUrl($item['image']);
            $item['large_image_url'] = getImageUrl($item['large_image']);
            $result[] = $item;
        }
        return array('total'=>$total, 'result' => $result);
    }
}