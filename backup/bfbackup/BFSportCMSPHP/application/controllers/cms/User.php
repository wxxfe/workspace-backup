<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function info() {
        $this->load->view('cms/userinfo', array('user' => $this->userInfo));
    }

    public function index($page=0){
        if($_POST){
            $this->createUser($_POST);
            exit();
        }
        $this->load->model('cms/Role_model','RM');
        $data = array();
        $users = $this->UM->getUsers(null,$page,10);
        $userData = array();
        foreach($users as $user){
            unset($user['password']);
            $user['roles'] = $this->RM->getRoleByUserId($user['id']);
            $userData[] = $user;
        }
        $total = $this->UM->getTotal('user');
        $data['page'] = $this->_pagination($total,10,'/cms/user/index/');
        $data['users'] = $userData;
        $data['roles'] = $this->RM->getRole();
        $data['isWrite'] = $this->AM->canModify();
        $this->load->view('cms/user', $data);
    }

    public function update() {
        $row = $_POST;
        if (!isset($row['pk']) || $row['pk'] == 0 || !$this->AM->canModify()) {
            return false;
        }
        $id = intval($row['pk']);
        if ($row['name'] != 'role') {
            if ($row['name'] == 'password') {
                $row['value'] = $this->UM->encrypt($row['value'], '', $id);
            }
            $newData = array($row['name'] => $row['value']);
            if ($id == $this->userInfo['id']) {
                $this->_changeUserInfo($newData);
            }
            return (boolean)$this->UM->db('kungfu')->update('user', $newData, array('id' => $id));
        } else {
            $rids = array('user_id' => $row['pk']);
            $sql = "DELETE FROM user_role WHERE user_id={$row['pk']}";
            $this->UM->db('kungfu')->query($sql);
            // $this->UM->db('kungfu')->remove_batch('user_role',$rids);

            if (isset($row['value'])) {
                $newData = array();
                foreach ($row['value'] as $item) {
                    $role = array('user_id' => $row['pk'],'role_id' => $item,'enable' => 1);
                    $newData[] = $role;
                }
                $this->UM->batch_insert('user_role',$newData);
            }
        }
    }

    public function updateUserInfo() {
        $row = $_POST;
        if (!isset($row['pk']) || $row['pk'] == 0) {
            return false;
        }
        
        $id = intval($row['pk']);
        if ($row['name'] == 'password') {
            $row['value'] = $this->UM->encrypt($row['value'], '', $id);
        }
        $newData = array($row['name'] => $row['value']);
        if ($id == $this->userInfo['id']) {
            $this->_changeUserInfo($newData);
        }
        return (boolean)$this->UM->update('user', $newData, array('id' => $id));
    }

    public function createPassword($length=8) {
        echo create_password($length);
    }

    private function createUser($user) {
        $data = $user;
        unset($data['image']);
        if (isset($data['password'])) {
            $data['password'] = $this->UM->encrypt($data['password'], $data['username']);
        }
        $this->UM->db('kungfu')->insert('user', $data);
        redirect(base_url('/cms/user'));
    }

}
