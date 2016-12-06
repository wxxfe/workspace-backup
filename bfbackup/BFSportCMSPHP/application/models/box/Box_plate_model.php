<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Box_plate_model extends MY_Model {
    private $table_name = 'box_plate';

    public function __construct() {
        parent::__construct();
    }

    public function getInfo($id){
        $query = $this->dbSports->get_where($this->table_name,array('id' => intval($id)));
        return $query->row_array();
    }

    public function getList($limit, $offset){
        $query = $this->dbSports->select('*')
            ->order_by('priority','DESC')
            ->order_by('id','ASC')
            ->limit($limit,$offset)
            ->get($this->table_name);
        return $query->result_array();
    }

    public function getMaxPriority(){
        $query = $this->dbSports->select_max('priority', 'max_priority')->get('box_plate');
        $result = $query->row_array();
        return isset($result['max_priority']) ? $result['max_priority'] : 0;
    }

//-----------box_plate_content 相关方法
    public function getContentInfo($id){
        $query = $this->dbSports->get_where('box_plate_content',array('id' => intval($id)));
        return $query->row_array();
    }

    public function getContentList($where, $limit, $offset){
        $this->dbSports->from('box_plate_content');
        if($where)
            $this->dbSports->where($where);
        $this->dbSports->order_by('priority','DESC');
        $this->dbSports->order_by('id','ASC');
        $this->dbSports->limit($limit, $offset);
        $query = $this->dbSports->get();
        $result = array();
        foreach ($query->result_array() as $item) {
            $result[] = $item;
        }
        return $result;
    }

    public function removeContent($id){
        if(!$id)return false;
        $this->db('sports')->where('id', $id);
        return $this->db('sports')->delete('box_plate_content');
    }

    public function getContentMaxPriority($plateid){
        $query = $this->dbSports->select_max('priority', 'max_priority')->where('plate_id',$plateid)->get('box_plate_content');
        $result = $query->row_array();
        return isset($result['max_priority']) ? $result['max_priority'] : 0;
    }
}
