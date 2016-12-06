<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('notification_model', 'NM');
        $this->limit = 10;
        $this->load->config('sports');
        $this->ntypes = $this->config->item('notification_types');
    }
    
    public function index() {
        return $this->getList();
    }
    
    public function getList($platf='android', $offset=0) {
        $list = $this->NM->getList($platf, $this->limit, $offset);
        $total = $this->NM->getTotal('notification', array('platf'=>$platf));
        
        $data = array();
        $data['page'] = $this->_pagination($total, $this->limit, '/notification/getlist');
        $data['list'] = $list;
        $data['platf'] = $platf;
        $data['ntypes'] = $this->ntypes;
        $this->load->view('notification/list', $data);
    }
    
    public function add($platf) {
        $data = array();
        $data['ntypes'] = $this->ntypes;
        $data['platf']  = $platf;
        
        if (!empty($_POST)) {
            $notificatioin_id = $this->_addNotification($_POST);
            
            $redirect = $this->input->get('redirect');
            if (!empty($redirect)) {
                redirect($redirect);
            } else {
                redirect(site_url('notification/getlist/').$platf);
            }
        }
        $this->load->view('notification/add', $data);
    }
    
    public function edit($id) {
        $info = $this->NM->getInfo($id);
        $platf = $info['platf'];
        
        if (!empty($_POST)) {
            $this->_editNotification($id, $_POST);
        
            $redirect = $this->input->get('redirect');
            if (!empty($redirect)) {
                redirect($redirect);
            } else {
                redirect(site_url('notification/getlist/').$platf);
            }
        }
        
        $data['ntypes'] = $this->ntypes;
        $data['platf']  = $platf;
        $data['info'] = $info;
        $data['nid']  = $id;
        $this->load->view('notification/add', $data);
    }
    
    public function send() {
        $id = $this->input->post('id');
        $platf = $this->input->post('platf');
        
        if (empty($id) || empty($platf)) {
            echo 'fail';
            return true;
        }
        
        // 拼接发送内容
        $send_data = $this->NM->createPushData($id);
        
        // 发送友盟推送
        $this->load->model('push', 'PUSH');
        if ($platf == 'android') {
            $this->PUSH->umengSendAndroidBroadcast($send_data);
        } else {
            $this->PUSH->umengSendIOSBroadcast($send_data);
        }
        
        // 更新发送状态
        $data = array('send_stat'=>1);
        $this->NM->updateInfo($id, $data);
        
        echo 'success';
        return true;
    }
    
    public function getRelInfo() {
        $type = $this->input->post('type');
        $ref_id = $this->input->post('ref_id');
        if (in_array($type, array('match', 'video', 'news', 'program'))) {
            $info = $this->NM->getRelInfo($type, $ref_id);
        }
        
        if (empty($info)) {
            echo 'fail';
        } else {
            echo json_encode($info);
        }
        return;
    }
    
    private function _addNotification($post_info) {
        $title = isset($post_info['title']) ? $post_info['title'] : '';
        $desc  = isset($post_info['desc']) ? $post_info['desc'] : '';
        $platf = isset($post_info['platf']) ? $post_info['platf'] : 'android';
        $type  = isset($post_info['type']) ? $post_info['type'] : '';
        $url = '';
        $ref_id = 0;
        if ($type == 'h5') {
            $url = $post_info['content'];
        } else {
            $ref_id = $post_info['content'];
        }
        if (empty($type)) {
            return false;
        }
        
        $data = array(
                'title' => $title,
                'desc'  => $desc,
                'platf' => $platf,
                'type'  => $type,
                'url'   => $url,
                'ref_id'=> $ref_id
        );
        
        $notification_id = $this->NM->addInfo($data);
        
        return $notification_id;
    }

    private function _editNotification($id, $post_info) {
        $title = isset($post_info['title']) ? $post_info['title'] : '';
        $desc  = isset($post_info['desc']) ? $post_info['desc'] : '';
        $platf = isset($post_info['platf']) ? $post_info['platf'] : 'android';
        $type  = isset($post_info['type']) ? $post_info['type'] : '';
        $url = '';
        $ref_id = 0;
        if ($type == 'h5') {
            $url = $post_info['content'];
        } else {
            $ref_id = $post_info['content'];
        }
        if (empty($type)) {
            return false;
        }
        
        $data = array(
                'title' => $title,
                'desc'  => $desc,
                'platf' => $platf,
                'type'  => $type,
                'url'   => $url,
                'ref_id'=> $ref_id
        );
        
        $this->NM->updateInfo($id, $data);
        
        return true;
    }
}