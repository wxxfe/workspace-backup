<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Gold_coin_quiz
 * 金豆管理
 */
class Gold_coin_quiz extends MY_Controller {

    /**
     * 映射控制器方法的路由url
     */
    private $url = array(
        'list_url' => '/interaction/quiz/gold_coin_quiz/index/',
        'detail_url' => '/interaction/quiz/gold_coin_quiz/detail/'
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

        $page_limit = $this->page_limit;

        $user_name = $this->input->get('user_name');
        $user_id = $this->input->get('user_id');

        if ($user_name) {
            $name_users_data = $this->m->getUsersIDAPI($user_name);
            if ($name_users_data AND isset($name_users_data[$user_name]) AND count($name_users_data[$user_name])) {
                $users_id = array();
                foreach ($name_users_data[$user_name] as $user) {
                    array_push($users_id, $user['id']);
                }
                $where = 'user_id in(' . implode(',', $users_id) . ')';
                $list = $this->m->getListCommon('account', $page_limit, $offset, $where);
                $page_total = $this->m->getTotal('account', $where);
            } else {
                $list = array();
                $page_total = 0;
            }
            $data['user_name'] = $user_name;
        } else if ($user_id) {
            $list = array_filter(array($this->m->getItemCommon(array('user_id' => $user_id), 'account')));
            $page_total = 1;
            $data['user_id'] = $user_id;
        } else {
            $list = $this->m->getListCommon('account', $page_limit, $offset);
            $page_total = $this->m->getTotal('account');
        }

        $data = array_merge($data, $this->_pageData($offset, $page_limit, $page_total, $data['list_url']));

        $data['list'] = $list;

        foreach ($data['list'] as &$item) {
            $item['user_name'] = $this->m->getUsersAPI($item['user_id'])[$item['user_id']];
        }
        unset($item);

        $this->load->view('interaction/quiz/gold_coin_list', $data);
    }

    /**
     * 明细
     * @param $user_id 用户ID
     * @param $user_name 用户名
     * @param $back_page_offset 返回页面的分页偏移值
     * @param int $offset 当前页面的分页偏移值
     */
    public function detail($user_id, $user_name, $back_page_offset, $offset = 0) {
        $data = $this->url;

        $data['list_url'] .= $back_page_offset;

        $data['user_id'] = $user_id;
        $data['user_name'] = urldecode($user_name);
        $data['type_title'] = array('0' => '支出', '1' => '收入');

        $page_limit = $this->page_limit;
        $where = array('user_id' => $user_id);
        $list = $this->m->getListCommon('account_detail', $page_limit, $offset, $where);
        $page_total = $this->m->getTotal('account_detail', $where);

        $page_url = $data['detail_url'] . $user_id . '/' . $user_name . '/' . $back_page_offset . '/';

        $data = array_merge($data, $this->_pageData($offset, $page_limit, $page_total, $page_url));
        $data['list'] = $list;

        $this->load->view('interaction/quiz/gold_coin_detail', $data);
    }

}