<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Program_model extends MY_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getInfo($id) {
        $query = $this->db('sports')->get_where('program', array('id'=>$id), 1);
        return $query->row_array();
    }
}