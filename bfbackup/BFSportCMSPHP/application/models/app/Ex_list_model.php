<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ex_list_model extends MY_Model {
    private $table_name='ex_list';

    public $types = array(
        'video'=>'视频',
        'collection'=>'合集',
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
        $query->order_by('priority', 'DESC');
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

    /**
     * 获取最大的priority
     */
    public function getMaxPriority(){
        $priority = 0;
        $query = $this->dbSports->select_max('priority')
            ->get($this->table_name);

        $data = $query->row_array();
        if ($data['priority']) {
            $priority = $data['priority'];
        }

        return $priority;
    }

    /**
     * 获取排序后的顺序值
     * @param $where
     * @return mixed
     */
    public function getSort($table_name, $id, $where = array()) {
        if (!$table_name || empty($where)) return false;
        $this->dbSports->from($table_name);
        $this->dbSports->where($where);
        $this->dbSports->order_by('priority', 'DESC');
        $this->dbSports->order_by('id', 'DESC');
        $result = $this->dbSports->get()->result_array();
        $sort = 0;
        $count = count($result);
        for ($i = 0; $i <= $count; $i++) {
            if ($result[$i]['id'] == $id) {
                $sort = $i + 1;
                break;
            }
        }
        if (!$sort) $sort = $count + 1;
        return $sort;
    }
}
