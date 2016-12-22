<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner_model extends MY_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getBanners() {
        $result = array();
        $query = $this->db('sports')->order_by('publish_tm DESC, id ASC')->get('banner');
        foreach($query->result_array() as $item) {
            $result[$item['id']] = $item;
        }
        return $result;
    }
}