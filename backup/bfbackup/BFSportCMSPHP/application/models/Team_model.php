<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team_model extends MY_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getTeams($ids) {
        return $this->dbSports->select('*')->where_in(array('id' => $ids))->get('team')->result_array();
    }
    
    public function getMatchTeams($match_id) {
        $team_ids = array();
        $row = $this->dbSports->select('team1_id, team2_id')
            ->get_where('match_extra_team', array('match_id' => $match_id), 1, 0)
            ->row_array();
        if ($row) {
            $team_ids[] = $row['team1_id'];
            $team_ids[] = $row['team2_id'];
        }
        return $this->getTeams($team_ids);
    }
    
    public function getEventTeamIds($event_ids, $event_season_ids) {
        $event_season_ids = array_intersect_key($event_season_ids, array_flip($event_ids));
        if (empty($event_season_ids)) {
            return array();
        }
        
        $result = array();
        $rows = $this->dbSports->distinct()
            ->where_in('event_id', array_keys($event_season_ids))->where_in('season_id', $event_season_ids)
            ->select('team_id AS team_id')->get('event_has_team')->result_array();
        foreach ($rows as $row) {
            $result[] = $row['team_id'];
        }
        return $result;
    }
    
    public function getTeamPlayerIds($team_ids, $event_season_ids) {
        $result = array();
        $rows = $this->dbSports->distinct()->where_in('team_id', $team_ids)
            ->where_in('event_id', array_keys($event_season_ids))->where_in('season_id', $event_season_ids)
            ->select('player_id AS player_id')->get('team_has_player')->result_array();
        foreach ($rows as $row) {
            $result[] = $row['player_id'];
        }
        return $result;
    }
    
}