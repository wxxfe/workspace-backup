<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Published_quiz
 * 已发布竞猜
 */
class Published_quiz extends MY_Controller {
    /**
     * @var array 竞猜状态
     * 'begin','wait','end'
     */
    private $status_title = array('begin' => '进行中', 'wait' => '等开奖', 'end' => '已开奖');
    /**
     * 映射控制器方法的路由url
     */
    private $url = array(
        'list_url' => '/interaction/quiz/published_quiz/index/',
        'detail_url' => '/interaction/quiz/published_quiz/detail/'
    );

    /**
     * @var int 分页每页条数
     */
    private $page_limit = 20;

    public function __construct() {
        parent::__construct();
        //载入控制器用到的模型，第二个参数是别名
        $this->load->model('interaction/quiz/Quiz_model', 'm');
    }

    /**
     * 最后一段是控制器类名的路由url映射的方法
     * 列表页显示数据处理
     * 如果有搜索关键字数据，则是搜索列表
     * 否则是正常列表
     * @param int $offset 分页偏移量
     */
    public function index($offset = 0) {
        $data = $this->url;

        $data['status_title'] = $this->status_title;

        $page_limit = $this->page_limit;

        $match_id = $this->input->get('match_id');
        $subject_id = $this->input->get('subject_id');

        if ($subject_id) {
            $list = array_filter(array($this->m->getItem($subject_id)));
            $page_total = 1;
            $data['subject_id'] = $subject_id;
        } else if ($match_id) {
            $list = $this->m->getList($match_id, $page_limit, $offset);
            $page_total = $this->m->getTotal('subject', array('match_id' => $match_id));
            $data['match_id'] = $subject_id;
        } else {
            $list = $this->m->getList(0, $page_limit, $offset);
            $page_total = $this->m->getTotal('subject');
        }

        $data = array_merge($data, $this->_pageData($offset, $page_limit, $page_total, $data['list_url']));

        $data['list'] = $list;

        foreach ($data['list'] as &$item) {

            $subject_id = $item['id'];

            $item = array_merge(
                $item,
                $this->m->getSubjectStatistic($subject_id, $item['answer']),
                $this->m->getDeadlineStatus($item['deadline'], $item['status'], $subject_id)
            );

            $item['option'] = json_decode($item['option'], true);
        }
        unset($item);

        $this->load->view('interaction/quiz/list', $data);
    }

    /**
     * 竞猜明细
     * @param $subject_id
     * @param $back_page_offset 返回页面的分页偏移值
     * @param int $offset 当前页面的分页偏移值
     */
    public function detail($subject_id, $back_page_offset = 0, $offset = 0) {
        $data = array_merge($this->url, $this->m->getItem($subject_id));
        $data = array_merge(
            $data,
            $this->m->getSubjectStatistic($subject_id, $data['answer']),
            $this->m->getDeadlineStatus($data['deadline'], $data['status'], $subject_id)
        );
        $options_name = array();
        $data['option'] = json_decode($data['option'], true);
        foreach ($data['option'] as &$o_v) {
            $o_v['num'] = $this->m->getSubjectAnswerNum($subject_id, $o_v['id']);
            $o_v['bet'] = $this->m->getSubjectAnswerBet($subject_id, $o_v['id']);
            $options_name[$o_v['id']] = $o_v['name'];
        }
        unset($o_v);


        $data['status_title'] = $this->status_title;

        if ($this->input->get('redirect')) {
            $data['list_url'] = $this->input->get('redirect');
            $page_url = $data['detail_url'] . $subject_id . '/';
        } else {
            $data['list_url'] .= $back_page_offset;
            $page_url = $data['detail_url'] . $subject_id . '/' . $back_page_offset . '/';
        }

        $page_limit = $this->page_limit;

        $page_total = $data['statistic_turnouts'];

        $data = array_merge($data, $this->_pageData($offset, $page_limit, $page_total, $page_url));

        $data['list'] = array_slice($data['statistic_src'], $offset, $page_limit);

        foreach ($data['list'] as &$item) {
            $item['user_name'] = $this->m->getUsersAPI($item['user_id'])[$item['user_id']];
            $item['answer_name'] = $options_name[$item['answer']];
        }
        unset($item);

        $this->load->view('interaction/quiz/detail', $data);
    }

}