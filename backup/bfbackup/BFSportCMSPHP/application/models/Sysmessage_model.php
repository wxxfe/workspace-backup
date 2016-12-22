<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sysmessage_model extends MY_Model {
    
    public function getList($limit, $offset) {
        $query = $this->db('kungfu')->select('*')
                ->from('sys_message')
                ->order_by('id', 'desc')
                ->limit($limit)
                ->offset($offset)
                ->get();
        
        return $query->result_array();
    }
    
    public function getInfo($id) {
        $query = $this->db('kungfu')->select('*')
            ->from('sys_message')
            ->where('id', $id)
            ->get();
        
        return $query->row_array();
    }
    
    public function addInfo($data) {
        return $this->db('kungfu')->insert('sys_message', $data);
    }
    
    public function updateInfo($id, $data) {
        return $this->db('kungfu')->update('sys_message', $data, array('id'=>$id));
    }
    
    public function deleteInfo($id) {
        return $this->db('kungfu')->remove('sys_message', $id);
    }
    
    public function send($data) {
        return $this->db('board')->insert('broadcast', $data);  
    }
}
