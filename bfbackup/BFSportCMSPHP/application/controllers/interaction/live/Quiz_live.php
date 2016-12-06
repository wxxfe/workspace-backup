<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Quiz_live
 * 互动直播间竞猜
 */
class Quiz_live extends MY_Controller {

    /**
     * 映射控制器方法的路由url
     */
    private $url = array(
        'index_url' => '/interaction/live/quiz_live/index/',
        'add_url' => '/interaction/live/quiz_live/add',
        'in_place_edit_url' => '/interaction/live/quiz_live/inPlaceEdit',
        'detail_url' => '/interaction/quiz/published_quiz/detail/'
    );

    /**
     * @var array 作为竞猜开始时间的类型
     */
    private $time_types = array('开赛前', '开赛后', '发布后');

    /**
     * @var array 竞猜状态
     * 'begin','wait','end'
     */
    private $status_title = array('begin' => '进行中', 'wait' => '等待开奖', 'end' => '竞猜明细');

    public function __construct() {
        parent::__construct();
        //载入控制器用到的模型，第二个参数是别名
        $this->load->model('interaction/quiz/Quiz_model', 'm');
        $this->load->model('Match_model', 'mm');
    }

    /**
     * 最后一段是控制器类名的路由url映射的方法
     * @param int $match_id 比赛ID
     * @param int $host_id 主持人ID
     */
    public function index($match_id, $host_id) {
        $data = $this->url;
        $data['add_url'] .= '?match_id=' . $match_id . '&host_id=' . $host_id;
        $data['in_place_edit_url'] .= '?match_id=' . $match_id . '&host_id=' . $host_id;

        $data['match_id'] = $match_id;
        //比赛数据
        $data['match_data'] = json_encode($this->mm->getMatchById($data['match_id']));

        $data['time_types'] = $this->time_types;


        $data['status_title'] = $this->status_title;
        $data['host_id'] = $host_id;

        $data['list'] = $this->m->getList($match_id);
        foreach ($data['list'] as &$item) {

            $subject_id = $item['id'];

            $item = array_merge(
                $item,
                $this->m->getSubjectStatistic($subject_id, $item['answer']),
                $this->m->getDeadlineStatus($item['deadline'], $item['status'], $subject_id)
            );

            $item['option'] = json_decode($item['option'], true);
            foreach ($item['option'] as &$o_v) {
                $o_v['num'] = $this->m->getSubjectAnswerNum($subject_id, $o_v['id']);
            }
            unset($o_v);
        }
        unset($item);

        $this->load->view('interaction/live/quiz', $data);
    }

    /**
     * 添加
     */
    public function add() {
        $this->m->add();
        redirect($this->input->get('redirect'));
    }

    /**
     * 就地编辑
     */
    public function inPlaceEdit() {
        $this->m->inPlaceEditQuiz();
    }
}