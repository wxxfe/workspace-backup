<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Gallery_model
 * 图集
 */
class Gallery_model extends MY_Model {
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
        $query = $this->db->get('gallery');
        return $query->result_array();
    }

    /**
     * 获得单条数据
     * @param $id
     * @return mixed
     */
    public function getItem($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('gallery');
        $data = $query->row_array();

        if ($data) {
            $data['tags'] = $this->getTagsId($id);
            $data['img_list'] = $this->getImagesList($id);
            $data['img_list_json'] = addslashes(json_encode($data['img_list']));
            $data['match_id'] = $this->getMatchId($id);
        }
        return $data;
    }

    /**
     * 获得处理过的post表单数据
     * @return array
     */
    private function _getPost() {
        $data = $this->input->post();

        $tags_string = $data['tags'];
        unset($data['tags']);

        $match_id = $data['match_id'];
        unset($data['match_id']);

        return array($data, $tags_string, $match_id);
    }

    /**
     * 插入数据
     */
    public function add() {
        $data = $this->_getPost();

        $this->db->insert('gallery', $data[0]);
        $id = $this->db->insert_id();

        $this->_addTags($data[1], $id);
        $this->_addMatch($data[2], $id);

        return $id;
    }

    /**
     * 更新数据
     */
    public function edit($id) {
        $data = $this->_getPost();
        $this->db->set('updated_at', date("Y-m-d H:i:s", time()));
        $this->db->where('id', $id);
        $this->db->update('gallery', $data[0]);

        $this->_addTags($data[1], $id);
        $this->_addMatch($data[2], $id);
    }

    /**
     * 更新数据库中表的单列数据
     * @param string $table
     */
    public function inPlaceEdit($table) {
        parent::inPlaceEdit($table);
        if ($this->input->post('name') == 'visible' && $this->input->post('value') == 0) {
            $fieldId = $this->input->post('pk');
            //$this->_delTags(array($fieldId));
            $this->_delMatch(array($fieldId));
        }
    }

    /**
     * 删除数据
     * @param $id
     */
    public function del() {
        $ids = $this->input->post('ids');
        $this->db->where_in('id', $ids);
        $this->db->delete('gallery');
        $this->db->where_in('gallery_id', $ids);
        $this->db->delete('gallery_image');
        $this->_delMatch($ids);
        $this->_delTags($ids);
    }

    /**
     * 获得关联标签数据
     * @param $id
     * @return string
     */
    public function getTagsId($id) {
        $query = $this->db->where('gallery_tag.gallery_id', $id)->get('gallery_tag');
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
    private function _addTags($tags_string, $gallery_id) {
        $tags_id = explode(',', $tags_string);
        if ($tags_id) {
            $this->_delTags(array($gallery_id));
            foreach ($tags_id as $tag_id) {
                if ($tag_id) {
                    $this->db->insert('gallery_tag', array('gallery_id' => $gallery_id, 'tag_id' => $tag_id));
                }
            }
        }
    }

    /**
     * 删除关联标签数据
     * @param $ids
     */
    private function _delTags($ids) {
        $this->db->where_in('gallery_id', $ids);
        $this->db->delete('gallery_tag');
    }

    /**
     * 获得关联比赛数据
     * @param $id
     * @return string
     */
    public function getMatchId($id) {
        $this->db->where('type', 'gallery');
        $this->db->where('ref_id', $id);
        $query = $this->db->get('match_related');
        $data = $query->row_array();
        if ($data AND isset($data['match_id'])) {
            return $data['match_id'];
        } else {
            return '';
        }

    }

    /**
     * 插入关联比赛数据
     * @param $match_id
     * @param $gallery_id
     */
    private function _addMatch($match_id, $gallery_id) {
        if ($match_id) {
            $this->_delMatch(array($gallery_id));
            $this->db->insert('match_related', array('type' => 'gallery', 'ref_id' => $gallery_id, 'match_id' => $match_id));
        }
    }

    /**
     * 删除关联比赛数据
     * @param $ids
     */
    private function _delMatch($ids) {
        $this->db->where('type', 'gallery');
        $this->db->where_in('ref_id', $ids);
        $this->db->delete('match_related');
    }

    /**
     * 获得图集包含的所有图片
     * @param $gallery_id
     * @return mixed
     */
    public function getImagesList($gallery_id) {
        $this->db->order_by('priority', 'DESC');
        $this->db->where('gallery_id', $gallery_id);
        $query = $this->db->get('gallery_image');
        return $query->result_array();
    }

    /**
     * 获得图集包含的图片数量
     * @param $gallery_id
     * @return mixed
     */
    public function getImagesNum($gallery_id) {
        $this->db->where('gallery_id', $gallery_id);
        $this->db->from('gallery_image');
        return $this->db->count_all_results();
    }

    /**
     * 插入图片数据，返回所有图片数据
     * @return mixed
     */
    public function addImages() {
        $data = $this->input->post();
        $this->db->insert_batch('gallery_image', $data['images']);
        
        // 处理图集的图片顺序
        $query = $this->db('sports')->select('*')
                ->from('gallery_image')
                ->where('gallery_id', $data['gallery_id'])
                ->where('priority', 0)
                ->order_by('created_at', 'ASC')
                ->get();
        
        $sort = $this->db('sports')->from('gallery_image')
                ->where('gallery_id', $data['gallery_id'])
                ->where('priority>0')
                ->count_all_results();
        
        foreach($query->result_array() as $item) {
            $this->setTbSort('gallery_image', $item['id'], array('priority' => $sort+1), array('gallery_id'=>$data['gallery_id']));
            $sort++;
        }

        $this->setGalleryUpdateTime($data['gallery_id']);
        return $this->getImagesList($data['gallery_id']);
    }

    /**
     * 编辑图片，如果是编辑图片排序，则返回所有图片数据
     * @return mixed
     */
    public function editImage() {
        $fieldK = $this->input->post('name');
        $fieldV = $this->input->post('value');
        $fieldId = $this->input->post('pk');
        
        // 检查gallery_id，修改gallery表格对应内容的updated_at字段
        $this->db->where('id', $fieldId);
        $query = $this->db->get('gallery_image');
        $data = $query->row_array();
        $this->setGalleryUpdateTime($data['gallery_id']);
        ///////
        
        if ($fieldK == 'priority') {
            $this->setTbSort('gallery_image', $fieldId, array('priority' => $fieldV), array('gallery_id'=>$data['gallery_id']));

            return $this->getImagesList($data['gallery_id']);
        } else {
            $this->inPlaceEdit('gallery_image');
        }
    }

    /**
     * 删除图片
     * @param $id 图片ID
     */
    public function delImage($id) {
        // 检查gallery_id，修改gallery表格对应内容的updated_at字段
        $this->db->where('id', $id);
        $query = $this->db->get('gallery_image');
        $data = $query->row_array();
        $this->setGalleryUpdateTime($data['gallery_id']);
        ///////
        
        $this->db->where('id', $id);
        $this->db->delete('gallery_image');
    }

    /**
     * 获取图集的相关推荐，目前只能推荐图集
     * @param int $id 新闻ID
     * @return array
     */
    public function getRelateds($id) {
        $query = $this->db('sports')->select('gallery.*,gallery_related.id AS related_id')
                ->from('gallery_related')
                ->join('gallery', 'gallery_related.ref_id=gallery.id', 'left')
                ->where('gallery_related.gallery_id', $id)
                ->order_by('priority', 'DESC')
                ->get();
        
        return $query->result_array();
        
    }
    
    public function setGalleryUpdateTime($gallery_id) {
        $this->dbSports->set('updated_at', 'NOW()', false)->where('id', $gallery_id)->limit(1)->update('gallery');
    }
}
