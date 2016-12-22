<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends MY_Controller {

    protected $_table = 'role';
    
    public function __construct() {
        parent::__construct();
        $this->load->model('cms/role_model', 'RM');
    }
    
    public function index($action='') {

        if($_POST){
            $this->createRole($_POST);
            exit();
        }

        $data = array();
        $data['roles'] = $this->RM->getRole();
        $data['isWrite'] = $this->AM->canModify();

        $this->load->view('cms/role',$data);

    }

    public function update(){
        $row = $_POST;
        if (!isset($row['pk']) || $row['pk'] == 0) {
            return false;
        }
        $id = intval($row['pk']);
        $newData = array($row['name'] => $row['value']);
        return (boolean)$this->RM->update($this->_table, $newData, array('id' => $id));
    }

    private function createRole($role){
        $data = $role;
        $this->RM->createRole($data);
        redirect(base_url('/cms/role'));
    }

    
}
