<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    public $userInfo;

    public function __construct() {
        parent::__construct();
        $this->load->library('session');

        $this->load->model('cms/user_model', 'UM');
        $this->load->model('cms/acl_model', 'AM');
        $this->load->model('cms/log_model', 'LM');

        $acl_uri = 'cms/acl';
        $admin_uri = $acl_uri . '/admin';
        if (stripos(uri_string(), $acl_uri) === false || stripos(uri_string(), $admin_uri) !== false) {
            if ($this->UM->hasUserSession()) {
                $this->userInfo = $this->UM->getUserInfoFromSession();
                $this->_generateUserMenuPower($this->AM->getAcl(1));
                if (!in_array($this->uri->segment(1), array('', 'main')) && !$this->AM->canAccess()) {
                    $this->_showPage403();
                }
            } else {
                redirect($acl_uri . '/login');
            }
        }
    }
    
    /**
     * 根据id删除记录（ajax）
     */
    public function deleteById($table) {
        $id = @intval($_POST['id']);
        if (!$id) {
            die('fail');
        }
        echo (int)$this->AM->db('sports')->remove($table, $id);
        exit();
    }
    
    /**
     * 设置通用字段的值（ajax）
     *
     * @param $table 表名
     * @param $field 字段名
     */
    public function setField($table, $field) {
        $data = $_POST;
        $id = @intval($data['id']);
        
        // 兼容x-editable: pk, name, value
        if (!$id && isset($data['pk']) && $field == $data['name']) {
            $id = intval($data['pk']);
            $data[$field] = $data['value'];
        }
        
        if (!$id || !isset($data[$field])) {
            die('fail');
        }
        
        echo $this->AM->db('sports')->update($table, array($field => trim($data[$field])), array('id' => $id));
        exit();
    }

    /**
     * 设置记录是否可见（ajax）
     */
    public function setVisible($table) {
        if ($_POST['name'] != 'visible') {
            die('fail');
        }
        
        $id = intval($_POST['pk']);
        echo $this->AM->db('sports')->update($table, array($_POST['name'] => $_POST['value']), array('id' => $id));
        exit();
    }

    /**
     * 设置记录的排序优先级（ajax）
     */
    public function setPriority($table) {
        if (!$this->AM->canModify()) {
            echo json_encode(array(
                'status' => -1, 'msg' => '无权操作！',
            ));
            exit();
        }
        
        $data = $_POST;
        $id = intval(isset($data['id'])? $data['id'] : (isset($data['pk'])? $data['pk'] : 0));
        $sort = intval(isset($data['priority'])? $data['priority'] : (isset($data['value'])? $data['value'] : 0));
        
        $result = false;
        if ($id) {
            $result = $this->AM->db('sports')->setTbSort($table, $id, array('priority' => $sort));
        }
        
        if ($result) {
            echo json_encode(array(
                'status' => 0, 'msg' => '修改成功',
            ));
        } else {
            echo json_encode(array(
                'status' => -1, 'msg' => '修改失败！',
            ));
        }
        exit();
    }
    
    /**
     * 删除关联表的记录
     */
    protected function _deleteRelated($table, $id) {
	$tables_down_save_tags = array(
	    'collection','gallery','program','special',	
	);
        $tables_has_tag = array(        // {table}_tag
            'video', 'news', 'collection', 'gallery', 'program', 'special', 'activity',
        );
        $tables_has_related = array(    // {table}_related, match_related
            'video', 'news', 'collection', 'gallery',
        );
        
        if (in_array($table, $tables_has_tag)&&!(in_array($table, $tables_down_save_tags))) {
            $this->AM->db('sports')->where("{$table}_id", intval($id))->delete("{$table}_tag");
        }
        if (in_array($table, $tables_has_related)) {
            $this->AM->db('sports')->where("{$table}_id", intval($id))->delete("{$table}_related");
            $this->AM->db('sports')->where(array('type' => $table, 'ref_id' => intval($id)))->delete('match_related');
        }
        
        return true;
    }

    /**
     * 分页
     * @param int $total 数据总数
     * @param int $offset 分页区间
     * @param string $route 分页地址路由,例: "/news/list/"
     * @return string 返回分页HTML代码
     */
    protected function _pagination($total, $offset = 20, $route, $myconfig=array()) {
        $this->load->library('pagination');
        $this->load->library('kfpagination');
        $config['base_url'] = base_url($route);
        $config['total_rows'] = $total;
        $config['per_page'] = $offset;
        $config['reuse_query_string'] = TRUE;
        $config['num_links'] = 15;
        $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_link'] = '上一页';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '下一页';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_link'] = '末页';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['first_link'] = '首页';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config = array_merge($config, $myconfig);
        $this->kfpagination->initialize($config);
        return $this->kfpagination->create_links();
    }

    protected function _generateUserMenuPower($acl) {
        $current_power = 0;
        $current_menu = array();
        $menu = $nodes = array();
        $powers = $this->userInfo['powers'];

        foreach ($acl as $val) {
            $nodes[$val['id']] = $val;
        }

        // powers of children
        $cpowers = array();
        $_cnodes = $nodes;
        krsort($_cnodes, SORT_NUMERIC);
        foreach ($_cnodes as $node) {
            $parent = $node['parent'];
            $nodeid = $node['id'];
            $power = isset($powers[$nodeid]) ? $powers[$nodeid] : 0;
            if ($parent > 0) {
                if (isset($cpowers[$parent])) {
                    $cpowers[$parent] |= $power;
                } else {
                    $cpowers[$parent] = $power;
                }
            }
        }

        foreach ($nodes as &$node) {
            $parent = $node['parent'];
            $nodeid = $node['id'];

            if ($parent == 0) {
                // if (isset($powers[$nodeid]) && $powers[$nodeid] > 0) {
                if (!isset($powers[$nodeid]) || $powers[$nodeid] > 0) {
                    $menu[$nodeid] = &$node;
                }
            } else {
                if (!isset($powers[$nodeid])) {
                    $powers[$nodeid] = (isset($powers[$parent]) ? $powers[$parent] : 0);
                }
                if ($powers[$nodeid] > 0 || (isset($cpowers[$nodeid]) && $cpowers[$nodeid] > 0)) {
                    $nodes[$parent]['children'][] = &$node;
                }
            }

            if (stripos(uri_string(), trim($node['route'], '/') ?: '#') === 0) {
                $current_power = &$powers[$nodeid];
                $current_menu = array(
                    'id' => $node['id'],
                    'name' => $node['name'],
                    'route' => $node['route'],
                );
            }
        }

        $this->userInfo['menu'] = $menu;
        $this->userInfo['powers'] = $powers;
        $this->userInfo['current_power'] = $current_power;
        $this->userInfo['current_menu'] = $current_menu;
        $this->_setUserInfo($this->userInfo);
    }

    protected function _setUserInfo($user) {
        $this->session->set_userdata('user_id', $user['id']);
        $this->session->set_userdata('user_info', json_encode($user));
        $this->userInfo = $user;
    }

    protected function _changeUserInfo($user) {
        $this->userInfo = array_merge($this->userInfo, $user);
        $this->session->set_userdata('user_info', json_encode($this->userInfo));
    }

    protected function _getUserPowers($user_id) {
        return $this->AM->getUserPowers($user_id) + $this->AM->getRolePowers($this->UM->getRoleIds($user_id));
    }

    protected function _showPage403() {
        show_error('您无权访问此项！', 403);
    }

    /**
     * 检查当前分页偏移值是否大等于总数，如果是，则属于无效偏移量，需要跳转有数据的一页或者第一页
     * @param $offset 分页偏移量
     * @param $limit 每页偏移量
     * @param $page_total 总量
     * @param $url 跳转URL前缀
     */
    protected function _pageCheck($offset, $limit, $page_total, $url) {
        if ($offset > 0 AND $offset >= $page_total) {
            if ($offset == $page_total) {
                $offset = $page_total - $limit;
            } else {
                $offset = floor($page_total / $limit) * $limit;
            }
            redirect($url . $offset);
        }
    }

    /**
     * 获得分页显示需要的完整数据
     * @param $offset 分页偏移量
     * @param $limit 每页偏移量
     * @param $page_total 总量
     * @param $url 分页url前缀
     * @return array
     */
    protected function _pageData($offset, $limit, $page_total, $url) {
        $data['page'] = $this->_pagination($page_total, $limit, $url);
        $data['page_limit'] = $limit;
        $data['page_total'] = $page_total;
        $data['page_offset'] = $offset;
        return $data;
    }

}
