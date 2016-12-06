<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Popup_model extends MY_Model
{
    //平台类型
    public $platf_type = array('iphone'=>'iphone', 'ipad'=>'ipad', 'android'=>'android');

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取列表数据
     * @param array $where
     * @param int $limit
     * @param int $offset
     */
    public function getList($where=array(), $limit=20, $offset=0) {
        $query = $this->dbSports->select('*')->from('popup');
        if (isset($where['title'])) {
            $query->like('title', $where['title'], 'both');
            unset($where['title']);
        }
        if(!empty($where)) {
            $query->where($where);
        }
        $query->order_by('id', 'DESC');
        $query->limit($limit, $offset);
        $result = $query->get();
        $data = $result ? $result->result_array() : array();
        return $data;
    }

    /**
     * 获取总数，支持like查询
     * @param array $where
     * @return mixed
     */
    public function getCount($where=array()){
        if (isset($where['title'])) {
            $this->dbSports->like('title', $where['title'], 'both');
            unset($where['title']);
        }
        if (!empty($where)) {
            $this->dbSports->where($where);
        }
        $this->dbSports->from('popup');
        return $this->dbSports->count_all_results();
    }

    /**
     * @param $id
     */
    public function getInfo($id) {
        $query = $this->dbSports->select('*')->from('popup')->where('id', $id)->get();
        return $query ? $query->row_array() : array();
    }
}