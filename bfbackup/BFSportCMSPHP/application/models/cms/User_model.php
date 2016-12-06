<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model {
    public function __construct() {
        parent::__construct();
    }
    
    public function login($username, $password) {
        $query = $this->dbKungfu->select('*')
            ->where('username',$username)
            ->where('password',$this->encrypt($password, $username))
            ->where('enable',1)
            ->get('user');

        $user = $query->row_array();
        if ($user) {
            return $user;
        }
        return false;
    }
    
    public function hasUserSession() {
        $this->load->library('session');
        return $this->session->has_userdata('user_info')
            && $this->session->has_userdata('user_id')
            && $this->session->userdata('user_id') > 0;
    }
    
    public function getUserInfoFromSession() {
        $this->load->library('session');
        return json_decode($this->session->userdata('user_info'), true);
    }
    
    public function getRoleIds($user_id) {
        $result = array();
        $query = $this->dbKungfu->select('*')
            ->where(array('user_id' => $user_id, 'enable' => 1))
            ->get('user_role');

        $roles = $query->result_array();
        foreach ($roles as $r) {
            $result[] = $r['role_id'];
        }
        return $result;
    }

    public function getUsers($enable=null,$offset=0,$limit=10){
        $where = array();
        if (is_numeric($enable)) {
            $where['enable'] = intval($enable);
        }

        $query = $this->dbKungfu->select('*')
            ->where($where)
            ->order_by('created_at','ASC')
            ->offset($offset)
            ->limit($limit)
            ->get('user');
        
        return $query->result_array();
    }

    public function remove($table,$ids){

        if(is_array($ids)){
            $this->dbKungfu->where_in('id',$ids);
        }else{
            $this->dbKungfu->where('id',$ids);
        }

        $this->dbKungfu->delete($table);

    }

    public function batch_insert($table,$data){
        
        $this->dbKungfu->insert_batch($table,$data);
    }

    public function createUser($data){

        $this->dbKungfu->insert('user',$data);

        return $this->dbKungfu->insert_id();

    }

    public function encrypt($password, $username, $userid=0) {
        $username = trim($username);
        $userid = intval($userid);
        
        if (!$username && $userid) {
            $query = $this->dbKungfu->select('username')->where('id', $userid)->get('user');
            $user = $query? $query->row_array() : array();
            if (isset($user['username'])) {
                $username = $user['username'];
            } else {
                $username = '';
            }
            echo $username;
        }
        
        return md5("p:{$password};u:{$username};r:Gu3g8WVFi7");
    }

}
