<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Columncm_model extends MY_Model {
    
    public function getList($where=array(), $limit=10, $offset=0) {
        $query = $this->db('board')->select('column.*,community.name AS community_name')
            ->join('community','column.community_id=community.id','left')
            ->from('column')
            ->order_by('column.display_order', 'ASC')
            ->limit($limit)
            ->offset($offset)
            ->get();
        
        $result = array();
        foreach ($query->result_array() as $item) {
            $item['thread_count'] = $this->_getHasCount($item['id']);
            $result[] = $item;
        }
        
        return $result;
    }
    
    public function getInfo($id) {
        $query = $this->db('board')->get_where('column', array('id'=>$id), 1);
        return $query->row_array();;
    }
    
    public function addInfo($data) {
        $column_id = $this->db('board')->insert('column', $data);
        $this->_resort('column');
        return $column_id;
    }
    
    public function updateInfo($id, $data) {
        return $this->db('board')->update('column', $data, array('id'=>$id));
    }
    
    public function deleteInfo($id) {
        $this->db('board')->remove('column', $id);
        $this->_resort('column');
        return true;
    }
    
    public function sortInfo($id, $sort) {
        return $this->_sort($id, $sort, 'column');
    }
    
    public function getHas($column_id, $limit=10, $offset=0) {
        $query = $this->db('board')->select('thread.*,column_has.display_order as display_order,
                column_has.id AS tid')
                        ->from('column_has')
                        ->join('thread', 'thread.id=column_has.thread_id','left')
                        ->where('column_has.column_id', $column_id)
                        ->order_by('display_order', 'ASC')
                        ->limit($limit)
                        ->offset($offset)
                        ->get();
        
        return $query->result_array();
    }
    
    public function addHas($column_id, $thread_id) {
        // 查询是否已经有了？
        $query = $this->db('board')->select('*')
            ->from('column_has')
            ->where('column_id', $column_id)
            ->where('thread_id', $thread_id)
            ->get();
        $result = $query->row_array();
        
        // 如果没有，则添加
        if (empty($result)) {
            $data = array(
                    'thread_id' => $thread_id,
                    'column_id' => $column_id,
            );
            $id = $this->db('board')->insert('column_has', $data);
            $this->_resort('column_has', array('column_id'=>$column_id));
        } else {
            $id = $result['id'];
        }
        
        return $id;
    }
    
    public function deleteHas($column_id, $thread_id) {
        $this->db('board')
            ->where('column_id', $column_id)
            ->where('thread_id', $thread_id)
            ->delete('column_has');
        
        $this->_resort('column_has', array('column_id'=>$column_id));
    }
    
    public function sortHas($column_id, $id, $sort) {
        $this->_sort($id, $sort, 'column_has', array('column_id'=>$column_id));
    }
    
    public function getTotal($table, $where=array()) {
        $this->db('board');
        return parent::getTotal($table, $where);
    }
    
    protected function _getHasCount($column_id) {
        return $this->db('board')->where('column_id', $column_id)
        ->from('column_has')
        ->count_all_results();
    }
    
    protected function _sort($id, $sort, $table, $condition=array()) {
        $condition_str = '';
        if (!empty($condition)) {
            foreach ($condition as $key=>$val) {
                $condition_str .= "AND `{$key}`='{$val}'";
            }
        }
        
        $orig_info = $this->db('board')->get_where($table, array('id'=>$id), 1);
        $orig_sort = $orig_info->row_array();
        $orig_sort = $orig_sort['display_order'];
        
        if ($sort > $orig_sort) {
            $sql = "UPDATE `{$table}` SET display_order=display_order-1 WHERE display_order<={$sort}";
        } else if( $sort < $orig_sort){
            $sql = "UPDATE `{$table}` SET display_order=display_order+1 WHERE display_order>={$sort}";
        } else {
            return TRUE;
        }
        
        if (!empty($condition_str)) {
            $sql .= ' '.$condition_str;
        }
        $this->db('board')->query($sql);
        
        $this->db('board')->update($table, array('display_order'=>$sort), array('id'=>$id));
        $this->_resort($table, $condition);
        return TRUE;
    }
    
    protected function _resort($table, $condition=array()) {
        $this->db('board')->select('*')->from($table);
        if (!empty($condition)) {
            $this->db('board')->where($condition);
        }
        $query = $this->db('board')
            ->order_by('display_order', 'ASC')
            ->get();
        
        $order = 1;
        foreach ($query->result_array() as $item) {
            $this->db('board')->update($table, array('display_order'=>$order), array('id'=>$item['id']));
            $order++;
        }
        
        return TRUE;
    }
}