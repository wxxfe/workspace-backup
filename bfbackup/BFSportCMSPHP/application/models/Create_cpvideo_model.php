<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Create_cpvideo_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function createAndGetChannel($data) {
        //云视频创建频道

        $ifpublic   = 1;//数值型，频道属性（0是私有，1是公有）
        $ifrecord   = 0;    //数值型，是否允许录制，0表示不录制，1表示录制，取1时下面的4个参数有效
        //$recordname = $data['channelName'];   //字符型，录制文件名前缀，可以为空，如果不指定该字段则录制文件以频道名称为前缀
        //$sizelimit  = 20971520; //数值型，录制文件最大字节数，以B为单位，最小不低于10M，最大不超过2G，与timelimit二选一。设置一个，另一个设为0
        //$timelimit  = 0; //数值型，录制文件最大时长，以秒为单位，最小不低于3分钟，最大不超过2小时，与sizelimit二选一。设置一个，另一个设为0
        //$recordposition = 0;    //数值型，录制文件存储位置，0表示云托管，1表示点播，默认存储到云托管
        //$callback   = '';    //字符型，录制完成时的回调地址，可以为空，如果不指定该字段则不回调
        $deadline   = time()+3600; //过期时间, Unix时间，从1970年01月01日起至今的秒数
        $iftranscode = 1;//是否允许转码，0表示不允许，1表示允许
        $CI = &get_instance();
        $CI->load->config('bfcloud',true,true);
        $config = $CI->config->item('bfcloud');
        $type = $data['type'];

        $accessKey = $config[$type]['accesskey'];
        $secretkey = $config[$type]['secretkey'];

        $request_data_yun['ifpublic'] = $ifpublic;
        $request_data_yun['channelname'] = $data['channelName'];
        $request_data_yun['deadline'] = $deadline;
        $request_data_yun['ifrecord'] = $ifrecord;
        $request_data_yun['iftranscode'] = $iftranscode;
        $request_data_yun['transchannel'][] = array('definition'=>0,'width'=>480,'height'=>270,'videocodec'=>0,'audiocodec'=>0,'videobitrate'=>300,'audiobitrate'=>48);
        $request_data_yun['transchannel'][] = array('definition'=>1,'width'=>640,'height'=>360,'videocodec'=>0,'audiocodec'=>0,'videobitrate'=>500,'audiobitrate'=>48);
        $request_data_yun['transchannel'][] = array('definition'=>2,'width'=>1280,'height'=>720,'videocodec'=>0,'audiocodec'=>0,'videobitrate'=>1024,'audiobitrate'=>64);

        $encodedData = base64_encode(json_encode($request_data_yun));
        $sign = hash_hmac('sha1',$encodedData,$secretkey,true);
        $encodedSign = base64_encode($sign);
        $token =  $accessKey.":".$encodedSign.":".$encodedData;
        $result_cloud = send_get($config['cloudUrl'].urlencode($token));
        $result_cloud = json_decode($result_cloud,true);

        //p2p创建视频
        $request_data_p2p['uid'] = $config['uid'];
        $request_data_p2p['ukey'] = $config['ukey'];
        $request_data_p2p['starttime'] = strtotime($data['beginTime']);
        $request_data_p2p['channelname'] = $data['channelName'];

        $result_p2p = send_post($config['p2pUrl'],json_encode($request_data_p2p));
        $result_p2p = json_decode($result_p2p,true);

        if(isset($result_cloud['status']) && $result_cloud['status'] == 0 && isset($result_p2p['status']) && $result_p2p['status'] == 0)
        {
            unset($data['ref_id']);
            unset($data['beginTime']);
            unset($data['channelName']);

            $data['play_code2'] = $result_cloud['url'];
            $data['feed_code2'] = "rtmp://123.57.69.146/bfclive/" . $result_cloud['channelid'];
            if ($data['type'] == 'vr') {
                $data['play_url'] = "http://live.baofengcloud.com/48853043/player/cloud.swf?" . $result_cloud['url'] . "&auto=1&si=1";
            } else {
                $data['play_url'] = "http://live.baofengcloud.com/48842737/player/cloud.swf?" . $result_cloud['url'] . "&auto=1&si=1";
            }

            $data['play_code'] = $result_p2p['qstp'];
            $data['feed_code'] = $result_p2p['rtmpurl'];

            unset($data['type']);
            if (isset($data['match/createAndGetChannel']))
                unset($data['match/createAndGetChannel']);
            $data['vr_delayed_range_start'] = intval($data['vr_delayed_range_start']);
            $data['vr_delayed_range_end'] = intval($data['vr_delayed_range_end']);
            $id = $this->db('sports')->insert('match_live_stream', $data);
            if($id != false)
            {
                $media_file_rate_data[] = array('type' => 'stream', 'ref_id' => intval($id), 'title' => '超清', 'isvip' => 0, 'args' => '超清', 'priority' => 3, 'visible' => 1);
                $media_file_rate_data[] = array('type' => 'stream', 'ref_id' => intval($id), 'title' => '高清', 'isvip' => 0, 'args' => '高清', 'priority' => 2, 'visible' => 1);
                $media_file_rate_data[] = array('type' => 'stream', 'ref_id' => intval($id), 'title' => '标清', 'isvip' => 0, 'args' => '标清', 'priority' => 1, 'visible' => 1);
                $this->db('sports')->insert_batch('media_file_rate', $media_file_rate_data);
                return 'success';
            }
            return 'fail';
        }
        return 'fail';
    }
}