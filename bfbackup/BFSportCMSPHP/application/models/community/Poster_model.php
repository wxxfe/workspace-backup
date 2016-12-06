<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Poster_model
 * 轮播图
 */
class Poster_model extends MY_Model {
    public function __construct() {
        parent::__construct();
        //模型用到的数据库
        $this->db('board');
        $this->db = $this->_dbCurrent;
    }

    /**
     * 获得多条数据
     * @param int $limit 条数
     * @param int $offset 偏移量
     * @return mixed
     */
    public function getList($limit = 0, $offset = 0) {
        $this->db->order_by('display_order', 'ASC');
        $this->db->order_by('updated_at', 'DESC');
        if ($limit) $this->db->limit($limit, $offset);
        $query = $this->db->get('poster');
        return $query->result_array();
    }

    /**
     * 获得单条数据
     * @param $id
     * @return mixed
     */
    public function getItem($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('poster');
        return $query->row_array();
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

        if (isset($data['threads_add'])) {
            unset($data['threads_add']);
        }

        $threads = array();
        if (isset($data['threads'])) {
            $threads = explode(',', $data['threads']);
            unset($data['threads']);
        }
        return array($data, $threads);
    }

    /**
     * 插入数据
     */
    public function add() {
        $data = $this->_getPost();

        $this->db->insert('poster', $data[0]);
        $id = $this->db->insert_id();

        $this->_addPosterHasThreads($data[1], $id);
    }

    /**
     * 更新数据
     */
    public function edit($id) {
        $data = $this->_getPost();

        $this->db->where('id', $id);
        $this->db->update('poster', $data[0]);

        $this->_addPosterHasThreads($data[1], $id);
    }

    /**
     * 更新数据库中表的单列数据
     * @param string $table
     */
    public function inPlaceEdit($table) {
        parent::inPlaceEdit($table);
        if ($this->input->post('name') == 'display_order') {
            $data = $this->getList();
            $order = 1;
            foreach ($data as $item) {
                $this->db->set('display_order', $order++);
                $this->db->where('id', $item['id']);
                $this->db->update('poster');
            }
        }
    }

    /**
     * 删除数据
     * @param $id
     */
    public function del($id) {
        $this->db->where('id', $id);
        $this->db->delete('poster');
    }

    /**
     * 获得指定轮播图关联的话题数据
     * @param $id
     * @return mixed
     */
    public function getPosterHasThreads($id) {
        $this->db->where('poster_id', $id);
        $this->db->order_by('display_order', 'ASC');
        $query = $this->db->get('poster_has');
        return $query->result_array();
    }

    /**
     * 插入轮播图关联的话题数据
     * @param $threads
     * @param $poster_id
     */
    private function _addPosterHasThreads($threads, $poster_id) {
        if ($threads) {
            $this->_delPosterHasThreads($poster_id);
            foreach ($threads as $thread) {
                if ($thread) {
                    $data = explode('|', $thread);
                    $thread_id = (int)$data[0];
                    $display_order = (int)$data[1];
                    if ($thread_id) $this->db->insert('poster_has', array('thread_id' => $thread_id, 'poster_id' => $poster_id, 'display_order' => $display_order));
                }
            }
        }
    }

    /**
     * 删除轮播图关联的话题数据
     * @param $id
     */
    private function _delPosterHasThreads($id) {
        $this->db->where('poster_id', $id);
        $this->db->delete('poster_has');
    }

}