<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PendingVideo extends MY_Controller {
    private $limit = 10;
    public function __construct() {
        parent::__construct();
        $this->load->model('pending_video_model','PVM');
    }

    /**
     * @param string $type spider蜘蛛 editor自上传
     * @param int $offset
     */
    public function index($type='editor',$offset=0){
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $status = isset($_GET['status']) ? $_GET['status'] : 'all';
        //关键字、id搜索
        if($keyword){
            if(is_numeric($keyword)){
                $where['id'] = intval($keyword);
            }else{
                $where['title like'] = '%'.$keyword."%";
            }
        }
        if($type == 'box'){//////同步列表
            //状态筛选
            if($status != 'all'){
                $where['box_status'] = intval($status);
            }else{
                $where['box_status'] = -1;//同步至在线的数据
            }
        }else{
            if($type == 'spider'){/////////蜘蛛
                $where['author'] = 'spider';
            }else{/////////编辑
                $where['author !='] = 'spider';
            }
            if($status != 'all'){
                $where['cloud_status'] = intval($status);
            }else{
                $where['cloud_status'] = -1;
            }
        }

        $list = $this->PVM->search($where, $this->limit, $offset);
        $total = $this->PVM->getTotalCount($where);
        $data['page'] = $this->_pagination($total, $this->limit, '/PendingVideo/index/'.$type);

        $data['list'] = $list;
        $data['type'] = $type;
        $data['keyword'] = $keyword;
        $data['status'] = $status;
        $data['offset'] = $offset;
        $this->load->view('/pending_video/list', $data);
    }

    /**
     * 编辑视频信息
     * @param $id
     */
    public function edit($id){
        $video = $this->PVM->getInfo($id);

        $video_tags = isset($video['video_tags']) ? $video['video_tags'] : '';
        if (!empty($_POST)) {
            $this->doEdit($id, $_POST);
            $redirect = $this->input->get('redirect');
            if (!empty($redirect)) {
                redirect($redirect);
            } else {
                redirect(site_url('pending_video/index'));
            }
        }
        $this->load->model('video_model','VM');
        $site_list = $this->VM->getSites();

        if (isset($video['site']) && !$video['site']) {
           $video['site'] = 'bfonline';//编辑时默认暴风
        }
        $data['video'] = $video;
        $data['site_list'] = $site_list;
        $data['video_tags'] = $video_tags;
        $data['match_id'] = $video['match_id'];
        $this->load->view('pending_video/edit', $data);
    }

    // 修改视频内容
    public function doEdit($id, $post_info) {
        $title = $post_info['title'];
        $match_id = isset($post_info['match_id']) ? $post_info['match_id'] : 0;
        $is_vr = isset($post_info['is_vr']) ? 1 : 0;
        // 处理时长
        $duration = isset($post_info['duration']) ? $post_info['duration'] : '00:00:00';
        $duration_arr = explode(":", $duration);
        $duration = intval($duration_arr[0])*3600 + intval($duration_arr[1])*60 + intval($duration_arr[2]);
        $image = isset($post_info['cover']) ? $post_info['cover'] : '';
        $brief = isset($post_info['brief']) ? $post_info['brief'] : '';
        $site = isset($post_info['site']) ? $post_info['site'] : '';
        $tags = isset($post_info['tags']) ? $post_info['tags'] : '';
        $publish_tm = isset($post_info['publish_tm']) ? $post_info['publish_tm'] : '';
        $play_url = isset($post_info['play_url']) ? $post_info['play_url'] : '';
        $play_code = isset($post_info['play_code']) ? $post_info['play_code'] : '';

        $video_data = array(
            'match_id'=>!empty($match_id) ? $match_id : null,
            'title' => $title,
            'duration' => $duration,
            'isvr' => $is_vr,
            'image' => $image,
            'brief' => $brief,
            'site' => $site,
            'publish_tm' => $publish_tm,
            'play_url' => $play_url,
            'play_code' => $play_code,
            'video_tags'=>$tags,
        );

        $this->PVM->update('pending_video',$video_data,array('id'=>$id));
        return true;
    }

    /**
     * 更新上传状态、同步状态
     * @param $id
     * @return bool
     */
    public function updateStatus(){
        $id = $this->input->post('id');
        $type = $this->input->post('upload_type');//cloud,box,cloud_box
        if(!$id || !$type){
            echo 'failed';die;
        }
        $result = $this->PVM->updateStatus($id,$type);
        echo $result ? 'success' : '';die;
    }

    /**
     * 批量编辑
     */
    public function batchedit(){
        $ids = $this->input->get('ids');
        $redirect = $this->input->get('redirect');

        if($_POST){
            $match_id = isset($_POST['match_id']) && $_POST['match_id'] ? $_POST['match_id'] : null;
            $is_vr = isset($_POST['is_vr']) && $_POST['is_vr'] =='on' ? 1 : 0;
            $video_tags = isset($_POST['tags']) ? $_POST['tags'] : '';
            $update_data = array('match_id'=>$match_id,'isvr'=>$is_vr,'video_tags'=>$video_tags);

            //更新
            $ids = explode(',', $ids);
            $result = $this->PVM->updatebatch($ids, $update_data);
            if($result){
                redirect($redirect);
            }else
                die;
        }

        $data['redirect'] = $redirect;
        $this->load->view('pending_video/batch_edit', $data);
    }
}
