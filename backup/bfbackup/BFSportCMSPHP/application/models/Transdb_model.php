<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transdb_model extends MY_Model {
    
    public function __construct() {
        parent::__construct();
        $this->filepath = dirname(BASEPATH).'/upload/trans_db/';
    }
    
    public function page() {
        $result = array();
        
        if (file_exists($this->filepath.'forbidden')) {
            $result['stat'] = 'forbidden';
            $result['key_date'] = '禁止操作';
        } else if (file_exists($this->filepath.'start')) {
            $result['stat'] = 'running';
            $result['key_date'] = file_get_contents($this->filepath.'start');
        } else if (file_exists($this->filepath.'end')) {
            $result['stat'] = 'end';
            $result['key_date'] = file_get_contents($this->filepath.'end');
        } else {
            $result['stat'] = 'end';
            $result['key_date'] = '未知';
        }
        
        return $result;
    }
    
    public function start() {
        if (file_exists($this->filepath.'start')) {
            return false;
        }
        
        
        file_put_contents($this->filepath.'start', date('Y-m-d H:i:s'));
        pclose(popen('cd '.dirname(BASEPATH).';/usr/local/bin/php index.php Cli transDataToIosdb &', 'r'));
        // exec('cd '.dirname(BASEPATH).';/usr/local/bin/php index.php Cli transDataToIosdb &');
        return true;
    }
    
    public function before() {
        // 需要保存的信息
        // 1.中超、德甲
        // 2.上线状态
        // 3.比赛（直播赛程）
        $visible_config = array(
            'match' => array('event_id' => array(3, 7)),
        );
        
        $result = array();
        $online = array();
        $offline = array();
        foreach ($visible_config as $table => $filter) {
            $this->db('sports')->select('id,visible')->from($table);;
            foreach ($filter as $f_k => $f_v) {
                $this->db('sports')->where_in($f_k, $f_v);
            }
            $query = $this->db('sports')->get();
            
            foreach ($query->result_array() as $item) {
                if ($item['visible'] == 1) {
                    if (!isset($online[$table])) {
                        $online[$table] = array();
                    }
                    array_push($online[$table], $item['id']);
                } else {
                    if (!isset($offline[$table])) {
                        $offline[$table] = array();
                    }
                    array_push($offline[$table], $item['id']);
                }
            }
        }
        
        $result = array(
            'online' => $online,
            'offline'=> $offline,
        );
        
        return $result;
    }
    
    public function after($before_data) {
        // 根据规则，处理需要下线的信息
        $offline_config = array(
            'channel' => 'id not in (1,8,13,16,22)',
            'event'   => 'id not in (3,7)',
            'match'   => 'event_id not in (3,7,10)',
        );
        foreach ($offline_config as $table => $condition) {
            $this->db('sports')->update($table, array('visible'=>0), $condition);
        }
        
        // 处理需要保存状态的before数据
        $online = $before_data['online'];
        $offline = $before_data['offline'];
        foreach ($online as $table=>$ids) {
            foreach ($ids as $id) {
                $this->db('sports')->update($table, array('visible'=>1), array('id' => $id));
            }
        }
        foreach ($offline as $table=>$ids) {
            foreach ($ids as $id) {
                $this->db('sports')->update($table, array('visible'=>0), array('id' => $id));
            }
        }
        
        return true;
    }
}