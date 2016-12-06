<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Community_type_model  extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getCommunityPeopleType() {
        $result = array();
        $rows = $this->db('user')->select('*')->get('topfinger')->result_array();
       foreach ($rows as $row) {
           $result[$row['id']] = $row['name'];
       }
        return $result;
    }

    public function getInfoById($type_id) {
        $query = $this->db('user')->get_where("topfinger",array('id' => $type_id));
        $result = $query->result_array();
        if ($result) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * 获取社区达人类型列表
     * @param int $offset 偏移
     * @param int $limit 每页数量
     * @return array
     */
    public function getAllCpType($offset=0,$limit=20) {

        $query = $this->dbUser->select('*')
            ->order_by('created_at','DESC')
            ->offset($offset)
            ->limit($limit)
            ->get('topfinger');

        return $query->result_array();
    }

}