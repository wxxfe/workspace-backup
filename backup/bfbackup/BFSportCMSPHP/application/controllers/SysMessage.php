<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SysMessage extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Sysmessage_model', 'SMM');
        $this->limit = 10;
    }

    public function index() {
        return $this->getList();
    }

    public function getList($offset = 0) {
        $list = $this->SMM->getList($this->limit, $offset);
        $total = $this->SMM->getTotal('sys_message');

        $data = array();
        $data['page'] = $this->_pagination($total, $this->limit, '/sysmessage/getlist');
        $data['list'] = $list;
        $this->load->view('sysmessage/list', $data);
    }

    public function add() {
        $data = array();

        if (!empty($_POST)) {
            $this->_addSysMessage();

            $redirect = $this->input->get('redirect');
            if (!empty($redirect)) {
                redirect($redirect);
            } else {
                redirect(site_url('SysMessage/getlist/'));
            }
        }
        $this->load->view('sysmessage/add', $data);
    }

    public function edit($id) {
        $info = $this->SMM->getInfo($id);

        if (!empty($_POST)) {
            $this->_editSysMessage($id, $_POST);

            $redirect = $this->input->get('redirect');
            if (!empty($redirect)) {
                redirect($redirect);
            } else {
                redirect(site_url('SysMessage/getlist/'));
            }
        }

        $info['users'] = null;
        if ($info['user_ids'] && $info['user_ids'] != 'all') {
            $info['users'] = $this->SMM->getUsersAPI($info['user_ids']);
        }

        $data['info'] = $info;
        $data['sid'] = $id;



        $this->load->view('sysmessage/add', $data);
    }

    public function remove() {
        $sysmsg_id = $this->input->post('id');

        $this->SMM->deleteInfo($sysmsg_id);
        echo 'success';
        return true;
    }

    public function send() {
        $sysmsg_id = $this->input->post('id');

        $sysmsg_info = $this->SMM->getInfo($sysmsg_id);
        $user_ids = $sysmsg_info['user_ids'];
        $user_ids_arr = explode(',', $user_ids);

        $content = array(
            'content' => $sysmsg_info['content']
        );
        if (!empty($sysmsg_info['image'])) {
            $content['url'] = getImageUrl($sysmsg_info['image']);
        } else {
            $content['url'] = '';
        }

        $send_data = array(
            'type' => 's_all',
            'data' => json_encode($content),
        );
        //$send_data['user_id']是用户的id或者字符串"all"
        foreach ($user_ids_arr as $uid) {
            if (!empty($uid)) {
                $send_data['user_id'] = $uid;
                $this->SMM->send($send_data);
                unset($send_data['user_id']);
            }
        }

        $this->SMM->updateInfo($sysmsg_id, array('send_at' => date('Y-m-d H:i:s'), 'send_status' => 1));

        echo 'success';
    }


    private function _getPost() {
        $user_ids = $this->input->post('user_ids');
        $content = $this->input->post('content');
        $image = $this->input->post('cover');

        if (empty($user_ids)) {
            $user_ids = 'all';
        }

        $data = array(
            'user_ids' => $user_ids,
            'content' => $content,
            'image' => $image
        );
        return $data;
    }

    private function _addSysMessage() {
        $sysmessage_id = $this->SMM->addInfo($this->_getPost());
        return $sysmessage_id;
    }

    private function _editSysMessage($id) {
        $this->SMM->updateInfo($id, $this->_getPost());
        return true;
    }
}