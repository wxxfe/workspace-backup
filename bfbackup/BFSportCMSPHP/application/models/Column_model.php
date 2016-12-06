<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Column_model extends MY_Model {
    
    public function getInfo($id) {
        $query = $this->db('sports')->select('*')
            ->from('column')
            ->where('id', $id)
            ->get();
        
        return $query->row_array();
    }
    
    public function getList($limit, $offset) {
        $query = $this->db('sports')->select('*')
            ->from('column')
            ->order_by('id', 'desc')
            ->limit($limit)
            ->offset($offset)
            ->get();
        
        $result = array();
        foreach ($query->result_array() as $item) {
            $item['news_count'] = $this->db('sports')->getTotal('column_content', array('column_id'=>$item['id']));
            $result[] = $item;
        }
        
        return $result;
    }
    
    public function addInfo($data) {
        return $this->db('sports')->insert('column', $data);
    }
    
    public function updateInfo($id, $data) {
        return $this->db('sports')->update('column', $data, array('id'=>$id));
    }
    
    public function deleteInfo($id) {
        
    }
    
    public function getRelNews($id) {
        $query = $this->db('sports')->select('news.*, column_content.priority AS priority, column_content.id AS tid')
            ->from('column_content')
            ->join('news', 'column_content.ref_id=news.id', 'left')
            ->where('column_content.column_id', $id)
            ->order_by('column_content.priority', 'DESC')
            ->order_by('column_content.created_at', 'DESC')
            ->get();
        
        return $query->result_array();
    }
    
    public function getRelList() {
        
    }
    
    public function addRelInfo($id, $news_id) {
        // 验证是否已经存在video
        $query = $this->db('sports')->select('*')
            ->from('column_content')
            ->where('column_id', $id)
            ->where('ref_id', $news_id)
            ->get();
        $ref_info = $query->row_array();
        if (!empty($ref_info)) {
            return false;
        }
        $data = array(
                'column_id' => $id,
                'type'      => 'news',
                'ref_id'    => $news_id
        );
        return $this->db('sports')->insert('column_content', $data);
    }
    
    public function removeRelInfo($id, $news_id) {
        $condition = array(
                'column_id' => $id,
                'type'      => 'news',
                'ref_id'    => $news_id
        );
        return $this->db('sports')->delete('column_content', $condition);
    }
    
}