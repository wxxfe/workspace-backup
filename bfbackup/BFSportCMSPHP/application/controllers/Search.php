<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 1. 建索引的方法，在项目根目录下执行
 *     - 标签 `php index.php search indexer tag [incr]`
 *     - 其他内容 `php index.php search indexer items [incr]`
 *
 * 2. 搜索异步接口
 *     - `/search/query/tag/{keyword}`
 *     - `/search/query/items/{type}/{keyword}/{offset}/{limit}`
 *
 * 3. 也可以直接使用`application/models/Search_model.php`里的搜索方法
 */
class Search extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Search_model', 'SM');
    }
    
    public function indexer($db, $action = '') {
        if (php_sapi_name() != 'cli') {
            show_404();
        }
        
        if ($action && $action != 'incr') {
            echo "Usage: $ php index.php search indexer database [incr]\n";
            exit();
        }
        
        if ($this->SM->lockIndexer($db)) {
            $args = func_get_args();
            unset($args[0]);
            $method = "{$db}Indexer";
            $result = call_user_func_array(array($this->SM, $method), $args);
            
            $this->SM->unlockIndexer($db);
            return $result;
        } else {
            echo "locked by another process!\n";
        }
    }
    
    public function query($db) {
        $args = func_get_args();
        unset($args[0]);
        $method = "{$db}Query";
        echo json_encode(call_user_func_array(array($this->SM, $method), $args));
    }
    
}