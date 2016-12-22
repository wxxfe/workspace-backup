<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('video_model', 'VM');
        $this->limit = 10;
    }

    public function index() {

    }

    // 待编辑视频列表
    public function pendingList($offset = 0) {
        $condition = array();
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        if (!empty($keyword) && !is_numeric($keyword)) {
            $result = $this->VM->searchList($keyword, $this->limit, $offset);
            $list = $result['result'];
            $total = $result['total'];
        } else {
            if (is_numeric($keyword)) {
                $condition['id'] = $keyword;
            }

            $list = $this->VM->getPendingList($condition, $this->limit, $offset);
            $total = $this->VM->getPendingTotal($condition);
        }

        $this->load->model('tag_model', 'TagModel');

        // 获取视频的标签
        foreach ($list as & $video) {
            $video_tag_ids = $this->VM->getVideoTags($video['id']);
            if (!empty($video_tag_ids)) {
                $video_tag = $this->TagModel->db('sports')->getTagsByFakeId(join(',', $video_tag_ids));
                $tag_str = '';
                foreach ($video_tag as $item) {
                    $tag_str .= $item['name'] . ',';
                }
            }
            $video['tag_str'] = isset($tag_str) ? trim($tag_str, ',') : '';
            unset($tag_str);
        }


        $data['page'] = $this->_pagination($total, $this->limit, '/video/pendingList');
        $data['list'] = $list;
        $data['keyword'] = $keyword;
        $this->load->view('video/edited_list', $data);
    }

    // 可用视频列表
    public function editedList($offset = 0) {
        $condition = array();
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        if (!empty($keyword) && !is_numeric($keyword)) {
            $result = $this->VM->searchList($keyword, $this->limit, $offset);
            $list = $result['result'];
            $total = $result['total'];
        } else {
            if (is_numeric($keyword)) {
                $condition['id'] = $keyword;
            }
            $list = $this->VM->getList($condition, $this->limit, $offset);
            $total = $this->VM->getVideosTotal($condition);
        }

        $this->load->model('tag_model', 'TagModel');
        $this->load->model('Match_model', 'MM');

        // 获取视频的标签
        if(!empty($list))
        {
            foreach ($list as $key =>  &$video) {
                $list[$key]['match_info'] = $this->MM->getMatchInfo($video['match_id'], '<br>');
                $video_tag_ids = $this->VM->getVideoTags($video['id']);
                if (!empty($video_tag_ids)) {
                    $video_tag = $this->TagModel->db('sports')->getTagsByFakeId(join(',', $video_tag_ids));
                    $tag_str = '';
                    foreach ($video_tag as $item) {
                        $tag_str .= $item['name'] . ',';
                    }
                }
                $video['tag_str'] = isset($tag_str) ? trim($tag_str, ',') : '';
                unset($tag_str);
            }
        }

        $data['page'] = $this->_pagination($total, $this->limit, '/video/editedList');
        $data['list'] = $list;
        $data['keyword'] = $keyword;
        $this->load->view('video/edited_list', $data);
    }

    // 修改启用/禁用状态的ajax调用
    public function update() {
        $video_id = $_POST['pk'];
        $info = array(
            $_POST['name'] => $_POST['value']
        );
        $this->VM->updateVideo($video_id, $info);

        if ($_POST['name'] == 'visible') {
            if ($_POST['value'] == 1) {
                $this->LM->uadd('video_enabled', json_encode($this->userInfo['current_menu']), array('id' => $video_id));
            } else if ($_POST['value'] == 0) {
                // 9月30日，视频下线需要同时删除视频相关标签，关联关系。删除比赛关联的视频关系
                $this->_deleteRelated('video', $video_id);

                $this->LM->uadd('video_disabled', json_encode($this->userInfo['current_menu']), array('id' => $video_id));
            }
        }

        return true;
    }

    // 待编辑视频，编辑页面
    public function pendingEdit($id) {
        $pending_video = $this->VM->getPendingInfo($id);
        if (!empty($_POST)) {
            $this->addVideo($id, $_POST);
            redirect(site_url('video/pendingList'));
        }
        $site_list = $this->VM->getSites();

        $data['video'] = $pending_video;
        $data['site_list'] = $site_list;
        $this->load->view('video/pending_edit', $data);
    }

    // 可用视频，视频详情
    public function detailEdit($id) {
        $video = $this->VM->getVideoInfo($id);
        $related_match = $this->VM->getMatchRelate($id);
        $match_id = isset($related_match['match_id']) ? $related_match['match_id'] : 0;

        $video_tags = $this->VM->getVideoTags($id);
        $video_tags = join(',', $video_tags);
        if (!empty($_POST)) {
            $this->editVideo($id, $_POST);

            $redirect = $this->input->get('redirect');
            if (!empty($redirect)) {
                redirect($redirect);
            } else {
                if (empty($video_tags)) {
                    redirect(site_url('video/pendingList'));
                } else {
                    redirect(site_url('video/editedList'));
                }
            }
        }
        $site_list = $this->VM->getSites();

        $data['video'] = $video;
        $data['site_list'] = $site_list;
        $data['video_tags'] = $video_tags;
        // $data['match_id'] = $match_id;
        $data['match_id'] = $video['match_id'];
        $this->load->view('video/detail_edit', $data);
    }

    // 修改视频内容
    public function editVideo($video_id, $post_info) {
        $title = $post_info['title'];
        $match_id = isset($post_info['match_id']) ? $post_info['match_id'] : 0;
        $is_vr = isset($post_info['is_vr']) ? 1 : 0;
        // 处理时长
        $duration = isset($post_info['duration']) ? $post_info['duration'] : '00:00:00';
        $duration_arr = explode(":", $duration);
        $duration = intval($duration_arr[0]) * 3600 + intval($duration_arr[1]) * 60 + intval($duration_arr[2]);
        $image = isset($post_info['cover']) ? $post_info['cover'] : '';
        $brief = isset($post_info['brief']) ? $post_info['brief'] : '';
        $site = isset($post_info['site']) ? $post_info['site'] : '';
        $tags = isset($post_info['tags']) ? $post_info['tags'] : '';
        $publish_tm = isset($post_info['publish_tm']) ? $post_info['publish_tm'] : '';
        $play_url = isset($post_info['play_url']) ? $post_info['play_url'] : '';
        $play_code = isset($post_info['play_code']) ? $post_info['play_code'] : '';

        $video_data = array(
            'title' => $title,
            'duration' => $duration,
            'isvr' => $is_vr,
            'image' => $image,
            'brief' => $brief,
            'site' => $site,
            'publish_tm' => $publish_tm,
            'play_url' => $play_url,
            'play_code' => $play_code
        );

        // 9月25日，杜、邱渝确认，比赛id需要修改video表格的match_id字段
        if (!empty($match_id)) {
            $video_data['match_id'] = $match_id;
        } else {
            $video_data['match_id'] = null;
        }

        $this->VM->updateVideo($video_id, $video_data);

        $tags_arr = explode(',', $tags);
        if (!empty($tags_arr)) {
            $this->VM->setVideoTag($video_id, $tags_arr);
        }

        // 添加关联比赛信息
        // 9月25日逻辑，如果标签id包含“集锦”、“录像”，则不能出现在关联比赛里
        if (array_intersect(array(1000005, 1000004), $tags_arr)) {
            $this->VM->setMatchRelate($video_id, 0);
        } else {
            $this->VM->setMatchRelate($video_id, $match_id);
        }

        $this->LM->uadd('video_edit', json_encode($this->userInfo['current_menu']), $video_data);

        return true;
    }

    public function related($id) {
        $video_info = $this->VM->getVideoInfo($id);
        // 获取关联视频
        $list = $this->VM->getRelated($id);

        // 获取视频的标签
        $this->load->model('tag_model', 'TagModel');
        foreach ($list as & $video) {
            $video_tag_ids = $this->VM->getVideoTags($video['id']);
            if (!empty($video_tag_ids)) {
                $video_tag = $this->TagModel->db('sports')->getTagsByFakeId(join(',', $video_tag_ids));
                $tag_str = '';
                foreach ($video_tag as $item) {
                    $tag_str .= $item['name'] . ',';
                }
            }
            $video['tag_str'] = isset($tag_str) ? trim($tag_str, ',') : '';
            unset($tag_str);
        }

        $data['video_id'] = $id;
        $data['video_info'] = $video_info;
        $data['list'] = $list;
        $this->load->view('video/related', $data);
    }

    public function setRelated($video_id, $type, $related_id) {
        $numargs = func_num_args();
        if ($numargs < 3) show_404();
        $related = array(
            'video_id' => intval($video_id),
            'type' => $type,
            'ref_id' => $related_id,
            'priority' => 0
        );
        $r = $this->VM->db('sports')->insert('video_related', $related);
        if ($r) redirect(base_url('/video/related/' . $video_id));
    }

    public function cancelRelated($rid) {
        try {
            $this->VM->db('sports')->remove('video_related', $rid);
            echo 'success';
        } catch (Exception $e) {
            echo 'fail';
        }
    }

    public function setRelatedSort() {
        $data = $_POST;
        $video_id = $data['video_id'];
        $related_id = $data['pk'];
        $sort = intval($data['value']);
        $result = $this->VM->db('sports')->setTbSort('video_related', $related_id, array('priority' => $sort), array('video_id' => $video_id));
        echo $result ? 'success' : 'fail';
    }

    protected function addVideo($pending_video_id, $post_info) {
        $title = $post_info['title'];
        $match_id = isset($post_info['match_id']) ? $post_info['match_id'] : 0;
        $is_vr = isset($post_info['is_vr']) ? 1 : 0;
        // 处理时长
        $duration = isset($post_info['duration']) ? $post_info['duration'] : '00:00:00';
        $duration_arr = explode(":", $duration);
        $duration = intval($duration_arr[0]) * 3600 + intval($duration_arr[1]) * 60 + intval($duration_arr[2]);
        $image = isset($post_info['cover']) ? $post_info['cover'] : '';
        $brief = isset($post_info['brief']) ? $post_info['brief'] : '';
        $site = isset($post_info['site']) ? $post_info['site'] : '';
        $tags = isset($post_info['tags']) ? $post_info['tags'] : '';
        $publish_tm = isset($post_info['publish_tm']) ? $post_info['publish_tm'] : '';
        $play_url = isset($post_info['play_url']) ? $post_info['play_url'] : '';
        $play_code = isset($post_info['play_code']) ? $post_info['play_code'] : '';

        $video_data = array(
            'title' => $title,
            'duration' => $duration,
            'isvr' => $is_vr,
            'image' => $image,
            'brief' => $brief,
            'site' => $site,
            'publish_tm' => $publish_tm,
            'play_url' => $play_url,
            'play_code' => $play_code
        );

        $video_id = $this->VM->addVideo($video_data);

        // 添加关联比赛信息
        $this->VM->setMatchRelate($video_id, $match_id);

        $tags_arr = explode(',', $tags);
        if (!empty($tags_arr)) {
            $this->VM->setVideoTag($video_id, $tags_arr);
        }

        // 删除pending_video里面的视频
        // $this->VM->deletePendingVideo($pending_video_id);

        $this->LM->uadd('pending_video_edit', json_encode($this->userInfo['current_menu']), $video_data);

        return true;
    }

    public function bindVid() {
        $video_id = $_POST['id'];
        $box_cid = $_POST['cid'];

        $box_vid = $this->VM->getVidRemote($box_cid);
        if (!empty($box_vid)) {
            $this->VM->updateVideoExtra(array('box_vid' => $box_vid), array('video_id' => $video_id));
            echo 'success';
        } else {
            echo 'fail';
        }
    }

    public function online() {
        $cid = $this->input->get('cid');
        $vid = $this->input->get('vid');
        $result = 0;
        if ($cid && $vid) {
            //更新video表vid字段
            $result = $this->VM->updateVideoWithCondition(array('box_vid' => $vid), array('box_cid' => $cid));
        }
        echo $result;
    }

    /**
     * 验证视频ID是否正确 API
     */
    public function checkVideoId() {
        $id = $this->input->post('id');

        if (is_numeric($id)) {
            $this->load->model('video_model', 'VM');
            $videoInfo = $this->VM->getVideoInfo($id);
            if ($videoInfo) {
                if ($videoInfo['site'] != 'bfonline') {
                    $result = array('status' => 'no', 'data' => '非暴风源视频');
                } else {
                    $result = array('status' => 'yes', 'data' => $videoInfo['title']);
                }
            } else {

                $result = array('status' => 'no', 'data' => '视频不存在');
            }
        } else {
            $result = array('status' => 'no', 'data' => '请输入有效ID');
        }
        echo json_encode($result);
    }
}
