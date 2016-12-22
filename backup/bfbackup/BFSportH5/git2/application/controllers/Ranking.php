<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ranking extends MY_Controller {

    public function __construct() {

        parent::__construct();

        $this->load->model('Ranking_model', 'RM');

    }

    public function index() {
        $this->load->view('welcome_message');
    }

    public function football($rankingType = 'points', $eventId) {
        $data = array();
        $data['header_footer_data'] = getHeaderFooterData('ranking');
        if ($rankingType == 'points') {
            $data['rankingData'] = $this->RM->getFootballPoints($eventId);
            $this->load->view('ranking/football_point', $data);
        } elseif ($rankingType == 'shooter') {
            $data['rankingData'] = $this->RM->getFootballShooter($eventId);
            $this->load->view('ranking/football_shooter', $data);
        } elseif ($rankingType == 'assists') {
            $data['rankingData'] = $this->RM->getFootballAssists($eventId);
            $this->load->view('ranking/football_assists', $data);
        } else {
            $this->load->view('errors/not-found');
        }
    }

    /**
     * 篮球排行榜
     *
     * @param string $type team 团队榜 player 球员榜
     * @param string $event_id 赛事ID
     *
     * 示例地址
     * http://m.sports.baofeng.com/ranking/basketball/team/10
     * http://m.sports.baofeng.com/ranking/basketball/player/9
     */
    public function basketball($type, $event_id) {

        $data = array();

        $data['header_footer_data'] = getHeaderFooterData('ranking');

        $get_func = 'get_' . $type . '_basketball_rankings';

        $data['t'] = $this->RM->$get_func($event_id);
        if ($data['t']) {
            $this->load->view('ranking/basketball_' . $type, $data);
        } else {
            $this->load->view('errors/not-found', array('page_type' => 'app'));
        }

    }

}
