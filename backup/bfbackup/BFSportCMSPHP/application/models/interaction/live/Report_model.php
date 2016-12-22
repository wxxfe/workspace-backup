<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends MY_Model {
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * 根据战报ID获取战报数据
     * @param int $reportId
     * @return array
     */
    public function getReportById($reportId){

        $query = $this->dbSports->get_where('match_report',array('id' => intval($reportId)));
    
        return $query->row_array();
    
    }
      
    /**
     * 获取战报列表
     * @return array
     */
    public function getAllReport($match_id){

        $query = $this->dbSports->select('*')
        ->where('match_id', $match_id)
        ->order_by('report_tm','ASC')
        ->get('match_report');

        return $query->result_array();
    }

    
    /**
     * 发送战报消息
     * @param $id 战报
     */
    public function _sendReport($id, $match_id, $host_id) {

        //
        //说明
        //
        //id - 战报ID
        //type - 消息类型
        //timeline  时间
        //summarize 综述
        //image     图片
        //text      消息
        //gif       动态图
        //gif_size  动态图大小
    
        $report_info = $this->getReportById($id);
        if (!$report_info) {
            return false;
        }
        
        $data = array(
            'id'        => $id,
            'type'      => 1,
            'timeline'  => $report_info['report_tm'],
            'summarize' => false,
            'image'     => $report_info['image'] ? getImageUrl($report_info['image']) : '',
            'text'      => $report_info['content'],
            'gif'       => $report_info['gif'] ? getImageUrl($report_info['gif']) : '',
            'gif_size'  => $report_info['gif_size'],
        );
        
        if (-1 == $report_info['report_tm']) {
            $data['timeline']  = '';
            $data['summarize'] = true;
        }
        
        
        return $this->send_host_message($data, $match_id, $host_id);
    }
}
