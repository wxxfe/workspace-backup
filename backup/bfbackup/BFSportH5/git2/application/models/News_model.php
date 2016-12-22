<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

//    public function getNewsRelated($newsId){
//        $query = $this->dbSports->get_where('news_related',array('news_id' => intval($newsId)));
//        $relatedNews = $query->result_array();
//        if (!$relatedNews) {
//            //个性化推荐接口获取数据
//            $this->load->model('api_model', 'AM');
//            $result = $this->AM->requestapi('sportsrec', array($newsId, 'news', 10));
//            $result_arr = json_decode($result, true);
//            if (isset($result_arr['error_code']) && $result_arr['error_code'] == 10000) {
//                foreach ($result_arr['data'] as $row) {
//                    $relatedNews[] = array('type'=>$row['type'], 'ref_id'=>$row['id']);
//                }
//            }
//        }
//        $full = array();
//        foreach($relatedNews as $news){
//            $item = array('news_type' => $news['type']);
//            $action = 'get' . strtolower($news['type']) .'ById';
//            $item['news_extra'] = $this->$action($news['ref_id']);
//            if ($item['news_extra']) {
//                $full[] = $item;
//            }
//        }
//        return $full;
//    }

    public function getNewsRelated($newsId){
        $limit = 5;
        // FROM DB
        $query = $this->dbSports->limit($limit, 0)->get_where('news_related',array('news_id' => intval($newsId)));
        $relatedData = $query->result_array();

        // DT REC
        if (count($relatedData) < $limit) {
            // GET DATA
            $relatedDt = $this->_getRelatedFromDt($newsId, 10, 'news');//取10条去重

            if (!empty($relatedDt)) {
                // 去掉重复数据
                $ref_ids = array();
                foreach ($relatedData as $val) {
                    $ref_ids[] = $val['ref_id'];
                }

                foreach ($relatedDt as $key=>$related) {
                    if (count($relatedData) >= $limit) {
                        break;
                    }
                    if (isset($related['ref_id']) && !in_array($related['ref_id'], $ref_ids)) {
                        $relatedData[] = $related;
                    }
                }
            }
        }

        // FORMAT DATA
        $formated_data = array();
        foreach($relatedData as $val){
            $item = array('news_type' => $val['type']);
            $action = 'get' . strtolower($val['type']) .'ById';

            $item['news_extra'] = $this->$action($val['ref_id']);
            if ($item['news_extra']) {
                $formated_data[] = $item;
            }
        }

        return $formated_data;
    }

}
