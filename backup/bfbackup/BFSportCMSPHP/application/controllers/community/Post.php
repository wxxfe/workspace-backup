<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Post
 * 全部帖子
 */
class Post extends MY_Controller {
    /**
     * @var string 模块名
     */
    private $title = array('module_title' => '全部帖子', 'module_title_en' => 'All Post');

    /**
     * 映射控制器方法的路由url
     */
    private $url = array(
        'post_list_url' => '/community/post/index/',
        'post_check_url' => '/community/post/check/',
        'post_del_url' => '/community/thread/postDel/',
        'in_place_edit_url' => '/community/thread/inPlaceEdit/'
    );

    /**
     * @var int 分页每页条数
     */
    private $page_limit = 20;

    public function __construct() {
        parent::__construct();
        //载入控制器用到的模型，第二个参数是别名
        $this->load->model('community/Thread_model', 'm');
    }

    /**
     * 最后一段是控制器类名的路由url映射的方法
     * 列表页显示数据处理
     * @param int $check 是否通过审查 0 通过 1未通过
     * @param string $column 数据库中表的列名
     * @param string $order 排序
     * @param int $offset 分页偏移量
     */
    public function index($check = 0, $column = 'created_at', $order = 'DESC', $offset = 0) {
        $data = array_merge($this->title, $this->url);

        $data['in_place_edit_url'] .= 'post';

        $data['post_list_condition_url'] = $data['post_list_url'] . $check;

        $where = array('check' => $check);

        $page_limit = $this->page_limit;

        $page_total = $this->m->getTotal('post', $where);

        $page_url = $data['post_list_condition_url'] . '/' . $column . '/' . $order . '/';

        $this->_pageCheck($offset, $page_limit, $page_total, $page_url);

        $data = array_merge($data, $this->_pageData($offset, $page_limit, $page_total, $page_url));

        $list = $this->m->getPostList($page_limit, $offset, $where, $column, $order);

        $data['list'] = array();
        $data['post_ids'] = 'id in (';
        foreach ($list as $index => $item) {
            if ($index) {
                $data['post_ids'] .= ',';
            }
            $data['post_ids'] .= $item['id'];
            $item['user_name'] = $this->m->getUsersAPI($item['user_id'])[$item['user_id']];
            array_push($data['list'], $item);
        }
        $data['post_ids'] .= ')';

        $data['sort_selected'] = array($column, $order);

        $data['post_type'] = 'check';
        $data['post_main_title'] = $this->title['module_title'];
        $data['post_btn'] = '通过审核';
        $data['post_btn_url'] = $data['post_check_url'];
        $data['post_tip'] = '只通过当前页面的帖子';
        $data['post_check'] = $check;

        $this->load->view('community/thread/post_list', $data);
    }

    /**
     * 通过审核
     * 更新数据库中指定ID帖子的check列值为1
     * 完成后跳转回上一页
     */
    public function check() {
        $this->m->passPosts();
        redirect($this->input->get('redirect'));
    }
}