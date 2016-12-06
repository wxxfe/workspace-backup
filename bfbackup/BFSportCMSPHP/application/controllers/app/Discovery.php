<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discovery extends MY_Controller {
    public $page_name = "发现频道";

    private $view_path = 'app/discovery';
    private $limit = 20;

    public function __construct() {
        parent::__construct();
        $this->load->model('app/Ex_discovery_model', 'EX_DM');
    }

    /**
     * 列表页
     * @param int $offset
     */
    public function index($offset=0) {
        $data = $this->EX_DM->getList($this->limit, $offset);
        $total= $this->EX_DM->getTotal('ex_discovery');

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
            $title = $this->input->post('title');
            $image = $this->input->post('poster');
            $visible = intval($this->input->post('visible'));
            $url = $this->input->post('data');
            $discovery_data = array(
                'title'=>$title,
                'image'=>$image,
                'url'=>isset($url) ? $url : '',
                'visible'=>$visible,
            );
            if ($id) {
                $this->EX_DM->db('sports')->update('ex_discovery', $discovery_data, array('id'=>$id));
            } else {
                $this->EX_DM->db('sports')->insert('ex_discovery', $discovery_data);
            }
            $redirect = $this->input->get('redirect') ? $this->input->get('redirect') : base_url('app/discovery');
            redirect($redirect);
        } else {
            if($id) {
                $data['info'] = $this->EX_DM->getInfo($id);
            }
            $this->load->view($this->view_path . '/add', $data);
        }
    }

    public function remove()
    {
        $id = $_POST['id'];
        try {
            $this->EX_DM->db('sports')->remove('ex_discovery', $id);
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

        $info = $this->EX_DM->getInfo($id);
        if (!$info) {
            echo 'data error';exit;
        }

        if ($info['visible']) {
            $data = array('visible'=>0);
        } else {
            $data = array('visible'=>1);
        }

        try{
            $result = $this->EX_DM->db('sports')->update('ex_discovery', $data, array('id'=>$id));
            echo 'success';
        } catch(Exception $e) {
            echo 'fail';
        }
    }

}
