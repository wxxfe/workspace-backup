<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ranking_model extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->db = $this->dbSports;
    }

    public function getFootballPoints($eventId) {

        $query = $this->db->select('team_football_stats.*,team.name,team.badge')
                ->join('team', 'team.id=team_football_stats.team_id')
                ->where('event_id', $eventId)
                ->order_by('team_football_stats.points', 'DESC')
                ->get('team_football_stats');

        return $query->result_array();

    }

    public function getFootballShooter($eventId) {

        $query = $this->db->select('player_football_stats.*,team.name as team_name,team.badge,player.name')
                ->distinct(true)
                ->join('team_has_player', 'team_has_player.player_id=player_football_stats.player_id')
                ->join('team', 'team.id=team_has_player.team_id')
                ->join('player', 'player.id=player_football_stats.player_id')
                ->where('player_football_stats.event_id', $eventId)
                ->where('player_football_stats.goals>', 0)
                ->where('team_has_player.visible', 1)
                ->order_by('player_football_stats.goals', 'DESC')
                ->get('player_football_stats');

        return $query->result_array();

    }

    public function getFootballAssists($eventId) {

        $query = $this->db->select('player_football_stats.*,team.name as team_name,team.badge,player.name')
                ->distinct(true)
                ->join('team_has_player', 'team_has_player.player_id=player_football_stats.player_id')
                ->join('team', 'team.id=team_has_player.team_id')
                ->join('player', 'player.id=player_football_stats.player_id')
                ->where('player_football_stats.event_id', $eventId)
                ->where('team_has_player.visible', 1)
                ->where('player_football_stats.assists>', 0)
                ->order_by('player_football_stats.assists', 'DESC')
                ->get('player_football_stats');

        return $query->result_array();

    }

    /**
     * 获得团队和队员的关联数据，比如 team_id 团队ID player_id 队员ID event_id 赛事ID season_id 赛季ID 等
     * UNIQUE KEY `uniq_player` (`team_id`,`event_id`,`season_id`,`player_id`) USING BTREE,
     * @param $where
     */
    public function get_team_has_player($where) {
        $this->db->where($where);
        $query = $this->db->get('team_has_player');
        return $query->result_array();
    }

    /**
     * 获得赛事和团队的关联数据，比如 team_id 团队ID group 所属组 event_id 赛事ID season_id 赛季ID 等
     * UNIQUE KEY `uniq_team` (`event_id`,`season_id`,`team_id`) USING BTREE,
     * @param array $where
     */
    public function get_event_has_team($where) {
        $this->db->where($where);
        $query = $this->db->get('event_has_team');
        return $query->result_array();
    }

    /**
     * 比赛团队的数据，比如名字 name，图标 badge
     * @param $where
     * @return mixed
     */
    public function get_team($where) {
        $this->db->where($where);
        $query = $this->db->get('team');
        return $query->row_array();
    }

    /**
     * 通过event_stage id 获得该条数据 比如 stage 赛事阶段名
     * @param $id
     * @return mixed
     */
    public function get_event_stage($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('event_stage');
        return $query->row_array();
    }

    /**
     * 通过event_id 获得当前赛事的赛季和赛段id
     * @param $id
     * @return mixed
     */
    public function get_current_season_stage($event_id) {
        $this->db->where(array('event_id' => $event_id, 'current' => 1));
        $query = $this->db->get('event_season_history');

        $result = $query->row_array();

        if (empty($result)) return null;

        $stage_ids = array($result['current_stage_id']);

        return array('season_id' => $result['id'], 'stage_ids' => $stage_ids);
    }

    /**
     * 篮球队员的统计数据 比如 points 得分  assists 助攻  rebounds 篮板  steals 抢断  blocks 盖帽
     * UNIQUE KEY `uniq_basketball_player_stats` (`player_id`,`event_id`,`season_id`,`stage_id`) USING BTREE
     * @param array $where 比如 event_id season_id stage_id，如果加上player_id，则肯定且必须只有一行数据
     * @param string $order_by
     * @return mixed
     */
    public function get_player_basketball_stats($where, $order_by = 'points DESC') {
        $this->db->where($where);
        $this->db->order_by($order_by);
        $query = $this->db->get('player_basketball_stats');
        return $query->result_array();
    }

    /**
     * 篮球队的统计数据 比如 stage_id 赛事阶段  wins 胜场次  loses 输场次  points 积分
     * UNIQUE KEY `uniq_basketball_team_stats` (`team_id`,`event_id`,`season_id`,`stage_id`) USING BTREE,
     * @param array $where 比如 event_id season_id stage_id，如果加上team_id，则肯定且必须只有一行数据
     */
    public function get_team_basketball_stats($where) {
        $this->db->select('*, wins/(wins+loses) as victories');
        $this->db->where($where);
        $this->db->order_by('ranking ASC');
        $query = $this->db->get('team_basketball_stats');
        return $query->result_array();
    }

    /**
     * 获得球队榜显示数据
     * @param int $event_id
     * @param int $season_id
     * @param array $stage_ids
     * @return array
     */
    public function get_player_basketball_rankings($event_id) {

        $current_season_stage = $this->get_current_season_stage($event_id);
        if (empty($current_season_stage)) return;


        //所有$stage_ids数组中赛事阶段下面所有队员的统计数据 数据结构层级为赛段-排序分类-队员统计
//        $rankings_data = array(
//                'order_names' => array(),
//                'stats_data' => array(
//                        'stage_name' => '',
//                        'stats_order' => array(
//                                'order' => array(
//                                        array(
//                                                'team_detail' => array()
//                                        )
//                                )
//                        )
//                )
//
//        );


        $stats_data = array();

        $order_names = array(
                'points' => '得分',
                'assists' => '助攻',
                'rebounds' => '篮板',
                'steals' => '抢断',
                'blocks' => '盖帽'
        );

        foreach ($current_season_stage['stage_ids'] as $stage_id) {

            $base_data = array('event_id' => $event_id, 'season_id' => $current_season_stage['season_id'], 'stage_id' => $stage_id);

            //当前赛段的所有队员统计数据
            $stats_stage = $this->get_player_basketball_stats($base_data);

            if ($stats_stage) {

                //获得队员的名字头像和所属团队的名字图标
                foreach ($stats_stage as &$item) {

                    $player_id = $item['player_id'];
                    $this->db->select(
                            'player.name as player_name
                            ,player.short_name as player_short_name
                            ,player.photo as player_photo
                            ,team.name as team_name
                            ,team.short_name as team_short_name
                            ,team.badge as team_badge'
                    );
                    $this->db->from('player');
                    $this->db->where('player.id', $player_id);
                    $this->db->join('team_has_player', 'team_has_player.player_id=' . $player_id . ' and team_has_player.visible=1');
                    $this->db->join('team', 'team.id=team_has_player.team_id');
                    $query = $this->db->get();
                    $extra = $query->result_array();
                    if ($extra) {
                        $item['extra'] = $extra[0];
                    }

                }
                unset($item);

                //赛段名
                $stage_name = '';
                $event_stage = $this->get_event_stage($stage_id);
                if ($event_stage) {
                    $stage_name = $event_stage['stage'];
                }

                //按排序分类
                $stats_order = array(
                        'points' => array_slice($stats_stage, 0, 5)
                );

                foreach ($order_names as $o_k => $o_v) {
                    if (isset($stats_order[$o_k]) && $stats_order[$o_k]) {
                        continue;
                    }

                    $order_temp = array();
                    foreach ($stats_stage as $s_k => $s_v) {
                        $order_temp[$s_k] = $s_v[$o_k];
                    }

                    array_multisort($order_temp, SORT_DESC, $stats_stage);

                    $stats_order[$o_k] = array_slice($stats_stage, 0, 5);
                }

                array_push($stats_data, array_merge($base_data, array('stage_name' => $stage_name, 'stats_order' => $stats_order)));

            }


        }

        $rankings_data = array(
                'order_names' => $order_names,
                'stats_data' => $stats_data
        );

        return $rankings_data;

    }

    /**
     * 获得球队榜显示数据
     * @param int $event_id
     * @param int $season_id
     * @param array $stage_ids
     * @return array
     */
    public function get_team_basketball_rankings($event_id) {

        $current_season_stage = $this->get_current_season_stage($event_id);
        if (empty($current_season_stage)) return;

        //所有$stage_ids数组中赛事阶段下面所有团队的统计数据 数据结构层级为赛段-分组-团队统计
//        $stats_data = array(
//                'stage_name' => '',
//                'stats_group' => array(
//                        'group' => array(
//                                array(
//                                        'team_detail' => array()
//                                )
//                        )
//                )
//
//        );
        $stats_data = array();

        foreach ($current_season_stage['stage_ids'] as $stage_id) {

            $base_data = array('event_id' => $event_id, 'season_id' => $current_season_stage['season_id'], 'stage_id' => $stage_id);

            //当前赛段的所有团队统计数据
            $stats_stage = $this->get_team_basketball_stats($base_data);

            if ($stats_stage) {

                //分组的团队以及团队统计数据，默认不分组，比如NBA分东西 'east' 'west'
                $stats_group = array();

                foreach ($stats_stage as &$item) {

                    //获得团队的名字，图标等
                    $team_detail = $this->get_team(array('id' => $item['team_id']));
                    if ($team_detail) {
                        $item['team_detail'] = $team_detail;

                        //获得参与赛事的团队所属group
                        $has_teams = $this->get_event_has_team(array('event_id' => $event_id, 'season_id' => $current_season_stage['season_id'], 'team_id' => $item['team_id'], 'visible' => 1));

                        if ($has_teams && $has_teams[0]) {

                            $has_team = $has_teams[0];

                            //把该赛段所有团队的统计数据，按组封装，没有组的放到default键名下面
                            if (isset($has_team['group']) && $has_team['group']) {
                                $group = $has_team['group'];
                            } else {
                                $group = 'default';
                            }
                            //如果该组还没有数据，则初始化数组
                            if (!isset($stats_group[$group])) {
                                $stats_group[$group] = array();
                            }

                            array_push($stats_group[$group], $item);
                        }
                    }
                }
                unset($item);

                //赛段名
                $stage_name = '';
                $event_stage = $this->get_event_stage($stage_id);
                if ($event_stage) {
                    $stage_name = $event_stage['stage'];
                }

                array_push($stats_data, array_merge($base_data, array('stage_name' => $stage_name, 'stats_group' => $stats_group)));

            }


        }

        return $stats_data;
    }

}
