<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data['headerUrl'] = site_url('/main/header');
        $data['asideUrl'] = site_url('/main/aside');
        $data['dashboardUrl'] = site_url('/main/dashboard');
        $this->load->view('main', $data);
    }

    public function header() {
        $data['user'] = $this->userInfo;
        $data['menu'] = $this->userInfo['menu'];
        $this->load->view('/cms/header', $data);
    }

    public function aside($acl_parent=0) {
        $menu = array();
        if ($acl_parent > 0) {
            $menu = $this->userInfo['menu'];
            foreach ($menu as $k => $v) {
                if ($v['id'] != $acl_parent) {
                    unset($menu[$k]);
                }
            }
            $menu = array_values($menu);
            if (isset($menu[0]['children'])) {
                $menu = $menu[0]['children'];
            } else {
                $menu = array();
            }
        }
        
        $data['menu'] = $menu;
        $data['user'] = $this->userInfo;
        $this->load->view('/cms/aside', $data);
    }

    public function dashboard() {
        $data = array();
        $data['user'] = $this->userInfo;
        
        // 加载用户的操作日志
        $offset   = isset($_REQUEST['offset']) ? intval($_REQUEST['offset']) : 0;
        $limit    = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 10;
        if ($offset < 0) {
            $offset = 0;
        }
        if ($limit <= 0) {
            $limit = 10;
        }
        $log_date = isset($_REQUEST['log_date']) ? $_REQUEST['log_date'] : '';
        $condition = array(
            'offset' => $offset,
            'limit'  => $limit,
            'log_date' => $log_date
        );
        $log = $this->LM->getByUid($this->userInfo['id'], $condition);
        $data['log'] = $log;
        
        $this->load->view('/cms/dashboard', $data);
    }

}
