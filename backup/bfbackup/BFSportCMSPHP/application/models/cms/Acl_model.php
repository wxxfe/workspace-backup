<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acl_model extends MY_Model {
    const POWER_ACCESS = 1;
    const POWER_INSERT = 2;
    const POWER_MODIFY = 2;
    const POWER_DELETE = 4;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getAcl($enable = null) {
        $where = array();
        if (is_numeric($enable)) {
            $where['enable'] = intval($enable);
        }

        $query = $this->dbKungfu->select('*')
            ->where($where)
            ->order_by('parent','ASC')
            ->order_by('sort','ASC')
            ->get('acl');

        return $query->result_array();
    }
    
    public function getRolePowers($role_ids) {
        $powers = array();

        if (is_array($role_ids)) {
            $this->dbKungfu->where_in('role_id', $role_ids);
        } else {
            $this->dbKungfu->where('role_id', $role_ids);
        }

        $query = $this->dbKungfu->select('*')
            ->where(array('user_id' => null, 'enable' => 1))
            ->get('power');

        $rows = $query->result_array();
        foreach ($rows as $row) {
            $power = intval($row['power']);
            if (!isset($powers[$row['acl_id']]) || $powers[$row['acl_id']] < $power) {
                $powers[$row['acl_id']] = $power;
            }
        }
        return $powers;
    }
    
    public function getUserPowers($user_id) {
        $powers = array();

        $query = $this->dbKungfu->select('*')
            ->where(array('user_id' => $user_id, 'role_id' => null, 'enable' => 1))
            ->get('power');

        $rows = $query->result_array();
        foreach ($rows as $row) {
            $powers[$row['acl_id']] = intval($row['power']);
        }
        return $powers;
    }
    
    public function canAccess() {
        return $this->_getUserCurrentPower() > 0;
    }
    
    public function canInsert() {
        return $this->_getUserCurrentPower() & self::POWER_INSERT;
    }
    
    public function canModify() {
        return $this->_getUserCurrentPower() & self::POWER_MODIFY;
    }
    
    public function canDelete() {
        return $this->_getUserCurrentPower() & self::POWER_DELETE;
    }
    
    private function _getUserCurrentPower() {
        $this->load->model('cms/user_model', 'UM');
        $info = $this->UM->getUserInfoFromSession();
        return $info['current_power'];
    }
    
}
