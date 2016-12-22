<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userm_model extends MY_Model {
    
    public function __construct() {
        parent::__construct();
        $this->db('board');
        $this->db = $this->_dbCurrent;
    }

    /**
     * 根据用户ID获取用户数据
     * @param int $userId
     * @return array
     */
    public function getUserById($userId){

        $query = $this->db->get_where('user',array('id' => intval($userId)));
    
        return $query->row_array();
    
    }
      
    /**
     * 获取用户列表
     * @return array
     */
    public function getAllUser($limit, $offset){

        $query = $this->db->select('*')
        ->order_by('created_at','DESC')
        ->limit($limit)
        ->offset($offset)
        ->get('user');

        return $query->result_array();
    }

}
