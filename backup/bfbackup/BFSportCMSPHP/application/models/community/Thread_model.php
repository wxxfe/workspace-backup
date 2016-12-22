<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Thread_model
 * 话题和话题的帖子
 */
class Thread_model extends MY_Model {
    public function __construct() {
        parent::__construct();
        //模型用到的数据库
        $this->db('board');
        $this->db = $this->_dbCurrent;

        //载入其他模型，第二个参数是别名
        $this->load->model('Match_model', 'mm');
    }

    /**
     * 获得多条数据
     * @param $limit 条数
     * @param $offset 偏移量
     * @return mixed
     */
    public function getList($limit, $offset) {
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get('thread');
        return $query->result_array();
    }

    /**
     * 获得单条数据
     * @param $id 话题ID
     * @return mixed
     */
    public function getItem($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('thread');
        $data = $query->row_array();
        if ($data) {
            return array_merge($data, array('user_name' => $this->mm->getUsersAPI($data['user_id'])[$data['user_id']]));
        } else {
            return NULL;
        }
    }

    /**
     * 获得话题的标题
     * @param $id 话题ID
     * @return null
     */
    public function getThreadTitle($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('thread');
        $data = $query->row_array();
        if ($data && isset($data['title'])) {
            if ($data['visible']) {
                return $data['title'];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * 获得指定比赛关联话题列表，赛前或赛中 和 逗号分隔的话题ID
     * @param $table 赛前或赛中表名
     * @param $match_id 比赛ID
     * @return array
     */
    public function getMatchThreadList($table, $match_id) {
        $this->db->order_by('display_order', 'ASC');
        $this->db->where('match_id', $match_id);
        $query = $this->db->get($table);

        $ids = '';
        $data = array();
        foreach ($query->result_array() as $value) {
            array_push($data, array_merge($this->getItem($value['thread_id']), array('match_thread' => $value)));
            if ($ids) {
                $ids .= ',';
            }
            $ids .= $value['thread_id'];
        }
        return array($data, $ids);
    }

    /**
     * 获得处理过的post表单数据
     * @return array
     */
    private function _getPost() {
        $data = $this->input->post();

        if (isset($data['visible']) AND $data['visible'] === 'on') {
            $data['visible'] = 1;
        } else {
            $data['visible'] = 0;
        }

        $communities = array_unique($data['communities']);
        unset($data['communities']);

        return array($data, $communities);
    }

    /**
     * 根据话题ID获取该话题下的标签
     * @param int $threadId
     * @return string 以','号分隔的ID字符串
     */
    public function getTagsByThreadId($threadId){

        $query = $this->dbBoard->get_where('thread_tag',array('thread_id' => intval($threadId)));
        $tags = $query->result_array();

        $tagIds = array();
        foreach($tags as $tag){
            $tagIds[] = $tag['tag_id'];
        }

        return implode(',',$tagIds);

    }

    /**
     * 插入数据
     */
    public function add() {
        $data = $this->_getPost();
        $tags = $data[0]['tags'];
        unset($data[0]['tags']);
        $this->db->insert('thread', $data[0]);
        $id = $this->db->insert_id();

        $this->_addCommunities($data[1], $id);
        $tags_array = explode(',',$tags);
        foreach ($tags_array as $tag_id) {
            $insert_batch_data[] = array('thread_id' => $id, 'tag_id'=> $tag_id);
        }
        $this->insert_batch('thread_tag',$insert_batch_data);
        //如果有比赛ID，则插入比赛和话题关联数据
        $this->addMatchThread($this->input->get('table'), $this->input->get('match_id'), $id, $this->input->get('host_id'));
    }

    /**
     * 插入关联比赛的话题，赛前或赛中
     * @param $table 赛前或赛中表名
     * @param $match_id 比赛ID
     * @param $thread_id 话题ID
     * @param int $host_id 主持人ID
     */
    public function addMatchThread($table, $match_id, $thread_id, $host_id) {
        if ((int)$match_id AND (int)$thread_id) {
            $this->db->insert($table, array('match_id' => $match_id, 'thread_id' => $thread_id));
        }
    }

    /**
     * 更新数据
     */
    public function edit($id) {
        $data = $this->_getPost();
        $tags = $data[0][tags];
        unset($data[0][tags]);

        $this->_setTags($id,$tags);
        $this->db->where('id', $id);
        $this->db->update('thread', $data[0]);

        $this->_addCommunities($data[1], $id);
    }

    private function _setTags($threadId,$tags){
        $tagsArr = explode(',',$tags);
        $this->db->where(array('thread_id' => $threadId))->delete('thread_tag');
        $full = array();
        foreach($tagsArr as $tagId){
            $full[] = array('thread_id' => $threadId, 'tag_id' => $tagId);
        }
        $this->insert_batch('thread_tag',$full);
    }

    /**
     * 删除数据
     */
    public function del($id) {
        $this->db->where('id', $id);
        $this->db->delete('thread');
    }


    /**
     * 更新话题帖子数和楼层数
     * @param $id 话题ID
     * @param $num 增减数
     */
    public function updateThreadPostCount($id, $num) {
        $this->db->set('count', 'count' . $num, FALSE);
        //如果是增加，则增加楼层数
        if ((int)$num > 0) {
            $this->db->set('lastseq', 'lastseq' . $num, FALSE);
        }
        $this->db->where('id', $id);
        $this->db->update('thread');

        $this->db->where('id', $id);
        $query = $this->db->get('thread');
        $data = $query->row_array();
        return $data['lastseq'];
    }

    /**
     * 获得社区分类
     * @return array
     */
    public function getCommunities() {
        $data = array();
        $query = $this->db->get('community');
        $t = $query->result_array();
        foreach ($t as $item) {
            $data[$item['id']] = $item['name'];
        }
        return $data;
    }

    /**
     * 获得话题选中的社区分类
     * @param $id 话题ID
     * @return array
     */
    public function getCommunitiesSelected($id) {
        $data = array();
        $this->db->where('thread_id', $id);
        $query = $this->db->get('thread_has');
        $t = $query->result_array();
        foreach ($t as $item) {
            array_push($data, $item['community_id']);
        }
        for ($i = 0; $i < 3; $i++) {
            if (!isset($data[$i])) {
                array_push($data, 0);
            }
        }
        return $data;
    }

    /**
     * 添加话题关联的分类
     * @param $data
     * @param $id
     */
    private function _addCommunities($data, $id) {
        $this->_delCommunities($id);
        foreach ($data as $data_id) {
            if ((int)$data_id) {
                $this->db->insert('thread_has', array('thread_id' => $id, 'community_id' => $data_id));
                $this->updateCommunitiesThreadCount($data_id, '+1');
            }
        }
    }

    /**
     * 删除话题关联的分类
     * @param $id
     */
    private function _delCommunities($id) {
        $data = $this->getCommunitiesSelected($id);
        foreach ($data as $community_id) {
            if ((int)$community_id) $this->updateCommunitiesThreadCount($community_id, '-1');
        }
        $this->db->where('thread_id', $id);
        $this->db->delete('thread_has');
    }

    /**
     * 更新分类的话题数
     * @param $id 分类ID
     * @param $num 增减数
     */
    public function updateCommunitiesThreadCount($id, $num) {
        $this->db->set('thread_count', 'thread_count' . $num, FALSE);
        $this->db->where('id', $id);
        $this->db->update('community');
    }

    /**
     * 获得帖子数据
     * @param $limit
     * @param $offset
     * @param array $where
     * @param string $column
     * @param string $order
     * @return mixed
     */
    public function getPostList($limit, $offset, $where = array(), $column = 'created_at', $order = 'DESC') {
        $this->db->query('SET NAMES utf8mb4');
        $this->db->where($where);
        $this->db->order_by($column, $order);
        $this->db->limit($limit, $offset);
        $query = $this->db->get('post');
        return $query->result_array();
    }

    /**
     * 获得赛后热帖
     * @param $match_id
     * @return array
     */
    public function getMatchHotPostList($match_id) {
        $this->db->order_by('display_order', 'ASC');
        $this->db->where('match_id', $match_id);
        $query = $this->db->get('match_post');

        $data = array();
        foreach ($query->result_array() as $value) {
            array_push($data, array_merge($this->getPostItem($value['post_id']), array('match_post' => $value)));
        }
        return $data;
    }


    /**
     * 获得单条帖子数据
     * @param $id 帖子ID
     * @return mixed
     */
    public function getPostItem($id) {
        $this->db->query('SET NAMES utf8mb4');
        $this->db->where('id', $id);
        $query = $this->db->get('post');
        $data = $query->row_array();
        if ($data) {
            return array_merge($data, array('user_name' => $this->mm->getUsersAPI($data['user_id'])[$data['user_id']]));
        } else {
            return NULL;
        }
    }

    /**
     * 添加帖子
     */
    public function addPost($seq) {
        $data = $this->input->post();

        if (isset($data['visible']) AND $data['visible'] === 'on') {
            $data['visible'] = 1;
        } else {
            $data['visible'] = 0;
        }

        $data['likes_json'] = json_encode(array());
        $data['seq'] = $seq;

        $this->db->query('SET NAMES utf8mb4');
        $this->db->insert('post', $data);
    }

    /**
     * 添加初始的赛后热帖
     * @param $ids 话题ID，逗号分隔字符串
     * @param $match_id
     */
    public function addInitMatchHotPost($ids, $match_id) {
        if ($ids AND $match_id) {
            $this->db->query('SET NAMES utf8mb4');
            $sql = 'select *,'
                . ' extra+likes+comment_count*5+unix_timestamp(created_at)/3600-reports as hot_score'
                . ' from post where thread_id in(' . $ids . ') and visible=1'
                . ' order by hot_score desc limit 10';
            $query = $this->db->query($sql);

            foreach ($query->result_array() as $index => $item) {
                $this->addMatchHotPost($match_id, $item['id'], $index);
            }
        }
    }

    /**
     * 添加赛后热帖
     * @param $match_id
     * @param $post_id
     * @param int $display_order
     * @return array|null
     */
    public function addMatchHotPost($match_id, $post_id, $display_order = 0) {
        if ((int)$match_id AND (int)$post_id) {
            $this->db->insert('match_post', array('match_id' => $match_id, 'post_id' => $post_id, 'display_order' => $display_order));
        }
    }

    /**
     * 删除帖子
     * @param $id
     */
    public function delPost($id) {
        $this->db->where('id', $id);
        $this->db->delete('post');
    }

    /**
     * 审核通过指定帖子
     */
    public function passPosts() {
        $this->db->query('SET NAMES utf8mb4');
        $this->db->where($this->input->post('post_ids'));
        $this->db->set('check', 1);
        $this->db->update('post');
    }

    /**
     * 重新排序
     * @param $table
     * @param $match_id
     */
    public function reorder($table, $match_id) {
        $this->db->order_by('display_order', 'ASC');
        $this->db->order_by('updated_at', 'DESC');
        $this->db->where('match_id', $match_id);
        $query = $this->db->get($table);
        $data = $query->result_array();
        $order = 1;
        foreach ($data as $item) {
            $this->db->set('display_order', $order++);
            $this->db->where('id', $item['id']);
            $this->db->update($table);
        }
    }

    /**
     * 发送比赛加入了话题的消息
     * @param $id 话题ID
     * @param $match_id 比赛ID
     * @param $host_id 主持人ID
     */
    public function sendMatchThread($id, $match_id, $host_id, $match_thread_id) {
        //        {
        //          id : 123,
        //          type : 4,
        //          title : "这是一个标题",
        //          count : 100
        //        }
        //
        //        说明
        //
        //        id - 话题ID
        //        type - 消息类型
        //        title - 话题名称
        //        count - 话题帖子数
        $item = $this->getItem($id);
        $data = array(
            'id' => $item['id'],
            'type' => 4,
            'title' => $item['title'],
            'count' => $item['count']
        );
        $this->send_host_message($data, $match_id, $host_id);

        //标记已发送广播消息
        $this->db->set('used', 1);
        $this->db->where('id', $match_thread_id);
        $this->db->update('matching_thread');
    }

    /**
     * 根据标签id获取话题列表（发布->头条资讯用到）
     * @param $type
     * @param $fakeId
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getThreadByFakeId($type,$fakeId,$offset=0,$limit=10){
        $table = $type.'_tag';
        $query = $this->db->select($type.'.*')
            ->join($type,$table.'.'.$type.'_id = '.$type.'.id','left')
            ->where($table.'.tag_id',$fakeId)
            ->offset($offset)
            ->limit($limit)
            ->order_by($table.'.id','DESC')
            ->get($table);
        $result = $query->result_array();
        $full = array();
        foreach($result as $item){
            $top = $this->dbSports->where(array('ref_id' => $item['id'], 'tag_id' => intval($fakeId)))->get('manual_priority')->row_array();
            $item['is_top'] = !empty($top);
            $item['tid'] = $top['id'];
            $item['image'] = $item['icon'];
            $item['publish_tm'] = $item['created_at'];
            $full[] = $item;
        }
        return $full;
    }
}