<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery_model extends MY_Model {
    function __construct() {
        parent::__construct();
    }

    /**
     * 获取图集内容
     * @param int $id
     * @return array
     */
//    public function getGalleryById($id){
//        if(!$id)return false;
//        $query = $this->dbSports->where(array('id'=>intval($id)))->get('gallery');
//        return $query ? $query->row_array() : array();
//    }

    /**
     * 获取图集内容
     * @param $id
     */
    public function getGalleryContent($where=array(), $limit=6, $offset=0, $order_by = array('priority'=>'DESC')){
        $data = array();
        if(!$where)return $data;
        $this->dbSports->where($where);

        //order by
        $order_by = each($order_by);
        $this->dbSports->order_by("{$order_by['key']}","{$order_by['value']}");

        $this->dbSports->limit($limit, $offset);
        $query = $this->dbSports->get('gallery_image');
        $result = $query ? $query->result_array() : array();

        foreach($result as $row){
            $row['image'] = isset($row['image']) ? getImageUrl($row['image']): '';
            $data[] = $row;
        }
        return $data;
    }

    /**
     * 获取相关图集内容
     * @param $galleryId
     * @return array
     */
    public function getGalleryRelated($galleryId)
    {
        $this->load->model('api_model', 'AM');

        $limit = 5;
        $relatedData = array();

        $result = $this->AM->requestapi('gallery_related', array($galleryId));
        $response = json_decode($result, true);
        if (isset($response['errno']) && $response['errno'] == 10000 && isset($response['data']['gallery'])) {
            $galleries = $response['data']['gallery'];
            foreach ($galleries as $val) {
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
            $relatedDt = $this->_getRelatedFromDt($galleryId, 10, 'gallery');

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
