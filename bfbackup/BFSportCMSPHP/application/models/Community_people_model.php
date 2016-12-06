<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Community_people_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 通过id获取社区达人信息
     * @param int $cp_id 社区达人id
     * @return array
     */
    public function getInfoById($cp_id) {
        $query = $this->db('user')->get_where("user_extra",array('id' => $cp_id));
        $result = $query->result_array();
        if ($result) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * 获取社区达人列表
     * @param int $offset 偏移
     * @param int $limit 每页数量
     * @return array
     */
    public function getAllCp($offset=0,$limit=20) {

        $query = $this->dbUser->select('*')
            ->order_by('created_at','DESC')
            ->offset($offset)
            ->limit($limit)
            ->get('user_extra');

        return $query->result_array();
    }

}