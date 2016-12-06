<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_model extends MY_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * 获取所有赛事的当前赛季
     *
     * @return [event_id => current season_id,]
     */
    public function getCurrentEventSeasonIds() {
        $ids = array();
        $sql = "SELECT e.id AS event_id, s.id AS season_id FROM event AS e, event_season_history AS s
            WHERE e.id=s.event_id AND e.visible=1 AND s.current=1";
        $rows = $this->dbSports->query($sql)->result_array();
        foreach ($rows as $row) {
            $ids[$row['event_id']] = $row['season_id'];
        }
        return $ids;
    }
    
    public function getEvents() {
        $query = $this->db('sports')->order_by('priority DESC, id ASC')->get_where('event');
        $result = array();
        foreach($query->result_array() as $item) {
            $result[$item['id']] = $item;
        }
        return $result;
    }
    
}