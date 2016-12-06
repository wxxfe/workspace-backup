<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Special_model extends MY_Model {
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * 根据专辑ID获取专辑数据
     * @param int $specialId
     * @return array
     */
    public function getSpecialById($specialId){

        $query = $this->dbSports->get_where('special',array('id' => intval($specialId)));
    
        return $query->row_array();
    
    }
    
   /**
     * 根据专辑ID获取该专辑下的标签
     * @param int $specialId
     * @return string 以','号分隔的ID字符串
     */
    public function getTagsBySpecialId($specialId){

        $query = $this->dbSports->get_where('special_tag',array('special_id' => intval($specialId)));
        $tags = $query->result_array();

        $tagIds = array();
        foreach($tags as $tag){
            $tagIds[] = $tag['tag_id'];
        }

        return implode(',',$tagIds);

    }
    
    /**
     * 根据专辑ID获取该专辑下的版块
     * @param int $specialId
     * @return array
     */
    public function getBlockBySpecialId($specialId){
    
        $query = $this->dbSports->select('*')
        ->where('special_id='.$specialId)
        ->order_by('priority','DESC')
        ->get('special_block');
        
        return $query->result_array();
    }
    
    /**
     * 根据版块ID获取该版块下的内容
     * @param int $blockId
     * @return array
     */
    public function getContentByBlockId($blockId){
    
        $query = $this->dbSports->select('*')
        ->where('special_block_id='.$blockId)
        ->order_by('priority','DESC')
        ->get('special_content');
    
        return $query->result_array();
    }
    
    /**
     * 根据ID获取该类型的内容
     * @param int $blockId
     * @return array
     */
    public function getContentinfoByData($table, $id){
    
        $query = $this->dbSports->select('*')
        ->where('id='.$id)
        ->get($table);
    
        return $query->result_array();
    }
    
    /**
     * 根据版块ID获取版块数据
     * @param int $blockId
     * @return array
     */
    public function getBlockById($blockId){

        $query = $this->dbSports->get_where('special_block',array('id' => intval($blockId)));

        return $query->row_array();
    
    }
    
    /**
     * 获取专辑列表
     * @param int $offset 偏移
     * @param int $limit 每页数量
     * @param string $like title匹配
     * @return array
     */
    public function getAllSpecial($offset=0,$limit=20,$like=''){

        if ($like) {
            $query = $this->dbSports->select('*')
            ->like('title', $like)
            ->order_by('created_at','DESC')
            ->offset($offset)
            ->limit($limit)
            ->get('special');
        } else {
            $query = $this->dbSports->select('*')
            ->order_by('created_at','DESC')
            ->offset($offset)
            ->limit($limit)
            ->get('special');
        }

        return $query->result_array();

    }
    
    /**
     * 获取专辑总数
     * @return num
     */
    public function getSpecialTotal($like=''){
        if ($like) {
            $query = $this->dbSports->select("count(*) as `num`")
            ->like('title', $like)
            ->get('special');
        } else {
            $query = $this->dbSports->select("count(*) as `num`")
            ->get('special');
        }
    
        $result = $query->result_array();
        if ($result) {
            return $result[0]['num'];
        }
        //var_dump($this->dbSports->last_query(),$query->result_array(), $like,5);exit;
        return 0;
    }

}
