<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slide_model extends MY_Model {
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * 根据焦点图ID获取焦点图数据
     * @param int $slideId
     * @return array
     */
    public function getSlideById($slideId){

        $query = $this->dbSports->get_where('slide',array('id' => intval($slideId)));
    
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
        ->get('slide');

        return $query->result_array();
    }
    
    /**
     * 获取最大的priority
     */
    public function getMaxPriority(){
        $priority = 0; 
        $query = $this->dbSports->select_max('priority')
        ->get('slide');
        
        $data = $query->row_array();
        if ($data['priority']) {
            $priority = $data['priority'];
        }
        
        return $priority;
    }

}
