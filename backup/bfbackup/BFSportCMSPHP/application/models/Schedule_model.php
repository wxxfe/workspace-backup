<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule_model extends MY_Model {
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * 获取比赛视频列表
     * @param int $offset 偏移
     * @param int $limit 每页数量
     * @param array $where 查询条件
     * @param string $like title匹配
     * @return array
     */
    public function getAllVideo($offset=0,$limit=20,$where=array(),$like='', $tags=''){

        if ($like) {
            $query = $this->dbSports->select('*')
            ->like('title', $like)
            ->order_by('created_at','DESC')
            ->offset($offset)
            ->limit($limit)
            ->get('video');
        } else if (isset($where['match_id'])) {
            $sql = "select v.id as id, v.image as image, v.title as title,v.created_at as created_at  from video v join video_tag t on t.video_id=v.id and v.match_id={$where['match_id']} ";
            if ($tags) {
                $sql .= " and t.tag_id in ($tags)";
            }
            
            $sql .= " group by v.id ";
            
            $query = $this->dbSports->query($sql);
        } else {
            $query = $this->dbSports->select('*')
            ->where($where)
            ->order_by('created_at','DESC')
            ->offset($offset)
            ->limit($limit)
            ->get('video');
        }

        return $query->result_array();

    }
    
    /**
     * 获取视频总数
     * @param int $offset 偏移
     * @param int $limit 每页数量
     * @param array $where 查询条件
     * @param string $like title匹配
     * @return num
     */
    public function getVideoTotal($where = array(), $like='', $tags=''){
        if ($like) {
            $query = $this->dbSports->select("count(*) as `num`")
            ->like('title', $like)
            ->get('video');
        } else if (isset($where['match_id'])) {
            $sql = "select count(distinct v.id) as `num` from video v join video_tag t on t.video_id=v.id and v.match_id={$where['match_id']} ";
            if ($tags) {
                $sql .= " and t.tag_id in ($tags)";
            }
            
            $query = $this->dbSports->query($sql);
            
        } else {
            $query = $this->dbSports->select("count(*) as `num`")
            ->where($where)
            ->get('video');
        }
    
        $result = $query->result_array();
        if ($result) {
            return $result[0]['num'];
        }
        //var_dump($this->dbSports->last_query(),$query->result_array(), $like,5);exit;
        return 0;
    }

}
