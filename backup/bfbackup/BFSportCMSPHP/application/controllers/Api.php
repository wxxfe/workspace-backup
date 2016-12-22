<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Tag_model', 'TM');
    }

    /**
     * 搜索接口
     * @param string $type 搜索类型
     * @param string $keyword 关键词
     * @return array
     */
    public function search($type, $keyword){
        $this->load->model('Search_model', 'SM');

        $isId = is_numeric($keyword);
        $result = array();
        if($isId){
            if ($type == 'thread') {
                $d = $this->SM->db('board')->select('*')->get_where($type,array('id' => $keyword))->row_array();
            } else {
                $d = $this->SM->db('sports')->select('*')->get_where($type,array('id' => $keyword))->row_array();
            }
            $result = array('total' => 1, 'result' => array($d));
            if(empty($d)) $result['total'] = 0;
        }else{
            $result = $this->SM->itemsQuery($type,$keyword);
        }

        echo json_encode($result);

    }

    /**
     * 标签多级联动接口
     * @param string $type 标签类型
     * @param int $id 类型ID
     * @return array
     */
    public function getPredefineTags($type, $id, $event_id = 0){
        $predefine = array('sports','event','team','player');
        $index = array_search($type,$predefine);
        $data = array();
        $_id = $id;
        $_eventId = $event_id;
        $_season = $this->_getSeasonId($_eventId);
        for($i = $index; $i < count($predefine); $i++){
            $funcName = '_get' . ucfirst($predefine[$i]);
            if($predefine[$i] == 'player'){
                $_data = $this->$funcName($_id,$_eventId,$_season);
            }else{
                $_data = $this->$funcName($_id);
            }
            //if(empty($_data)) continue;
            $_id = empty($_data) ? 0 : $_data[0]['id'];
            $_eventId = isset($_data[0]['event_id']) ? $_data[0]['event_id'] : $_eventId;
            $_season = isset($_data[0]['season_id']) ? $_data[0]['season_id'] : $_season;

            $data[] = array('type' => $predefine[$i],'data' => $_data);
        }

        echo json_encode($data);

    }

    private function _getEvent($sportsId){
        $query = $this->TM->db('sports')->select('id,name,sports_id')
            ->where('sports_id',$sportsId)
            ->get('event');

        return $this->_setFakeId('event',$query->result_array());
    }

    private function _getSeasonId($eventId) {
        return $eventId > 0? @intval($this->TM->db('sports')
            ->get_where('event_season_history',array('event_id' => $eventId,'current' => 1))
            ->row()->id) : 0;
    }

    private function _getTeam($eventId){
        $season = $this->TM->db('sports')->get_where('event_season_history',array('event_id' => $eventId,'current' => 1))
            ->row_array();
        $current = $season['id'];

        $query = $this->TM->db('sports')->select('event_has_team.event_id,event_has_team.season_id,team.id,team.name')
            ->join('team','event_has_team.team_id = team.id','left')
            ->where('event_has_team.event_id',$eventId)
            ->where('event_has_team.season_id',$current)
            ->get('event_has_team');

        return $this->_setFakeId('team',$query->result_array());
    }

    private function _getPlayer($teamId,$eventId,$seasonId){
        $this->TM->db('sports')->select('player.id,player.name')
            ->join('player','team_has_player.player_id = player.id','left')
            ->where('team_has_player.team_id',$teamId)
            ->where('team_has_player.event_id',$eventId)
            ->where('team_has_player.season_id',$seasonId);
        
        $query = $this->TM->db('sports')->get('team_has_player');

        return $this->_setFakeId('player',$query->result_array());
    }

    private function _setFakeId($type,$data){
        $full = array();
        foreach($data as $item){
            $item['fake_id'] = $this->TM->makeFakeId($type,$item['id']);
            $full[] = $item;
        }
        return $full;
    }

}
