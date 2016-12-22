<?php
require_once APPPATH . 'libraries/BFCloudPHPSDK/VideoMgr.php';

/**
 * Class BfcUpload
 * 暴风云视频类
 */
class BfcUpload{
    public $cloud_uid;
    public $obj;
    public function __construct($cloudname=array()){
        $cloudname = isset($cloudname[0]) && !empty($cloudname[0]) ? $cloudname[0] : '';
        if(!$cloudname) {
            return false;
        }

        $CI = &get_instance();
        $CI->load->config('bfcloud',true,true);
        $config = $CI->config->item('bfcloud');

        if(!isset($config[$cloudname])) {
            return false;
        }
        $clound_config = $config[$cloudname];
        $this->cloud_uid = $clound_config['uid'];
        $this->obj = new VideoMgr($clound_config['accesskey'], $clound_config['secretkey']);
    }

    public function __call($method, $params){
        if(!$this->obj) {
            return false;
        }
        return call_user_func_array(array($this->obj, $method), $params);
    }
}
?>