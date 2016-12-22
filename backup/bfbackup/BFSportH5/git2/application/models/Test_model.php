<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_model extends Test_Base_Model
{
    public function getNewsRelated($newsId)
    {
        $data = array(
            'editor'=>array(),
            'Dt'=>array(),
            'all'=>array()
        );


        $limit = 5;
        // FROM DB
        $query = $this->dbSports->limit($limit, 0)->get_where('news_related', array('news_id' => intval($newsId)));
        $relatedData = $query->result_array();
        $data['editor'] = $relatedData;

        // DT REC
        if (count($relatedData) < $limit) {
            // GET DATA
            $relatedDt = $this->_getRelatedFromDt($newsId, 10, 'news');//取10条去重

            if (!empty($relatedDt)) {
                // 去掉重复数据
                $ref_ids = array();
                foreach ($relatedData as $val) {
                    $ref_ids[] = $val['ref_id'];
                }

                foreach ($relatedDt as $key => $related) {
                    if (count($relatedData) >= $limit) {
                        break;
                    }
                    if (isset($related['ref_id']) && !in_array($related['ref_id'], $ref_ids)) {
                        $data['Dt'][] = $related;
                        $relatedData[] = $related;
                    }
                }
            }
        }

        // FORMAT DATA
        $formated_data = array();
        foreach ($relatedData as $val) {
            $item = array('news_type' => $val['type']);
            $action = 'get' . strtolower($val['type']) . 'ById';

            $item['news_extra'] = $this->$action($val['ref_id']);
            if ($item['news_extra']) {
                unset($item['news_extra']['content']);
                $formated_data[] = $item;
            }
        }
        $data['all'] = $formated_data;

        return $data;
    }


    /**
     * 获取相关图集内容
     * @param $galleryId
     * @return array
     */
    public function getGalleryRelated($galleryId)
    {
        $data = array(
            'editor'=>array(),
            'Dt'=>array(),
            'all'=>array()
        );

        $this->load->model('api_model', 'AM');

        $limit = 5;
        $relatedData = array();

        $result = $this->AM->requestapi('gallery_related', array($galleryId));
        $response = json_decode($result, true);
        if (isset($response['errno']) && $response['errno'] == 10000 && isset($response['data']['gallery'])) {
            $galleries = $response['data']['gallery'];
            foreach ($galleries as $val) {
                if (count($relatedData) >= 5) {
                    break;
                }
                $tmp = array(
                    'ref_id' => isset($val['id']) ? $val['id'] : '',
                    'type' => isset($val['type']) ? $val['type'] : '',
                );
                $relatedData[] = $tmp;
            }
        }
        $data['editor'] = $relatedData;

        // DT REC
        if (count($relatedData) < $limit) {
            // GET DATA
            $relatedDt = $this->_getRelatedFromDt($galleryId, 10, 'gallery');

            if (!empty($relatedDt)) {
                // 去掉重复数据
                $ref_ids = array();
                foreach ($relatedData as $val) {
                    $ref_ids[] = $val['ref_id'];
                }

                foreach ($relatedDt as $key => $related) {
                    if (count($relatedData) >= $limit) {
                        break;
                    }
                    if (isset($related['ref_id']) && !in_array($related['ref_id'], $ref_ids)) {
                        $data['Dt'][] = $related;
                        $relatedData[] = $related;
                    }
                }
            }
        }

        // FORMAT DATA
        $formated_data = array();
        foreach ($relatedData as $val) {
            $item = array('news_type' => $val['type']);
            $action = 'get' . strtolower($val['type']) . 'ById';

            $item['news_extra'] = $this->$action($val['ref_id']);
            if ($item['news_extra']) {
                $formated_data[] = $item;
            }
        }

        $data['all'] = $formated_data;

        return $data;
    }


    /**
     * 获取相关推荐
     * @param $video_id
     */
    public function getVideoRelated($video_id)
    {
        $data = array(
            'editor'=>array(),
            'Dt'=>array(),
            'all'=>array()
        );

        $limit = 5;
        $relatedData = array();

        $this->load->model('Api_model', 'AM');
        $api_data = $this->AM->requestapi('video_related', array($video_id));
        $api_data_arr = json_decode($api_data, true);
        if ($api_data_arr['errno'] == '10000') {
            $videos = $api_data_arr['data']['video'];
            foreach ($videos as $val) {
                if (count($relatedData) >= 5) {
                    break;
                }
                $tmp = array(
                    'ref_id' => isset($val['id']) ? $val['id'] : '',
                    'type' => isset($val['type']) ? $val['type'] : '',
                );
                $relatedData[] = $tmp;
            }
        }
        $data['editor'] = $relatedData;

        // DT REC
        if (count($relatedData) < $limit) {
            // GET DATA
            $relatedDt = $this->_getRelatedFromDt($video_id, 10, 'video');

            if (!empty($relatedDt)) {
                // 去掉重复数据
                $ref_ids = array();
                foreach ($relatedData as $val) {
                    $ref_ids[] = $val['ref_id'];
                }

                foreach ($relatedDt as $key => $related) {
                    if (count($relatedData) >= $limit) {
                        break;
                    }
                    if (isset($related['ref_id']) && !in_array($related['ref_id'], $ref_ids)) {
                        $data['Dt'][] = $related;
                        $relatedData[] = $related;
                    }
                }
            }
        }

        // FORMAT DATA
        $formated_data = array();
        foreach ($relatedData as $val) {
            $item = array('news_type' => $val['type']);
            $action = 'get' . strtolower($val['type']) . 'ById';

            $item['news_extra'] = $this->$action($val['ref_id']);
            if ($item['news_extra']) {
                $formated_data[] = $item;
            }
        }

        $data['all'] = $formated_data;

        return $data;
    }


    /**
     * 个性化推荐
     * @param $id
     * @param $limit
     * @param $type
     */
    protected function _getRelatedFromDt($id, $limit, $type)
    {
        $relatedData = array();
        if (!isset($this->AM)) {
            $this->load->model('api_model', 'AM');
        }

        $result = $this->AM->requestapi('sportsrec_test', array($id, $type, $limit));
        $result_arr = json_decode($result, true);
        if (isset($result_arr['error_code']) && $result_arr['error_code'] == 10000) {
            foreach ($result_arr['data'] as $row) {
                $relatedData[] = array('type' => $row['type'], 'ref_id' => $row['id']);
            }
        }

        return $relatedData;
    }
}

//------

class Test_Base_Model extends CI_Model {

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
            ->where('news.id', $id)
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

        $query = $this->dbSports->get_where('gallery', array('id' => intval($id)));
        $gallery = $query->row_array();

        $images = $this->dbSports->get_where('gallery_image', array('gallery_id' => intval($id)));

        $gallery['images'] = $images->result_array();

        return $gallery;

    }

    public function getVideoById($id) {

        $query = $this->dbSports->get_where('video', array('id' => intval($id)));

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

}
?>