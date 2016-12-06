<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChannelNews extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Channel_news_model', 'CNM');
        $this->load->model('community/Thread_model', 'TRDM');
    }
    
    public function index($tagId, $type='top', $page=0) {
        $this->load->config('sports');
        $this->load->model('Tag_model','TM');
        $limit = 20;
        $news = array();
        $total = 0;
        $data = array();
        $data['type'] = $type;
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
                if($type == 'thread') {
                    $news = $this->CNM->db('board')->get_where($stype,array('id' => intval($keyword)))->result_array();
                    $news = $this->CNM->db('sports')->extendTopTag($news, $stype, $tagId);
                } else {
                    $news = $this->CNM->db('sports')->get_where($stype,array('id' => intval($keyword)))->result_array();
                    $news = $this->CNM->db('sports')->extendTopTag($news, $stype, $tagId);
                }
            } else {
                $searchResult = $this->CNM->getSearchResult($stype,$keyword,$tagId,$page,$limit);
                $total = $searchResult['total'];
                $news = $searchResult['result'];
            }
        }else{
            if($type == 'top') {
                $total = $this->CNM->db('sports')->getTotal('manual_priority',array('tag_id' => $tagId));
                $news = $this->_getTopNews($this->CNM->getTopNewsByFakeId($tagId,$page,$limit));
            } else if($type == 'thread') {
                $total = $this->TRDM->db('board')->getTotal($type.'_tag',array('tag_id' => $tagId));
                $news = $this->TRDM->getThreadByFakeId($type,$tagId,$page,$limit);
            } else {
                $total = $this->CNM->db('sports')->getTotal($type.'_tag',array('tag_id' => $tagId));
                $news = $this->CNM->getNewsByFakeId($type,$tagId,$page,$limit);
            }
        }
        $top_num = $this->CNM->db('sports')->getTotal('manual_priority',array('tag_id' => $tagId,'priority' =>2147483647));
        $data['top_num'] = $top_num;
        $data['news'] = $news;
        $data['total'] = $total;
        $data['page'] = $this->_pagination($total,$limit,'/ChannelNews/index/'.$tagId.'/'.$type.'/', array('uri_segment'=>5) );
        // $data['offset'] = ($page - 1 < 0 ? 0 : $page - 1) * $limit;
        $data['offset'] = $page;
        $this->load->view('channel/news', $data);
    }

    private function _getTopNews($tops = array()){
        $full = array();
        foreach($tops as $news){
            $type = $news['type'];
            $item = array(
                'tid' => $news['id'], // id of table `manual_priority`
                'type' => $type,
                'sort' => $news['priority']
            );
            $item['info'] = $this->CNM->getNewsInfo($type,$news['ref_id']);
            $full[] = $item;
        }
        return $full;
    }

    public function removeTag($newsId,$type,$fakeId,$currentPage,$tid=0){
        try{
            if($type == 'thread') {
                $this->TRDM->db('board')->where(array($type.'_id' => intval($newsId), 'tag_id' => intval($fakeId)))->delete($type.'_tag');
                if($tid > 0){
                    $this->TRDM->db('sports')->remove('manual_priority',intval($tid));
                }
                $this->TRDM->db('board')->set('updated_at', 'NOW()', false)->where('id', $newsId)->limit(1)->update($type);
            } else {
                $this->CNM->db('sports')->where(array($type.'_id' => intval($newsId), 'tag_id' => intval($fakeId)))->delete($type.'_tag');
                if($tid > 0){
                    $this->CNM->db('sports')->remove('manual_priority',intval($tid));
                }
                $this->CNM->db('sports')->set('updated_at', 'NOW()', false)->where('id', $newsId)->limit(1)->update($type);
            }
            echo 'success';
        }catch(Exception $e){
            echo 'fail';
        }
    }

    public function cancelTop($tid){
        try{
            $this->CNM->db('sports')->freshTopRefUpdatedAt('manual_priority', $tid);
            $this->CNM->db('sports')->remove('manual_priority',intval($tid));
            echo 'success';
        }catch(Exception $e){
            echo 'fail';
        }
    }

    public function cancelForeverTop($tid){
        try{
            $this->CNM->db('sports')->freshTopRefUpdatedAt('manual_priority', $tid);
            $this->CNM->db('sports')->set('priority', 0)->where('id', $tid)->limit(1)->update('manual_priority');
            $this->CNM->setTbSort('manual_priority', $tid, array('priority' => 1), array('tag_id' => 1000001,' priority <' => '2147483646'));
            echo 'success';
        }catch(Exception $e){
            echo 'fail';
        }
    }

    public function setTop($type, $refId, $tagId){
        try{
            $tid = $this->CNM->db('sports')->insert('manual_priority',array('type' => $type, 'tag_id' => intval($tagId), 'ref_id' => intval($refId)));
            $this->CNM->db('sports')->freshTopRefUpdatedAt('manual_priority', $tid);
            // 修改排序，新添加的默认排序第一个
            $this->CNM->setTbSort('manual_priority', $tid, array('priority' => 1), array('tag_id' => intval($tagId),' priority <' => '2147483646'));
            echo 'success';
        }catch(Exception $e){
            echo 'fail';
        }
    }

    public function setForeverTop($type, $refId, $tagId){
        try{
            $info = $this->CNM->db('sports')->get_where('manual_priority',array('id' => $type))->result_array();
            //var_dump($info[0]['priority']);exit;
           // $tid = $this->CNM->db('sports')->insert('manual_priority',array('type' => $type, 'tag_id' => intval($tagId), 'ref_id' => intval($refId)));
            $this->CNM->db('sports')->freshTopRefUpdatedAt('manual_priority', $type);
            // 修改排序，新添加的默认排序第一个
            $this->CNM->db('sports')->set('priority', 2147483647)->where('id', $type)->limit(1)->update('manual_priority');
            $this->CNM->frushForSetForeverTop('manual_priority',1000001,'priority',$info[0]['priority']);
            //$this->CNM->setTbSort('manual_priority', $tid, array('priority' => 1), array('tag_id' => intval($tagId),' priority <' => '2147483646'));
            echo 'success';
        }catch(Exception $e){
            echo 'fail';
        }
    }


    public function updateSort(){
        $data = $_POST;
        $tid = $data['pk'];
        $tag_id = $data['tagId'];
        $sort = intval($data['value']);
        $result = $this->CNM->db('sports')->setTbSort('manual_priority',$tid,array('priority' => $sort),array('tag_id'=>$tag_id,' priority <' => '2147483646'));
        echo $result ? 'success' : 'fail';
    }

    public function setTag($tagId,$type,$newsId){
        try{
            $this->CNM->db('sports')->insert($type.'_tag',array($type.'_id' => $newsId, 'tag_id' => intval($tagId)));
            $this->CNM->db('sports')->set('updated_at', 'NOW()', false)->where('id', $newsId)->limit(1)->update($type);
            echo 'success';
        }catch(Exception $e){
            echo 'fail';
        }

    }
    
}
