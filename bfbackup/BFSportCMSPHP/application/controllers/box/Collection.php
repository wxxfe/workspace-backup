<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Collection extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('box/box_collection_model', 'BOX_CM');
        $this->limit = 20;
    }

    public function index() {
        return $this->getList();
    }

    public function getList($offset = 0) {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $condition = array();
        if (!empty($keyword) && !is_numeric($keyword)) {
            $result = $this->BOX_CM->searchList($keyword, $this->limit, $offset);
            $list = $result['result'];
            $total = $result['total'];
        } else {
            if (is_numeric($keyword)) {
                $condition['id'] = $keyword;
            }
            $list = $this->BOX_CM->getList($condition, $this->limit, $offset);
            $total = $this->BOX_CM->getTotal('box_collection');
        }
        $data = array();
        $data['page'] = $this->_pagination($total, $this->limit, '/box/collection/getlist');
        $data['list'] = $list;
        $this->load->view('box/collection/list', $data);
    }

    public function addStep1() {
        $data = array();
        if (!empty($_POST)) {
            $collection_id = $this->addCollection($_POST);
            redirect(site_url('box/collection/edit/') . $collection_id);
        }
        $this->load->view('box/collection/add_step1', $data);
    }

    public function addVideo($collection_id, $video_id) {
        $response = array('status'=>0,'errmsg'=>'加入成功');

        // check box_vid start
        $query = $this->BOX_CM->dbSports->select('*')->from('video_extra')->where('video_id', $video_id)->get();
        $video_extra = $query ? $query->row_array() : array();
        $box_vid = isset($video_extra['box_vid']) ? intval($video_extra['box_vid']) : '';
        $box_cid = isset($video_extra['box_cid']) ? $video_extra['box_cid'] : '';
        if(!$box_vid) {
            $this->load->model('video_model', 'VM');
            $box_vid = $this->VM->getVidRemote($box_cid);
            if ($box_vid) {
                $result = $this->VM->updateVideoExtra(array('box_vid' => $box_vid), array('box_cid' => $box_cid));
            } else {
                $response['status'] = 1;
                $response['errmsg'] = '添加失败，请稍后再试';
                echo json_encode($response);
                exit;
            }
        }
        // check box_vid end

        $result = $tid = $this->BOX_CM->addVideo($collection_id, $video_id);
        $sort = $this->BOX_CM->getMaxPriority($collection_id);
        if ($sort > 0) {
            $this->BOX_CM->db('sports')->setTbSort('box_collection_content', $tid, array('sort' => $sort + 1), array('collection_id' => $collection_id));
        }

        // 同步数据
        $collection_info = $this->BOX_CM->getInfo($collection_id);
        $collection_videos = $this->BOX_CM->getVideosJoinExtra($collection_id);
        @file_put_contents('/tmp/1.txt', "action:addVideo\n", FILE_APPEND);
        if ($collection_videos && !$collection_info['box_aid']) {
            $this->_rsync($act = 'create', $collection_id);
        } else {
            $this->_rsync($act = 'update', $collection_id);
        }

        echo json_encode($response);
    }

    public function removeVideo($collection_id, $video_id) {
        //同步数据
        @file_put_contents('/tmp/1.txt', "action:removeVideo\n", FILE_APPEND);
        $this->_rsync($act = 'del', $collection_id, array(), $video_id);
        //删除表中数据
        $result = $this->BOX_CM->removeVideo($collection_id, $video_id);
        echo 'success';
        return;
    }

    public function videoSearch($collection_id, $type = 'video', $offset = 0) {
        // add|edit
        $type = isset($_GET['type']) ? $_GET['type'] : 'edit';

        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $this->load->model('video_model', 'VM');
        $condition = $like_condition = array();
        if (!empty($keyword) && !is_numeric($keyword)) {
            $like_condition['video.title'] = $keyword;
        } else {
            if (is_numeric($keyword)) {
                $condition['video.id'] = $keyword;
            }
        }
        $condition['video_extra.box_cid !='] = '';
        $list = $this->VM->getListJoinExtra($condition, $like_condition, $this->limit, $offset);
        $total = $this->VM->getTotalJoinExtra($condition, $like_condition);

        // 合集视频列表
        $collection_videos = $this->BOX_CM->getVideos($collection_id);
        $collection_videos_ids = $collection_videos ? array_column($collection_videos, 'id') : array();

        $data = array();
        $data['page'] = $this->_pagination($total, $this->limit, "/box/collection/videoSearch/{$collection_id}/video");
        $data['list'] = $list;
        $data['keyword'] = $keyword;
        $data['type'] = $type;
        $data['collection_id'] = $collection_id;
        $data['collection_videos_ids'] = $collection_videos_ids;
        $this->load->view('box/collection/search_video', $data);
    }

    public function edit($id) {
        $collection_info = $this->BOX_CM->getInfo($id);

        if (!empty($_POST)) {
            $this->editCollection($id, $_POST);
            //同步数据
            $collection_info['title'] = isset($_POST['title']) ? $_POST['title'] : '';
            $collection_info['image'] = isset($_POST['cover_image']) ? $_POST['cover_image'] : '';
            $collection_info['brief'] = isset($_POST['brief']) ? $_POST['brief'] : '';

            @file_put_contents('/tmp/1.txt', "action:edit\n", FILE_APPEND);
            if ($collection_info['box_aid']) {
                $this->_rsync($act = 'update', $id, $collection_info);
            } else {
                // 合集视频列表
                $collection_videos = $this->BOX_CM->getVideosJoinExtra($id);
                if ($collection_videos) {
                    $this->_rsync($act = 'create', $id, $collection_info);
                }
            }
            $redirect = $this->input->get('redirect') ? $this->input->get('redirect') : base_url('box/collection/getlist');
            redirect($redirect);
        }

        $data = array();
        // 合集视频列表
        $collection_videos = $this->BOX_CM->getVideosJoinExtra($id);
        $data['info'] = $collection_info;
        $data['videos'] = $collection_videos;
        $data['redirect'] = $this->input->get('redirect') ? $this->input->get('redirect') : site_url('box/collection/getlist');
        $this->load->view('box/collection/edit', $data);
    }

    public function remove() {
        $id = intval($_POST['id']);
        if (strpos($id, ',') . '' != '') {
            $ids = explode(',', $id);
            try {
                $this->BOX_CM->db('sports')->remove_batch('box_collection', $ids);
                echo 'success';
            } catch (Exception $e) {
                echo 'fail';
            }
        } else {
            try {
                $this->BOX_CM->db('sports')->remove('box_collection', $id);
                echo 'success';
            } catch (Exception $e) {
                echo 'fail';
            }
        }
    }

    public function update() {
        $collection_id = intval($_POST['pk']);
        $info = array(
            $_POST['name'] => $_POST['value']
        );
        $this->BOX_CM->updateInfo($collection_id, $info);

        return true;
    }

    public function updateSort() {
        $data = $_POST;
        $collection_id = intval($data['collection_id']);
        $tid = intval($data['pk']);
        $sort = intval($data['value']);
        $result = $this->BOX_CM->db('sports')->setTbSort('box_collection_content', $tid, array('sort' => $sort), array('collection_id' => $collection_id));
        @file_put_contents('/tmp/1.txt', "action:updateSort\n", FILE_APPEND);
        if ($result) {
            $this->_rsync($act = 'update', $collection_id);
        }
        echo $result ? 'success' : 'fail';
    }

    protected function addCollection($post_info) {
        $title = $post_info['title'];
        $image = isset($post_info['cover_image']) ? $post_info['cover_image'] : '';
        $large_image = isset($post_info['large_image']) ? $post_info['large_image'] : '';
        $brief = isset($post_info['brief']) ? $post_info['brief'] : '';
        $visible = isset($post_info['visible']) ? 1 : 0;

        $data = array(
            'title' => $title,
            'brief' => $brief,
            'image' => $image,
            'large_image' => $large_image,
            'visible' => $visible
        );

        $collection_id = $this->BOX_CM->addInfo($data);

        return $collection_id;
    }

    protected function editCollection($id, $post_info) {
        $title = $post_info['title'];
        $image = isset($post_info['cover_image']) ? $post_info['cover_image'] : '';
        $large_image = isset($post_info['large_image']) ? $post_info['large_image'] : '';
        $brief = isset($post_info['brief']) ? $post_info['brief'] : '';
        $visible = isset($post_info['visible']) ? 1 : 0;

        $data = array(
            'title' => $title,
            'brief' => $brief,
            'image' => $image,
            'large_image' => $large_image,
            'visible' => $visible,
        );

        $this->BOX_CM->updateInfo($id, $data);

        return true;
    }

    /**
     * 合集列表页同步功能(ajax同步)
     */
    public function rsync() {
        $act = $this->input->post("act");
        $collection_id = (int)$this->input->post("id");

        //同步数据
        @file_put_contents('/tmp/1.txt', "action:rsync\n", FILE_APPEND);
        $result = $this->_rsync($act, $collection_id);
        if ($result) {
            $info = $this->BOX_CM->getInfo($collection_id);
        }
        $response = array('errcode' => $result, 'publish_tm' => date("Y-m-d H:i:s"), 'aid' => isset($info['box_aid']) ? $info['box_aid'] : '');
        echo json_encode($response);
    }

    /**
     * @param $act update|create|del
     * @param $collection_id int
     * @param $collection array
     * @param $del_vid 要删除的vid
     * @return int
     */
    protected function _rsync($act, $collection_id, $collection = array(), $del_vid = '') {
        $result = 0;
        //------格式化数据-----
        $collection = $collection ? $collection : $this->BOX_CM->getInfo($collection_id);
        if (!$collection) return $result;
        $data = array('act' => $act);
        if ($act != 'del') {//update|create
            $data['title'] = $collection['title'];
            $data['image'] = $collection['image_url'];
            $data['desc'] = $collection['brief'];
        }
        if ($act == 'update' || $act == 'del') {
            if (!$collection['box_aid']) return $result;
            $data['aid'] = $collection['box_aid'];
        }
        //video顺序
        $videos = $this->BOX_CM->getVideosJoinExtra($collection_id);

        $arr = array();
        if ($videos) {
            foreach ($videos as $key => $val) {
                if ($act == 'del') {
                    if ($del_vid == $val['id']) {
                        $arr[] = array("vid" => strval($val['box_vid']), "sort" => strval($key + 1));
                        break;
                    }
                } else {
                    $arr[] = array("vid" => strval($val['box_vid']), "sort" => strval($key + 1));
                }
            }
        }

        if (!empty($arr)) {
            $data['vids'] = json_encode($arr);
        }
        //------格式化数据-----end

        file_put_contents('/tmp/1.txt', "------" . date('Y-m-d H:i:s') . "\ncollection_id:$collection_id\n" . var_export($data, true) . "\n", FILE_APPEND);
        $url = ONLINE_SYNC_URL_HOST . '/PlayResource/video/sport-album';
        $response = send_post($url, $data);
        $response_arr = json_decode($response, true);
        $result = isset($response_arr['status']) && $response_arr['status'] == 0 ? 1 : 0;
        file_put_contents('/tmp/1.txt', var_export($response_arr, true) . "\n", FILE_APPEND);
        if ($result) {
            //更新发布状态和发布时间
            $update_data = array(
                'status_sync' => $result == 1 ? 1 : 2,//2失败
                'publish_tm' => date('Y-m-d H:i:s')
            );
            //创建时更新box_aid
            if (isset($data['act']) && $data['act'] == 'create') {
                if (!isset($response_arr['aid'])) return 0;
                $update_data['box_aid'] = isset($response_arr['aid']) ? intval($response_arr['aid']) : 0;
            }
            $this->BOX_CM->updateInfo($collection_id, $update_data);
        }
        return $result;
    }

    //video_extra表box_vid更新
    public function syncVideo() {
        $box_cid = $this->input->post("box_cid");
        $res = array('errcode' => 0);
        if ($box_cid) {
            $this->load->model('video_model', 'VM');
            $vid = $this->VM->getVidRemote($box_cid);
            if ($vid) {
                $result = $this->VM->updateVideoExtra(array('box_vid' => $vid), array('box_cid' => $box_cid));
                $res['errcode'] = $result;
                $res['box_vid'] = $vid;
            }
        }
        echo json_encode($res);
    }

    /**
     * 验证合集ID是否正确 API
     */
    public function checkCollectionId() {
        $id = $this->input->post('id');

        if (is_numeric($id)) {
            $info = $this->BOX_CM->getInfo($id);
            if ($info) {
                $result = array('status' => 'yes', 'data' => $info['title']);
            } else {
                $result = array('status' => 'no', 'data' => '合集不存在');
            }
        } else {
            $result = array('status' => 'no', 'data' => '请输入有效ID');
        }
        echo json_encode($result);
    }
}