<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChannelVideo extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Channel_video_model', 'CVM');
    }
    
    public function index() {
        if($_POST) $this->_add($_POST);
        $channels = $this->CVM->getChannels();
        $data['channels'] = $channels;
        $data['tmpls'] = array('block' => '多视频模板', 'program' => '节目模板', 'single' => '单视频模板');
        $this->load->view('channel_video/channel', $data);
    }

    public function channelEdit($id){

        if($_POST){
            $data = $_POST;
            $this->CVM->db('sports')->update('channel_video',$data,array('id' => intval($id)));
            redirect(base_url('/ChannelVideo/index'));
            exit;
        }

        $data = array();
        $data['channel'] = $this->CVM->getChannelById($id);
        $data['currentTag'] = array();
        if($data['channel']['tmpl'] == 'single'){
            $this->load->model('Tag_model', 'TM');
            $data['currentTag'] = $this->TM->db('sports')->getTagsByFakeId($data['channel']['tags'])[0];
        }
        $this->load->view('channel_video/channel_edit', $data);
    }

    public function updateField($table){
        $data = $_POST;
        $new = array($data['name'] => $data['value']);
        if($data['name'] == 'priority'){
            $result = $this->CVM->db('sports')->setTbSort($table,intval($data['pk']),array('priority' => intval($data['value'])));
        }else{
            $result = $this->CVM->db('sports')->update($table,$new,array('id' => intval($data['pk'])));
        }
        echo $result ? 'success' : 'fail';
    }

    private function _add($channelData){
        $channel = $channelData;
        $new = array(
            'name' => $channel['name'],
            'tmpl' => $channel['tmpl'],
            'tags' => $channel['tags'],
            'visible' => intval($channel['visible'])
        );
        $cid = $this->CVM->db('sports')->insert('channel_video',$new);
        if(!empty($channel['priority'])){
            $this->CVM->db('sports')->setTbSort('channel_video',$cid,array('priority' => intval($channel['priority'])));
        }
        redirect(base_url('/ChannelVideo/index'));
    }

    public function channel($cid, $type='list', $offset=0){

        $tmpl = $this->CVM->getTmplByCid($cid);

        switch($tmpl){
            case 'block':
                $this->_showBlock($cid, $offset);
                break;
            case 'single':
                $args = func_get_args();
                $this->_showSingle($args);
                break;
            case 'program':
                $this->_showProgram($cid);
                break;
        }

    }

    public function block($blockId){

        $this->load->config('sports');

        $blockVideos = $this->CVM->getBlockVideos($blockId);
        $data['block'] = $this->CVM->getBlockInfo($blockId);
        $data['videos'] = $blockVideos;

        $this->load->view('channel_video/block/block_video_list',$data);
    }

    public function program($blockId){

        $this->load->config('sports');

        $blockVideos = $this->CVM->getProgramVideos($blockId);
        $data['block'] = $this->CVM->getProgramBlockInfo($blockId);
        $data['videos'] = $blockVideos;

        $this->load->view('channel_video/program/block_video_list',$data);
    }

    public function addVideoToBlock($blockId){
        if($_POST){
            $new = array(
                'tmpl_block_id' => intval($blockId),
                'title' => $_POST['title'],
                'image' => $_POST['poster'],
                'large_image' => $_POST['cover'],
                'type' => $_POST['type'],
                'visible' => $_POST['visible'],
                'ref_id' => $_POST['ref_id']
            );
            $bid = $this->CVM->db('sports')->insert('tmpl_block_content',$new);
            if(!empty($_POST['priority'])){
                $this->CVM->db('sports')->setTbSort('tmpl_block_content',$bid,array('priority' => intval($_POST['priority'])));
            }
            redirect(base_url('/ChannelVideo/block/'.$blockId));
            exit;
        }
        $data = array();
        $data['block'] = $this->CVM->getBlockInfo($blockId);
        $this->load->view('channel_video/block/block_add',$data);
    }

    public function addProgramToBlock($blockId){
        if($_POST){
            $new = array(
                'tmpl_program_id' => intval($blockId),
                'title' => $_POST['title'],
                'image' => $_POST['poster'],
                'large_image' => $_POST['cover'],
                'visible' => $_POST['visible'],
                'program_id' => $_POST['program_id']
            );
            $bid = $this->CVM->db('sports')->insert('tmpl_program_content',$new);
            if(!empty($_POST['priority'])){
                $this->CVM->db('sports')->setTbSort('tmpl_program_content',$bid,array('priority' => intval($_POST['priority'])));
            }
            redirect(base_url('/ChannelVideo/program/'.$blockId));
            exit;
        }
        $data = array();
        $data['block'] = $this->CVM->getProgramInfo($blockId);
        $this->load->view('channel_video/program/block_add',$data);
    }

    public function editVideoFromBlock($contentId){
        $data = array();
        $data['content'] = $this->CVM->getContentById($contentId);
        if($_POST){
            $new = array(
                'title' => $_POST['title'],
                'image' => $_POST['poster'],
                'large_image' => $_POST['cover'],
                'type' => $_POST['type'],
                'visible' => $_POST['visible'],
                'ref_id' => $_POST['ref_id']
            );
            $this->CVM->db('sports')->update('tmpl_block_content',$new,array('id' => intval($contentId)));
            redirect(base_url('/ChannelVideo/block/'.$data['content']['tmpl_block_id']));
            exit;
        }
        $this->load->view('channel_video/block/block_edit',$data);
    }

    public function editProgramFromBlock($contentId){
        $data = array();
        $data['content'] = $this->CVM->getProgramContentById($contentId);
        if($_POST){
            $new = array(
                'title' => $_POST['title'],
                'image' => $_POST['poster'],
                'large_image' => $_POST['cover'],
                'visible' => $_POST['visible'],
                'program_id' => $_POST['program_id']
            );
            $this->CVM->db('sports')->update('tmpl_program_content',$new,array('id' => intval($contentId)));
            redirect(base_url('/ChannelVideo/program/'.$data['content']['tmpl_program_id']));
            exit;
        }
        $this->load->view('channel_video/program/block_edit',$data);
    }

    private function _showBlock($cid, $offset){

        if($_POST) $this->_addBlock($_POST,$cid);

        $channelInfo = $this->CVM->getChannelById($cid);
        $total = $this->CVM->getTotal('tmpl_block', array('channel_video_id'=>$cid));
        $data['channelInfo'] = $channelInfo;
        // 分页，每页20条
        $limit = 20;
        $data['blocks'] = $this->CVM->getBlocksLimit($cid, $limit, $offset);
        $data['page'] = $this->_pagination($total, $limit, "/channelVideo/channel/{$cid}/list/");
        $data['offset'] = $offset;

        $this->load->view('channel_video/block/block_list',$data);
    }

    private function _showProgram($cid){

        if($_POST) $this->_addProgramBlock($_POST,$cid);

        $channelInfo = $this->CVM->getChannelById($cid);
        $data['channelInfo'] = $channelInfo;
        $data['blocks'] = $this->CVM->getPrograms($cid);

        $this->load->view('channel_video/program/block_list',$data);
    }

    private function _showSingle($args){
        $cid = $args[0];
        $channelInfo = $this->CVM->getChannelById($cid);
        $tagId = $channelInfo['tags'];
        $type = $args[1];
        $page = isset($args[2]) ? $args[2] : 0;
        $this->load->config('sports');
        $this->load->model('Tag_model','TM');
        $this->load->model('Channel_news_model','CNM');
        $limit = 20;
        $news = array();
        $total = 0;
        $data = array();
        $data['channelInfo'] = $channelInfo;
        $data['type'] = $type;
        $data['channelId'] = $cid;
        $data['tagId'] = $tagId;
        $data['tag'] = $this->TM->db('sports')->getTagsByFakeId($tagId)[0]['name'];
        $data['keyword'] = '';
        $data['stype'] = '';

        if(isset($_GET['keyword'])){
            $data['type'] = 'search';
            $keyword = $_GET['keyword'];
            $stype = $_GET['stype'];
            $data['keyword'] = $keyword;
            $data['stype'] = $stype;
            if (is_numeric($keyword)) {
                $total = 1;
                $news = $this->CVM->db('sports')->get_where($stype,array('id' => intval($keyword)))->result_array();
            } else {
                $searchResult = $this->CNM->getSearchResult($stype,$keyword,$tagId,$page,$limit);
                $total = $searchResult['total'];
                $news = $searchResult['result'];
            }
        }else{
            if($type == 'top'){
                $total = $this->CVM->db('sports')->getTotal('manual_priority',array('tag_id' => $tagId,'type' => 'video'));
                $news = $this->_getTopNews($this->CVM->getTopVideosByFakeId($tagId,$page,$limit));
            }else{
                $total = $this->CVM->db('sports')->getTotal($type.'_tag',array('tag_id' => $tagId));
                $news = $this->CNM->getNewsByFakeId($type,$tagId,$page,$limit);
            }
        }
        $data['news'] = $news;
        $data['total'] = $total;
        $data['page'] = $this->_pagination($total,$limit,'/ChannelVideo/channel/'.$cid.'/'.$type.'/');
        $data['offset'] = ($page - 1 < 0 ? 0 : $page - 1) * $limit;
        $this->load->view('channel_video/single/video', $data);
    }

    private function _getTopNews($tops = array()){
        $full = array();
        foreach($tops as $news){
            $type = $news['type'];
            $item = array(
                'tid' => $news['id'],
                'type' => $type,
                'sort' => $news['priority']
            );
            $item['info'] = $this->CNM->getNewsInfo($type,$news['ref_id']);
            $full[] = $item;
        }
        return $full;
    }

    private function _addBlock($data,$channelId){

        $new = array(
            'channel_video_id' => intval($channelId),
            'title' => $data['title'],
            'visible' => $data['visible']
        );

        $bid = $this->CVM->db('sports')->insert('tmpl_block',$new);
        if(!empty($data['priority'])){
            $this->CVM->db('sports')->setTbSort('tmpl_block',$bid,array('priority' => intval($data['priority'])));
        }
        redirect(base_url('/ChannelVideo/channel/'.$channelId));
        exit;

    }

    private function _addProgramBlock($data,$channelId){

        $new = array(
            'channel_video_id' => intval($channelId),
            'title' => $data['title'],
            'visible' => $data['visible']
        );

        $bid = $this->CVM->db('sports')->insert('tmpl_program',$new);
        if(!empty($data['priority'])){
            $this->CVM->db('sports')->setTbSort('tmpl_program',$bid,array('priority' => intval($data['priority'])));
        }
        redirect(base_url('/ChannelVideo/channel/'.$channelId));
        exit;

    }

    public function remove($table,$id){
        try{
            $this->CVM->db('sports')->remove($table,$id);
            echo 'success';
        }catch(Exception $e){
            echo 'fail';
        }
    }

}
