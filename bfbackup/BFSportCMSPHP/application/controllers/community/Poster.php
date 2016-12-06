<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Poster
 * 轮播图
 */
class Poster extends MY_Controller {
    /**
     * @var string 模块名
     */
    private $title = array('module_title' => '话题轮播图', 'module_title_en' => 'Thread Poster');

    /**
     * @var array poster表中type列的值对应列表页面显示的字符串
     */
    private $types = array('threads' => '专题', 'thread' => '单个', 'url' => '外链');

    /**
     * @var array poster表中type列的值对应添加编辑页面显示的字符串
     */
    private $types_title = array('threads' => '专题轮播图', 'thread' => '单话题轮播图', 'url' => '外链轮播图');

    /**
     * 映射控制器方法的路由url
     */
    private $url = array(
        'list_url' => '/community/poster/index/',
        'add_url' => '/community/poster/add/',
        'in_place_edit_url' => '/community/poster/inPlaceEdit/',
        'edit_url' => '/community/poster/edit/',
        'del_url' => '/community/poster/del/',
        'relate_threads_url' => '/community/poster/relateThreads/'
    );

    /**
     * @var int 分页每页条数
     */
    private $page_limit = 20;

    public function __construct() {
        parent::__construct();
        //载入控制器用到的模型，第二个参数是别名
        $this->load->model('community/Poster_model', 'm');
        $this->load->model('community/Thread_model', 'tm');
    }

    /**
     * 最后一段是控制器类名的路由url映射的方法
     * 列表页显示数据处理
     * @param int $offset 分页偏移量
     */
    public function index($offset = 0) {
        $data = array_merge($this->title, $this->url);

        $page_limit = $this->page_limit;

        $page_total = $this->m->getTotal('poster');

        $page_url = $data['list_url'];

        $this->_pageCheck($offset, $page_limit, $page_total, $page_url);

        $data = array_merge($data, $this->_pageData($offset, $page_limit, $page_total, $page_url));

        $list = $this->m->getList($page_limit, $offset);

        $data['list'] = array();

        foreach ($list as $item) {
            $item['type_name'] = $this->types[$item['type']];
            $item['post_list_url'] = '';
            if ($item['type'] == 'thread') {
                $item['post_list_url'] = '/community/thread/postList/' . $this->m->getPosterHasThreads($item['id'])[0]['thread_id'];
            }
            array_push($data['list'], $item);
        }

        $data['types'] = $this->types;

        $this->load->view('community/poster/list', $data);
    }

    /**
     * 添加
     * @param $type 类型
     */
    public function add($type) {
        /*
         * 如果post的表单数据为空，则显示添加页
         * 否则把post数据插入数据库，然后跳转回列表页
         */
        if (empty($this->input->post())) {
            $data = array_merge($this->title, $this->url);
            $data['redirect'] = $data['list_url'];
            $data['submit_btn'] = '添加';
            $data['main_title'] = $data['submit_btn'] . $this->types_title[$type];

            $data['id'] = 0;
            $data['name'] = '';
            $data['image_small'] = '';
            $data['image_large'] = '';
            $data['url'] = '';
            $data['content'] = '';
            $data['visible'] = 1;
            $data['type'] = $type;

            if ($type == 'threads') {
                $data['threads'] = array();
            } else {
                $data['threads'] = array(array('id' => '', 'title' => '', 'display_order' => ''));
            }

            $this->load->view('community/poster/' . $type . '_add_edit', $data);
        } else {
            $this->m->add();
            redirect($this->url['list_url']);
        }
    }

    /**
     * 编辑
     * @param $id 编辑的数据ID
     * @param $type 类型
     */
    public function edit($id, $type) {
        /*
         * 如果post的表单数据为空，则显示编辑页
         * 否则用post数据更新数据库，然后跳转回上一页
         */
        if (empty($this->input->post())) {
            $data = array_merge($this->title, $this->url, $this->m->getItem($id));

            $posterHasThreads = $this->m->getPosterHasThreads($id);
            if ($posterHasThreads) {
                $data['threads'] = array();
                foreach ($posterHasThreads as $item) {
                    array_push($data['threads'], array('id' => $item['thread_id'], 'title' => $this->tm->getThreadTitle($item['thread_id']), 'display_order' => $item['display_order']));
                }
            } else {
                if ($type == 'threads') {
                    $data['threads'] = array();
                } else {
                    $data['threads'] = array(array('id' => '', 'title' => '', 'display_order' => ''));
                }
            }

            $data['submit_btn'] = '保存';
            $data['main_title'] = '编辑' . $this->types_title[$type];

            $data['redirect'] = $this->input->get('redirect');

            $this->load->view('community/poster/' . $type . '_add_edit', $data);
        } else {
            $this->m->edit($id);
            redirect($this->input->get('redirect'));
        }
    }

    /**
     * 就地编辑
     * 输出编辑的列名
     * @param string $table 表名
     */
    public function inPlaceEdit($table = 'poster') {
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
     * 轮播图关联话题
     * 通过输入的话题ID字符串，获得对应的标题。
     * 如果出错返回错误提示，否则返回所有ID对应的标题
     */
    public function relateThreads() {
        $threads_title = array();
        $threads_id = array_unique(explode(',', $this->input->post('threads_add')));
        foreach ($threads_id as $thread_id) {
            $msg = '';
            if (is_numeric($thread_id)) {
                $title = $this->tm->getThreadTitle($thread_id);
            } else {
                $title = null;
                $msg = 'ID是空！';
            }
            if ($title === null) {
                if (empty($msg)) $msg = 'ID ' . $thread_id . ' 错误！';
                echo json_encode(array('threads_add' => $msg . '请输入正确并上线的话题ID！'));
                exit();
            } else {
                array_push($threads_title, array('id' => $thread_id, 'title' => $title));
            }
        }
        echo json_encode(array('threads_add' => 'ok', 'data' => $threads_title));
    }

}