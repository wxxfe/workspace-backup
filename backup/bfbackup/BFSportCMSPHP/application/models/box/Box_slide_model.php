<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Box_slide_model extends MY_Model {
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * 根据焦点图ID获取焦点图数据
     * @param int $slideId
     * @return array
     */
    public function getSlideById($slideId){
        $query = $this->dbSports->get_where('box_slide',array('id' => intval($slideId)));
    
        return $query->row_array();
    }
      
    /**
     * 获取焦点图列表
     * @return array
     */
    public function getAllSlide($limit, $offset){
        $query = $this->dbSports->select('*')
        ->order_by('priority','DESC')
        ->limit($limit)
        ->offset($offset)
        ->get('box_slide');

        return $query->result_array();
    }

    public function getMaxPriority(){
        $query = $this->dbSports->select_max('priority', 'max_priority')->get('box_slide');
        $result = $query->row_array();
        return isset($result['max_priority']) ? $result['max_priority'] : 0;
    }
}
