<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Focus extends MY_Controller {
    public $page_name = "焦点图";

    private $view_path = 'app/focus';
    private $limit = 20;

    public function __construct() {
        parent::__construct();
        $this->load->model('app/Ex_focus_model', 'EX_FM');
    }

    /**
     * 列表页
     * @param int $offset
     */
    public function index($offset=0) {
        $data = $this->EX_FM->getList($this->limit, $offset);
        $total= $this->EX_FM->getTotal('ex_focus');

        $data = array(
            'list' => $data ? $data : array(),
            'total'     => $total
        );

        $data['page'] = $this->_pagination($total, $this->limit, strtolower(__CLASS__ . '/' . __FUNCTION__) . '/index');
        $data['offset'] = $offset;
        $this->load->view($this->view_path . '/list', $data);
    }

    public function add(){
        $id = $this->input->get('id');
        $data = array();
        if ($_POST) {
            $type = $this->input->post('type');
            $title = $this->input->post('title');
            $image = $this->input->post('poster');
            $position = $this->input->post('position');
            $visible = intval($this->input->post('visible'));
            if ($type == 'html') {
                $url = $this->input->post('data');
            } else {
                $ref_id = intval($this->input->post('data'));
            }
            $focus_data = array(
                'title'=>$title,
                'image'=>$image,
                'type'=>$type,
                'ref_id'=>isset($ref_id) ? $ref_id : '',
                'url'=>isset($url) ? $url : '',
                'position'=>$position,
                'visible'=>$visible,
            );
            if ($id) {
                $this->EX_FM->db('sports')->update('ex_focus', $focus_data, array('id'=>$id));
            } else {
                $this->EX_FM->db('sports')->insert('ex_focus', $focus_data);
            }
            $redirect = $this->input->get('redirect') ? $this->input->get('redirect') : base_url('app/focus');
            redirect($redirect);
        } else {
            if($id) {
                $data['info'] = $this->EX_FM->getInfo($id);
            }
            $this->load->view($this->view_path . '/add', $data);
        }
    }

    public function remove()
    {
        $id = $_POST['id'];
        try {
            $this->EX_FM->db('sports')->remove('ex_focus', $id);
            echo 'success';
        } catch (Exception $e) {
            echo 'fail';
        }
    }

    public function upstatus() {
        $id = $this->input->post('pk');
        if (!$id) {
            echo 'pk error';exit;
        }

        $info = $this->EX_FM->getInfo($id);
        if (!$info) {
            echo 'data error';exit;
        }

        if ($info['visible']) {
            $data = array('visible'=>0);
        } else {
            $data = array('visible'=>1);
        }

        try{
            $result = $this->EX_FM->db('sports')->update('ex_focus', $data, array('id'=>$id));
            echo 'success';
        } catch(Exception $e) {
            echo 'fail';
        }
    }

    public function checkContent() {
        $result = array('status'=>'yes');

        $id = $this->input->post('id');
        $type = $this->input->post('type');

        if (is_numeric($id)) {
            if ($type == 'video') {
                $this->load->model('video_model', 'VM');
                $info = $this->VM->getVideoInfo($id);
                if ($info) {
                    $result = array('status' => 'yes', 'data' => '<font color="red">【视频】</font>' . $info['title']);
                } else {
                    $result = array('status' => 'no', 'data' => '视频不存在');
                }
            } elseif ($type == 'collection') {
                $this->load->model('collection_model', 'CM');
                $info = $this->CM->getInfo($id);
                if ($info) {
                    $result = array('status' => 'yes', 'data' => '<font color="red">【合集】</font>' . $info['title']);
                } else {
                    $result = array('status' => 'no', 'data' => '合集不存在');
                }
            } elseif ($type == 'program') {
                $this->load->model('program_model', 'PM');
                $info = @$this->PM->getInfo($id);
                if (isset($info['id'])) {
                    $result = array('status' => 'yes', 'data' => '<font color="red">【节目】</font>' . $info['title']);
                } else {
                    $result = array('status' => 'no', 'data' => '节目不存在');
                }
            } elseif ($type == 'match') {
                $this->load->model('match_model', 'MM');
                $info = @$this->MM->getMatchInfo($id);
                if ($info) {
                    $result = array('status' => 'yes', 'data' => '<font color="red">【比赛】</font>' . $info);
                } else {
                    $result = array('status' => 'no', 'data' => '比赛不存在');
                }
            }
        }
        echo json_encode($result);
    }

}
