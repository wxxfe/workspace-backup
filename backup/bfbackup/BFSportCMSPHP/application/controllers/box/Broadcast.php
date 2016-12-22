<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Broadcast extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('box/Box_broadcast_model', 'BOX_BM');
    }

    public function index($offset=0) {
        $limit = 20;

        $listdata = $this->BOX_BM->getAllData($limit, $offset);
        $total     = $this->BOX_BM->getTotal('box_broadcast');

        $data = array(
            'listdata' => $listdata ? $listdata : array(),
            'total'     => $total
        );

        $data['page'] = $this->_pagination($total, $limit, '/box/broadcast/index');
        $data['sortOffset'] = $offset;
        $this->load->view('box/broadcast/list', $data);
    }

    public function add(){
        $data = array();
        if ($_POST) {
            $id      = $this->input->post('id');
            $data['title']   = $this->input->post('title');
            $data['type']   = $this->input->post('type');
            $data['visible'] = !$this->input->post('visible') ? 0 : intval($this->input->post('visible'));
            $s_data = $this->input->post('data');
            $data['ref_id'] = $s_data;

            $target_sort = $this->input->post('priority');
            if ($id) {
                $info = $this->BOX_BM->getInfo($id);
                if (!$info) {
                    redirect(base_url('/box/broadcast/add/'));
                    exit;
                }
                $where = array('priority >='=>$info['priority']);
                $info['priority'] = $this->BOX_BM->getSort('box_broadcast', $id, $where);
                unset($data['priority']);
                $this->BOX_BM->db('sports')->update('box_broadcast', $data, array('id'=>$id));
            } else {
                $data['priority'] = $target_sort ? $target_sort  : 0;
                $id = $this->BOX_BM->db('sports')->insert('box_broadcast', $data);
            }
            //更新排序值
            if($id && $target_sort>0){
                $this->BOX_BM->db('sports')->setTbSort('box_broadcast', $id, array('priority' => $target_sort));
            }
            $redirect = $this->input->get('redirect') ? $this->input->get('redirect') : base_url('/box/broadcast');
            redirect($redirect);
        } else {
            $id = $this->input->get('id');
            $data = array(
                'id' => $id
            );

            if ($id) {
                $info = $this->BOX_BM->getInfo($id);
                if (!$info) {
                    redirect(base_url('/box/broadcast/'));
                    exit;
                }
                $where = array('priority >='=>$info['priority']);
                $info['priority'] = $this->BOX_BM->getSort('box_broadcast', $id, $where);
                $data['info'] = $info;
            }
            $this->load->view('box/broadcast/add',$data);
        }
    }

    public function remove(){
        $id = $_POST['id'];
        if(strpos($id,',').'' != ''){
            $ids = explode(',',$id);
            try{
                $this->BOX_BM->db('sports')->remove_batch('box_broadcast',$ids);
                echo 'success';
            }catch(Exception $e){
                echo 'fail';
            }
        }else{
            try{
                $this->BOX_BM->db('sports')->remove('box_broadcast',$id);
                echo 'success';
            }catch(Exception $e){
                echo 'fail';
            }
        }
    }

    public function updatesort(){
        $data = $_POST;
        $id = $data['id'];
        $sort = intval($data['sort']);
        $result = $this->BOX_BM->db('sports')->setTbSort('box_broadcast', $id, array('priority' => $sort));

        if (!$result) {
            echo json_encode(array('status'=>-1, 'info'=>'error'));
            return false;
        }

        echo json_encode(array('status'=>0));
    }

    public function upstatus()
    {
        $id = $this->input->post('pk');
        if (!$id) {
            echo 'pk error';exit;
        }

        $info = $this->BOX_BM->getInfo($id);
        if (!$info) {
            echo 'data error';exit;
        }

        if ($info['visible']) {
            $data = array('visible'=>0);
        } else {
            $data = array('visible'=>1);
        }

        try{
            $result = $this->BOX_BM->db('sports')->update('box_broadcast', $data, array('id'=>$id));
            echo 'success';
        } catch(Exception $e) {
            echo 'fail';
        }
    }

}
