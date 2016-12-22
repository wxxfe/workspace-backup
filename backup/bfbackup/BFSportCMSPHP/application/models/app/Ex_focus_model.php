<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ex_focus_model extends MY_Model {
    private $table_name='ex_focus';

    public $types = array(
        'video'=>'视频',
        'collection'=>'合集',
        'match'=>'比赛',
        'program'=>'节目',
        'html'=>'URL'
    );

    public function __construct() {
        parent::__construct();
    }

    /**
     * 列表
     * @param $limit
     * @param $offset
     * @param array $where
     */
    public function getList($limit=20, $offset=0, $where=array()) {
        $query = $this->dbSports->select("*")->from($this->table_name);
        if(!empty($where)) {
            $query->where($where);
        }
        $query->order_by('id', 'DESC');
        $query->limit($limit, $offset);
        $result = $query->get();
        $data = $result ? $result->result_array() : array();
        return $data;
    }

    public function getInfo($id){
        $query = $this->dbSports->get_where($this->table_name,array('id' => intval($id)));
        return $query->row_array();
    }
}
