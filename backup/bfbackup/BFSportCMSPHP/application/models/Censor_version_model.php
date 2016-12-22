<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Censor_version_model extends MY_Model {
    
    public function getList() {
        $query = $this->db('sports')->select('*')
                    ->from('censor_version')
                    ->get();
        
        return $query->result_array();
    }
    
    public function updateInfo($id, $data) {
        return $this->db('sports')->update('censor_version', $data, array('id'=>$id));
    }
}