<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    public function getVideoById($id){

        $query = $this->dbSports->select('video.*,site.origin as site_name,video_extra.box_vid, video_extra.box_cid, video_extra.file_size')
            ->join('site','site.site = video.site','left')
            ->join('video_extra','video_extra.video_id = video.id','left')
            ->where('video.id',$id)
            ->get('video');

        return $query->row_array();

    }

    public function getVideosExtra($ids) {
        $query = $this->db('sports')->select('*')
            ->from('video_extra')
            ->where_in('video_id', $ids)
            ->get();

        $result = array();
        foreach ($query->result_array() as $item) {
            $result[$item['video_id']] = $item;
        }

        return $result;
    }

    /**
     * 获取相关推荐
     * @param $video_id
     */
    public function getVideoRelated($video_id) {
        $limit = 5;
        $relatedData = array();

        $this->load->model('Api_model', 'AM');
        $api_data = $this->AM->requestapi('video_related', array($video_id));
        $api_data_arr = json_decode($api_data, true);
        if ($api_data_arr['errno'] == '10000') {
            $videos = $api_data_arr['data']['video'];
            foreach ($videos as $val) {
                if (count($relatedData) >= 5) {
                    break;
                }
                $tmp = array(
                    'ref_id' => isset($val['id']) ? $val['id'] : '',
                    'type'=>isset($val['type']) ? $val['type'] : '',
                );
                $relatedData[] = $tmp;
            }
        }

        // DT REC
        if (count($relatedData) < $limit) {
            // GET DATA
            $relatedDt = $this->_getRelatedFromDt($video_id, 10, 'video');

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

        return array('video'=>$formated_data);
    }

}
