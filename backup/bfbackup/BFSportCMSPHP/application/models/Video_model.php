<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video_model extends MY_Model {
    const POWER_ACCESS = 1;
    const POWER_INSERT = 2;
    const POWER_MODIFY = 2;
    const POWER_DELETE = 4;
    
    public function __construct() {
        parent::__construct();
        $this->load->model('search_model', 'SM');
    }
    
    /**
     * 待编辑视频：没有打tag标签的视频
     * 
     * @param array  $where
     * @param intval $limit
     * @param intval $offset
     * @return array $result
     */
    public function getPendingList($where, $limit, $offset) {
        $this->db('sports')->select('video.*,video_extra.box_cid AS box_cid,video_extra.box_vid AS box_vid')
            ->from('video')
            ->join('video_extra', 'video_extra.video_id=video.id', 'left')
            ->join('video_tag as vt', 'video.id=vt.video_id', 'left')
            ->where('`vt`.`tag_id` IS NULL')
            ->group_by('id');
        
        if(!empty($where)) {
            foreach ($where as $k=>$v) {
                $this->db('sports')->where('video.'.$k, $v);
            }
        }
        $query = $this->db('sports')->order_by('video.id', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();
        
        $result = array();
        foreach ($query->result_array() as $item) {
            $item['image_url'] = getImageUrl($item['image']);
            $item['duration'] = gmstrftime('%H:%M:%S', intval($item['duration']/1000));
            // 暴风站点，且没有box_vid，则需要去在线接口获取box_vid
            if ($item['site'] == 'bfonline' && empty($item['box_vid'])) {
                $item['need_bfonline_vid'] = 1;
                $item['box_vid'] = '';
            } else {
                $item['need_bfonline_vid'] = 0;
            }
            
            $result[] = $item;
        }
        return $result;
    }
    
    public function getPendingTotal($where) {
        $this->db('sports')->select('video.*')
            ->from('video')
            ->join('video_tag', 'video.id=video_tag.video_id', 'left')
            ->where('`video_tag`.`tag_id` is null');
        
        if(!empty($where)) {
            foreach ($where as $k=>$v) {
                $this->db('sports')->where('video.'.$k, $v);
            }
        }
        
        $query = $this->db('sports')->count_all_results();
        
        return $query;
    }
    
    public function getList($where, $limit, $offset) {
        $this->db('sports')->select('video.*,video_extra.box_cid AS box_cid,video_extra.box_vid AS box_vid')
            ->from('video')
            ->join('video_extra', 'video_extra.video_id=video.id', 'left')
            ->join('video_tag as vt', 'video.id=vt.video_id', 'left')
            ->where('`vt`.`tag_id` IS NOT NULL')
            ->group_by('id');
        
        if(!empty($where)) {
            foreach ($where as $k=>$v) {
                $this->db('sports')->where('video.'.$k, $v);
            }
        }
        $query = $this->db('sports')
            ->order_by('id', 'DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();
        
        $result = array();
        foreach ($query->result_array() as $item) {
            $item['image_url'] = getImageUrl($item['image']);
            $item['duration'] = gmstrftime('%H:%M:%S', intval($item['duration']));
            // 暴风站点，且没有box_vid，则需要去在线接口获取box_vid
            if ($item['site'] == 'bfonline' && empty($item['box_vid'])) {
                $item['need_bfonline_vid'] = 1;
                $item['box_vid'] = '';
            } else {
                $item['need_bfonline_vid'] = 0;
            }
            $result[] = $item;
        }
        return $result;
    }
    
    public function getVideosTotal($where) {
        $this->db('sports')->select('video.*,video_extra.box_cid AS box_cid,video_extra.box_vid AS box_vid')
            ->from('video')
            ->join('video_extra', 'video_extra.video_id=video.id', 'left')
            ->join('(SELECT video_id, max(tag_id) AS tag_id FROM video_tag GROUP BY video_id) as vt', 'video.id=vt.video_id', 'left')
            ->where('`vt`.`tag_id` is not null');
        
        if(!empty($where)) {
            foreach ($where as $k=>$v) {
                $this->db('sports')->where('video.'.$k, $v);
            }
        }
        $query = $this->db('sports')->count_all_results();
        
        return $query;
    }
    
    public function searchList($key_word, $limit, $offset) {
        $result = $this->SM->itemsQuery('video', $key_word, $offset, $limit);
        
        $total = $result['total'];
        $list  = $result['result'];
        
        $video_list = array();
        foreach ($list as $item) {
            $item['image_url'] = getImageUrl($item['image']);
            $item['duration'] = gmstrftime('%H:%M:%S', intval($item['duration']));
            $video_list[] = $item;
        }
        return array('total'=>$result['total'], 'result' => $video_list);
    }
    
    public function getPendingInfo($id) {
        $query = $this->dbSports->get_where('pending_video', array('id'=>$id), 1);
        
        /*
        $query = $this->db('sports')->select('video.*,video_extra.box_cid AS box_cid,video_extra.box_vid AS vid')
            ->from('video')
            ->join('video_extra', 'video.id=video_extra.video_id', 'LEFT')
            ->where('video.id', $id)
            ->limit(1)
            ->get();
         */
        
        $result = array();
        $row = $query->row_array();
        $row_data = json_decode($row['data'], true);
        $item = array_merge($row, $row_data);
        $item['image_url'] = getImageUrl($item['image']);
        $item['duration'] = gmstrftime('%H:%M:%S', intval($item['duration']/1000));
        $result = $item;
        return $result;
    }
    
    public function getVideoInfo($id) {
        $query = $this->dbSports->get_where('video', array('id'=>$id), 1);
        $item = $query->row_array();
        $result = array();
        if($item){
            $item['image_url'] = getImageUrl($item['image']);
            $item['duration'] = gmstrftime('%H:%M:%S', intval($item['duration']));
            $result = $item;
        }
        return $result;
    }
    
    public function getVideoTags($id) {
        $query = $this->dbSports->get_where('video_tag', array('video_id'=>$id));
        $result = array();
        foreach ($query->result_array() as $item) {
            $result[] = $item['tag_id'];
        }
        return $result;
    }
    
    public function addVideo($video_data) {
        return $this->dbSports->insert('video', $video_data);
    }
    
    public function setMatchRelate($video_id, $match_id) {
        if ($match_id == 0) { // 删除关联比赛关系
            $this->db('sports')->delete('match_related', array('type'=>'video', 'ref_id'=>$video_id));
        } else { // 设置关联比赛关系
            $query = $this->db('sports')->select('*')
            ->where('match_id', $match_id)
            ->where('type', 'video')
            ->where('ref_id', $video_id)
            ->get('match_related');
            
            $info = $query->row_array();
            if (empty($info)) {
                $data = array(
                        'match_id' => $match_id,
                        'type' => 'video',
                        'ref_id' => $video_id,
                );
                $this->db('sports')->insert('match_related', $data);
            }
        }
        
        return TRUE;
    }
    
    public function getMatchRelate($video_id) {
        $query = $this->db('sports')->select('match_id')
            ->where('type', 'video')
            ->where('ref_id', $video_id)
            ->get('match_related');
        
        return $query->row_array();
    }
    
    public function updateVideo($id, $data) {
        return $this->dbSports->update('video', $data, array('id'=>$id));
    }
    
    public function deletePendingVideo($id) {
        return $this->dbSports->remove('pending_video', $id);
    }
    
    public function addVideoTag($video_id, $tag_id) {
        $data = array('video_id' => $video_id, 'tag_id' => $tag_id);
        return $this->dbSports->insert('video_tag', $data);
    }
    
    public function setVideoTag($video_id, $tag_ids) {
        $orig_tag_ids = $this->db('sports')->get_where('video_tag', array('video_id'=>$video_id));
        $delete_batch_ids = array();
        foreach ($orig_tag_ids->result_array() as $item) {
            if (!in_array($item['tag_id'], $tag_ids)) {
                array_push($delete_batch_ids, $item['id']);
            } else {
                $key = array_search($item['tag_id'], $tag_ids);
                unset($tag_ids[$key]);
            }
        }
        
        if (!empty($delete_batch_ids)) {
            $this->remove_batch('video_tag', $delete_batch_ids);
        }
        
        if (!empty($tag_ids)) {
            $insert_batch_data = array();
            foreach ($tag_ids as $tag_id) {
                if ($tag_id > 0) {
                    $insert_batch_data[] = array('video_id' => $video_id, 'tag_id'=> $tag_id);
                }
            }
            if (!empty($insert_batch_data)) {
                $this->insert_batch('video_tag', $insert_batch_data);
            }
        }
    }
    
    public function getSites() {
        $query = $this->dbSports->get('site');
        $result = array();
        foreach ($query->result_array() as $row) {
            $item = array(
                'id' => $row['id'],
                'site' => $row['site'],
            );
            $result[] = $item;
        }
        return $result;
    }

    public function getListJoinExtra($where, $like=array(), $limit, $offset, $type='') {
        $this->dbSports->select('video.*,video_extra.video_id,video_extra.box_cid,video_extra.box_vid');
        $this->dbSports->from('video');
        $this->dbSports->join('video_extra', 'video.id=video_extra.video_id', 'left');
        if (!empty($where))
            $this->dbSports->where($where);
        if(!empty($like))
            $this->dbSports->like($like);
        $this->dbSports->order_by('id', 'DESC');
        $this->dbSports->limit($limit, $offset);
        $query = $this->dbSports->get();

        $result = array();
        foreach ($query->result_array() as $item) {
            $item['image_url'] = getImageUrl($item['image']);
            $item['duration'] = gmstrftime('%H:%M:%S', intval($item['duration']));
            $result[] = $item;
        }
        return $result;
    }

    public function getTotalJoinExtra($where, $like=array()){
        $this->dbSports->from('video');
        $this->dbSports->join('video_extra', 'video.id=video_extra.video_id', 'left');
        if($where)
            $this->dbSports->where($where);
        if($like)
            $this->dbSports->like($like);

        return $this->dbSports->count_all_results();
    }

    public function updateVideoExtra($update_data, $condition){
        return $this->dbsports->update('video_extra', $update_data, $condition);
    }

    public function updateVideoWithCondition($update_data, $condition) {
        $result = 0;
        if(empty($condition) || empty($update_data))return $result;
        $result = $this->dbSports->update('video', $update_data, $condition);
        return $result;
    }

    /**
     * 调用在线接口获取vid
     * @param $box_cid
     * @return mix
     */
    public function getVidRemote($box_cid){
        $url = ONLINE_SYNC_URL_HOST . "/PlayResource/video/sport-get-vid";
        $response = send_post($url, array("cid"=>$box_cid));
        $response_array = json_decode($response, true);
        $vid = isset($response_array['vid']) ? intval($response_array['vid']) : '';
        return $vid;
    }
    
    /**
     * 获取视频的相关视频
     */
    public function getRelated($id) {
        $query =$this->db('sports')->select('video.*, video_extra.box_cid AS box_cid, 
                video_extra.box_vid AS box_vid, video_related.id AS relate_id')
            ->from('video_related')
            ->join('video', 'video.id=video_related.ref_id', 'left')
            ->join('video_extra', 'video_extra.video_id=video_related.video_id', 'left')
            ->where('video_related.video_id', $id)
            ->order_by('video_related.priority', 'DESC')
            ->get();
        
        $result = array();
        foreach ($query->result_array() as $item) {
            $item['image_url'] = getImageUrl($item['image']);
            $item['duration'] = gmstrftime('%H:%M:%S', intval($item['duration']));
            // 暴风站点，且没有box_vid，则需要去在线接口获取box_vid
            if ($item['site'] == 'bfonline' && empty($item['box_vid'])) {
                $item['need_bfonline_vid'] = 1;
                $item['box_vid'] = '';
            } else {
                $item['need_bfonline_vid'] = 0;
            }
            $result[] = $item;
        }
        return $result;
    }
}
