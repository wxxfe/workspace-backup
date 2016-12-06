<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ex_live_model extends MY_Model {
    private $table_name='ex_live';

    public function __construct() {
        parent::__construct();
    }

    /**
     * 列表
     * @param $limit
     * @param $offset
     * @param array $where
     */
    public function getList($limit=20, $offset=0, $where=array()) {
        $query = $this->dbSports->select("*")->from($this->table_name);
        if(!empty($where)) {
            $query->where($where);
        }
        $query->order_by('id', 'DESC');
        $query->limit($limit, $offset);
        $result = $query->get();
        $data = $result ? $result->result_array() : array();
        return $data;
    }

    public function getInfo($id){
        $query = $this->dbSports->get_where($this->table_name,array('id' => intval($id)));
        return $query->row_array();
    }

    /**
     * 获取推荐直播
     * @return array
     */
    public function getRecommendMatch() {
        $this->db('sports')->select('match.*,ex_live.id as ex_live_id')
            ->join('match', 'match.id=ex_live.match_id', 'LEFT');
        $this->db('sports')->order_by('ex_live.id', 'DESC');
        $query = $this->db('sports')->get($this->table_name);

        $result = array();
        foreach ($query->result_array() as $item) {
            if ($item['type'] == 'team') {
                $team_info = $this->getMatchTeamInfo($item['id']);
                $item['team_info'] = $team_info;
            } else if ($item['type'] == 'player') {
                $player_info = $this->getMatchPlayerInfo($item['id']);
                $item['player_info'] = $player_info;
            } else if ($item['type'] == 'various') {
                $various_info = $this->getMatchVariousInfo($item['id']);
                $item['various_info'] = $various_info;
            }

            $item['forecast'] = $this->getMatchForecast($item['id']);
            $item['live'] = $this->getMatchLive($item['id']);
            $result[] = $item;
        }
        return $result;
    }

    protected function getMatchTeamInfo($match_id) {
        $query = $this->db('sports')->get_where('match_extra_team', array('match_id' => $match_id), 1);
        $match_info = $query->row_array();
        $team1_id = $match_info['team1_id'];
        $team2_id = $match_info['team2_id'];

        $query = $this->db('sports')->select('*')
            ->where_in('id', array($team1_id, $team2_id))
            ->get('team');
        foreach ($query->result_array() as $team_info) {
            if ($team_info['id'] == $match_info['team1_id']) {
                $match_info['team1_info'] = $team_info;
            }
            if ($team_info['id'] == $match_info['team2_id']) {
                $match_info['team2_info'] = $team_info;
            }
        }

        return $match_info;
    }

    protected function getMatchPlayerInfo($match_id) {
        $query = $this->db('sports')->get_where('match_extra_player', array('match_id' => $match_id), 1);
        $match_info = $query->row_array();
        $player1_id = $match_info['player1_id'];
        $player2_id = $match_info['player2_id'];

        $query = $this->db('sports')->select('*')
            ->where_in('id', array($player1_id, $player2_id))
            ->get('player');
        foreach ($query->result_array() as $player_info) {
            if ($player_info['id'] == $match_info['player1_id']) {
                $match_info['player1_info'] = $player_info;
            }
            if ($player_info['id'] == $match_info['player2_id']) {
                $match_info['player2_info'] = $player_info;
            }
        }

        return $match_info;
    }

    protected function getMatchVariousInfo($match_id) {
        return $this->db('sports')->get_where('match_extra_various', array('match_id' => $match_id), 1)->row_array();
    }

    protected function getMatchForecast($match_id) {
        $query = $this->db('sports')->select('*')
            ->where('match_id', $match_id)->order_by('priority DESC, id ASC')
            ->get('match_forecast');
        return $query->result_array();
    }

    protected function getMatchLive($match_id) {
        $query = $this->db('sports')->select('*')
            ->where('match_id', $match_id)
            ->get('match_live_stream');
        return $query->result_array();
    }
}
