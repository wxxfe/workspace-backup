<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acl extends MY_Controller {
    protected $_table  = 'acl';
    protected $_powers = array(1 => '查看', 2 => '增改', 4 => '删除');
    
    public function __construct() {
        parent::__construct();
    }
    
    public function login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $user = $this->UM->login($username, $password);
        
        $data = array();
        $data['user_id'] = 0;
        if ($user) {
            $data['user_id'] = $user['id'];
            $user['password'] = md5($user['password']); // phony
            $user['powers'] = $this->_getUserPowers($user['id']);
            
            $this->_setUserInfo($user);
            // 添加代码，记录用户的登录信息
            // $this->userInfo['current_menu']['route']
            $this->LM->uadd('user_login', $route='', array());
        } else {
            $data['is_login_failed'] = false;
            if ($username || $password) {
                $data['is_login_failed'] = true;
            }
        }
        
        $this->load->view('cms/login', $data);
    }
    
    public function logout() {
        $this->session->sess_destroy();
        // $this->load->view('cms/login');
        redirect(site_url());
    }
    
    public function admin($action='list') {
        $action = strtolower($action);
        $method = '_admin' . ucwords($action);
        if (!method_exists($this, $method)) {
            show_404();
        }
        
        $rtn = call_user_func_array(array($this, $method), array());
        if ($action == 'list') {
            return $rtn;
        } else {

            $result = array(
                'status' => 'succ'
            );

            if($rtn){
                $result['data'] = $rtn;
            }else{
                $result['status'] = 'fail';
            }

            echo json_encode($result);
        }
    }
    
    private function _adminUserjson() {
        $role_id = @intval($_GET['role_id']);
        $user_id = @intval($_GET['user_id']);
        if ($role_id > 0) {
            $powers = $this->AM->getRolePowers($role_id);
        } elseif ($user_id > 0) {
            $powers = $this->_getUserPowers($user_id);
        } else {
            show_404();
        }
        
        $menu = $nodes = array();
        
        $acl = $this->AM->getAcl(1);
        foreach ($acl as $val) {
            $nodes[$val['id']] = $val;
        }
        
        foreach ($nodes as &$node) {
            $parent = $node['parent'];
            $nodeid = $node['id'];
            
            if ($parent == 0) {
                $menu[$nodeid] = & $node;
            } else {
                if (!isset($powers[$nodeid])) {
                    $powers[$nodeid] = (isset($powers[$parent])? $powers[$parent] : 0);
                }
                $nodes[$parent]['children'][] = & $node;
            }
            
            $node['text']    = $node['name'];
            $node['li_attr'] = new stdClass();
            $node['a_attr']  = new stdClass();
            $node['state']   = array(
                'opened'   => true,
                'disabled' => false,
            );
            
            if ($node['type'] == 'item') {
                foreach ($this->_powers as $p => $text) {
                    $node['children'][] = array(
                        'id'      => "{$nodeid}_{$p}",
                        'text'    => $text,
                        'icon'    => '',
                        'li_attr' => new stdClass(),
                        'a_attr'  => new stdClass(),
                        'state'   => array(
                            'opened'   => true,
                            'disabled' => false,
                            'selected' => boolval($powers[$nodeid] & $p),
                        ),
                    );
                }
            }
            
            unset($node['name']);
            unset($node['route']);
            unset($node['type']);
            unset($node['parent']);
            unset($node['sort']);
            unset($node['enable']);
            unset($node['created_at']);
            unset($node['updated_at']);
        }
        
        echo json_encode(array_values($menu));
        exit();
    }
    
    private function _adminChangePower() {
        $role_id = @intval($_POST['role_id']);
        $user_id = @intval($_POST['user_id']);
        $powers  = @json_decode($_POST['powers'], true);
        
        if (empty($powers)) {
            return false;
        }
        if ($role_id > 0) {
            $user_id = 'null';
        } elseif ($user_id > 0) {
            $role_id = 'null';
        } else {
            return false;
        }
        
        $sql = '';
        $sql_values = array();
        foreach ($powers as $acl_id => $power) {
            $sql_values[] = "({$role_id}, {$user_id}, {$acl_id}, {$power})";
        }
        $sql_values = implode(', ', $sql_values);
        
        $sql = "INSERT INTO power (role_id, user_id, acl_id, power) VALUES {$sql_values}
            ON DUPLICATE KEY UPDATE power=VALUES(power), enable=1";
        
        return $this->AM->db('kungfu')->query($sql);
    }
    
    private function _adminList() {
        $row = $_GET;

        $isEnable = isset($row['enable']) ? $row['enable'] : null;
        
        $menu = $nodes = array();
        
        $acl = $this->AM->getAcl($isEnable);
        foreach ($acl as $val) {
            $nodes[$val['id']] = $val;
        }
        
        foreach ($nodes as &$node) {
            $parent = $node['parent'];
            $nodeid = $node['id'];
            
            if ($parent == 0) {
                $menu[$nodeid] = &$node;
            } else {
                $nodes[$parent]['children'][] = &$node;
            }
        }

        $data['acl'] = $menu;

        $this->load->view('cms/category', $data);
    }

    private function _adminAdd() {
        $row = $_POST;
        if (!isset($row['parent'])) {
            return false;
        }
        if (@empty($row['sort'])) {
            $this->AM->db('kungfu')->select_max('sort');
            $sort = $this->AM->db('kungfu')->get_where($this->_table, array('parent' => intval($row['parent'])))->row()->sort;
            $row['sort'] = $sort + 1;
        }
        return $this->AM->db('kungfu')->insert($this->_table, $row);
    }
    
    private function _adminUpdate() {
        $row = $_POST;
        if (!isset($row['id']) || $row['id'] == 0) {
            return false;
        }
        $id = intval($row['id']);
        unset($row['id']);
        return (boolean)$this->AM->db('kungfu')->update($this->_table, $row, array('id' => $id));
    }
    
}
