<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plate extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('box/Box_plate_model', 'BOX_PM');
    }

    public function index($offset=0) {
        $limit = 20;

        $listdata = $this->BOX_PM->getList($limit, $offset);
        $total     = $this->BOX_PM->getTotal('box_plate');

        $data = array(
            'listdata' => $listdata ? $listdata : array(),
            'total'     => $total
        );

        $data['page'] = $this->_pagination($total, $limit, '/box/plate/index');
        $data['sortOffset'] = $offset;
        $this->load->view('box/plate/list', $data);
    }

    public function add(){
        $data = array();
        if ($_POST) {
            $data['title']   = $this->input->post('title');
            $data['visible'] = !$this->input->post('visible') ? 0 : intval($this->input->post('visible'));

            $target_sort = $this->input->post('priority');
            $data['priority'] = $target_sort ? $target_sort  : 0;
            $id = $this->BOX_PM->db('sports')->insert('box_plate', $data);
            //更新排序值
            if($id && $target_sort>0){
                $this->BOX_PM->db('sports')->setTbSort('box_plate', $id, array('priority' => $target_sort));
            }
            redirect(base_url('/box/plate/'));
        }
        $this->load->view('box/plate/add',$data);
    }

    public function edit(){
        $data = array();
        $id = $this->input->get('id');
        $info = $this->BOX_PM->getInfo($id);
        $where = array('priority >='=>$info['priority']);
        $info['priority'] = $this->BOX_PM->getSort('box_plate', $id, $where);//获取排序
        if ($_POST) {
            $data['title']   = $this->input->post('title');
            $data['visible'] = !$this->input->post('visible') ? 0 : intval($this->input->post('visible'));

            $target_sort = $this->input->post('priority');
            unset($data['priority']);//不更新排序
            $this->BOX_PM->db('sports')->update('box_plate', $data, array("id"=>$id));
            //更新排序
            $this->BOX_PM->db('sports')->setTbSort('box_plate', $id, array('priority' => $target_sort));
            $redirect = $this->input->get('redirect') ? $this->input->get('redirect') : base_url('/box/plate/');
            redirect($redirect);
        }
        $data['info'] = $info;
        $data['id'] = $id;
        $this->load->view('box/plate/add', $data);
    }

    public function remove(){
        $id = intval($_POST['id']);
        try{
            $this->BOX_PM->db('sports')->remove('box_plate',$id);
            echo 'success';
        }catch(Exception $e) {
            echo 'fail';
        }
    }

    public function updatesort(){
        $data = $_POST;
        $id = $data['id'];
        $sort = intval($data['sort']);
        $result = $this->BOX_PM->db('sports')->setTbSort('box_plate', $id, array('priority' => $sort));

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

        $slide_info = $this->BOX_PM->getInfo($id);
        if (!$slide_info) {
            echo 'data error';exit;
        }

        if ($slide_info['visible']) {
            $data = array('visible'=>0);
        } else {
            $data = array('visible'=>1);
        }

        try{
            $result = $this->BOX_PM->db('sports')->update('box_plate', $data, array('id'=>$id));
            echo 'success';
        } catch(Exception $e) {
            echo 'fail';
        }
    }

    public function updatecontentsort(){
        $data = $_POST;
        $id = $data['id'];
        $plateid = $data['plateid'];
        $sort = intval($data['sort']);
        $result = $this->BOX_PM->db('sports')->setTbSort('box_plate_content', $id, array('priority' => $sort),array('plate_id'=>$plateid));

        if (!$result) {
            echo json_encode(array('status'=>-1, 'info'=>'error'));
            return false;
        }

        echo json_encode(array('status'=>0));
    }

    public function upcontentstatus()
    {
        $id = $this->input->post('pk');
        if (!$id) {
            echo 'pk error';exit;
        }

        $info = $this->BOX_PM->getContentInfo($id);
        if (!$info) {
            echo 'data error';exit;
        }

        $data = array('visible'=>$info['visible'] ? 0 : 1);

        try{
            $result = $this->BOX_PM->db('sports')->update('box_plate_content', $data, array('id'=>$id));
            echo 'success';
        } catch(Exception $e) {
            echo 'fail';
        }
    }

    public function contentlist($id,$offset=0){
        $limit=20;
        $plate_info = $this->BOX_PM->getInfo($id);

        $content_list = $this->BOX_PM->getContentList(array('plate_id'=>$id), $limit, $offset);
        //page
        $total = $this->BOX_PM->getTotal('box_plate_content', array('plate_id'=>$id));
        $data['page'] = $this->_pagination($total, $limit, '/box/plate/contentlist/'.$id, array('uri_segment'=>5));
        $data['total'] = $total;
        $data['listdata'] = $content_list;
        $data['plate_info'] = $plate_info;
        $data['id'] = $id;
        $data['sortOffset'] = $offset;
        $this->load->view('/box/plate/contentlist', $data);
    }

    public function addContent($plateid){
        $plate_info = $this->BOX_PM->getInfo($plateid);

        if($_POST){
            $data['plate_id']  = $plateid;
            $data['title']   = $this->input->post('title');
            $data['type']   = $this->input->post('type');
            $data['image']   = $this->input->post('poster');
            $s_data = $this->input->post('data');
            $data['ref_id'] = $s_data;
            $data['visible'] = !$this->input->post('visible') ? 0 : intval($this->input->post('visible'));
            $data['brief']   = $this->input->post('brief');

            $target_sort = $this->input->post('priority');
            $data['priority'] = $target_sort ? $target_sort : 0;
            $id = $this->BOX_PM->db('sports')->insert('box_plate_content', $data);
            //更新排序值
            if($id && $target_sort>0){
                $this->BOX_PM->db('sports')->setTbSort('box_plate_content', $id, array('priority' => $target_sort), array('plate_id'=>$plateid));
            }
            redirect(base_url('/box/plate/contentlist/'.$plateid));
        }

        $data['plate_info'] = $plate_info;
        $data['plateid'] = $plateid;
        $this->load->view('/box/plate/addcontent', $data);
    }

    public function editContent($id){
        $data = $this->BOX_PM->getContentInfo($id);
        $plate_info = $this->BOX_PM->getInfo($data['plate_id']);
        $where = array('plate_id'=>$data['plate_id'], 'priority >='=>$data['priority']);
        $data['priority'] = $this->BOX_PM->getSort('box_plate_content', $id, $where);//获取排序
        if($_POST){
            $data['title']   = $this->input->post('title');
            $data['type']   = $this->input->post('type');
            $data['image']   = $this->input->post('poster');
            $s_data = $this->input->post('data');
            $data['ref_id'] = $s_data;
            $data['visible'] = !$this->input->post('visible') ? 0 : intval($this->input->post('visible'));
            $data['brief']   = $this->input->post('brief');

            $target_sort = $this->input->post('priority');
            unset($data['priority']);//不更新排序
            $this->BOX_PM->db('sports')->update('box_plate_content', $data, array('id'=>$id));
            //更新排序值
            $this->BOX_PM->db('sports')->setTbSort('box_plate_content', $id, array('priority' => $target_sort), array('plate_id'=>$plate_info['id']));
            $redirect = $this->input->get('redirect') ? $this->input->get('redirect') : base_url('/box/plate/contentlist/'.$plate_info['id']);
            redirect($redirect);
        }

        $data['plate_info'] = $plate_info;
        $data['id'] = $id;
        $data['contentinfo'] = $data;
        $this->load->view('/box/plate/editcontent', $data);
    }

    public function removeContent($id){
        $result = $this->BOX_PM->removeContent($id);
        echo $result;
    }

}

