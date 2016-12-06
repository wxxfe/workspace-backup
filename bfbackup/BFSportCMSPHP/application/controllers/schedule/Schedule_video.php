<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//赛程视频列表
class Schedule_video extends MY_Controller {
    
    public function __construct() {
        parent::__construct();

        $this->load->model('Tag_model', 'TG');
        $this->load->model('Schedule_model', 'SC');
    }
    
    public function index($match_id = 0, $offset = 0) {
        
        $keyword = $this->input->get('keyword');
        
        $limit = 20;
        $tags  = '';
        
        //获取标签为集锦和回放
        $this->TG->db('sports')->where('alias', 'highlight');
        $this->TG->db('sports')->or_where('alias', 'replay');
        $query = $this->TG->db('sports')->get('tag')->result_array();

        $tag_array = array();
        foreach ($query as $k=>$v) {
            $nid = $this->TG->db('sports')->makeFakeId('none', $v['id']);
            $tag_array[$nid] = $v['alias'];
        }
        
        if ($tag_array) {
            $tags = implode(',', array_keys($tag_array));
        }
        
        $where = array();
        $like  = '';
        
        if (is_numeric($keyword)) {
            $where = array('id'=>intval($keyword));
        } elseif ($keyword) {
            $like = $keyword;
        } else {
            $where = array(
                'match_id' => $match_id
            );
        }

        //echo "<pre>";
        $total = $this->SC->getVideoTotal($where, $like, $tags);
        $list  = array();
        if ($total) {
            $list = $this->SC->getAllVideo($offset, $limit, $where, $like, $tags);
            foreach ($list as $k=>$v) {
                $list[$k]['tag'] = '';
                $list[$k]['tag_name'] = '';
                $tag = $this->SC->get_where('video_tag', array('video_id'=>$v['id']))->result_array();
                foreach ($tag as $k1=>$v1) {
                    if (isset($tag_array[$v1['tag_id']])) {
                        $list[$k]['tag'] = $v1['tag_id'];
                        $list[$k]['tag_name'] = $tag_array[$v1['tag_id']];
                        break;
                    }
                }
            }
            
        }
        

        $data = array(
            'total' => $total,
            'list'  => $list,
            'page'  => $this->_pagination($total,$limit,'/schedule/schedule_video/index/'.$match_id.'/'),
            'match_id' => $match_id,
            'keyword'  => $keyword,
            'tag_array' => $tag_array
        );

        $this->load->view('schedule/video', $data);
    }
    
    public function add()
    {
        $id = $this->input->post('id');
        $mid = $this->input->post('mid');
        $tag = $this->input->post('tag');

        if (!$id || !$mid || !$tag) {
            echo json_encode(array('status'=>1, 'info'=>'data error'));
            return false;
        }
        
        try{
            $this->SC->db('sports')->update('video', array('match_id'=>$mid), array('id'=>$id));
            $this->SC->db('sports')->insert('video_tag', array('video_id'=>$id,'tag_id'=>$tag));
            echo json_encode(array('status'=>0));
        }
        catch(Exception $e){
            echo json_encode(array('status'=>1, 'info'=>'add error'));
        }
        
    }

    public function delete()
    {
        $id = $this->input->post('id');
        $mid = $this->input->post('mid');
        $tag = $this->input->post('tag');

        if (!$id || !$mid || !$tag) {
            echo json_encode(array('status'=>1, 'info'=>'data error'));
            return false;
        }
        
        try{
            $this->SC->db('sports')->query('set foreign_key_checks=0');
            $this->SC->db('sports')->update('video', array('match_id'=>0), array('id'=>$id));
            $this->SC->db('sports')->where('video_id', $id)->where('tag_id', $tag)->delete('video_tag');
            $this->SC->db('sports')->query('set foreign_key_checks=1');
            echo json_encode(array('status'=>0));
        }
        catch(Exception $e){
            echo json_encode(array('status'=>1, 'info'=>'add error'));
        }
        
    }
}