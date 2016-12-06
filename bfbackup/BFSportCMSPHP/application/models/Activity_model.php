<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Activity_model
 * 活动
 */
class Activity_model extends MY_Model {
    public function __construct() {
        parent::__construct();
        //模型用到的数据库
        $this->db('sports');
        $this->db = $this->_dbCurrent;
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
        $query = $this->db->get('activity');
        return $query->result_array();
    }

    /**
     * 获得单条数据
     * @param $id
     * @return mixed
     */
    public function getItem($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('activity');
        $data = $query->row_array();
        if ($data) {
            $data['tags'] = $this->getTagsId($id);
        }
        return $data;
    }

    /**
     * 获得处理过的post表单数据
     * @return array
     */
    private function _getPost($data) {
        $visible = 0;
        if (isset($data['visible']) AND $data['visible'] === 'on') {
            $visible = 1;
        }
        $main_data = array(
            'title' => $data['title'],
            'image' => $data['image'],
            'url' => $data['url'],
            'visible' => $visible
        );
        return $main_data;
    }

    /**
     * 插入数据
     */
    public function add() {
        $data = $this->input->post();

        $this->db->insert('activity', $this->_getPost($data));

        $this->_addTags($data['tags'], $this->db->insert_id());
    }

    /**
     * 更新数据
     */
    public function edit($id) {
        $data = $this->input->post();
        $this->db->set('updated_at', date("Y-m-d H:i:s", time()));
        $this->db->where('id', $id);
        $this->db->update('activity', $this->_getPost($data));

        $this->_addTags($data['tags'], $id);

    }

    /**
     * 更新数据库中表的单列数据
     * @param string $table
     */
    public function inPlaceEdit($table) {
        parent::inPlaceEdit($table);
        if ($this->input->post('name') == 'visible' && $this->input->post('value') == 0) {
            $fieldId = $this->input->post('pk');
            $this->_delTags(array($fieldId));
        }
    }

    /**
     * 删除数据
     */
    public function del() {
        $ids = $this->input->post('ids');
        $this->db->where_in('id', $ids);
        $this->db->delete('activity');
        $this->_delTags($ids);
    }

    /**
     * 获得关联标签数据
     * @param $id
     * @return string
     */
    public function getTagsId($id) {
        $query = $this->db->where('activity_tag.activity_id', $id)->get('activity_tag');
        $tags = $query->result_array();
        $tagsId = '';
        foreach ($tags as $tag) {
            if ($tagsId !== '') {
                $tagsId .= ',';
            }
            if ($tag['tag_id'] !== '') {
                $tagsId .= $tag['tag_id'];
            }
        }
        return $tagsId;
    }

    /**
     * 插入关联标签数据
     * @param $tags_string
     * @param $gallery_id
     */
    private function _addTags($tags_string, $activity_id) {
        $tags_id = explode(',', $tags_string);
        if ($tags_id) {
            $this->_delTags(array($activity_id));
            foreach ($tags_id as $tag_id) {
                if ($tag_id) {
                    $this->db->insert('activity_tag', array('activity_id' => $activity_id, 'tag_id' => $tag_id));
                }
            }
        }
    }

    /**
     * 删除关联标签数据
     * @param $ids
     */
    private function _delTags($ids) {
        $this->db->where_in('activity_id', $ids);
        $this->db->delete('activity_tag');
    }

}