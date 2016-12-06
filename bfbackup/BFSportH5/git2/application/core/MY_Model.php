<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

    protected $_dbConns;
    protected $_dbCurrent;

    function __construct() {
        parent::__construct();

        foreach (array('sports', 'board', 'feedback') as $db) {
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

    ////////////////////////////////////////////////////////////////////////

    public function getNewsById($id) {

        $query = $this->dbSports->select('news.*,site.origin as site_name')
                ->join('site', 'site.site = news.site', 'left')
                ->where(array('news.id' => $id, 'news.visible' => 1))
                ->get('news');

        $result = $query->row_array();

        if ($result) {
            $result['column'] = $this->getNewsColumn($id);
            if ($result['column']) {
                $result['columnRelated'] = $this->getColumnRelated($result['column'], $id);
            }
            return $result;

        }
        return null;

    }

    public function getGalleryById($id) {
        $query = $this->dbSports->get_where('gallery', array('id' => intval($id), 'visible' => 1));
        $gallery = $query->row_array();
        if ($gallery) {
            $images = $this->dbSports->get_where('gallery_image', array('gallery_id' => intval($id)));

            $gallery['images'] = $images->result_array();
        }

        return $gallery;
    }

    public function getVideoById($id) {

        $query = $this->dbSports->get_where('video', array('id' => intval($id), 'visible' => 1));

        return $query->row_array();

    }

    //合集
    public function getCollectionById($id) {
        $query = $this->dbSports->get_where('collection', array('id' => intval($id)));
        return $query->row_array();
    }

    //节目
    public function getProgramById($id) {
        $query = $this->dbSports->get_where('program', array('id' => intval($id)));
        return $query->row_array();
    }

    //活动
    public function getActivityById($id) {
        $query = $this->dbSports->get_where('activity', array('id' => intval($id)));
        return $query->row_array();
    }

    //专题
    public function getSpecialById($id) {
        $query = $this->dbSports->get_where('special', array('id' => intval($id)));
        return $query->row_array();
    }

    //话题
    public function getThreadById($id) {
        $query = $this->dbBoard->get_where('thread', array('id' => intval($id)));
        return $query->row_array();
    }

    /**
     * 获得新闻关联专栏数据
     * @param $newsId
     * @return array
     */
    public function getNewsColumn($newsId) {

        $query = $this->dbSports->get_where('column_content', array('ref_id' => intval($newsId), 'type' => 'news'));

        $result = $query->row_array();
        if ($result && isset($result['column_id']) && $result['column_id']) {
            return $this->getColumnInfo($result['column_id']);
        }

        return null;

    }

    public function getColumnInfo($columnId) {
        $query = $this->dbSports->get_where('column', array('id' => intval($columnId)));
        return $query->row_array();
    }

    public function getColumnRelated($column, $newsId) {
        $query = $this->dbSports
                ->select('news.id,news.title,UNIX_TIMESTAMP(news.publish_tm) as publish_tm,news.image')
                ->join('news', 'news.id=column_content.ref_id', 'left')
                ->where(array('column_content.column_id' => intval($column['id']), 'column_content.ref_id !=' => intval($newsId)))
                ->order_by('column_content.priority', 'DESC')
                ->get('column_content');


        $full = array();

        foreach ($query->result_array() as $news) {

            $item = array('news_type' => 'news');
            $news['column'] = $column;
            $item['news_extra'] = $news;

            $full[] = $item;
        }

        return $full;
    }

    /**
     * 个性化推荐
     * @param $id
     * @param $limit
     * @param $type
     */
    protected function _getRelatedFromDt($id, $limit, $type) {
        $relatedData = array();
        if (!isset($this->AM)) {
            $this->load->model('api_model', 'AM');
        }

        $result = $this->AM->requestapi('sportsrec', array($id, $type, $limit));
        $result_arr = json_decode($result, true);
        if (isset($result_arr['error_code']) && $result_arr['error_code'] == 10000) {
            foreach ($result_arr['data'] as $row) {
                $relatedData[] = array('type' => $row['type'], 'ref_id' => $row['id']);
            }
        }

        return $relatedData;
    }

}
