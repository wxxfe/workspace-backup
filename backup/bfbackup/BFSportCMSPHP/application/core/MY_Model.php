<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

    protected $_dbConns;
    protected $_dbCurrent;

    function __construct() {
        parent::__construct();

        foreach (array('kungfu', 'sports', 'board', 'quiz', 'user') as $db) {
            if (!isset($this->_dbConns[$db])) {
                $this->_dbConns[$db] = $this->load->database($db, true);
            }
            $this->_dbCurrent = current($this->_dbConns);
        }
    }

    public function db($db) {
        if (isset($this->_dbConns[$db])) {
            $this->_dbCurrent = $this->_dbConns[$db];
            return $this;
        } else {
            show_error("no database connection [$db]!");
        }
    }

    public function __get($key) {
        if (substr($key, 0, 2) == 'db') {
            return $this->db(strtolower(substr($key, 2)));
        } else {
            return parent::__get($key);
        }
    }

    public function __call($name, $args) {
        if (method_exists($this->_dbCurrent, $name)) {
            return call_user_func_array(array($this->_dbCurrent, $name), $args);
        }
    }

    //////////////////////////////////////////////////////////////////////////

    /**
     * 删除
     * @param string $table 表名
     * @param int $id 目标ID
     */
    public function remove($table, $id) {

        $this->_dbCurrent->where('id', intval($id));
        return $this->_dbCurrent->delete($table);

    }

    /**
     * 删除多条
     * @param string $table 表名
     * @param array $ids 多个目标ID
     */
    public function remove_batch($table, $ids) {

        if (is_array($ids)) {
            $this->_dbCurrent->where_in('id', $ids);
        } else {
            $this->_dbCurrent->where('id', $ids);
        }

        return $this->_dbCurrent->delete($table);

    }

    /**
     * 插入数据
     * @param string $table 表名
     * @param array $data 要插入的数据
     * @return int 返回新插入行的ID
     */
    public function insert($table, $data) {

        $this->_dbCurrent->insert($table, $data);

        return $this->_dbCurrent->insert_id();

    }

    /**
     * 插入多条数据
     * @param string $table 表名
     * @param array $data 多条数据的二维数组
     * @return int 返回新插入行的ID
     */
    public function insert_batch($table, $data) {

        $this->_dbCurrent->insert_batch($table, $data);

        return $this->_dbCurrent->insert_id();

    }

    /**
     * 更新数据
     * @param string $table 表名
     * @param array $newData 需要更新的数据
     * @param array $where 更新条件
     * @return int 返回受影响的行数
     */
    public function update($table, $newData, $where) {

        $this->_dbCurrent->where($where);
        $this->_dbCurrent->update($table, $newData);
        return $this->_dbCurrent->affected_rows();

    }

    /**
     * 获取数据总数
     * @param string $table 表名
     * @param array $where 查询条件
     * @return int 总数
     */
    public function getTotal($table, $where = array()) {

        $this->_dbCurrent->where($where);
        $this->_dbCurrent->from($table);

        return $this->_dbCurrent->count_all_results();

    }

    /**
     * 根据$table表的id更新其关联表（`type`）中的关联记录（`ref_id`）的更新时间字段
     * $table可能是`manual_priority`或`match_related`等
     */
    public function freshTopRefUpdatedAt($table, $id) {
        $id = intval($id);
        if ($id) {
            $query = $this->dbSports->select('type, ref_id')->get_where($table, array('id' => $id), 1);
            $row = $query ? $query->row_array() : array();
            if ($row) {
                $table = $row['type'];
                $ref_id = $row['ref_id'];
                if($table == 'thread') {
                    $result = $this->dbBoard->set('updated_at', 'NOW()', false)->where('id', $ref_id)->limit(1)->update($table);
                    $this->db('sports');//切换current数据库
                    return $result;
                } else {
                    return $this->dbSports->set('updated_at', 'NOW()', false)->where('id', $ref_id)->limit(1)->update($table);
                }
            }
        }
        return false;
    }

    /**
     * 设置排序
     * 最终显示顺序：从大到小，0是默认
     * @param string $table 表格名称
     * @param intval $id 数据id
     * @param array $sort 排序字段名和值，例：
     *          array('priority' => 1);
     *          注：排序值为显示最终排序的位置。
     * @param array $sort_condition 排序对应表格内条件，例：
     *          array();
     *          array('event_id' => 7);
     *          array('type' => 'event','ref_id' => 7);
     * @return boolean
     */
    public function setTbSort($table, $id, $sort, $sort_condition = array()) {
        // 前期准备，获取排序的key和value，拼接sort的条件
        $orig_sort_str = '';
        foreach ($sort_condition as $key => $value) {
            $orig_sort_str .= "{$key}='{$value}' AND";
        }
        $orig_sort_str = trim($orig_sort_str, 'AND');
        if (empty($orig_sort_str)) {
            $orig_sort_str = '1=1 ';
        }

        $sort_k_v = each($sort);
        $sort_k = $sort_k_v['key'];
        $sort_v = $sort_k_v['value'];
        if ($sort_v <= 0) {
            return $this->setTbSortZero($table, $id, $sort_k);
            throw new Exception('invalid sort');
        }

        // 查询待修改排序的数据的排序值
        $query = $this->_dbCurrent->get_where($table, array('id' => $id), 1);
        $orig_info = $query->row_array();
        if (empty($orig_info)) {
            return false;
            throw new Exception('invalid item');
        }

        // 如果待排序的数据排序值为0，原排序队列，所有排序值大于0的全部加1
        if ($orig_info[$sort_k] == 0) {
            $sql = "UPDATE {$table} SET {$sort_k}={$sort_k}+1 WHERE {$orig_sort_str} AND {$sort_k}>0";
            $this->_dbCurrent->query($sql);
        }

        $sql = "SELECT {$sort_k} FROM {$table} WHERE {$orig_sort_str} ORDER BY {$sort_k} DESC LIMIT 1 OFFSET " . intval($sort_v - 1);
        $info = $this->_dbCurrent->query($sql)->row_array();
        $sort_target_v = $info[$sort_k];
        if ($sort_target_v <= 0) {
            $sort_target_v = 1;
        }

        // 判断，要设置的排序，是从低往高排，或是从高往低排
        if ($orig_info[$sort_k] > $sort_target_v) { // 从高改到低
            $sql = "UPDATE {$table} SET {$sort_k}={$sort_k}+1 WHERE {$sort_k}>={$sort_target_v} AND {$sort_k}<={$orig_info[$sort_k]}";
            if (!empty($orig_sort_str)) {
                $sql .= " AND $orig_sort_str";
            }
            $this->_dbCurrent->query($sql);
        } else { // 从低改到高
            // 将原数据空出一个位置，给要排序的内容
            $sql = "UPDATE {$table} SET {$sort_k}={$sort_k}-1 WHERE {$sort_k}>{$orig_info[$sort_k]} AND {$sort_k}<={$sort_target_v}";
            if (!empty($orig_sort_str)) {
                $sql .= " AND $orig_sort_str";
            }
            $this->_dbCurrent->query($sql);
        }

        // 设置排序
        $sql = "UPDATE {$table} SET {$sort_k}={$sort_target_v} WHERE id={$id}";
        $this->_dbCurrent->query($sql);

        if (in_array($table, array('manual_priority', 'column_content'))) {
            $this->_setManualPriorityTime($table, $sort_condition);
        }
        
        return TRUE;
    }

    public function setTbSortZero($table, $id, $sort_key) {
        return $this->_dbCurrent->update($table, array($sort_key => 0), array('id' => $id));
    }

    private function _setManualPriorityTime($table, $sort_condition) {
        $this->_dbCurrent->select('*')
            ->from($table);
        
        foreach ($sort_condition as $key => $value) {
            $this->_dbCurrent->where($key, $value);
        }
        
        $query = $this->_dbCurrent->get();
        
        $type_ref_ids = array();
        foreach ($query->result_array() as $item) {
            if (isset($type_ref_ids[$item['type']])) {
                array_push($type_ref_ids[$item['type']], $item['ref_id']);
            } else {
                $type_ref_ids[$item['type']] = array($item['ref_id']);
            }
        }
        
        foreach ($type_ref_ids as $type=>$ids) {
            if (in_array($type, array('video','news','program','gallery','special','activity','collection')))
            if ($type != 'thread') {
                $sql = "UPDATE {$type} SET updated_at=sysdate() WHERE id IN (".join(',', $ids).")";
                $this->_dbCurrent->query($sql);
                // var_dump($this->_dbCurrent->last_query());
            }
        }
        
        return TRUE;
    }
    
    /**
     * 就地编辑
     * 更新数据库中表的单列数据
     * @param $table 表名
     */
    public function inPlaceEdit($table) {
        $fieldK = $this->input->post('name');
        $fieldV = $this->input->post('value');
        $fieldId = $this->input->post('pk');
        $this->_dbCurrent->set($fieldK, $fieldV);
        $this->_dbCurrent->set('updated_at', date("Y-m-d H:i:s", time()));
        $this->_dbCurrent->where('id', $fieldId);
        $this->_dbCurrent->update($table);
    }

    /**
     * 公司注册的用户 id数组
     * @var array
     */
    public $company_users = array(135601920097128682, 135601920097128683, 135601920097128684, 135601920097128685, 135601920097128686, 135601920097128687, 135601920097128688, 135601920097128689, 135601920097128690, 135601920097128691, 135601920097128692, 135601920097128693, 135601920097128694, 135601920097128695, 135601920097128696, 135601920097128697, 135601920097128698, 135601920097128699, 135601920097128700, 135601920097128702, 135601920097128703, 135601920097128704, 135601920097128705, 135601920097128706, 135601920097128707, 135601920097128708, 135601920097128709, 135601920097128710, 135601920097128711, 135601920097128712, 135601920097128713, 135601920097128714, 135601920097128715, 135601920097128716, 135601920097128717, 135601920097128718, 135601920097128719, 135601920097128720, 135601920097128721, 135601920097128722, 135601920097128723, 135601920097128724, 135601920097128725, 135601920097129108, 135601920097129109, 135601920097129110, 135601920097129111, 135601920097129112, 135601920097940709, 135601920097940710, 135601920097940711, 135601920097940712, 135601920097940713, 135601920097940714, 135601920097940715, 135601920097940716, 135601920097940717, 135601920097940718, 135601920097940719, 135601920097940720, 135601920097940721, 135601920097940722, 135601920097940723, 135601920097940724, 135601920097940725, 135601920097940726, 135601920097940727, 135601920097940728, 135601920097940729, 135601920097940730, 135601920097940731, 135601920097940732, 135601920097940733, 135601920097940734, 135601920097940735, 135601920097940736, 135601920097940737, 135601920097940738, 135601920097940739, 135601920097940740, 135601920097940741, 135601920097940742, 135601920097940743, 135601920097940744, 135601920097940745, 135601920097940747, 135601920097940748, 135601920097940749, 135601920097940750, 135601920097940751, 135601920097940752, 135601920097940753, 135601920097940754, 135601920097940755, 135601920097940756, 135601920097940757, 135601920097940758, 135601920097940759, 135601920097940760, 135601920097940761, 135601920097940762, 135601920097940763, 135601920097940764, 135601920097940765, 135601920097940766, 135601920097940767, 135601920097940768, 135601920097940769, 135601920097940771, 135601920097940772, 135601920097940773, 135601920097940774, 135601920097940775, 135601920097940776, 135601920097940777, 135601920097940778, 135601920097940779, 135601920097940780, 135601920097940781, 135601920097940782, 135601920097940783, 135601920097940784, 135601920097940785, 135601920097940786, 135601920097940787, 135601920097940788, 135601920097940789, 135601920097940790, 135601920097940791, 135601920097940792, 135601920097940794, 135601920097940795, 135601920097940796, 135601920097940797, 135601920097940798, 135601920097940799, 135601920097940800, 135601920097940801, 135601920097940802, 135601920097940803, 135601920097940804, 135601920097940805, 135601920097940806, 135601920097941027, 135601920097941028, 135601920097941030
    );

    /**
     * 互动主持人消息，发送给前端的数据
     * @param $data 具体模块的消息数据
     * @param $match_id 比赛ID
     * @param $host_id 主持人ID
     * @return 接口返回数据
     */

    public function send_host_message($data, $match_id, $host_id) {
        //发送给前端的数据
        $post_data = array(
            'type' => 9,
            'version' => 2,
            'data' => array(
                "match" => $match_id,
                'payload' => $data,
                'user' => $this->getUsersAPI($host_id, 2)[0]
            )
        );
        $r = send_post(API_INTERACTION_COMMIT, json_encode($post_data));
        return $r;
    }

    /**
     * 获得用户数据，通过API
     * @param string $ids 用户ID 逗号分隔字符串，如果为空，就取公司注册的用户
     * @param int $type 返回数据格式类型
     * 1 数据格式 array key是用户ID，value是用户名,
     * 2 数据格式 具体看接口说明 主持人消息 /api/v1/server/commit
     * 其他数字 原数据格式，具体看接口说明 获取用户信息 /api/server/v1/info/batch
     * @return array
     */
    public function getUsersAPI($ids = '', $type = 1) {

        // 因为工期原因，临时使用这个方法获取用户，最后正式还是要改成这个return下面的代码
        // return $this->_getUsersTemp($ids, $type);

        if (empty($ids)) {
            $ids = $this->company_users;
            $ids = explode(',', implode(',', $ids));
        } else {
            $ids = array_unique(explode(',', $ids));
        }
        $post_data = array(
            'ids' => $ids,
            'f' => API_GET_USER_PARAMETER_F,
            's' => API_GET_USER_PARAMETER_S
        );
        $users = json_decode(
            send_post(API_GET_USER, json_encode($post_data))
            , true
        );
        $users = $users['data'];
        $data = array();
        //返回数据格式类型
        switch ($type) {
            case 1:
                foreach ($users as $id => $user) {
                    $data[$id] = $user['name'];
                }
                break;
            case 2:
                foreach ($users as $id => $user) {
                    array_push(
                        $data,
                        array(
                            "id" => $id,
                            "name" => $user['name'],
                            "avatar" => $user['avatar'],
                            "favor" => 0
                        )
                    );
                }
                break;
            default:
                $data = $users;
                break;
        }
        return $data;
    }

    /**
     * 临时从表读取用户数据的方法，因为工期问题的临时方案，后面正式是通过接口获得用户数据，会删除此方法
     * @param string $ids
     * @param int $type 返回数据格式类型
     * @return array
     */
    private function _getUsersTemp($ids = '', $type = 1) {
        if (empty($ids)) {
            $ids = $this->company_users;
        } else {
            $ids = array_unique(explode(',', $ids));
        }

        //切换数据库之前先备份之前使用的数据库
        $backup_db = $this->_dbCurrent;
        //切换到话题数据库
        $this->db('board');
        $this->_dbCurrent->where_in('id', $ids);
        $query = $this->_dbCurrent->get('user');
        $users = $query->result_array();
        //还原
        $this->_dbCurrent = $backup_db;

        $data = array();
        //数据类型
        switch ($type) {
            case 1:
                foreach ($users as $user) {
                    $data[$user['id']] = $user['nickname'];
                }
                break;
            case 2:
                foreach ($users as $user) {
                    array_push(
                        $data,
                        array(
                            "id" => $user['id'],
                            "name" => $user['nickname'],
                            "avatar" => $user['avatar'],
                            "favor" => 0
                        )
                    );
                }
                break;
            default:
                $data = $users;
                break;
        }
        return $data;
    }

    /**
     * 通过用户名获得用户ID，通过API
     * @param $names 用户名 逗号分隔字符串
     * @return array 键名是用户名，键值用户数据数组
     */
    public function getUsersIDAPI($names) {
        //临时从表读取用户数据的方法，因为工期问题的临时方案，后面正式是通过接口获得，会更新这里的代码
        $names = array_unique(explode(',', $names));

        //切换数据库之前先备份之前使用的数据库
        $backup_db = $this->_dbCurrent;
        //切换到话题数据库
        $this->db('board');
        $this->_dbCurrent->where_in('nickname', $names);
        $query = $this->_dbCurrent->get('user');
        $users = $query->result_array();
        //还原
        $this->_dbCurrent = $backup_db;

        $data = array();
        foreach ($users as $user) {
            $name = $user['nickname'];
            $user_data = array(
                "id" => $user['id'],
                "name" => $name,
                "avatar" => $user['avatar']
            );
            if (isset($data[$name]) AND $data[$name]) {
                array_push($data[$name], $user_data);
            } else {
                $data[$name] = array($user_data);
            }
        }

        return $data;
    }

    /**
     * 获取排序后的顺序值
     * @param $where
     * @return mixed
     */
    public function getSort($table_name, $id, $where = array()) {
        if (!$table_name || empty($where)) return false;
        $this->dbSports->from($table_name);
        $this->dbSports->where($where);
        $this->dbSports->order_by('priority', 'DESC');
        $this->dbSports->order_by('id', 'ASC');
        $result = $this->dbSports->get()->result_array();
        $sort = 0;
        $count = count($result);
        for ($i = 0; $i <= $count; $i++) {
            if ($result[$i]['id'] == $id) {
                $sort = $i + 1;
                break;
            }
        }
        if (!$sort) $sort = $count + 1;
        return $sort;
    }

}
