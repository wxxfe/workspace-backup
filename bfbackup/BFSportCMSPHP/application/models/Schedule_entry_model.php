<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule_entry_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function add($data) {
        //check event_id title
        if (!isset($data['event_id']) || !$data['event_id'] || !isset($data['title']) || !$data['title']) {
            return false;
        }
        $event_id = intval($data['event_id']);
        $where = array('event_id'=>$event_id);
        $row = $this->getInfo($where);
        if ($row) {
            //update
            $result = $this->dbSports->update('schedule_entry', $data, $where);
        } else {
            $result = $this->dbSports->insert('schedule_entry', $data);
        }
        return $result;
    }

    public function getInfo($where=array(), $type='Row') {
        if (empty($where)) {
            return false;
        }

        $query = $this->dbSports->select('*')->from('schedule_entry')->where($where)->get();
        if ($type == 'Row') {
            $data = $query ? $query->row_array() : array();
        } else {
            $data = $query ? $query->result_array() : array();
        }
        return $data;
    }
}
