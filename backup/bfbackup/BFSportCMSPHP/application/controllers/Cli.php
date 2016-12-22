<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 互动直播间话题-互动事件
 */
class Cli extends CI_Controller {

    private $_path = null;

    public function __construct() {
        parent::__construct();
        $this->_path = dirname(BASEPATH);
    }
    
    // php index.php Cli createLiveEventsConfig
    public function createLiveEventsConfig() {
        $this->load->model('Eventhd_model', 'EHM');
        
        $this->upload_path = $this->_path.'/upload/live_source_tmp/';
        $this->static_path = $this->_path.'/static/upload/live_source/';
        $this->source_path = '/static/upload/live_source/';
        
        $config = array(
                'events' => array(),
                'static' => array(),
        );
    
        $events = $this->EHM->getAllEvents();
        foreach ($events as $event) {
            $config_event_item = array();
            $config_event_item = array(
                    'id' => intval($event['id']),
                    'sports_id' => intval($event['sport_id']),
                    'title' => $event['title'],
                    'desc'  => $event['desc'],
                    'duration' => intval($event['duration']),
                    'total_duration' => intval($event['total_duration']),
                    'image' => $event['image'],
                    'audio' => $event['audio'],
                    'video' => $event['video'],
                    'video_repeat' => intval($event['video_repeat']),
                    'tools_info' => array(),
            );
            if (!empty($event['image']) && !in_array($event['image'], $config['static'])) {
                array_push($config['static'], $event['image']);
            }
            if (!empty($event['video']) && !in_array($event['video'], $config['static'])) {
                array_push($config['static'], $event['video']);
            }
            if (!empty($event['audio']) && !in_array($event['audio'], $config['static'])) {
                array_push($config['static'], $event['audio']);
            }
            $event_tools = $this->EHM->getEventTools($event['id']);
    
            foreach($event_tools as $et) {
                $config_event_item['tools_info'][] = array(
                        'et_id' => intval($et['id']),
                        'title' => $et['title'],
                        'type'  => $et['type'],
                        'action'=> intval($et['action']),
                        'duration'=> intval($et['duration']),
                        'image' => $et['image'],
                        'audio' => $et['audio'],
                        'video' => $et['video'],
                        'video_repeat' => intval($et['video_repeat']),
                );
                if (!empty($et['image']) && !in_array($et['image'], $config['static'])) {
                    array_push($config['static'], $et['image']);
                }
                if (!empty($et['video']) && !in_array($et['video'], $config['static'])) {
                    array_push($config['static'], $et['video']);
                }
                if (!empty($et['audio']) && !in_array($et['audio'], $config['static'])) {
                    array_push($config['static'], $et['audio']);
                }
            }
    
            $config['events'][] = $config_event_item;
        }
    
        // 生成打包目录
        exec('rm -R '.$this->_path.'/static/upload/live_source_pack/');
        exec('mkdir '.$this->_path.'/static/upload/live_source_pack/');
        foreach ($config['static'] as $source) {
            list($dir, $file) = explode('/', $source);
            exec('mkdir '.$this->_path.'/static/upload/live_source_pack/'.$dir);
            exec('cp '.$this->static_path.$source.' '.$this->_path.'/static/upload/live_source_pack/'.$source);
        }
    
        file_put_contents($this->_path.'/static/upload/live_source_pack/config.json', json_encode($config));
        echo 'success';
    }

    /**
     * 正式库往审核库导入数据
     * php index.php Cli transDataToIosdb
     */
    public function transDataToIosdb() {
        $this->load->model('Transdb_model', 'TDM');
        $this->ol_filepath = $this->_path.'/upload/trans_db/';
        
        // 查询需要保存上线状态的比赛信息
        $online_status = $this->TDM->before();
        // 保存文件
        file_put_contents($this->ol_filepath.'/before', json_encode($online_status));
//         exit();
        
        // 导出、导入sql文件
        //////////////////////////////////////////////////////////////////////////
        $ol_config = array(
                'host' => '192.168.204.240',
                'user' => 'sports_rw',
                'password' => 'Q41IrJPg7S#1',
                'database' => 'sports',
                'dsn'  => 'mysql:host=192.168.204.240;dbname=sports'
        );
        
        $ios_config = array(
                'host' => '192.168.204.240',
                'user' => 'sports_rw',
                'password' => 'Q41IrJPg7S#1',
                'database' => 'xsports',
                'dsn'  => 'mysql:host=192.168.204.240;dbname=xsports'
        );
        $ol_filename = 'db_file.sql';
        
        exec("mysqldump -h {$ol_config['host']} -u{$ol_config['user']} -p{$ol_config['password']} {$ol_config['database']} > ".$this->ol_filepath.$ol_filename);
        exec("mysql -h {$ios_config['host']} -u{$ios_config['user']} -p{$ios_config['password']} {$ios_config['database']} < ".$this->ol_filepath.$ol_filename);
        ///////////////////////////////////////////////////////////////////////////
        
        $before_data = json_decode(file_get_contents($this->ol_filepath.'/before'), true);
        $this->TDM->after($before_data);
        exec('rm '.$this->ol_filepath.'start');
        file_put_contents($this->ol_filepath.'end', date('Y-m-d H:i:s'));
    }
}