<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('community/Comment_model', 'ct');
    }

    private function _getUrl() {
        $data = array();
        $data['post_list_url'] = '/community/comment/index/';
        $data['post_check_url'] = '/community/post/check/';
        $data['post_del_url'] = '/community/comment/del/';
        $data['in_place_edit_url'] = '/community/thread/inPlaceEdit/';

        return $data;
    }

    public function index($check = 0, $column = 'created_at', $order = 'DESC', $offset = 0) {
        $data = $this->_getUrl();

        $data['in_place_edit_url'] .= 'post';
        $data['post_list_condition_url'] = $data['post_list_url'] . $check;

        $limit = 20;

        $where = array('check' => $check);

        $page_total = $this->ct->getTotal('comment', $where);

        if ($offset > 0 AND $offset >= $page_total) {
            if ($offset == $page_total) {
                $offset = $page_total - $limit;
            } else {
                $offset = floor($page_total / $limit) * $limit;
            }
            redirect($data['post_list_condition_url'] . '/' . $column . '/' . $order . '/' . $offset);
        }

        $data['page'] = $this->_pagination($page_total, $limit, $data['post_list_condition_url'] . '/' . $column . '/' . $order . '/');
        $data['page_limit'] = $limit;
        $data['page_total'] = $page_total;
        $data['page_offset'] = $offset;

        $data['sort_selected'] = array($column, $order);

        $data['list'] = array();

        $list = $this->ct->getCommentList($limit, $offset, $where, $column, $order);

        $ids = array();
        foreach ($list as $item) {
            $item['user_name'] = '';
            if ($item['owner_id']) {
                $userinfo = $this->ct->getUsersAPI($item['owner_id']);
                if ($userinfo) {
                    $item['user_name'] = $userinfo[$item['owner_id']];
                } 
            }
            $ids[] = $item['id'];
            array_push($data['list'], $item);
        }

        $ids = implode(',', $ids);
        
        $data['ids']       = $ids;
        $data['check']     = $check;
        $data['post_type'] = 'check';
        $data['post_main_title'] = '全部帖子回复';
        $data['post_btn'] = '通过审核';
        $data['post_btn_url'] = $data['post_check_url'];
        $data['post_tip'] = '只通过当前页面的帖子';
        $data['post_check'] = $check;
        $this->load->view('community/comment/list', $data);
    }

    public function checkok()
    {
        $ids = $this->input->post('ids');

        try {
            if (strpos($ids, ',')) {
                $where = ' id in ('.$ids.')';
            } else {
                $where = array('id'=>$ids);
            }
            
            $this->ct->db('board')->update('comment', array('check'=>1), $where);
            echo 'success';
        }catch(Exception $e){
            echo 'fail';
        }

    }
    
    public function del($id) {
        try {
            $comment_info = $this->ct->db('board')->get_where('comment', array('id'=>$id))->row_array();
            if ($comment_info) {
                $this->ct->db('board')->query('SET FOREIGN_KEY_CHECKS = 0');
                //删除评论
                $this->ct->db('board')->remove('comment', $id);
                
                //帖子评论数-1
                $this->ct->upPostCount($comment_info['post_id']);

                $this->ct->db('board')->query('SET FOREIGN_KEY_CHECKS = 1'); 
            }
            
            echo 'success';
        } catch(Exception $e) {
            echo 'fail';
        }
    }

}