<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends MY_Model {
    
    public function getList($platf, $limit, $offset) {
        $query = $this->db('kungfu')->select('*')
                ->from('notification')
                ->where('platf', $platf)
                ->order_by('id', 'desc')
                ->limit($limit)
                ->offset($offset)
                ->get();
        
        return $query->result_array();
    }
    
    public function getInfo($id) {
        $query = $this->db('kungfu')->select('*')
            ->from('notification')
            ->where('id', $id)
            ->get();
        
        return $query->row_array();
    }
    
    public function addInfo($data) {
        return $this->db('kungfu')->insert('notification', $data);
    }
    
    public function updateInfo($id, $data) {
        return $this->db('kungfu')->update('notification', $data, array('id'=>$id));
    }
    
    public function getRelInfo($type, $ref_id) {
        $query = $this->db('sports')->select('*')
                ->from($type)
                ->where('id', $ref_id)
                ->get();
        
        return $query->row_array();
    }
    
    public function createPushData($id) {
        $notification = $this->getInfo($id);
        
        if (in_array($notification['type'], array('video', 'news', 'program'))) {
            $rel_content = $this->getRelInfo($notification['type'], $notification['ref_id']);
            if ($notification['type'] == 'video') {
                $rel_info = array(
                    'id' => intval($notification['ref_id']),
                    'title' => $rel_content['title'],
                    'site'  => $rel_content['site'],
                    'image' => getImageUrl($rel_content['image']),
                    'play_url' => $rel_content['play_url'],
                    'play_code' => $rel_content['play_code'],
                    'play_code2' => $rel_content['play_code2'],
                    'isvr'  => $rel_content['isvr'],
                    'publishTm' => strtotime($rel_content['publish_tm'])
                );
            } else if ($notification['type'] == 'news') {
                $rel_info = array(
                    'id' => intval($notification['ref_id']),
                    'title' => $rel_content['title'],
                    'image' => getImageUrl($rel_content['image']),
                    'large_image' => !empty($rel_content['large_image']) ? 
                        getImageUrl($rel_content['large_image']) : "",
                    'publishTm' => strtotime($rel_content['publish_tm'])
                );
            } else if ($notification['type'] == 'program') {
                $rel_info = array(
                    'programId' => intval($notification['ref_id']),
                    'title' => $rel_content['title'],
                    'publishTm' => strtotime($rel_content['publish_tm']),
                );
            }
        } else if ($notification['type'] == 'match') {
            $rel_info = array(
                'matchId' => intval($notification['ref_id'])
            );
        } else if ($notification['type'] == 'h5') {
            $rel_info = array('url' => $notification['url']);
        }
        
        $result = array(
            'type' => $notification['type'],
            'title'=> $notification['title'],
            'desc' => $notification['desc'],
            'data' => $rel_info
        );
        
        return json_encode($result);
    }
}