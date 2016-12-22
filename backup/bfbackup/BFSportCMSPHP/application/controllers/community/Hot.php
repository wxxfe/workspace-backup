<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hot extends MY_Controller {
    public function __construct() {
        parent::__construct();
               
        $this->load->model('community/Thread_model', 'tm');
    }

    private function _getUrl() {
        $data = array();
        $data['post_list_url'] = '/community/hot/index/';
        $data['post_del_url'] = '/community/thread/postDel/';
        $data['in_place_edit_url'] = '/community/thread/inPlaceEdit/';

        return $data;
    }

    public function index($column = 'created_at', $order = 'DESC', $offset = 0) {
        $data = $this->_getUrl();
        $data['in_place_edit_url'] .= 'post';
        $data['post_list_condition_url'] = '/community/hot/index';
        
        $limit = 20;
        /*$where = array();
        $ids   = array();
        
        $hot_data = $this->gethotdata();
        $hot_data = $hot_data ? $hot_data : array();
        foreach ($hot_data as $item) {
            $ids[] = $item['id'];
        }
        
        $ids = implode(',', $ids);
        if ($ids) {
            $where = 'id in ('.$ids.')';
        }*/
        //var_dump($hot_data, '11');exit;
        
        $page_total = $this->tm->db('board')->getTotal('post', array('visible'=>1, 'featured'=>1));
        //$page_total = count($hot_data);
        
        if ($offset > 0 AND $offset >= $page_total) {
            if ($offset == $page_total) {
                $offset = $page_total - $limit;
            } else {
                $offset = floor($page_total / $limit) * $limit;
            }
            redirect($data['post_list_condition_url'] . $column . '/' . $order . '/' . $offset);
        }
        
        $data['page']          = $this->_pagination($page_total, $limit, $data['post_list_condition_url'] .'/'. $column . '/' . $order . '/');
        $data['page_limit']    = $limit;
        $data['page_total']    = $page_total;
        $data['page_offset']   = $offset; 
        $data['sort_selected'] = array($column, $order);
        $data['list']          = array();
        
        $list = $this->tm->db('board')->query("select *, extra+likes+comment_count*5+unix_timestamp(created_at)/3600-reports as hot_score from post where visible=1 and featured=1 order by hot_score desc,$column $order limit $offset,$limit")->result_array();
        //var_dump($this->tm->db('board')->last_query());
        //$list = $this->tm->getPostList($limit, $offset, $where, $column, $order);
        
        foreach ($list as $item) {
            $item['user_name'] = '';
            
            $user_info = $this->tm->getUsersAPI($item['user_id']);
            if ($user_info) {
                $item['user_name'] = $user_info[$item['user_id']];
            }
            
            array_push($data['list'], $item);
        }
        //var_dump($list);exit;
        $data['post_type']       = 'hot';
        $data['post_main_title'] = '热门帖子';
        
        $this->load->view('community/thread/post_list', $data);
    }

    private function gethotdata()
    {
        $hotdata = array();
        $result  = json_decode(send_get(API_GET_HOT_POST), true);
        //var_dump($result);exit;
        if ($result && 'OK' == $result['message']) {
            $hotdata = $result['data']['body'];
        }
        
        return $hotdata;
    }

}