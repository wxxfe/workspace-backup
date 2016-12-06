<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slide extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Slide_model', 'SD');
    }

    public function index($offset=0) {
        $limit = 20;
        
        $slideData = $this->SD->getAllSlide($limit, $offset);
        $total     = $this->SD->getTotal('slide');
        
        $data = array(
            'slidedata' => $slideData ? $slideData : array(),
            'total'     => $total
        );

        $data['page'] = $this->_pagination($total, $limit, '/slide/index');
        $data['offset'] = $offset;
        
        $this->load->view('slide/list', $data);
    }

    public function add(){
        $data = array();
        if ($_POST) {
            $id      = $this->input->post('id');
            $data['title']   = $this->input->post('title');
            $data['type']   = $this->input->post('type');
            $data['image']   = $this->input->post('poster');
            $data['priority'] = empty($this->input->post('priority')) ? 1 : intval($this->input->post('priority'));
            $data['visible'] = empty($this->input->post('visible')) ? 0 : intval($this->input->post('visible'));
            
            $s_data = $this->input->post('data');
            
            if ('html' == $data['type']) {
                $data['data'] = $s_data;
            } else {
                $data['ref_id'] = $s_data;
            }
            
            if ($id) {
                $slide_info = $this->SD->getSlideById($id);
                if (!$slide_info) {
                    redirect(base_url('/slide/add/'));
                    exit;
                }
                // 编辑页面不设置焦点图的排序
                /*
                $max_priority = $this->SD->getMaxPriority();
                if ($data['priority'] > $max_priority || $data['priority'] <= 0) {
                    $priority = 1;
                } else {
                    $priority = $data['priority'];
                }
                $this->SD->db('sports')->setTbSort('slide', $id, array('priority' => $priority), array());
                */
                unset($data['priority']);
                $this->SD->db('sports')->update('slide', $data, array('id'=>$id));
            } else {
                /*
                if (1 == $data['priority']) {
                    $max_priority = $this->SD->getMaxPriority();
                    $data['priority'] = $max_priority + 1;
                }*/
                
                $priority = $data['priority'];
                unset($data['priority']);
                
                $id = $this->SD->db('sports')->insert('slide', $data);
                
                $max_priority = $this->SD->getMaxPriority();
                if ($priority > $max_priority || $priority <= 0) {
                    $priority = 1;
                }
                $this->SD->db('sports')->setTbSort('slide', $id, array('priority' => $priority), array());
            }
            
            redirect(base_url('/slide/'));
        } else {
            $id = $this->input->get('id');
            $data = array(
                'id' => $id
            );
            
            if ($id) {
                $slide_info = $this->SD->getSlideById($id);
                if (!$slide_info) {
                    redirect(base_url('/slide/'));
                    exit;
                }
                
                // slide_info 的priority并不是真实的排序，需要转化为真实排序
                $data['slide_info'] = $slide_info;
            }
            $this->load->view('slide/add',$data);
        }
    }

    public function remove(){
        $id = $_POST['id'];
        if(strpos($id,',').'' != ''){
            $ids = explode(',',$id);
            try{
                $this->SD->db('sports')->remove_batch('slide',$ids);
                echo 'success';
            }catch(Exception $e){
                echo 'fail';
            }
        }else{
            try{
                $this->SD->db('sports')->remove('slide',$id);
                echo 'success';
            }catch(Exception $e){
                echo 'fail';
            }
        }
    }
    
    /*public function updatesort(){
        $data = $_POST;
        //var_dump($data);exit;
        $id = $data['id'];
        $sort = intval($data['sort']);
        $result = $this->SD->db('sports')->update('slide', array('priority' => $sort), array('id'=>$id));
        //$result = $this->SP->db('sports')->setTbSort('special_content',$cid,array('priority' => $sort),array('special_block_id' => $bid));
        
        if (!$result) {
            echo json_encode(array('status'=>-1, 'info'=>'error'));
            return false;
        }
        
        echo json_encode(array('status'=>0));
    }*/
    
    public function updatesort(){
        $data = $_POST;
        $tid = $data['pk'];
        $sort = intval($data['value']);
        $result = $this->SD->db('sports')->setTbSort('slide',$tid,array('priority' => $sort));
        echo $result ? 'success' : 'fail';
    }
    
    public function upstatus()
    {
        $id = $this->input->post('pk');
        if (!$id) {
            echo 'pk error';exit;
        }
        
        $slide_info = $this->SD->getSlideById($id);
        if (!$slide_info) {
            echo 'data error';exit;
        }
        
        if ($slide_info['visible']) {
            $data = array('visible'=>0);
        } else {
            $data = array('visible'=>1);
        }
        
        try{
            $result = $this->SD->db('sports')->update('slide', $data, array('id'=>$id));
            echo 'success';
        } catch(Exception $e) {
            echo 'fail';
        }
        //var_dump($data, $slide_info);
    }

}
