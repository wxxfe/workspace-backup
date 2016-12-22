<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Community_model extends MY_Model {
    
    public function getList($where=array(), $limit=10, $offset=0) {
        $query = $this->db('board')->select('*')
            ->from('community')
            ->order_by('id', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();
        
        $result = array();
        foreach ($query->result_array() as $item) {
            $item['icon_url'] = getImageUrl($item['icon']);
            $result[] = $item;
        }
        
        return $result;
    }
    
    public function getAll() {
        $query = $this->db('board')->select('*')
            ->from('community')
            ->order_by('id', 'DESC')
            ->get();
        
        return $query->result_array();
    }
    
    public function addInfo($data) {
        return $this->db('board')->insert('community', $data);
    }
    
    public function updateInfo($id, $data) {
        return $this->db('board')->update('community', $data, array('id'=>$id));
    }
    
    public function deleteInfo($id) {
        return $this->db('board')->remove('community', $id);
    }
    
    public function getInfo($id) {
        $query = $this->db('board')->get_where('community', array('id'=>$id), 1);
        $result = array();
        $item = $query->row_array();
        $item['icon_url'] = getImageUrl($item['icon']);
        $result = $item;
        return $result;
    }
    
    public function getTotal($table, $where=array()) {
        $this->db('board');
        return parent::getTotal($table, $where);
    }
    
    public function getColumnList($where=array(), $limit=10, $offset=0) {
        $query = $this->db('board')->select('*')
        ->from('community_column')
        ->order_by('display_order', 'ASC')
        ->limit($limit)
        ->offset($offset)
        ->get();
    
        $result = array();
        foreach ($query->result_array() as $item) {
            $item['community_count'] = $this->_getColulmnHasCount($item['id']);
            $result[] = $item;
        }
    
        return $result;
    }
    
    public function getColumn($column_id) {
        $query = $this->db('board')->select('*')
            ->from('community_column')
            ->where('id', $column_id)
            ->get();
        
        $result = $query->row_array();
        return $result;
    }
    
    public function getColumnHas($column_id, $limit=10, $offset=0) {
        $query = $this->db('board')->select('community.*,community_has.display_order as display_order,
                community_has.id AS tid')
            ->from('community_has')
            ->join('community', 'community.id=community_has.community_id','left')
            ->where('community_has.community_column_id', $column_id)
            ->order_by('community_has.display_order', 'ASC')
            ->limit($limit)
            ->offset($offset)
            ->get();
        
        $result = array();
        foreach ($query->result_array() as $item) {
            $item['icon_url'] = getImageUrl($item['icon']);
            $result[] = $item;
        }
        
        return $result;
    }
    
    public function addColumn($data) {
        $column_id = $this->db('board')->insert('community_column', $data);
        $this->_resort('community_column');
        return $column_id;
    }
    
    public function deleteColumn($id) {
        $this->db('board')->remove('community_column', $id);
        $this->_resort('community_column');
        return true;
    }
    
    public function updateColumn($id, $data) {
        return $this->db('board')->update('community_column', $data, array('id'=>$id));
    }
    
    public function sortColumn($id, $sort) {
        return $this->_sort($id, $sort, 'community_column');
    }
    
    public function addColumnHas($column_id, $community_id) {
        // 查询是否已经有了？
        $query = $this->db('board')->select('*')
            ->from('community_has')
            ->where('community_column_id', $column_id)
            ->where('community_id', $community_id)
            ->get();
        $result = $query->row_array();
        
        // 如果没有，则添加
        if (empty($result)) {
            $data = array(
                    'community_id' => $community_id,
                    'community_column_id' => $column_id,
            );
            $id = $this->db('board')->insert('community_has', $data);
            $this->_resort('community_has', array('community_column_id'=>$column_id));
        } else {
            $id = $result['id'];
        }
        
        return $id;
    }
    
    public function deleteColumnHas($column_id, $community_id) {
        $this->db('board')
            ->where('community_column_id', $column_id)
            ->where('community_id', $community_id)
            ->delete('community_has');
        
        $this->_resort('community_has', array('community_column_id'=>$column_id));
    }
    
    public function sortColumnHas($column_id, $id, $sort) {
        $this->_sort($id, $sort, 'community_has', array('community_column_id'=>$column_id));
    }
    
    public function getThreadList($community_id, $limit, $offset) {
        $query = $this->db('board')->select('thread.*')
            ->from('thread_has')
            ->join('thread','thread_has.thread_id=thread.id', 'left')
            ->where('thread_has.community_id', $community_id)
            ->order_by('thread.created_at', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();
        
        return $query->result_array();
    }
    
    protected function _getColulmnHasCount($column_id) {
        return $this->db('board')->where('community_column_id', $column_id)
            ->from('community_has')
            ->count_all_results();
    }
    
    /**
     * 
     * @param intval $id
     * @param intval $sort
     * @param array  $condition
     *  例: $condition = array('column_id' => $column_id);
     */
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