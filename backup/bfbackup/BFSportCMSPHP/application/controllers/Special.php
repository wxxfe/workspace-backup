<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Special extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Special_model', 'SP');
    }

    public function index($page=0) {
        $keyword = $this->input->post('keyword');
        $this->load->model('Tag_model', 'TG');
        $data = array();
        $all = array();
        $total = 0;
        if (is_numeric($keyword)) {
                $special_info = $this->SP->getSpecialById($keyword);
                if ($special_info) {
                    $total = 1;
                }
                $tags = $this->SP->getTagsBySpecialId($special_info['id']);
                $tags_name = $tags ? $this->TG->db('sports')->getTagsByFakeId($tags) : array();
                $special_info['tags'] = '';
                foreach ($tags_name as $k=>$v) {
                    $special_info['tags'][] = $v['name'];
                }
                
                if ($special_info['tags']) {
                    $special_info['tags'] = implode(',', $special_info['tags']);
                }

                $all[] = $special_info;
        } else {
            $like = '';
            if ($keyword) {
                $like = $keyword;
            }
            $total = $this->SP->getSpecialTotal($like);
            
            $specialData = $this->SP->getAllSpecial($page,20, $like);
            
            foreach($specialData as $special){
                $tags = $this->SP->getTagsBySpecialId($special['id']);
                $tags_name = $tags ? $this->TG->db('sports')->getTagsByFakeId($tags) : array();
                $special['tags'] = '';
                foreach ($tags_name as $k=>$v) {
                    $special['tags'][] = $v['name'];
                }
                
                if ($special['tags']) {
                    $special['tags'] = implode(',', $special['tags']);
                }
                $all[] = $special;
            }
        }
        

        $data['keyword'] = empty($keyword) ? '' : $keyword;
        $data['total'] = $total;
        $data['allSpecial'] = $all;
        $data['page'] = $this->_pagination($total,20,'/special/index/');

        $this->load->view('special/list', $data);
    }

    public function add(){
        $redirect = $this->input->get('redirect');
        if ($redirect) {
            $_SESSION['special_redirect'] = $redirect;
        } else {
            $_SESSION['special_redirect'] = '';
        }
        
        $data = array();
        if ($_POST) {
            $id      = $this->input->post('sid');
            $data['title']   = $this->input->post('title');
            $data['image']   = $this->input->post('poster');
            $data['remarks'] = $this->input->post('remarks');
            $data['brief']   = $this->input->post('brief');
            $data['visible'] = empty($this->input->post('visible')) ? 0 : intval($this->input->post('visible'));
            $tags            = $this->input->post('tags');
            
            if ($id) {
                $special_info = $this->SP->getSpecialById($id);
                if (!$special_info) {
                    redirect(base_url('/special/add/'));
                    exit;
                }
                $data['updated_at'] = date('Y-m-d H:i:s');
                $this->SP->db('sports')->update('special', $data, array('id'=>$id));
                $jump_url = base_url('/special/addmod?id='.$id.'&is_up=1');
            } else {
                //$data['create_at']    = date('Y-m-d H:i:s');
                $id = $this->SP->db('sports')->insert('special', $data);
                $jump_url = base_url('/special/addmod?id='.$id);
            }
            
            $this->_setTags($id, $tags);
            
            redirect($jump_url);
        } else {
            $id = $this->input->get('id');
            $data = array(
                'id' => $id
            );
            
            if ($id) {
                $special_info = $this->SP->getSpecialById($id);
                if (!$special_info) {
                    redirect(base_url('/special/'));
                    exit;
                }
                
                $data['special_info'] = $special_info;
                
                $tags = $this->SP->getTagsBySpecialId($id);
                
                $data['tags'] = $tags;
            }
            $this->load->view('special/add',$data);
        }
    }

    
    public function addmod()
    {
        $id = $this->input->get('id');
        //$modid = $this->input->get('modid');
        $is_up = intval($this->input->get('is_up'));
        if (!$id) {
            redirect(base_url('/special/add'));exit;
        }
        
        $data = array();
        if ($_POST) {
            $data['title']    = $this->input->post('mod_name'); 
            $data['priority'] = $this->input->post('mod_sort');
            $data['visible']  = empty($this->input->post('mod_visible')) ? 0 : intval($this->input->post('mod_visible'));
            $is_ajax = empty($this->input->post('is_ajax')) ? 0 : intval($this->input->post('is_ajax'));
            $mid     = empty($this->input->post('mod_id')) ? 0 : intval($this->input->post('mod_id'));
            
            if ($mid) {
                $block_info = $this->SP->getBlockById($mid);
                //var_dump($block_info, $mid);exit;
                if (!$block_info) {
                    if ($is_ajax) {
                        echo json_encode(array('status'=>-1, 'info'=>'no info'));exit;
                    }
                    redirect(base_url('/special/addmod?id='.$id));
                    exit;
                }
                
                $priority = $data['priority'];
                unset($data['priority']);
                $this->SP->db('sports')->update('special_block', $data, array('id'=>$mid));
                $this->SP->db('sports')->setTbSort('special_block', $mid, array('priority' => $priority), array('special_id' => $id));
                
                echo json_encode(array('status'=>0));exit;
            } else {
                $data['special_id'] = $id;
                $priority = $data['priority'];
                unset($data['priority']);
                $mid = $this->SP->db('sports')->insert('special_block', $data);
                $this->SP->db('sports')->setTbSort('special_block', $mid, array('priority' => $priority), array('special_id' => $id));
                redirect(base_url('/special/addmod?id='.$id));
                exit;
            }

        } else {
            $block_list = $this->SP->getBlockBySpecialId($id);
            //var_dump($block_list);exit;
            if ($block_list) {
                foreach ($block_list as $k=>$v) {
                    $content = $this->SP->getContentByBlockId($v['id']);
                    if ($content) {
                        foreach ($content as $k1=>$v1) {
                            $content[$k1]['info'] = $this->SP->getContentinfoByData($v1['type'], $v1['ref_id']);
                        }
                    }
                    $block_list[$k]['child'] = $content;
                }
            }
            
            $data = array(
                'id'         => $id,
                'block_list' => $block_list,
                'is_up'      => $is_up,
                'jump_url'   => base_url('/special/'),
            );
            
            if (isset($_SESSION['special_redirect']) && $_SESSION['special_redirect']) {
                $data['jump_url'] = $_SESSION['special_redirect'];
            }
        }

        
        $this->load->view('special/addmod',$data);
    }
    
    public function addcontent()
    {
        $id = $this->input->get('id');
        $modid = $this->input->get('modid');
        $is_up = intval($this->input->get('is_up'));
        if (!$id) {
            redirect(base_url('/special/add'));exit;
        }
        
        if (!$modid) {
            redirect(base_url('/special/add?id='.$id.'&is_up='.$is_up));exit;
        }
        
        $data = array();
        if ($_POST) {
            $id = intval($this->input->post('id'));
            $data['special_block_id'] = intval($this->input->post('modid'));
            $data['type']     = $this->input->post('type');
            $data['ref_id']   = intval($this->input->post('content_id'));
            $data['priority'] = intval($this->input->post('sort'));
            
            if (!$data['ref_id']) {
                redirect(base_url("/special/addcontent?id=$id&modid=".$data['special_block_id']));exit;
            }
            
            $ref_info = $this->SP->getContentinfoByData($data['type'], $data['ref_id']);
            //var_dump($data,$ref_info, 12);exit;
            if (!$ref_info) {
                redirect(base_url("/special/addcontent?id=$id&modid=".$data['special_block_id']));exit;
            }
            //var_dump($data,$ref_info);exit;
            // 新添内容默认放在block的第一位置
            unset($data['priority']);
            $cid = $this->SP->db('sports')->insert('special_content', $data);
            $this->SP->db('sports')->setTbSort('special_content', $cid, array('priority' => 1), array('special_block_id' => $data['special_block_id']));
            
            redirect(base_url("/special/addmod?id=$id&is_up=$is_up"));exit;
        }
        
        $data = array(
            'id'    => $id,
            'modid' => $modid,
            'is_up' => $is_up
        );
        $this->load->view('special/addcontent',$data);
    }
    
    private function _setTags($specialId, $tags){
        $tagsArr = explode(',',$tags);
        $this->SP->db('sports')->where(array('special_id' => $specialId))->delete('special_tag');
        $full = array();
        foreach($tagsArr as $tagId){
            $full[] = array('special_id' => $specialId, 'tag_id' => $tagId);
        }
        $this->SP->db('sports')->insert_batch('special_tag',$full);
    }
    

    public function remove(){
        $id = $_POST['id'];
        if(strpos($id,',').'' != ''){
            $ids = explode(',',$id);
            try{
                $this->SP->db('sports')->remove_batch('special',$ids);
                echo 'success';
            }catch(Exception $e){
                echo 'fail';
            }
        }else{
            try{
                $this->SP->db('sports')->remove('special',$id);
                echo 'success';
            }catch(Exception $e){
                echo 'fail';
            }
        }
    }

    public function removecontent()
    {
        $cid = $this->input->post('cid');
        if (!$cid) {
            echo json_encode(array('status'=>-1, 'info'=>'data error'));
            return false;
        }
        
        $this->SP->db('sports')->remove('special_content', $cid);
        
        echo json_encode(array('status'=>0));
    }
    
    public function updatecontentsort(){
        $data = $_POST;
        //var_dump($data);exit;
        $cid = $data['cid'];
        $bid = $data['bid'];
        $sort = intval($data['sort']);
        // $result = $this->SP->db('sports')->update('special_content', array('priority' => $sort), array('id'=>$cid));
        $result = $this->SP->db('sports')->setTbSort('special_content', $cid, array('priority' => $sort), array('special_block_id' => $bid));
        
        if (!$result) {
            echo json_encode(array('status'=>-1, 'info'=>'error'));
            return false;
        }
        
        echo json_encode(array('status'=>0));
    }
    
    //上下线 专题
    public function upstatus()
    {
        $id = $this->input->post('pk');
        if (!$id) {
            echo 'pk error';exit;
        }
    
        $special_info = $this->SP->getSpecialById($id);
        if (!$special_info) {
            echo 'data error';exit;
        }
    
        if ($special_info['visible']) {
            $data = array('visible'=>0);
        } else {
            $data = array('visible'=>1);
        }
    
        try{
            $result = $this->SP->db('sports')->update('special', $data, array('id'=>$id));
            
            if (!$data['visible']) {
                $this->_deleteRelated('special', $id);
            }
            echo 'success';
        } catch(Exception $e) {
            echo 'fail';
        }
        //var_dump($data, $slide_info);
    }

    //版块上下线
    public function upblockstatus()
    {
        $id = $this->input->post('pk');
        if (!$id) {
            echo 'pk error';exit;
        }
    
        $block_info = $this->SP->getBlockById($id);
        if (!$block_info) {
            echo 'data error';exit;
        }
    
        if ($block_info['visible']) {
            $data = array('visible'=>0);
        } else {
            $data = array('visible'=>1);
        }
    
        try{
            $result = $this->SP->db('sports')->update('special_block', $data, array('id'=>$id));
            echo 'success';
        } catch(Exception $e) {
            echo 'fail';
        }
        //var_dump($data, $slide_info);
    }
    
    //版块上下线
    public function upcontentstatus()
    {
        $id = $this->input->post('pk');
        $table = $this->input->post('field');
        if (!$id || !$table) {
            echo 'data error';exit;
        }
    
        $content_info = $this->SP->getContentinfoByData($table, $id);
        if (!$content_info) {
            echo 'data error 1';exit;
        }

        if ($content_info[0]['visible']) {
            $data = array('visible'=>0);
        } else {
            $data = array('visible'=>1);
        }
    
        try{
            $result = $this->SP->db('sports')->update($table, $data, array('id'=>$id));
            echo 'success';
        } catch(Exception $e) {
            echo 'fail';
        }
        //var_dump($data, $slide_info);
    }
    
}
