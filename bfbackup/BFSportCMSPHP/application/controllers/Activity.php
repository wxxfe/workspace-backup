<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Activity
 * 活动
 */
class Activity extends MY_Controller {
    /**
     * @var string 模块名
     */
    private $title = array('module_title' => '活动', 'module_title_en' => 'Activity');

    /**
     * 映射控制器方法的路由url
     */
    private $url = array(
        'list_url' => '/activity/index/',
        'add_url' => '/activity/add',
        'in_place_edit_url' => '/activity/inPlaceEdit',
        'edit_url' => '/activity/edit/',
        'del_url' => '/activity/del'
    );

    /**
     * @var int 分页每页条数
     */
    private $page_limit = 20;

    public function __construct() {
        parent::__construct();
        //载入控制器用到的模型，第二个参数是别名
        $this->load->model('Activity_model', 'm');
        $this->load->model('Tag_model', 'tm');
        $this->load->model('Search_model', 'sm');
    }

    /**
     * 最后一段是控制器类名的路由url映射的方法
     * 列表页显示数据处理
     * 如果有搜索关键字数据，则是搜索列表
     * 否则是正常列表
     * @param int $offset 分页偏移量
     */
    public function index($offset = 0) {
        $data = array_merge($this->title, $this->url);

        $page_limit = $this->page_limit;

        $keyword = $this->input->get('keyword');

        if ($keyword) {
            if (is_numeric($keyword)) {
                $list = array_filter(array($this->m->getItem($keyword)));
                $page_total = 1;
            } else {
                $search_result = $this->sm->itemsQuery('activity', $keyword, $offset, $page_limit);
                $list = $search_result['result'];
                $page_total = $search_result['total'];

            }
            $data['keyword'] = $keyword;
        } else {
            $list = $this->m->getList($page_limit, $offset);
            $page_total = $this->m->getTotal('activity');
        }

        $this->_pageCheck($offset, $page_limit, $page_total, $data['list_url']);

        $data = array_merge($data, $this->_pageData($offset, $page_limit, $page_total, $data['list_url']));

        $data['list'] = array();
        foreach ($list as $item) {
            $item['tags'] = $this->tm->db('sports')->getTagsByFakeId($this->m->getTagsId($item['id']));
            array_push($data['list'], $item);
        }

        $this->load->view('activity/list', $data);
    }

    /**
     * 添加
     */
    public function add() {
        /*
         * 如果post的表单数据为空，则显示添加页
         * 否则把post数据插入数据库，然后跳转回列表页
         */
        if (empty($this->input->post())) {
            $data = array_merge($this->title, $this->url);
            $data['redirect'] = $data['list_url'];
            $data['main_title'] = '添加' . $this->title['module_title'];
            $data['submit_btn'] = '添加';
            $data['title'] = '';
            $data['image'] = '';
            $data['url'] = '';
            $data['visible'] = 1;
            $data['tags'] = '';
            $data['id'] = 0;

            $this->load->view('activity/add_edit', $data);
        } else {
            $this->m->add();
            redirect('/activity/index/');
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
        if (empty($this->input->post())) {
            $data = array_merge($this->title, $this->url, $this->m->getItem($id));

            $data['main_title'] = '编辑' . $this->title['module_title'];
            $data['submit_btn'] = '保存';

            $data['redirect'] = $this->input->get('redirect');

            $this->load->view('activity/add_edit', $data);
        } else {
            $this->m->edit($id);
            redirect($this->input->get('redirect'));
        }
    }

    /**
     * 就地编辑
     */
    public function inPlaceEdit() {
        $this->m->inPlaceEdit('activity');
    }

    /**
     * 删除，包括批量删除
     */
    public function del() {
        $this->m->del();
    }

}