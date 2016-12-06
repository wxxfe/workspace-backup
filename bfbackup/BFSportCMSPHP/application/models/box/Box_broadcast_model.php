<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Box_broadcast_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getInfo($id){
        $query = $this->dbSports->get_where('box_broadcast',array('id' => intval($id)));
        return $query->row_array();
    }

    public function getAllData($limit, $offset){
        $query = $this->dbSports->select('*')
            ->order_by('priority','DESC')
            ->limit($limit)
            ->offset($offset)
            ->get('box_broadcast');
        return $query->result_array();
    }

    public function getMaxPriority(){
        $query = $this->dbSports->select_max('priority', 'max_priority')->get('box_broadcast');
        $result = $query->row_array();
        return isset($result['max_priority']) ? $result['max_priority'] : 0;
    }
}

