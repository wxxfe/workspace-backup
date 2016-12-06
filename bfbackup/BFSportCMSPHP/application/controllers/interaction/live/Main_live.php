<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_live extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Match_model', 'MM');
    }

    public function index($matchId, $host = '') {
        $this->config->load('sports');
        
        $data = array();
        $data['match'] = $this->MM->getMatchById($matchId);
        $data['matchId'] = $matchId;
        $data['match_vs'] = $this->MM->getMatchInfo($matchId, null, false);
        $data['host'] = $host;
        // $data['status'] = array('notstarted' => '未开始', 'ongoing' => '直播中', 'finished' => '已结束', 'postponed' => '比赛推迟', 'cancelled' => '取消');
        $data['status'] = $this->config->item('match_statuses');
        
        
        $this->load->view('interaction/live/main', $data);
    }

    public function updateScore($match_id) {

        $team1_score = $this->input->post('team1_score');
        $team2_score = $this->input->post('team2_score');
        $this->MM->db('sports')->update('match_extra_team', array('team1_score' => $team1_score, 'team2_score' => $team2_score), array('match_id' => $match_id));

        // 发送比分消息推送
        $rtn = $this->MM->sendMatchStatus($match_id);
        echo 'success';
    }
    
    public function refreshScore($match_id) {
        $match = $this->MM->getMatchById($match_id);

        echo json_encode($match);
    }
    
    public function addScore($match_id) {
        $team_pa  = $this->input->post('team_pa');
        $score = $this->input->post('score');
        if ($score < 0) {
            $direction ='';
        } else {
            $direction ='+';
        }
        
        $sql = "UPDATE `match_extra_team` SET `".$team_pa.'_score'."` = ".$team_pa.'_score'.$direction.$score." WHERE `match_id` = '{$match_id}'";
        $this->MM->db('sports')->query($sql);
        // 发送比分消息推送
        $rtn = $this->MM->sendMatchStatus($match_id);
        $match = $this->MM->getMatchById($match_id);
        
        echo json_encode($match);
    }
}
