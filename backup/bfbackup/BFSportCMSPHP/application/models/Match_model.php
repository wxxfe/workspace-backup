<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Match_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getSites() {
        $result = array();
        $rows = $this->dbSports->select('site, origin')->get('site')->result_array();
        foreach ($rows as $row) {
            $result[$row['site']] = $row['origin'];
        }
        return $result;
    }


    /**
     * 根据比赛ID获取比赛通用显示信息
     * @param $matchId
     * @param bool $start_tm 是否加上开始时间,如果是null，则不显示，其他字符串则显示，并且作为连接后面字符串的连接符
     * @param bool $various 是否显示非对阵式
     * @return string
     */
    public function getMatchInfo($matchId, $start_tm = ' ', $various = true) {
        $match_info = '';
        if ($matchId) {
            //比赛数据
            $match = $this->getMatchById($matchId);
            if ($match) {
                if ($start_tm != null) $match_info .= (substr($match['start_tm'], 0, -3) . $start_tm);
                switch ($match['type']) {
                    case 'team':
                        if (isset($match['team_info']) && $match['team_info']) {

                            $match_info .= ($match['team_info']['team1_info']['name'] . 'VS' . $match['team_info']['team2_info']['name']);
                        }
                        break;
                    case 'player':
                        if (isset($match['player_info']) && $match['player_info']) {
                            $match_info .= ($match['player_info']['player1_info']['name'] . 'VS' . $match['player_info']['player2_info']['name']);
                        }
                        break;
                    case 'various':
                        if ($various) $match_info .= $match['title'];
                        break;
                }
            }
        }
        return $match_info;
    }

    public function getMatchCountByWeek($date, $event_id) {
        $result = array();

        $days_in_week = array(
            1 => '一', 2 => '二', 3 => '三', 4 => '四', 5 => '五', 6 => '六', 7 => '日'
        );
        $week = date('W', strtotime($date));
        $week_day = date('N', strtotime($date));
        $week_start_date = date('Y-m-d', strtotime("{$date} - " . intval($week_day - 1) . " days"));
        $week_prev_date = date('Y-m-d', strtotime("{$week_start_date} - 1 days"));
        $week_next_date = date('Y-m-d', strtotime("{$week_start_date} + 7 days"));
        $days = 1;
        $now_day = $week_start_date;
        $result['week_prev_date'] = $week_prev_date;
        $result['week_next_date'] = $week_next_date;
        $result['week_data'] = array();
        while ($days <= 7) {
            $next_day = date('Y-m-d', strtotime("{$now_day} +1 days"));
            // 获取本日的比赛数据
            $this->db('sports')->select('*');
            if ($event_id > 0) {
                $this->db('sports')->where('event_id', $event_id);
            }
            $query = $this->db('sports')->where('start_tm >=', $now_day)
                ->where('start_tm <', $next_day)
                ->count_all_results('match');
            $result['week_data'][] = array(
                'date' => $now_day,
                'count' => $query,
                'day' => $days_in_week[date('N', strtotime($now_day))],
            );

            $now_day = $next_day;
            $days++;
        }

        return $result;
    }

    public function getSelectedMatch($event_id) {
        $this->db('sports')->select('match.*,selected_match.priority as priority,selected_match.id as sm_id')
            ->join('match', 'match.id=selected_match.match_id', 'LEFT');
        if (!empty($event_id)) {
            $this->db('sports')->where('selected_match.event_id', $event_id);
        } else {
            $this->db('sports')->where('selected_match.event_id IS NULL');
        }
        $this->db('sports')->order_by('selected_match.priority', 'DESC');
        $query = $this->db('sports')->get('selected_match');

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

    public function getSelectedMatchSimple($event_id) {
        $query = $this->db('sports')->select('*')
            ->where('event_id', $event_id)
            ->order_by('priority', 'DESC')
            ->get('selected_match');

        return $query->result_array();
    }

    public function getMatchById($match_id) {
        $query = $this->db('sports')->get_where('match', array('id' => $match_id));
        $matches = $this->extendMatches($query->result_array());
        if ($matches) {
            return $matches[0];
        } else {
            return array();
        }
    }

    public function getMatch($event_id, $day) {
        $begin_tm = date('Y-m-d H:i:s', strtotime($day));
        $end_tm = date('Y-m-d H:i:s', strtotime($day) + 86400);
        $this->db('sports')->select('*');
        if ($event_id > 0) {
            $this->db('sports')->where('event_id', $event_id);
        }
        $query = $this->db('sports')->where('start_tm >=', $begin_tm)
            ->where('start_tm <', $end_tm)
            ->order_by('start_tm', 'ASC')
            ->get('match');

        return $this->extendMatches($query->result_array());
    }

    public function updateMatch($match_id, $data) {
        return $this->dbSports->update('match', $data, array('id' => $match_id));
    }

    public function addSelectedMatch($event_id, $match_id, $priority) {
        $selected_match_data = array(
            'event_id' => $event_id,
            'match_id' => $match_id,
            'priority' => $priority,
        );
        if (empty($event_id)) {
            unset($selected_match_data['event_id']);
        }
        return $this->db('sports')->insert('selected_match', $selected_match_data);
    }

    public function removeSelectedMatch($id) {
        return $this->db('sports')->remove('selected_match', $id);
    }
    
    public function sendMatchStatus($id) {
        $match_info = $this->getMatchById($id);
        $post_data = array(
                'type' => 4,
                'version' => 1,
                'data' => array(
                        "match" => $id,
                        'status' => $match_info['status'],
                        'score'  => array(
                                intval($match_info['team_info']['team1_id']) => intval($match_info['team_info']['team1_score']),
                                intval($match_info['team_info']['team2_id']) => intval($match_info['team_info']['team2_score']),
                        ),
                )
        );
        
        $r = send_post(API_INTERACTION_COMMIT, json_encode($post_data));
        return $r;
    }

    ///////////////////////////////////////////////////////////////////////

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
            $item['extra'] = $this->getMatchExtra($item['id']);
            $item['forecast'] = $this->getMatchForecast($item['id']);
            $item['live'] = $this->getMatchLive($item['id']);
            $result[] = $item;
        }
        return $result;
    }

    protected function getMatchExtra($match_id) {
        return (array)$this->db('sports')->get_where('match_extra', array('match_id' => $match_id), 1)->row_array();
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
            ->order_by('isvr','ASC')
            ->get('match_live_stream');
        $result = $query->result_array();
        if(count($result) == 1 && $result[0]['isvr'] == 1)
        {
            array_unshift($result,array());
        }else
        {
            array_push($result,array());
        }
        return $result;
    }

}
