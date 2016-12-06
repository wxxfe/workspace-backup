<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Thread
 * 话题和话题的帖子
 */
class Thread extends MY_Controller {
    /**
     * @var string 模块名
     */
    private $title = array(
        'module_title' => '话题', 'module_title_en' => 'Thread',
        'module_post_title' => '帖子', 'module_post_title_en' => 'Post'
    );

    /**
     * 映射控制器方法的路由url
     */
    private $url = array(
        'list_url' => '/community/thread/index/',
        'add_url' => '/community/thread/add',
        'in_place_edit_url' => '/community/thread/inPlaceEdit/',
        'edit_url' => '/community/thread/edit/',
        'del_url' => '/community/thread/del/',
        'post_list_url' => '/community/thread/postList/',
        'post_add_url' => '/community/thread/postAdd/',
        'post_del_url' => '/community/thread/postDel/'
    );

    /**
     * @var int 分页每页条数
     */
    private $page_limit = 20;

    private $default_images = array('71e44f3ad82840add8b2d11f57dbd21e',
        'aa17a0ef564521190e2a85aa8683f443',
        '0254803aff79861fe1b05973d9c8f742',
        'e2b785d76f698abf2b1b5e816f067bca',
        '9ea0b14417f99492d2193ff7c80a7d9c',
        '990f575ca88e398913ecd99ffe0fb586',
        'e2852bd4e6e88c2046daa8b3b8c90445',
        '131a67ffca9f3bf46d203990408c6fc6',
        'f4929c9238448fc3308159f98937b689',
        '0230899a8785b2f15fc5901c97d54d0e',
        '28361afa17ec192784f43091e44c0be9',
        'b5786a6ccea2b27671afa8373e9b3043',
        '7c128aaee571394c243e516b7f938367',
        '4aceb32b509986e8617f20c2aead2ce2',
        '6bcebeba3cbbc8b5eb810370a2d66e7f',
        'c7b9fd7fe05922b03b9717d50c40c89b',
        '992d6d417ad087df6fb5ed7798081c59',
        'db03a6bd346110bfbcf49ac6c2cc41b4',
        '77688d184f72584ae60b02613b01f492',
        '55d793e168089783fbd3b4b17fe51f14',
        '7ba4e2fd45bd1f607e3ce02481d4d187');

    public function __construct() {
        parent::__construct();
        //载入控制器用到的模型，第二个参数是别名
        $this->load->model('community/Thread_model', 'm');
    }

    /**
     * 最后一段是控制器类名的路由url映射的方法
     * 列表页显示数据处理
     * @param int $offset 分页偏移量
     */
    public function index($offset = 0) {
        $data = array_merge($this->title, $this->url);

        $page_limit = $this->page_limit;

        $page_total = $this->m->getTotal('thread');

        $page_url = $data['list_url'];

        $this->_pageCheck($offset, $page_limit, $page_total, $page_url);

        $data = array_merge($data, $this->_pageData($offset, $page_limit, $page_total, $page_url));

        $list = $this->m->getList($page_limit, $offset);

        $data['list'] = array();

        foreach ($list as $item) {
            $item['user_name'] = $this->m->getUsersAPI($item['user_id'])[$item['user_id']];
            array_push($data['list'], $item);
        }

        $this->load->view('community/thread/list', $data);
    }

    /**
     * 添加
     */
    public function add() {
        //如果get数据没有redirect，则重定向到话题列表页
        $redirect = $this->input->get('redirect');
        if (empty($redirect)) {
            $redirect = $this->url['list_url'];
        }
        /*
         * 如果post数据为空，则显示添加页
         * 否则把post数据插入数据库，然后重定向到指定页面
         */
        if (empty($_POST)) {
            $data = array_merge($this->title, $this->url);
            $data['redirect'] = $redirect;
            $data['main_title'] = '添加' . $this->title['module_title'];
            $data['submit_btn'] = '添加';
            $data['id'] = 0;
            $data['title'] = '';
            $data['icon'] = '';
            $data['descrip'] = '';
            $data['visible'] = 1;
            $data['user_id'] = 0;
            $data['users'] = $this->m->getUsersAPI();
            $data['communities_selected'] = array(0, 0, 0);
            $data['communities'] = $this->m->getCommunities();

            $data['default_images'] = $this->default_images;

            $this->load->view('community/thread/add_edit', $data);
        } else {
            $this->m->add();
            redirect($redirect);
        }
    }

