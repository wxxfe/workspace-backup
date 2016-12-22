<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Channel_model extends MY_Model {
    
    /////////////////////////////////////////////////////////
    ///// channel
    /////////////////////////////////////////////////////////
    // 获取频道列表
    public function getList($where, $limit, $offset) {
        $query = $this->db('sports')->select('channel.*,event.name as event_name')
            ->join('event', 'channel.ref_id=event.id', 'left')
            ->order_by('priority', 'DESC')
            ->offset($offset)
            ->limit($limit)
            ->get('channel');
        
        $result = array();
        foreach ($query->result_array() as $item) {
            $result[] = $item;
        }
        return $result;
    }
    
    public function getById($channel_id) {
        $query = $this->db('sports')->select('channel.*,event.name as event_name')
            ->join('event', 'channel.ref_id=event.id', 'left')
            ->where('channel.id', $channel_id)
            ->get('channel');
        
        return $query->row_array();
    }
    
    public function edit($id, $data) {
        return $this->db('sports')->update('channel', $data, array('id'=>$id));
    }
    
    public function delete($id) {
        return $this->db('sports')->remove('channel', $id);
    }
    
    public function add($data) {
        return $this->db('sports')->insert('channel', $data);
    }
    
    ////////////////////////////////////////////////////////////////
    /////   shortcut
    ////////////////////////////////////////////////////////////////
    
    public function addPriority($priority) {
        $sql = "UPDATE channel SET priority=priority+1 WHERE priority>={$priority}";
        $this->db('sports')->query($sql);
        
        return true;
    }
    
    public function getShortcut($event_id) {
        $result = array();
        $query = $this->db('sports')->select('*')
            ->where('event_id', $event_id)
            ->order_by('priority', 'DESC')
            ->get('shortcut');
        foreach ($query->result_array() as $item) {
            $result[] = $item;
        }
        return $result;
    }
    
    public function getShortcutInfo($id) {
        $query = $this->db('sports')->select('*')
        ->where('id', $id)
        ->get('shortcut');
        
        return $query->row_array();
    }
    
    public function addShortcut($data) {
        return $this->db('sports')->insert('shortcut', $data);
    }
    
    public function editShortcut($id, $data) {
        return $this->db('sports')->update('shortcut', $data, array('id'=>$id));
    }
    
    public function addShortcutPriority($event_id, $priority) {
        $sql = "UPDATE shortcut SET priority=priority+1 WHERE priority>={$priority} AND event_id={$event_id}";
        $this->db('sports')->query($sql);
    
        return true;
    }
    
    public function deleteShortcut($shortcut_id) {
        return $this->db('sports')->remove('shortcut', $shortcut_id);
    }
    
    public function setTbSort($table, $id, $sort, $sort_condition=array()) {
        $this->db('sports');
        return parent::setTbSort($table, $id, $sort, $sort_condition);
    }
    
}