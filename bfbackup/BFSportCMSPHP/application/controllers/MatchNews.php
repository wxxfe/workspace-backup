<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MatchNews extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Match_news_model', 'MNM');
        $this->limit = 20;
        // 需要处理，只能添加新闻、视频、图集
        $this->limit_types = array(
            'gallery', 'news', 'video'
        );
    }
    
    public function index($match_id, $type='top', $offset=0) {
        $this->load->config('sports');

        $data = array();
        $data['type'] = $type;
        $data['match_id'] = $match_id;
        $data['keyword'] = '';
        $data['stype'] = '';
        $news = array();
        $total = 0;
        
        
        if (isset($_GET['keyword'])){
            $data['type'] = 'search';
            $keyword = $_GET['keyword'];
            $stype = $_GET['stype'];
            $data['keyword'] = $keyword;
            $data['stype'] = $stype;
            if (is_numeric($keyword)) {
                $total = 1;
                $news = $this->MNM->db('sports')->get_where($stype, array('id' => intval($keyword)))->result_array();
                if (!empty($news)) {
                    foreach ($news as & $item) {
                        $top = $this->MNM->db('sports')->where(array('type' => $stype, 'ref_id' => $item['id'], 'match_id' => intval($match_id)))->get('match_related')->row_array();
                        $item['is_top'] = !empty($top);
                        $item['tid'] = $top['id'];
                    }
                } else {
                    $total = 0;
                }
            } else {
                $searchResult = $this->MNM->getSearchResult($stype, $keyword, $match_id, $offset, $this->limit);
                $total = $searchResult['total'];
                $news = $searchResult['result'];
            }
        } else {
            $total = $this->MNM->db('sports')->getTotal('match_related', array('match_id' => $match_id));
            $rel_list = $this->MNM->getRelList($match_id, $this->limit, $offset);
            foreach ($rel_list as $rel_item) {
                $item = array(
                        'tid' => $rel_item['id'],
                        'type' => $rel_item['type'],
                        'sort' => $rel_item['priority'],
                );
                $item['info'] = $this->MNM->getRelInfo($rel_item['type'], $rel_item['ref_id']);
                $news[] = $item;
            }
        }
        
        $data['news'] = $news;
        $data['total'] = $total;
        $data['page'] = $this->_pagination($total, $this->limit, '/MatchNews/index/'.$match_id.'/'.$type.'/');
        $data['offset'] = $offset;
        $data['limit_types'] = $this->limit_types;
        $this->load->view('match/news', $data);
    }
    
    public function cancelTop($tid) {
        try{
            $this->MNM->db('sports')->freshTopRefUpdatedAt('match_related', $tid);
            $this->MNM->db('sports')->remove('match_related',intval($tid));
            echo 'success';
        }catch(Exception $e){
            echo 'fail';
        }
    }
    
    public function setTop($type, $ref_id, $match_id) {
        // 检测是否已经包含内容，如果已经包含，不允许再次添加
        $exist = $this->MNM->db('sports')->get_where('match_related',array('type' => $type, 'match_id' => intval($match_id), 'ref_id' => intval($ref_id)));
        if (!empty($exist->row_array())) {
            echo 'fail';
            return false;
        }
        
        try{
            $tid = $this->MNM->db('sports')->insert('match_related',array('type' => $type, 'match_id' => intval($match_id), 'ref_id' => intval($ref_id)));
            // 新添加的排在第一个
            $this->MNM->setTbSort('match_related', $tid, array('priority'=>1), array('match_id'=> intval($match_id)));
            $this->MNM->db('sports')->freshTopRefUpdatedAt('match_related', $tid);
            echo 'success';
        }catch(Exception $e){
            echo 'fail';
        }
    }
    
    public function updateSort($match_id){
        $data = $_POST;
        $tid = $data['pk'];
        $sort = intval($data['value']);
        $result = $this->MNM->db('sports')->setTbSort('match_related', $tid, array('priority' => $sort), array('match_id' => $match_id));
        echo $result ? 'success' : 'fail';
    }
}