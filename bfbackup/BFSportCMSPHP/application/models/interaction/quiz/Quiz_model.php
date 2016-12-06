<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Quiz_model
 * 竞猜
 */
class Quiz_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        //模型用到的数据库
        $this->db('quiz');
        $this->db = $this->_dbCurrent;

        //载入其他模型，第二个参数是别名
        $this->load->model('Match_model', 'mm');
    }

    /**
     * 获得指定比赛ID的所有题目
     * @param $match_id 比赛id
     * @param int $limit 分页条数
     * @param int $offset 分页偏移量
     * @return mixed
     */
    public function getList($match_id, $limit = 0, $offset = 0) {
        $this->db->query('SET NAMES utf8mb4');
        $this->db->order_by('created_at', 'DESC');
        if ($match_id) $this->db->where('match_id', $match_id);
        if ($limit) $this->db->limit($limit, $offset);
        $query = $this->db->get('subject');
        return $query->result_array();
    }

    /**
     * 获得单个题目数据
     * @param $id 竞猜题目ID
     * @return mixed
     */
    public function getItem($id) {
        $this->db->query('SET NAMES utf8mb4');
        $this->db->where('id', $id);
        $query = $this->db->get('subject');
        return $query->row_array();
    }

    /**
     * 获得竞猜截止的剩余时间(分钟)和当前状态
     * @param $deadline 截止时间戳，秒
     * @param $status 当前状态
     * @param $id 竞猜题目ID
     * @return array
     */
    public function getDeadlineStatus($deadline, $status, $id) {
        $data = array();

        //获得剩余截止时间,单位分钟，不足1分钟，按1分钟
        $data['remaining_time'] = ceil((strtotime($deadline) - time()) / 60);

        //如果剩余小于等于0，并且状态还是'begin'，则更新为'wait'
        if ($data['remaining_time'] <= 0 AND $status == 'begin') {
            $data['status'] = 'wait';
            $this->updateStatus($data['status'], $id);
        }

        return $data;
    }

    /**
     * 获得处理过的post表单数据
     * @return array
     */
    private function _getPost() {
        $data = $this->input->post();

        //比赛数据
        $match = $this->mm->getMatchById($data['match_id']);

        $data['match_title'] = '';
        if ($match) {
            switch ($match['type']) {
                case 'team':
                    if (isset($match['team_info']) AND $match['team_info']) {
                        $data['match_title'] = substr($match['start_tm'], 0, -3) . ' ' . $match['team_info']['team1_info']['name'] . 'VS' . $match['team_info']['team2_info']['name'];
                    }
                    break;
                case 'player':
                    if (isset($match['player_info']) AND $match['player_info']) {
                        $data['match_title'] = substr($match['start_tm'], 0, -3) . ' ' . $match['player_info']['player1_info']['name'] . 'VS' . $match['player_info']['player2_info']['name'];
                    }
                    break;
                case 'various':
                    $data['match_title'] = substr($match['start_tm'], 0, -3) . ' ' . $match['title'];
                    break;
            }
        }

        //把答案选项转换成kv的json字符串
        $option = array();
        $option_k = 1;
        foreach ($data['option'] as $value) {
            if ($value != '') {
                array_push($option, array('id' => $option_k++, 'name' => $value));
            }
        }
        $data['option'] = json_encode($option);

        //把表单中的多少分钟截止转换为秒
        $deadline = $data['deadline'] * 60;

        //把比赛开始时间转换成时间戳，秒
        $start_tm = strtotime($match['start_tm']);

        //竞猜截止时间的计算类型
        $time_types = $data['time_types'];
        unset($data['time_types']);

        $now_time = time();

        //array('开赛前', '开赛后', '发布后');
        switch ($time_types) {
            case 0:
                $deadline = $start_tm - $deadline;
                break;
            case 1:
                $deadline = $start_tm + $deadline;
                break;
            case 2:
                $deadline = $now_time + $deadline;
                break;
        }

        //竞猜的截止时间
        $data['deadline'] = date("Y-m-d H:i:s", $deadline);
        
        // 发起竞猜的主播ID:user_id包含在post的data里面

        $data['created_at'] = $data['updated_at'] = date("Y-m-d H:i:s", $now_time);
        return $data;
    }

    /**
     * 插入数据
     */
    public function add() {
        $data = $this->_getPost();
        $this->db->query('SET NAMES utf8mb4');
        $this->db->insert('subject', $data);
        $this->_sendSubjectBegin($this->db->insert_id(), $this->input->get('match_id'), $this->input->get('host_id'));
    }

    /**
     * 更新数据库中表的单列数据
     */
    public function inPlaceEditQuiz() {
        $match_id = $this->input->get('match_id');
        $host_id = $this->input->get('host_id');

        $fieldK = $this->input->post('name');
        $fieldV = $this->input->post('value');
        $fieldId = $this->input->post('pk');

        //如果是更新答案选项
        if (strpos($fieldK, 'option') !== false) {
            $o_id = str_replace('option', '', $fieldK);
            $option = json_decode($this->getItem($fieldId)['option'], true);
            foreach ($option as &$value) {
                if ($value['id'] == $o_id) {
                    $value['name'] = $fieldV;
                }
            }
            unset($value);
            $fieldK = 'option';
            $fieldV = json_encode($option);
        }
        //如果是更新正确答案，把状态变为已开奖，并且分奖金
        //否则是更新题目或答案选项
        if ($fieldK == 'answer') {
            $this->updateUserWinCount($fieldId, $fieldV);
            $this->updateStatus('end', $fieldId);
            $this->_sendSubjectEnd($fieldId, $fieldV, $match_id, $host_id);
        } else {
//            $this->_sendSubjectBegin($fieldId, $match_id, $host_id);
        }
        $this->db->query('SET NAMES utf8mb4');
        $this->db->set($fieldK, $fieldV);
        $this->db->where('id', $fieldId);
        $this->db->update('subject');
    }

    /**
     * 更新竞猜状态
     * @param $status 'begin','wait','end'
     * @param $id 竞猜题目ID
     */
    public function updateStatus($status, $id) {
        $this->db->set('status', $status);
        $this->db->where('id', $id);
        $this->db->update('subject');
    }


    /**
     * 获得指定题目的统计数据，参与人数，奖金池等等
     * @param $id 竞猜题目ID
     * @param $answer_id 正确答案ID
     * @return mixed
     */
    public function getSubjectStatistic($id, $answer_id) {
        //获得所有参与这个竞猜的用户统计数据
        $this->db->where('subject_id', $id);
        $query = $this->db->get('user_subject');
        $subject_statistic = $query->result_array();

        $data = array();
        $data['statistic_src'] = $subject_statistic;
        $data['statistic_turnouts'] = count($subject_statistic);
        $data['statistic_bonus_pool'] = 0;
        foreach ($subject_statistic as $s_v) {
            $data['statistic_bonus_pool'] += (float)$s_v['bet_count'];
        }
        $data['statistic_odds'] = 0;
        $data['statistic_answer_bet'] = $this->getSubjectAnswerBet($id, $answer_id);
        if ($data['statistic_answer_bet']) {
            $data['statistic_odds'] = number_format($data['statistic_bonus_pool'] / $data['statistic_answer_bet'], 2, '.', '');
        }

        return $data;
    }

    /**
     * 获得用户选择指定题目的指定答案选项的数量
     * @param $id 竞猜题目ID
     * @param $answer_id 答案ID
     * @return mixed
     */
    public function getSubjectAnswerNum($id, $answer_id) {
        $this->db->where(array('subject_id' => $id, 'answer' => $answer_id));
        $this->db->from('user_subject');
        return $this->db->count_all_results();
    }

    /**
     * 获得用户选择指定题目的指定答案选项的金额
     * @param $id 竞猜题目ID
     * @param $answer_id 答案ID
     * @return mixed
     */
    public function getSubjectAnswerBet($id, $answer_id) {
        $this->db->where(array('subject_id' => $id, 'answer' => $answer_id));
        $query = $this->db->get('user_subject');
        $subject_statistic = $query->result_array();
        $bet = 0;
        foreach ($subject_statistic as $value) {
            $bet += (float)$value['bet_count'];
        }
        return $bet;
    }

    /**
     * 更新所有选择了正确答案用户的获奖金额
     * @param $id 竞猜题目ID
     * @param $answer_id 答案ID
     */
    public function updateUserWinCount($id, $answer_id) {
        $data = $this->getSubjectStatistic($id, $answer_id);
        $statistic_bonus_pool = $data['statistic_bonus_pool'];
        $statistic_answer_bet = $data['statistic_answer_bet'];

        $this->db->where(array('subject_id' => $id, 'answer' => $answer_id));
        $query = $this->db->get('user_subject');
        $subject_statistic = $query->result_array();

        foreach ($subject_statistic as $value) {
            //用户ID
            $user_id = $value['user_id'];

            //该用户赢得钱
            $win_count = $statistic_bonus_pool / $statistic_answer_bet * $value['bet_count'];

            //更新user_subject
            $this->db->set('win_count', 'win_count+' . $win_count, FALSE);
            $this->db->where(array('user_id' => $user_id, 'subject_id' => $id, 'answer' => $answer_id));
            $this->db->update('user_subject');

            //更新account
            $this->db->set('count', 'count+' . $win_count, FALSE);
            $this->db->where('user_id', $user_id);
            $this->db->update('account');

            $balance = $this->db->get_where('account', array('user_id' => $user_id))->row_array()['count'];

            //插入account_detail
            $account_detail_data = array(
                'user_id' => $user_id,
                'type' => 1,
                'description' => 'guess_win',
                'consume' => $win_count,
                'balance' => $balance
            );
            $this->db->insert('account_detail', $account_detail_data);

//            $this->db->set('win_count', $data['statistic_bonus_pool'] . '/' . $data['statistic_answer_bet'] . '*bet_count', FALSE);
        }


    }

    /**
     * 发送竞猜开始消息
     * @param $id 竞猜题目ID
     * @param $match_id 比赛ID
     * @param $host_id 主持人ID
     */
    private function _sendSubjectBegin($id, $match_id, $host_id) {
        //{
        //  id : 123,
        //  type : 3,
        //  action : "create",
        //  title : "这是一个标题",
        //  options : [
        //    {id : 1, name : "选项一"},
        //    {id : 2, name : "选项二"}
        //  ],
        //  current : 1473760191007,
        //  end : 1473760191007
        //}
        //
        //说明
        //
        //id - 竞猜ID
        //type - 消息类型
        //action - 动作
        //title - 标题
        //options - 选项列表
        //current - 当前服务器时间「时间戳」
        //end - 竞猜结束时间「时间戳」

        $src = $this->getItem($id);
        $data = array(
            'id' => $id,
            'type' => 3,
            'action' => "create",
            'title' => $src['question'],
            'options' => json_decode($src['option']),
            'current' => strtotime($src['created_at']),
            'end' => strtotime($src['deadline'])
        );
        $this->send_host_message($data, $match_id, $host_id);
    }

    /**
     * 发送竞猜结束消息
     * @param $id 竞猜题目ID
     * @param $answer_id 选择的答案ID
     * @param $match_id 比赛ID
     * @param $host_id 主持人ID
     */
    private function _sendSubjectEnd($id, $answer_id, $match_id, $host_id) {
        //{
        //  id :123,
        //  type : 3,
        //  action : "open",
        //  answer : 1
        //}
        //
        //说明
        //
        //id - 竞猜ID
        //type - 消息类型
        //answer - 答案ID「创建时选项中的ID」
        $data = array(
            'id' => $id,
            'type' => 3,
            'action' => "open",
            'answer' => $answer_id
        );
        $this->send_host_message($data, $match_id, $host_id);
    }


    /**
     * 获得多条数据，通用
     * @param $table
     * @param null $where
     * @param int $limit
     * @param int $offset
     * @param string $order_column
     * @param string $order
     * @return mixed
     */
    public function getListCommon($table, $limit = 0, $offset = 0, $where = NULL, $order_column = 'created_at', $order = 'DESC') {
        $this->db->order_by($order_column, $order);
        if ($where) $this->db->where($where);
        if ($limit) $this->db->limit($limit, $offset);
        $query = $this->db->get($table);
        return $query->result_array();
    }

    /**
     * 获得单条数据，通用
     * @param $where
     * @param $table
     * @return mixed
     */
    public function getItemCommon($where, $table) {
        $this->db->where($where);
        $query = $this->db->get($table);
        return $query->row_array();
    }
}