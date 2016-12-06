<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_model extends MY_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getRole($enable = null) {
        $where = array();
        if (is_numeric($enable)) {
            $where['role.enable'] = intval($enable);
        }

        $query = $this->dbKungfu->select('role.*,count(power.id) as total')
            ->join('power','role.id=power.role_id AND power.enable=1','left')
            ->group_by('role.id')
            ->where($where)
            ->order_by('role.created_at','ASC')
            ->get('role');

        return $query->result_array();
    }

    public function getRoleByUserId($userId,$enable=null){
        $where = array('user_role.user_id' => $userId);
        if (is_numeric($enable)) {
            $where['user_role.enable'] = intval($enable);
        }
        $query = $this->dbKungfu->select('role.id,role.name')
            ->join('role','user_role.role_id=role.id')
            ->group_by('role.id')
            ->where($where)
            ->order_by('user_role.created_at','ASC')
            ->get('user_role');

        return $query->result_array();
    }

    public function update($table,$newData,$where=array()){

        $this->dbKungfu->where($where);
        $this->dbKungfu->update($table,$newData);
        return $this->dbKungfu->affected_rows();

    }

    public function createRole($data){

        $this->dbKungfu->insert('role',$data);

        return $this->dbKungfu->insert_id();

    }

}
