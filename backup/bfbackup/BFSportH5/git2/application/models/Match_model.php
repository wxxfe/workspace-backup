<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Match_model extends MY_Model {
    function __construct() {
        parent::__construct();
    }

    /**
     * 通过比赛id，获得完整的比赛数据，包括match关联表
     * @param $match_id
     * @return array|mixed
     */
    public function getMatchById($match_id) {
        $query = $this->db('sports')->get_where('match', array('id' => $match_id));
        $matches = $this->extendMatches($query->result_array());
        if ($matches) {
            return $matches[0];
        } else {
            return array();
        }
    }

    /**
     * 完整的比赛数据生成，包括match关联表，支持根据多个match表的行数据生成
     * @param $matches
     * @return array
     */
    protected function extendMatches($matches) {
        $result = array();
        foreach ($matches as $item) {
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

            $item['show_data'] = $this->getMatchShowData($item);

            $item['extra'] = $this->getMatchExtra($item['id']);
            $item['forecast'] = $this->getMatchForecast($item['id']);
            $item['live_stream'] = $this->getMatchLiveStream($item['id']);

            $result[] = $item;
        }
        return $result;
    }

    /**
     * 获得团体对阵数据
     * @param $match_id
     * @return mixed
     */
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


    /**
     * 获得个人对阵数据
     * @param $match_id
     * @return mixed
     */
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

    /**
     * 获得非对阵数据
     * @param $match_id
     * @return mixed
     */
    protected function getMatchVariousInfo($match_id) {
        return $this->db('sports')->get_where('match_extra_various', array('match_id' => $match_id), 1)->row_array();
    }

    /**
     * 背景图，是否有主持人，统计，高亮等
     * @param $match_id
     * @return array
     */
    protected function getMatchExtra($match_id) {
        return (array)$this->db('sports')->get_where('match_extra', array('match_id' => $match_id), 1)->row_array();
    }

    /**
     * 获得比赛的预测数据
     * @param $match_id
     * @return mixed
     */
    protected function getMatchForecast($match_id) {
        $query = $this->db('sports')->select('*')
                ->where('match_id', $match_id)->order_by('priority DESC, id ASC')
                ->get('match_forecast');
        return $query->result_array();
    }

    /**
     * 获取直播流，播放用（只返回一个）
     * @param $match_id
     * @return mixed
     */
    protected function getMatchLiveStream($match_id) {
        $query = $this->db('sports')->select('*')->where('match_id', $match_id)->get('match_live_stream');
        return $query->result_array();
    }

    /**
     * 根据比赛ID获取比赛通用显示信息
     * @param $match_data 比赛ID或者比赛数组
     * @param $start_tm 是否加上开始时间,如果是null，则不显示，其他字符串则显示，并且作为连接后面字符串的连接符
     * @return string
     */
    public function getMatchShowData($match_data, $start_tm = ' ') {
        $show_data = array();
        $show_data['txt_1'] = '';
        $show_data['txt_2'] = '';
        $show_data['vs_1'] = null;
        $show_data['vs_2'] = null;

        if ($match_data) {

            //比赛数据
            if (is_array($match_data)) {
                $match = $match_data;
            } else {
                $match = $this->getMatchById($match_data);
            }

            if ($match) {

                if ($match['type'] == 'various') {

                    $show_data['txt_1'] .= $match['title'];
                    $show_data['txt_2'] = $match['title'];

                } else {

                    switch ($match['type']) {
                        case 'team':
                            if (isset($match['team_info']) && $match['team_info']) {

                                $show_data['vs_1'] = $match['team_info']['team1_info'];
                                $show_data['vs_1']['extra_score'] = $match['team_info']['team1_score'];
                                $show_data['vs_1']['extra_likes'] = $match['team_info']['team1_likes'];
                                $show_data['vs_2'] = $match['team_info']['team2_info'];
                                $show_data['vs_2']['extra_score'] = $match['team_info']['team2_score'];
                                $show_data['vs_2']['extra_likes'] = $match['team_info']['team2_likes'];

                                $show_data['txt_1'] .= ($show_data['vs_1']['name'] . 'VS' . $show_data['vs_2']['name']);
                            }
                            break;
                        case 'player':
                            if (isset($match['player_info']) && $match['player_info']) {

                                $show_data['vs_1'] = $match['player_info']['player1_info'];
                                $show_data['vs_1']['extra_score'] = $match['player_info']['player1_score'];
                                $show_data['vs_1']['extra_likes'] = $match['player_info']['player1_likes'];
                                $show_data['vs_2'] = $match['player_info']['player2_info'];
                                $show_data['vs_2']['extra_score'] = $match['player_info']['player2_score'];
                                $show_data['vs_2']['extra_likes'] = $match['player_info']['player2_likes'];
                                $show_data['txt_1'] .= ($show_data['vs_1']['name'] . 'VS' . $show_data['vs_2']['name']);
                            }
                            break;
                    }

                    $show_data['txt_2'] = $match['brief'];

                }

                if ($start_tm != null) {
                    $show_data['txt_1'] .= ($start_tm . substr($match['start_tm'], 0, -3));
                }

            }
        }
        return $show_data;
    }

}
