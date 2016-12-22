<?php
class Log_model extends MY_Model {
    protected $userInfo;
    
    protected $_userlog_index;
    
    public function __construct() {
        parent::__construct();
        // 获取userlog类型配置文件
        include(APPPATH.'config/userlog.php');
        
        // 获取用户信息
        $this->load->model('cms/user_model', 'UM');
        if ($this->UM->hasUserSession()) {
            $this->userInfo = $this->UM->getUserInfoFromSession();
        } else {
            $this->userInfo = array();
        }
        
        $this->_userlog_index = $_USERLOG_INDEX;
        
        // 设置本条log_id的id
        $this->load->library('session');
        $log_id = $this->_createUserlogID();
        $this->session->set_userdata('log_id', $log_id);
    }
    
    // 注意，$USER_LOG_ID是字符串型，需要做一个id生成规则
    // 最好自己取user_info信息
    public function uadd($action, $route='', $data=array()) {
        if (!isset($this->_userlog_index[$action])) {
            return FALSE;
        }
        // 登录时取不到userInfo，需要重新加载
        if (empty($this->userInfo)) {
            $this->userInfo = $this->UM->getUserInfoFromSession();
        }
        $user_id = $this->userInfo['id'];
        $formated_data = array();
        foreach ($data as $k=>$v) {
            if (in_array($k, $this->_userlog_index[$action]['data_format'])) {
                $formated_data[$k] = $v;
            }
        }
        
        $this->_insertUserLog($user_id, $action, $route, $formated_data);
        
        return TRUE;
    }
    
    
    public function addDataLog($user_log_id) {
        echo 'add data log';
    }
    
    /**
     * 
     * @param intval $user_id
     * @param array  $condition
     * 
     * $condition 包含内容
     * offset, limit, log_date
     */
    public function getByUid($user_id, $condition) {
        $offset = isset($condition['offset']) ? intval($condition['offset']) : 0;
        $limit  = isset($condition['limit']) ? intval($condition['limit']) : 10;
        $log_date = isset($condition['log_date']) ? $condition['log_date'] : '';
        $whereArray = array(
            'user_id' => $user_id,
        );
        if (!empty($log_date)) {
            $table = $this->_getTableNameByDate('log_user_action', $log_date);
//             $whereArray = array('created_at' => array('gt' => $log_date, 
//                     'lt' => date(strtotime($log_date, '+1 day'), 'Y-m-d')));
        } else {
            $table = $this->_getTableNameAndCreate('log_user_action');
        }

        $query = $this->dbKungfu->select('*')
            ->where($whereArray)
            ->order_by('created_at','DESC')
            ->offset($offset)
            ->limit($limit)
            ->get($table);

        $list = $query->result_array();
        foreach ($list as & $item) {
            $item['iclass'] = $this->_userlog_index[$item['type']]['iclass'];
            $item['desc'] = $this->_userlog_index[$item['type']]['desc'];
            $item['data'] = json_decode($item['data'], TRUE);
            if (isset($item['data']['image'])) {
                $item['data']['image_url'] = getImageUrl($item['data']['image']);
            }
        }
        
        return $list;
    }

    protected function _insertUserLog($user_id, $action, $route, $formated_data) {
        $insertArray = array(
            'id'      => $this->session->userdata('log_id'),
            'user_id' => $user_id,
            'type'    => $action,
            'route'   => $route,
            'data'    => json_encode($formated_data),
        );
        
        $table = $this->_getTableNameAndCreate('log_user_action');

        $this->dbKungfu->insert($table, $insertArray);

        return $this->dbKungfu->insert_id();
    }
    
    protected function _insertDataLog() {
        
    }
    
    protected function _createUserlogID() {
        $prefix = date('ymdHis');
        $log_id = $prefix.'-'.rand(000,999);
        return $log_id;
    }
    
    // 用户行为日志，按周分表，表名 年+周
    protected function _getTableNameAndCreate($orig_table_name) {
        $postfix = date('YW');
        $table_name = $orig_table_name.'_'.$postfix;
        $exist_sql = "SELECT table_name FROM information_schema.TABLES WHERE table_name ='{$table_name}'";
        $query  = $this->dbKungfu->query($exist_sql);
        $result = $query->row_array();
        if (empty($result)) {
            $create_table_sql = " CREATE TABLE `{$table_name}` (
              `id` varchar(32) NOT NULL DEFAULT '',
              `user_id` int(10) unsigned NOT NULL DEFAULT '0',
              `type` varchar(32) DEFAULT NULL,
              `route` varchar(128) DEFAULT NULL,
              `data` text,
              `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`),
              KEY `user_id` (`user_id`),
              KEY `created_at` (`created_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
            $result = $this->dbKungfu->query($create_table_sql);
        }
        
        return $table_name;
    }
    
    // 根据要获取的日志的日期，推断分表名
    protected function _getTableNameByDate($orig_table_name, $date) {
        $postfix = date('YW', strtotime($date));
        return $orig_table_name.'_'.$postfix;
    }
}
