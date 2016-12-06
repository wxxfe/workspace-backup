<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Match_news_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function getRelList($match_id, $limit=5, $offset=0) {
        $query = $this->db('sports')->select('*')
            ->from('match_related')
            ->where('match_id', $match_id)
            ->order_by('priority','DESC')
            ->order_by('id','DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();
        
        return $query->result_array();
    }
    
    public function getRelInfo($type, $id) {
        if($type == 'video'){
            $this->db('sports')->select('id,title,image,publish_tm,play_code,isvr');
        }else{
            $this->db('sports')->select('id,title,image,publish_tm');
        }
        
        $query = $this->db('sports')->where('id',intval($id))->get($type);
        
        return $query->row_array();
    }
    
    public function getSearchResult($type, $keyword, $match_id, $offset=0, $limit=20){
        $this->load->model('search_model', 'SM');
        $searchResult = $this->SM->itemsQuery($type, $keyword, $offset, $limit);
        $full = array('total' => $searchResult['total']);
        $fdata = array();
        foreach($searchResult['result'] as $item){
            $top = $this->dbSports->where(array('type' => $type, 'ref_id' => $item['id'], 'match_id' => intval($match_id)))->get('match_related')->row_array();
            $item['is_top'] = !empty($top);
            $item['tid'] = $top['id'];
            $fdata[] = $item;
        }
    
        $full['result'] = $fdata;
    
        return $full;
    }
}
