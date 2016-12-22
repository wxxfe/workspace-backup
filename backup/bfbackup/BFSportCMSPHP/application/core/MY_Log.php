<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Log extends CI_Log {
    protected $_orig_log_path;
    protected $_dbsql_log_path;
    protected $_levels = array('ERROR' => 1, 'DEBUG' => 2, 'INFO' => 3, 'ALL' => 4, 'DBSQL' => 5);
    
    public function __construct() {
        parent::__construct();
        $this->_orig_log_path = $this->_log_path;
        
    }
    
    public function write_log($level, $msg) {
        if ($level == 'dbsql') {
            $sql = $msg;
            $msg = $this->createDbsqlMsg($sql);
            $this->use_dbsql_path();
            if (substr($sql, 0, 6) != 'SELECT') {
                parent::write_log($level, $msg);
            }
            $this->reset_log_path();
        } else {
            parent::write_log($level, $msg);
        }
    }
    
    public function reset_log_path() {
        $this->set_log_path($this->_orig_log_path);
    }
    
    public function use_dbsql_path() {
        $year = date('Y');
        $week = date('W');
        $this->_dbsql_log_path = APPPATH.'logs/dbsql/'.$year.'_week'.$week.'/';
        $this->set_log_path($this->_dbsql_log_path);
    }
    
    public function set_log_path($path) {
        file_exists($path) OR mkdir($path, 0755, TRUE);
        $this->_log_path = $path;
    }
    
    protected function createDbsqlMsg($sql) {
        $CI = & get_instance();
        $CI->load->library('session');
        $log_id = $CI->session->userdata('log_id');
        $user_info = json_decode($CI->session->userdata('user_info'), true);
        $sql = str_replace(array("\n"), ' ', $sql);
        if (isset($user_info['current_menu']) && !empty($user_info['current_menu'])) {
            $msg = 'log_id:'.$log_id.';user_id:'.$user_info['id'].';menu:'.$user_info['current_menu']['route'].';sql:'.$sql;
        } else {
            $msg = 'log_id:'.$log_id.';user_id:'.$user_info['id'].';sql:'.$sql;
        }
        return $msg;
    }
}