    /**
     * 编辑
     * @param $id 编辑的数据ID
     */
    public function edit($id) {
        /*
         * 如果post的表单数据为空，则显示编辑页
         * 否则用post数据更新数据库，然后跳转回上一页
         */
        if (empty($_POST)) {
            $data = array_merge($this->title, $this->url, $this->m->getItem($id));

            $data['main_title'] = '编辑' . $this->title['module_title'];
            $data['submit_btn'] = '保存';

            $data['redirect'] = $this->input->get('redirect');

            $data['users'] = $this->m->getUsersAPI();
            $data['users'][$data['user_id']] = $data['user_name'];

            $data['communities_selected'] = $this->m->getCommunitiesSelected($id);
            $data['communities'] = $this->m->getCommunities();

            $data['default_images'] = $this->default_images;

            $data["tags"] = $this->m->getTagsByThreadId($id);

            $this->load->view('community/thread/add_edit', $data);
        } else {
            $this->m->edit($id);
            redirect($this->input->get('redirect'));
        }
    }

    /**
     * 就地编辑
     * @param string $table 表名
     */
    public function inPlaceEdit($table = 'thread') {
        $this->m->inPlaceEdit($table);
    }

    /**
     * 删除
     * @param $id
     */
    public function del($id) {
        $this->m->del($id);
    }

    /**
     * 话题的帖子列表页显示数据处理
     * @param $thread_id 话题ID
     * @param string $column 数据库中表的列名
     * @param string $order 排序
     * @param int $offset 分页偏移量
     */
    public function postList($thread_id, $column = 'created_at', $order = 'DESC', $offset = 0) {
        $data = array_merge($this->title, $this->url);

        $data['in_place_edit_url'] .= 'post';

        $data['post_list_condition_url'] = $data['post_list_url'] . $thread_id;

        $where = array('thread_id' => $thread_id);

        $page_limit = $this->page_limit;

        $page_total = $this->m->getTotal('post', $where);

        $page_url = $data['post_list_condition_url'] . '/' . $column . '/' . $order . '/';

        $this->_pageCheck($offset, $page_limit, $page_total, $page_url);

        $data = array_merge($data, $this->_pageData($offset, $page_limit, $page_total, $page_url));

        $list = $this->m->getPostList($page_limit, $offset, $where, $column, $order);

        $data['list'] = array();

        foreach ($list as $item) {
            $item['user_name'] = $this->m->getUsersAPI($item['user_id'])[$item['user_id']];
            array_push($data['list'], $item);
        }

        $data['sort_selected'] = array($column, $order);

        $data['post_type'] = 'thread';
        $data['post_main_title'] = $this->title['module_post_title'];
        $data['post_title'] = '所属话题:' . $this->m->getThreadTitle($thread_id);
        $data['post_btn'] = '添加' . $this->title['module_post_title'];
        $data['post_btn_url'] = $data['post_add_url'] . $thread_id;

        $this->load->view('community/thread/post_list', $data);
    }

    /**
     * 添加帖子
     * @param $thread_id 话题ID
     */
    public function postAdd($thread_id) {
        /*
         * 如果post的表单数据为空，则显示添加页
         * 否则把post数据插入数据库，然后跳转回列表页
         */
        if (empty($_POST)) {
            $data = array_merge($this->title, $this->url);
            $data['main_title'] = '添加' . $this->title['module_post_title'];
            $data['submit_btn'] = '添加';

            $data['thread_id'] = $thread_id;
            $data['thread_title'] = $this->m->getThreadTitle($thread_id);
            $data['users'] = $this->m->getUsersAPI();
            $data['visible'] = 1;

            $this->load->view('community/thread/post_add', $data);
        } else {
            $seq = $this->m->updateThreadPostCount($thread_id, '+1');

            $this->m->addPost($seq);

            redirect($this->url['post_list_url'] . $thread_id);
        }
    }

    /**
     * 删除帖子
     * @param $id
     */
    public function postDel($id, $thread_id) {
        $this->m->delPost($id);
        $this->m->updateThreadPostCount($thread_id, '-1');
    }
}