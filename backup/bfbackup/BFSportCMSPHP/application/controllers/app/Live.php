<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * appa 热门直播类
 * Class Live
 */
class Live extends MY_Controller
{
    public $page_name = "热门直播";

    private $view_path = 'app/live';
    private $limit = 20;

    public function __construct() {
        parent::__construct();
        $this->load->model('channel_model', 'CM');
        $this->load->model('match_model', 'MM');
        $this->load->model('event_model', 'EM');
        $this->load->model('app/ex_live_model', 'EX_LM');
    }

    // 热门直播
    public function index()
    {
        $event_id = isset($_GET['event_id']) ? $_GET['event_id'] : 0;
        $date = isset($_GET['date']) ? $_GET['date'] : '';

        if (empty($event_id)) {
            $event_id = 'all';
        }
        if (empty($date)) {
            $date = date('Y-m-d');;
        }

        //热门直播
        $recommend_match = $this->EX_LM->getRecommendMatch();

        // 组建日期插件条的数据
        $date_slide = $this->MM->getMatchCountByWeek($date, $event_id);

        // 获取event列表
        $event_list = $this->EM->getEvents();

        $data = array();
        $data['recommend_match'] = $recommend_match;
        $data['event_list'] = $event_list;
        $data['date_slide'] = $date_slide;
        $data['event_id'] = $event_id;
        $data['date'] = $date;
        $data['match_list_view'] = $this->_getMatchListView(array('events'=>$event_list, 'curr_event_id'=>$event_id, 'curr_date'=>$date, 'date_slide'=>$date_slide));
        $this->load->view($this->view_path . '/index', $data);
    }

    /**
     * @param $event_id
     * @param $date
     * @return mixed
     */
    private function _getMatchListView($data) {
        $this->config->load('sports');
        $match_list = $this->MM->getMatch($data['curr_event_id'], $data['curr_date']);
        $live_sites = $this->MM->getSites();

        $live_types = $this->config->item('live_types');
        $match_types = $this->config->item('match_types');
        $match_statuses = $this->config->item('match_statuses');
        $forecast_types = $this->config->item('match_forecast_types');

        $data['match_list'] = $match_list;
        $data['live_sites'] = $live_sites;
        $data['live_types'] = $live_types;
        $data['match_types'] = $match_types;
        $data['match_statuses'] = $match_statuses;
        $data['forecast_types'] = $forecast_types;
        $data['controller'] = $this->router->class;
        return $this->load->view($this->view_path . '/_match_list', $data, true);
    }

    /**
     * 取消推荐
     */
    public function cancel() {
        $id = $this->input->post("id");
        if (!$id) {
            echo "failed";exit(0);
        }
        $result = $this->EX_LM->dbSports->remove('ex_live', $id);
        if ($result) {
            echo 'success';exit(0);
        } else {
            echo "failed";exit(0);
        }
    }

    public function add() {
        $match_id = $this->input->post('match_id');
        if (!$match_id) {
            echo "failed";exit(0);
        }
        //检查是否推荐过
        $query = $this->EX_LM->dbSports->select('*')->from('ex_live')->where('match_id', $match_id)->get();
        $result = $query ? $query->row_array() : false;
        if($result) {
            echo 'already';exit(0);
        } else {
            $result = $this->EX_LM->dbSports->insert('ex_live', array('match_id'=>$match_id));
            if ($result) {
                echo 'success';exit(0);
            } else {
                echo "failed";exit(0);
            }
        }
    }
}
?>