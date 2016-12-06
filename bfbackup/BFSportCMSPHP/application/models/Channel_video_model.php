<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Channel_video_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('Tag_model','TM');
    }

    public function getChannels(){

        $query = $this->dbSports->select('*')
            ->order_by('priority','DESC')
            ->get('channel_video');

        $channels = array(); 
        foreach($query->result_array() as $channel){
            $channel['tagsData'] = $this->TM->db('sports')->getTagsByFakeId($channel['tags']);
            $channels[] = $channel;
        }

        return $channels;

    }

    public function getTopVideosByFakeId($fakeId,$offset=0,$limit=0){

        $query = $this->dbSports->select('*')
            ->where('tag_id',$fakeId)
            ->where('type','video')
            ->offset($offset)
            ->limit($limit)
            ->order_by('priority','DESC')
            ->order_by('id','DESC')
            ->get('manual_priority');

        return $query->result_array();

    }

    public function getChannelById($cid){

        $query = $this->dbSports->get_where('channel_video',array('id' => intval($cid)));

        return $query->row_array();

    }

    public function getTmplByCid($cid){

        $query = $this->dbSports->select('tmpl')->get_where('channel_video',array('id' => intval($cid)));

        return $query->row_array()['tmpl'];

    }

    public function getBlocks($channelId){

        $query = $this->dbSports->order_by('priority','DESC')->get_where('tmpl_block',array('channel_video_id' => intval($channelId)));

        return $query->result_array();

    }
    
    public function getBlocksLimit($channelId, $limit=20, $offset=0) {
        $query = $this->db('sports')->select('*')
                ->from('tmpl_block')
                ->where('channel_video_id', $channelId)
                ->order_by('priority', 'DESC')
                ->limit($limit)
                ->offset($offset)
                ->get();
                
        return $query->result_array();
    }

    public function getPrograms($channelId){

        $query = $this->dbSports->order_by('priority','DESC')->get_where('tmpl_program',array('channel_video_id' => intval($channelId)));

        return $query->result_array();

    }

    public function getBlockVideos($blockId){

        $query = $this->dbSports->order_by('priority','DESC')->get_where('tmpl_block_content',array('tmpl_block_id' => intval($blockId)));

        $full = array();

        foreach($query->result_array() as $item){
            $item['info'] = $this->dbSports->get_where($item['type'],array('id' => $item['ref_id']))->row_array();
            $full[] = $item;
        }

        return $full;

    }

    public function getProgramVideos($blockId){

        $query = $this->dbSports->order_by('priority','DESC')->get_where('tmpl_program_content',array('tmpl_program_id' => intval($blockId)));

        $full = array();

        foreach($query->result_array() as $item){
            $item['info'] = $this->dbSports->get_where('program',array('id' => $item['program_id']))->row_array();
            $full[] = $item;
        }

        return $full;

    }

    public function getBlockInfo($blockId){

        $query = $this->dbSports->get_where('tmpl_block',array('id' => intval($blockId)));

        return $query->row_array();

    }

    public function getProgramInfo($blockId){

        $query = $this->dbSports->get_where('tmpl_program',array('id' => intval($blockId)));

        return $query->row_array();

    }

    public function getProgramBlockInfo($blockId){

        $query = $this->dbSports->get_where('tmpl_program',array('id' => intval($blockId)));

        return $query->row_array();

    }

    public function getContentById($cid){

        $query = $this->dbSports->get_where('tmpl_block_content',array('id' => intval($cid)));

        return $query->row_array();

    }

    public function getProgramContentById($cid){

        $query = $this->dbSports->get_where('tmpl_program_content',array('id' => intval($cid)));

        return $query->row_array();

    }

}
