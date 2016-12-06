<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Match extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Match_model', 'MM');
    }

    public function index($event_id = 0, $date = '-') {
        $data['event_id'] = $event_id;
        $data['date'] = $date;
        $this->load->view('match/list', $data);
    }

    /**
     * 增加或编辑比赛信息
     */
    public function edit($match_id = 0) {
        $this->config->load('sports');
        $this->load->model('Event_model', 'EM');

        $referer = @strval($_GET['referer']);

        $info = $_POST;
        if ($info) {
            // $match_id大于0为编辑，否则为添加
            $post_match_id = @intval($info['match_id']);
            if ($match_id != $post_match_id) {
                die('match_id不匹配！');
            }

            $this->MM->db('sports')->trans_start();

            // match表
            $match = array();
            $match['type'] = $info['type'];
            $match['start_tm'] = $info['start_tm'];
            $match['event_id'] = $info['event_id'];
            $match['title'] = $info['title'];
            $match['stage'] = $info['stage'];
            $match['brief'] = $info['brief'];
            $match['brief2'] = $info['brief2'];
            $match['status'] = $info['status'];
            $match['live_type'] = $info['live_type'];
            if ($match_id) {
                $this->MM->db('sports')->update('match', $match, array('id' => $match_id));
            } else {
                $match_id = $this->MM->db('sports')->insert('match', $match);
            }

            // match_extra表
            if ($match_id) {
                $extra = array();
                $extra['match_id'] = $match_id;
                $extra['bgimage'] = $info['bgimage'];
                $extra['highlight'] = $info['highlight'];
                $extra['has_host'] = (@$info['has_host'] == 'on' ? 1 : 0);
                $extra['stats_url'] = @strval(trim($info['stats_url']));

                $extra_id = @intval($info['extra_id']);
                if ($extra_id) {
                    $this->MM->db('sports')->update('match_extra', $extra, array('id' => $extra_id));
                } else {
                    $this->MM->db('sports')->insert('match_extra', $extra);
                }
            }

            // match_extra_*表
            $type = $info['type'];
            if ($match_id) {
                $extra_table = "match_extra_{$type}";

                $extra = array();
                $extra['match_id'] = $match_id;
                if (in_array($type, array('team', 'player'))) {
                    $extra["{$type}1_id"] = @intval($info["{$type}1_id"]);
                    $extra["{$type}1_score"] = @intval($info["{$type}1_score"]);
                    $extra["{$type}2_id"] = @intval($info["{$type}2_id"]);
                    $extra["{$type}2_score"] = @intval($info["{$type}2_score"]);
                } elseif ($type == 'various') {
                    $extra["name_1st"] = $info["various_name_1st"];
                    $extra["image_1st"] = $info["various_image_1st"];
                    $extra["name_2nd"] = $info["various_name_2nd"];
                    $extra["image_2nd"] = $info["various_image_2nd"];
                    $extra["name_3rd"] = $info["various_name_3rd"];
                    $extra["image_3rd"] = $info["various_image_3rd"];
                }

                $extra_info_id = @intval($info['extra_info_id']);
                if ($extra_info_id) {   // 已存在则更新
                    $this->MM->db('sports')->update($extra_table, $extra, array('id' => $extra_info_id));
                } else {                // 否则插入
                    $this->MM->db('sports')->insert($extra_table, $extra);
                }
            }

            $this->MM->db('sports')->trans_complete();
            
            // 在编辑页编辑比赛信息，会发送推送。服务端刷新比赛状态用
            if (!empty($post_match_id)) {
                $this->MM->sendMatchStatus($match_id);
            }

            echo $match_id;
            exit();
        }

        $data['referer'] = $referer;
        $data['match'] = ($match_id > 0) ? $this->MM->getMatchById($match_id) : array();
        $data['match_id'] = intval($match_id);
        $data['live_types'] = $this->config->item('live_types');
        $data['match_types'] = $this->config->item('match_types');
        $data['match_statuses'] = $this->config->item('match_statuses');
        $data['forecast_types'] = $this->config->item('match_forecast_types');
        $data['live_sites'] = $this->MM->getSites();
        $data['events'] = $this->EM->getEvents();
        $this->load->view('match/edit', $data);
    }

    public function delete($match_id) {
        $this->MM->db('sports')->remove('match', $match_id);
        if (!@empty($_GET['referer'])) {
            redirect($_GET['referer']);
        } else {
            redirect('/match/index');
        }
    }

    // ajax
    public function setVisible($table = 'match') {
        parent::setVisible($table);
    }

    /////////////////////////////////////////////////////////////

    public function addForecast() {
        $row = array();
        $row['match_id'] = @intval($_POST['match_id']);
        $row['title'] = @trim(addslashes($_POST['title']));
        $row['type'] = @trim($_POST['type']);

        $data = @trim($_POST['data']);
        if (in_array($row['type'], array('news', 'thread'))) {
            $row['ref_id'] = intval($data);
        } else {
            $row['data'] = addslashes($data);
        }

        $id = $this->MM->db('sports')->insert('match_forecast', $row);
        echo intval($id);
        exit();
    }

    public function editForecast() {
        $id = @intval($_POST['id']);
        $data = @trim($_POST['data']);

        $row = array();
        $row['title'] = @trim(addslashes($_POST['title']));
        $row['type'] = @trim($_POST['type']);
        if (in_array($row['type'], array('news', 'thread'))) {
            $row['ref_id'] = intval($data);
        } else {
            $row['data'] = addslashes($data);
        }

        if ($id > 0) {
            $this->MM->db('sports')->update('match_forecast', $row, array('id' => $id));
            echo 1;
            exit();
        }
        echo 0;
        exit();
    }

    public function deleteForecast() {
        $id = @intval($_POST['id']);
        if ($id > 0) {
            $this->MM->db('sports')->remove('match_forecast', $id);
            echo 1;
            exit();
        }
        echo 0;
        exit();
    }

    public function setForecastSort() {
        $id = @intval($_POST['id']);
        $match_id = @intval($_POST['match_id']);
        $sort = @intval($_POST['sort']);

        if ($id && $match_id && $sort) {
            echo (int)$this->AM->db('sports')->setTbSort('match_forecast', $id, array('priority' => $sort), array('match_id' => $match_id));
        } else {
            echo 0;
        }

        exit();
    }

    /////////////////////////////////////////////////////////////

    public function addLive() {
        $row = $_POST;
     /*   if ($row['isvr'] == 1) {
            $row['play_url'] = "http://live.baofengcloud.com/48853043/player/cloud.swf?" . $row['play_code2'] . "&auto=1&si=1";
        } else {
            $row['play_url'] = "http://live.baofengcloud.com/48842737/player/cloud.swf?" . $row['play_code2'] . "&auto=1&si=1";
        }*/
        $id = $this->MM->db('sports')->insert('match_live_stream', $row);
      /*  if(intval($id)>0)
        {
            $media_file_rate_data[] = array('type' => 'stream', 'ref_id' => intval($id), 'title' => '超清', 'isvip' => 0, 'args' => '超清', 'priority' => 3, 'visible' => 1);
            $media_file_rate_data[] = array('type' => 'stream', 'ref_id' => intval($id), 'title' => '高清', 'isvip' => 0, 'args' => '高清', 'priority' => 2, 'visible' => 1);
            $media_file_rate_data[] = array('type' => 'stream', 'ref_id' => intval($id), 'title' => '标清', 'isvip' => 0, 'args' => '标清', 'priority' => 1, 'visible' => 1);
            $this->MM->db('sports')->insert_batch('media_file_rate', $media_file_rate_data);
        }*/
        echo intval($id);
        exit();
    }

    public function editLive() {
        $info = $_POST;
        $id = @intval($info['id']);
        unset($info['id']);
        if ($id > 0) {
            $this->MM->db('sports')->update('match_live_stream', $info, array('id' => $id));
            echo 1;
            exit();
        }
        echo 0;
        exit();
    }

    public function deleteLive() {
        $id = @intval($_POST['id']);
        if ($id > 0) {
            $this->MM->db('sports')->remove('match_live_stream', $id);
        /*    $condition = array(
                'ref_id' => $id
            );
            $this->MM->db('sports')->delete('media_file_rate', $condition);*/
            echo 1;
            exit();
        }
        echo 0;
        exit();
    }

    /**
     * 关联比赛的选择比赛页面
     * @param int $event_id
     * @param string $date
     */
    public function matchSelect($event_id = 0, $date = '-') {
        $data['event_id'] = $event_id;
        $data['date'] = $date;
        $this->load->view('match/match_select', $data);
    }


    /**
     * 根据比赛ID获取比赛通用显示信息API
     * @param $match_id
     */
    public function getMatchInfo($match_id) {
        echo $this->MM->getMatchInfo($match_id);
    }
    
    /**
     * 设置通用字段的值（ajax）
     * 此方法在复制MY_Controller的同名方法的同时，做了以下修改：
     * 在exit()之前，判断，如果修改的是match表格的status字段，则发送推送至互动直播系统
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
        
        /////////////////////////////////////////////////
        if ($table == 'match' && $field == 'status') {
            $this->MM->sendMatchStatus($id);
        }
        /////////////////////////////////////////////////
        exit();
    }

    /**
     *  直播管理直播流逻辑修改，创建云视频和p2p视频
     */
    public function createAndGetChannel() {
        $data = $_POST;
        $this->load->model('Create_cpvideo_model','CCM');
        $result = $this->CCM->createAndGetChannel($data);
        echo $result;
    }

}