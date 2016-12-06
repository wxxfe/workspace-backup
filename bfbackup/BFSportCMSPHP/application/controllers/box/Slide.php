<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slide extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('box/Box_slide_model', 'BOX_SD');
    }

    public function index($offset=0) {
        $limit = 20;

        $slideData = $this->BOX_SD->getAllSlide($limit, $offset);
        $total     = $this->BOX_SD->getTotal('box_slide');

        $data = array(
            'slidedata' => $slideData ? $slideData : array(),
            'total'     => $total
        );

        $data['page'] = $this->_pagination($total, $limit, '/box/slide/index');
        $data['sortOffset'] = $offset;
        $this->load->view('box/slide/list', $data);
    }

    public function add(){
        $data = array();
        if ($_POST) {
            $id      = $this->input->post('id');
            $data['title']   = $this->input->post('title');
            $data['type']   = $this->input->post('type');
            $data['image']   = $this->input->post('poster');
            $s_data = $this->input->post('data');
            $data['ref_id'] = $s_data;
            $data['visible'] = !$this->input->post('visible') ? 0 : intval($this->input->post('visible'));

            $target_sort = $this->input->post('priority');
            if ($id) {
                $slide_info = $this->BOX_SD->getSlideById($id);
                if (!$slide_info) {
                    redirect(base_url('/box/slide/add/'));
                    exit;
                }
                $where = array('priority >='=>$slide_info['priority']);
                $slide_info['priority'] = $this->BOX_SD->getSort('box_slide', $id, $where);
                unset($data['priority']);
                $this->BOX_SD->db('sports')->update('box_slide', $data, array('id'=>$id));
            } else {
                $data['priority'] = $target_sort ? $target_sort : 0;
                $id = $this->BOX_SD->db('sports')->insert('box_slide', $data);
            }
            //更新排序值
            if($id && $target_sort>0){
                $this->BOX_SD->db('sports')->setTbSort('box_slide', $id, array('priority' => $target_sort));
            }
            $redirect = $this->input->get('redirect') ? $this->input->get('redirect') : base_url('box/slide');
            redirect($redirect);
        } else {
            $id = $this->input->get('id');
            $data = array(
                'id' => $id
            );

            if ($id) {
                $slide_info = $this->BOX_SD->getSlideById($id);
                if (!$slide_info) {
                    redirect(base_url('/box/slide/'));
                    exit;
                }
                $where = array('priority >='=>$slide_info['priority']);
                $slide_info['priority'] = $this->BOX_SD->getSort('box_slide', $id, $where);
                $data['slide_info'] = $slide_info;
            }
            $this->load->view('box/slide/add',$data);
        }
    }

    public function remove(){
        $id = $_POST['id'];
        if(strpos($id,',').'' != ''){
            $ids = explode(',',$id);
            try{
                $this->BOX_SD->db('sports')->remove_batch('box_slide',$ids);
                echo 'success';
            }catch(Exception $e){
                echo 'fail';
            }
        }else{
            try{
                $this->BOX_SD->db('sports')->remove('box_slide',$id);
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
        $result = $this->BOX_SD->db('sports')->setTbSort('box_slide', $id, array('priority' => $sort));

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

        $slide_info = $this->BOX_SD->getSlideById($id);
        if (!$slide_info) {
            echo 'data error';exit;
        }

        if ($slide_info['visible']) {
            $data = array('visible'=>0);
        } else {
            $data = array('visible'=>1);
        }

        try{
            $result = $this->BOX_SD->db('sports')->update('box_slide', $data, array('id'=>$id));
            echo 'success';
        } catch(Exception $e) {
            echo 'fail';
        }
        //var_dump($data, $slide_info);
    }

}
