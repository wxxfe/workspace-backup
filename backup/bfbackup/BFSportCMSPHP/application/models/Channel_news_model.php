<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Channel_news_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getTopNewsByFakeId($fakeId,$offset=0,$limit=0){

        $query = $this->dbSports->select('*')
            ->where('tag_id',$fakeId)
            ->offset($offset)
            ->limit($limit)
            ->order_by('priority','DESC')
            ->order_by('id','DESC')
            ->get('manual_priority');

        return $query->result_array();

    }

    public function getNewsByFakeId($type,$fakeId,$offset=0,$limit=10){
        
        $table = $type.'_tag';

        $query = $this->dbSports->select($type.'.*')
            ->join($type,$table.'.'.$type.'_id = '.$type.'.id','left')
            ->where($table.'.tag_id',$fakeId)
            ->offset($offset)
            ->limit($limit)
            ->order_by($table.'.created_at','DESC')
            ->get($table);

        $result = $query->result_array();

        $full = array();

        foreach($result as $item){
            $top = $this->dbSports->where(array('ref_id' => $item['id'], 'tag_id' => intval($fakeId)))->get('manual_priority')->row_array();
            $item['is_top'] = !empty($top);
            $item['tid'] = $top['id'];
            $full[] = $item;
        }

        return $full;

    }

    public function getNewsInfo($type,$id){
        if($type == 'thread') {
            $this->dbBoard->select('*');
            $query = $this->dbBoard->where('id',intval($id))->get($type);
            $data = $query ? $query->row_array() : array();
            $data['image'] = $data['icon'];
            $data['publish_tm'] = $data['created_at'];
            $this->db('sports');//切回sports
            return $data;
        } else {
            if ($type == 'video') {
                $this->dbSports->select('id,title,image,publish_tm,play_code,isvr');
            } else {
                $this->dbSports->select('id,title,image,publish_tm');
            }
            $query = $this->dbSports->where('id', intval($id))->get($type);
            return $query->row_array();
        }
    }

    public function getSearchResult($type,$keyword,$tagId,$offset=0,$limit=20){
        $this->load->model('search_model', 'SM');
        $searchResult = $this->SM->itemsQuery($type, $keyword, $offset, $limit);
        $full = array('total' => $searchResult['total']);
        $full['result'] = $this->extendTopTag($searchResult['result'], $type, $tagId);
        
        return $full;
    }

    public function extendTopTag($news, $type, $tagId) {
        $fdata = array();
        foreach($news as $item){
            $top = $this->dbSports->where(array('ref_id' => $item['id'], 'tag_id' => intval($tagId)))->get('manual_priority')->row_array();
            if($type == 'thread') {
                $tag = $this->db('board')->where(array($type.'_id' => $item['id'], 'tag_id' => intval($tagId)))->get($type.'_tag')->row_array();
                $this->db('sports');//切回currentdb
            } else {
                $tag = $this->dbSports->where(array($type.'_id' => $item['id'], 'tag_id' => intval($tagId)))->get($type.'_tag')->row_array();
            }
            $item['is_top'] = !empty($top);
            $item['has_tag'] = !empty($tag);
            $item['tid'] = $top['id'];
            $fdata[] = $item;
        }
        return $fdata;
    }

    public function frushForSetForeverTop($table,$tagId,$sort_k,$sort_start) {
        $sql = "UPDATE {$table} SET {$sort_k}={$sort_k}-1 WHERE tag_id = {$tagId} AND priority >{$sort_start} AND priority <> 2147483647";
        $this->_dbCurrent->query($sql);
    }

}
