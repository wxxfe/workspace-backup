<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('News_model', 'NM');
    }

    public function index($page = 0) {

        $data = array();
        $data['type'] = 'news';
        $data['keyword'] = '';
        $limit = 20;
        $news = array();
        if (isset($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            $data['keyword'] = $keyword;
            $data['type'] = 'search';
            if (is_numeric($keyword)) {
                $news = $this->_getFullNews($this->NM->db('sports')->get_where('news', array('id' => intval($keyword)))->result_array());
                $total = empty($news) ? 0 : 1;
            } else {
                $this->load->model('search_model', 'SM');
                $searchResult = $this->SM->itemsQuery('news', $keyword, $page, $limit);
                $total = $searchResult['total'];
                $news = $this->_getFullNews($searchResult['result']);
            }
        } else {
            $total = $this->NM->db('sports')->getTotal('news');
            $news = $this->_getFullNews($this->NM->getAllNews($page, $limit));
        }

        $data['total'] = $total;
        $data['allNews'] = $news;
        $data['page'] = $this->_pagination($total, $limit, '/news/index/');

        $this->load->view('news/list', $data);
    }

    private function _getFullNews($data) {
        $this->load->model('Match_model', 'MM');
        $news = $data;
        $full = array();
        foreach ($news as $item) {
            $item['match_info'] = $this->MM->getMatchInfo($item['rm_id'], '<br>');
            $full[] = $item;
        }
        return $full;
    }


    public function edit($type, $id = 0) {

        if (!$type) show_404();

        $route = '_' . $type;

        $this->$route($id);

    }

    public function related($newsId) {
        $this->load->model('Tag_model', 'TM');

        $relateds = $this->NM->getNewsRelateds($newsId);
        $relatedData = array();
        foreach ($relateds as $r) {
            $type = $r['type'];
            $item = array('type' => $type, 'rid' => $r['id']);
            
            if ($type == 'thread') {
                $item['info'] = $this->NM->db('board')->select('id,title,icon,visible,created_at')
                    ->get_where($type, array('id' => $r['ref_id']))
                    ->row_array();
                $item['info']['image'] = $item['info']['icon'];
                $item['info']['publish_tm'] = $item['info']['created_at'];
                $tags = $this->NM->db('board')->select('tag_id')->get_where($type . '_tag', array($type . '_id' => $r['ref_id']))->result_array();
            } else {
                $item['info'] = $this->NM->db('sports')->select('id,title,image,visible,publish_tm')
                    ->get_where($type, array('id' => $r['ref_id']))
                    ->row_array();
                $tags = $this->NM->db('sports')->select('tag_id')->get_where($type . '_tag', array($type . '_id' => $r['ref_id']))->result_array();
            }
            $tagIds = array();
            foreach ($tags as $t) {
                $tagIds[] = $t['tag_id'];
            }
            $item['info']['tags'] = $this->TM->db('sports')->getTagsByFakeId(implode(',', $tagIds));
            $relatedData[] = $item;
        }
        $data = array();
        $data['newsInfo'] = $this->NM->getNewsById($newsId);
        $data['news'] = $relatedData;
        $data['newsId'] = $newsId;
        $this->load->view('news/related', $data);
    }

    public function setRelated($newsId, $type, $relatedId) {
        $numargs = func_num_args();
        if ($numargs < 3) show_404();
        $related = array(
            'news_id' => intval($newsId),
            'type' => $type,
            'ref_id' => $relatedId,
            'priority' => 0
        );
        $r = $this->NM->db('sports')->insert('news_related', $related);
        if ($r) redirect(base_url('/news/related/' . $newsId));
    }

    public function cancelRelated($rid) {
        try {
            $this->NM->db('sports')->remove('news_related', $rid);
            echo 'success';
        } catch (Exception $e) {
            echo 'fail';
        }
    }

    public function updateSort() {
        $data = $_POST;
        $rid = $data['pk'];
        $newsId = $data['news_id'];
        $sort = intval($data['value']);
        $result = $this->NM->db('sports')->setTbSort('news_related', $rid, array('priority' => $sort), array('news_id' => $newsId));
        echo $result ? 'success' : 'fail';
    }

    public function updateField() {
        $data = $_POST;
        $new = array($data['name'] => $data['value']);
        $result = $this->NM->db('sports')->update('news', $new, array('id' => $data['pk']));

        //9月30日，新闻下线需要同时删除新闻相关标签，关联关系。删除比赛关联的视频关系
        if ($data['name'] == 'visible' && $data['value'] == 0) {
            $result = $this->NM->db('sports')->update('news', array('match_id' => null), array('id' => $data['pk']));
            $this->_deleteRelated('news', $data['pk']);
        }
        return $result > 0 ? 'success' : 'fail';
    }


    private function _add() {
        $data = array();
        if ($_POST) {
            $data['title'] = $this->input->post('title');
            $data['subtitle'] = $this->input->post('subtitle');
            $data['site'] = $this->input->post('site');
            $data['image'] = $this->input->post('poster');
            $data['large_image'] = $this->input->post('cover');
            $data['orig_url'] = NULL;
            $data['content'] = $this->input->post('content');
            $data['visible'] = $this->input->post('visible');
            $data['duration'] = intval($this->input->post('duration') * 60);
            if ($this->input->post('publish_tm')) {
                $data['publish_tm'] = $this->input->post('publish_tm');
            }
            $tags = $this->input->post('tags');
            $matchId = $this->input->post('match_id');
            if (!empty($matchId)) {
                $data['match_id'] = $matchId;
            } else {
                $data['match_id'] = null;
            }
            $newsId = $this->NM->db('sports')->insert('news', $data);

            if ($newsId) {
                if ($tags) $this->_setTags($newsId, $tags);
                if ($matchId) $this->_setMatchId($newsId, $matchId);
            }

            redirect(base_url('/news/'));

        } else {
            $data['sites'] = $this->NM->db('sports')->get('site')->result_array();

            $this->load->view('news/add', $data);
        }
    }

    private function _update($newsId) {

        if ($_POST) $this->_updateNewsData($newsId, $_POST);

        $news = $this->NM->getNewsById($newsId);
        $tags = $this->NM->getTagsByNewsId($newsId);
        $matchId = $this->NM->getMatchIdByNewsId($newsId);
        $news['tags'] = $tags;
        $news['match_id'] = $matchId;
        $data['news'] = $news;

        $data['sites'] = $this->NM->db('sports')->get('site')->result_array();
        $this->load->view('news/edit', $data);

    }

    private function _updateNewsData($id, $news) {
        $redirect = $this->input->get('redirect');

        $now = date('Y-m-d H:i:s', time());
        $data = array();
        $data['title'] = $news['title'];
        $data['subtitle'] = $news['subtitle'];
        $data['site'] = $news['site'];
        $data['image'] = $news['poster'];
        $data['large_image'] = $news['cover'];
        $data['content'] = $news['content'];
        $data['visible'] = $news['visible'];
        $data['publish_tm'] = $news['publish_tm'];
        $data['updated_at'] = $now;
        $data['duration'] = intval($news['duration'] * 60);

        $tags = $news['tags'];
        $matchId = $news['match_id'];
        if (!empty($matchId)) {
            $data['match_id'] = $matchId;
        } else {
            $data['match_id'] = null;
        }

        $this->NM->db('sports')->update('news', $data, array('id' => $id));
        $this->_setTags($id, $tags);
        if (!empty($matchId)) {
            $exits = $this->NM->db('sports')->get_where('match_related', array('ref_id' => $id, 'type' => 'news'))->num_rows() > 0;
            if ($exits) {
                $this->NM->db('sports')->update('match_related', array('match_id' => $matchId), array('ref_id' => $id, 'type' => 'news'));
            } else {
                $this->NM->db('sports')->insert('match_related', array('match_id' => $matchId, 'type' => 'news', 'ref_id' => $id, 'priority' => 0));
            }
        }else{
            $this->NM->db('sports')->where(array('ref_id' => $id, 'type' => 'news'))->delete('match_related');
        }
        // 9月30日，新闻下线需要同时删除新闻相关标签，关联关系。删除比赛关联的新闻关系
        if ($data['visible'] == 0) {
            $this->_deleteRelated('news', $id);
        }

        if (!empty($redirect)) {
            redirect($redirect);
        } else {
            redirect(base_url('/news/index'));
        }
    }

    private function _setTags($newsId, $tags) {
        $tagsArr = explode(',', $tags);
        $this->NM->db('sports')->where(array('news_id' => $newsId))->delete('news_tag');
        $full = array();
        foreach ($tagsArr as $tagId) {
            $full[] = array('news_id' => $newsId, 'tag_id' => $tagId);
        }
        $this->NM->db('sports')->insert_batch('news_tag', $full);
    }

    private function _setMatchId($newsId, $matchId) {
        $data = array(
            'match_id' => $matchId,
            'type' => 'news',
            'ref_id' => $newsId,
            'priority' => 0
        );
        $this->NM->db('sports')->insert('match_related', $data);
    }

    public function remove() {
        $id = $_POST['id'];
        if (strpos($id, ',') . '' != '') {
            $ids = explode(',', $id);
            try {
                $this->NM->db('sports')->remove_batch('news', $ids);
                echo 'success';
            } catch (Exception $e) {
                echo 'fail';
            }
        } else {
            try {
                $this->NM->db('sports')->remove('news', $id);
                echo 'success';
            } catch (Exception $e) {
                echo 'fail';
            }
        }
    }

}
