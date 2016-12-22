<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Thread_live
 * 互动直播间话题
 */
class Thread_live extends MY_Controller {

    /**
     * 映射控制器方法的路由url
     */
    private $url = array(
        'add_url' => '/community/thread/add',
        'in_place_edit_url' => '/community/thread/inPlaceEdit/',
        'edit_url' => '/community/thread/edit/',
        'post_list_url' => '/community/thread/postList/',
        'add_threads_live_url' => '/interaction/live/thread_live/addThreads/',
        'out_live_url' => '/interaction/live/thread_live/out/',
        'in_place_edit_live_url' => '/interaction/live/thread_live/inPlaceEdit/',
        'add_posts_live_url' => '/interaction/live/thread_live/addPosts/',
        'send_message_live_url' => '/interaction/live/thread_live/sendMessage/'
    );

    public function __construct() {
        parent::__construct();
        //载入控制器用到的模型，第二个参数是别名
        $this->load->model('community/Thread_model', 'm');
        $this->load->model('Match_model', 'mm');
    }

    /**
     * 最后一段是控制器类名的路由url映射的方法
     * 列表页显示数据处理
     * @param int $match_id 比赛ID
     * @param int $host_id 主持人ID
     */
    public function index($match_id, $host_id) {
        $data = $this->url;
        $data['match_id'] = $match_id;
        $data['host_id'] = $host_id;

        //比赛数据
        $match = $this->mm->getMatchById($data['match_id']);

        //赛前话题列表

        $t = $this->m->getMatchThreadList('match_thread', $match_id);
        $data['pregame_thread_list'] = $t[0];
        $ids = $t[1];

        //赛中话题列表
        if ($match['status'] == 'ongoing' || $match['status'] == 'finished') {
            $t = $this->m->getMatchThreadList('matching_thread', $match_id);
            $data['game_box_thread_list'] = $t[0];
            if ($t[1]) {
                if ($ids) {
                    $ids .= ',';
                }
                $ids .= $t[1];
            }
        }
        //赛后热帖列表
        if ($match['status'] == 'finished') {
            $data['postgame_post_list'] = $this->m->getMatchHotPostList($match_id);
            if (count($data['postgame_post_list']) == 0) {
                $this->m->addInitMatchHotPost($ids, $match_id);
                $data['postgame_post_list'] = $this->m->getMatchHotPostList($match_id);
            }
        }

        $this->load->view('interaction/live/thread', $data);
    }

    /**
     * 插入关联比赛的话题，赛前或赛中
     * @param $table 赛前或赛中表名
     * @param $match_id 比赛ID
     * @param int $host_id 主持人ID
     */
    public function addThreads($table, $match_id, $host_id) {
        $ids = array_unique(explode(',', $this->input->post('add_ids')));
        foreach ($ids as $id) {
            if (is_numeric($id) AND $this->m->getItem($id)) $this->m->addMatchThread($table, $match_id, $id, $host_id);
        }
        redirect($this->input->get('redirect'));
    }

    /**
     * 就地编辑
     * @param $table 赛前或赛中表名
     * @param $match_id 比赛ID
     */
    public function inPlaceEdit($table, $match_id) {
        $this->m->inPlaceEdit($table);
        $this->m->reorder($table, $match_id);
    }

    /**
     * 移出比赛话题
     * @param $table 赛前或赛中表名
     * @param $id
     */
    public function out($table, $id) {
        $this->m->remove($table, $id);
        redirect($this->input->get('redirect'));
    }

    /**
     * 插入关联比赛的热帖，赛后
     * @param $match_id 比赛ID
     */
    public function addPosts($match_id) {
        $ids = array_unique(explode(',', $this->input->post('add_ids')));
        foreach ($ids as $id) {
            if (is_numeric($id) AND $this->m->getPostItem($id)) $this->m->addMatchHotPost($match_id, $id);
        }
        redirect($this->input->get('redirect'));
    }

    /**
     * 发送消息，赛中话题
     * @param $id
     * @param $match_id
     * @param $host_id
     * @param $match_thread_id
     */
    public function sendMessage($id, $match_id, $host_id, $match_thread_id) {
        $this->m->sendMatchThread($id, $match_id, $host_id, $match_thread_id);
        redirect($this->input->get('redirect'));
    }
}