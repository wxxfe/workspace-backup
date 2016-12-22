<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment_model extends MY_Model {
    public function __construct() {
        parent::__construct();
        $this->db('board');
        $this->db = $this->_dbCurrent;
    }

    public function getCommentList($limit, $offset, $where = array(), $column = 'created_at', $order = 'DESC') {
        $this->db->query('SET NAMES utf8mb4');
        $this->db->where($where);
        $this->db->order_by($column, $order);
        $this->db->limit($limit, $offset);
        $query = $this->db->get('comment');
        
        //var_dump($column, $order,$this->db->last_query(),2);exit;
        return $query->result_array();
    }

    public function upPostCount($id){
        return $this->db->query("update post set comment_count=comment_count-1 where id=".$id);
    }
}