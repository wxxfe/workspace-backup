<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lists extends MY_Controller {
    public $page_name = "同步列表";

    private $view_path = 'app/lists';
    private $limit = 20;

    public function __construct() {
        parent::__construct();
        $this->load->model('app/Ex_list_model', 'EX_LM');
    }

    /**
     * 列表页
     * @param int $offset
     */
    public function index($offset=0) {
        $data = $this->EX_LM->getList($this->limit, $offset);
        $total= $this->EX_LM->getTotal('ex_list');

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
            $brief = $this->input->post('brief');
            $image = $this->input->post('poster');
            $large_image = $this->input->post('large_image');
            $priority = intval($this->input->post('priority'));
            $visible = intval($this->input->post('visible'));
            if ($type == 'html') {
                $url = $this->input->post('data');
            } else {
                $ref_id = intval($this->input->post('data'));
            }
            $list_data = array(
                'title'=>$title,
                'brief'=>$brief,
                'image'=>$image,
                'large_image'=>$large_image,
                'type'=>$type,
                'ref_id'=>isset($ref_id) ? $ref_id : '',
                'url'=>isset($url) ? $url : '',
                'visible'=>$visible,
            );

            if ($id) {
                $this->EX_LM->db('sports')->update('ex_list', $list_data, array('id'=>$id));
            } else {
                $id = $this->EX_LM->db('sports')->insert('ex_list', $list_data);
            }

            $max_priority = $this->EX_LM->getMaxPriority();
            if ($priority > $max_priority || $priority <= 0) {
                $priority = 1;
            }
            $this->EX_LM->db('sports')->setTbSort('ex_list', $id, array('priority' => $priority), array());

            $redirect = $this->input->get('redirect') ? $this->input->get('redirect') : base_url('app/lists');
            redirect($redirect);
        } else {
            if($id) {
                $data['info'] = $this->EX_LM->getInfo($id);
                if(isset($data['info']['priority'])) {
                    $where = array('priority >='=>$data['info']['priority']);
                    $data['info']['priority'] = $this->EX_LM->getSort('ex_list', $id, $where);
                }
            }
            $this->load->view($this->view_path . '/add', $data);
        }
    }

    public function remove()
    {
        $id = $_POST['id'];
        try {
            $this->EX_LM->db('sports')->remove('ex_list', $id);
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

        $info = $this->EX_LM->getInfo($id);
        if (!$info) {
            echo 'data error';exit;
        }

        if ($info['visible']) {
            $data = array('visible'=>0);
        } else {
            $data = array('visible'=>1);
        }

        try{
            $result = $this->EX_LM->db('sports')->update('ex_list', $data, array('id'=>$id));
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

    public function updatesort(){
        $data = $_POST;
        $id = $data['id'];
        $sort = intval($data['sort']);
        $result = $this->EX_LM->db('sports')->setTbSort('ex_list', $id, array('priority' => $sort));

        if (!$result) {
            echo json_encode(array('status'=>-1, 'info'=>'error'));
            return false;
        }

        echo json_encode(array('status'=>0));
    }


}
