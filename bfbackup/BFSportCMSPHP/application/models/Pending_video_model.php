<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pending_video_model extends MY_Model
{
    //同步状态：0~9体育cms状态 10~19上传状态  20~29审核状态  30~39转码状态 40~49发布状态
    public $all_status = array(
        0  =>'待上传',//cms未操作过
        1  =>'待处理',//cms已操作&&后台进程未处理
        2  =>'开始上传',
        3  =>'初审失败',//例如：云视频用户空间不足，用户余额不足等
        10 =>'初审中',//上传中
        11 =>'上传成功',
        12 =>'上传失败',
        21 =>'审核通过',
        22 =>'审核未通过',//审核未通过
        30 =>'初审通过(转码中)',//转码中
        31 =>'转码完成(待审核)',//转码成功
        32 =>'转码失败',
        40 =>'发布中',
        41 =>'发布成功',//文件已成功发布完成
        42 =>'发布未成功',//CDN发布失败
    );
    //云视频相关列表展示内容
    public $cloud_status_show = array(0,1,2,11,12,10,30,3,31,32,21,22,40,41,42);
    //媒资相关列表展示内容
    public $box_status_show = array(1,2,11,30,3,31,32,21,22,41,42);

    public function __construct()
    {
        parent::__construct();
        $this->cloud_status_show = array_flip(array_intersect(array_flip($this->all_status),$this->cloud_status_show));
        $this->box_status_show = array_flip(array_intersect(array_flip($this->all_status),$this->box_status_show));
    }

    /**
     * 列表
     * @param array $where
     * @param $limit
     * @param $offset
     */
    public function search($where=array(), $limit, $offset){
        $query = $this->dbsports->select('*')->from('pending_video');
        if($where) {
            if(isset($where['cloud_status']) && $where['cloud_status'] == -1) {
                $query->where_in('cloud_status',array_keys($this->cloud_status_show));
                unset($where['cloud_status']);
            }
            if(isset($where['box_status']) && $where['box_status'] == -1) {
                $query->where_in('box_status',array_keys($this->box_status_show));
                unset($where['box_status']);
            }
            if(!empty($where)) {
                $query->where($where);
            }
        }
        $result = $query->order_by('id','DESC')
            ->limit($limit,$offset)
            ->get();
        $data = array();
        if(!$result)return $data;
        foreach ($result->result_array() as $row) {
            $data[] = $row;
        }
        return $data;
    }

    /**
     * 计算总数
     * @param $where
     * @return mixed
     */
    public function getTotalCount($where){
        $query = $this->dbsports->from('pending_video');
        if($where) {
            if(isset($where['cloud_status']) && $where['cloud_status'] == -1) {
                $query->where_in('cloud_status',array_keys($this->cloud_status_show));
                unset($where['cloud_status']);
            }
            if(isset($where['box_status']) && $where['box_status'] == -1) {
                $query->where_in('box_status',array_keys($this->box_status_show));
                unset($where['box_status']);
            }
            if(!empty($where)) {
                $query->where($where);
            }
        }
        return $query->count_all_results();
    }

    /**
     * 获取视频信息
     * @param $id
     * @return array
     */
    public function getInfo($id){
        $query = $this->dbsports->select('*')->from('pending_video')->where('id',$id)->get();
        $data = $query ? $query->row_array() : array();
        if($data){
            $data['image_url'] = getImageUrl($data['image']);
            $data['duration'] = gmstrftime('%H:%M:%S', intval($data['duration']));
        }
        return $data;
    }

    /**
     * 更新上传状态：cloud_status,box_status
     * @param $id string 多个id用英文逗号隔开
     * @param $type
     */
    public function updateStatus($id, $type){
        if(!$id || !$type)return false;

        if($type == 'cloud'){//云视频
            $update_data['cloud_status'] = '1';//上传中
        }elseif($type == 'box'){//在线媒资
            $update_data['box_status'] = '1';//上传中
        }else{//同时上传到云视频和媒资
            $update_data['cloud_status'] = '1';
            $update_data['box_status'] = '1';
        }

        //检查需要上传的文件
        $need_update_status = array(0,3,12);//未上传|上传失败
        $ids = explode(',', $id);
        $query = $this->PVM->dbsports->select('*')->from('pending_video')->where_in('id',$ids)->get();
        foreach($query->result_array() as $row){
            $cloud_status = $row['cloud_status'];
            $box_status = $row['box_status'];

            $update_feilds = array();
            //更新上传云视频状态
            if(isset($update_data['cloud_status']) && in_array($cloud_status,$need_update_status)){
                $update_feilds['cloud_status'] = $update_data['cloud_status'];
            }
            //更新同步至在线的状态
            if(isset($update_data['box_status']) && in_array($box_status,$need_update_status)){
                $update_feilds['box_status'] = $update_data['box_status'];
            }
            if(!empty($update_feilds))
                $this->PVM->dbsports->where('id',$row['id'])->update('pending_video',$update_feilds);
        }
        return true;
    }

    public function updatebatch($ids=array(),$update_data=array()){
        if(empty($ids) || empty($update_data))return false;
        $result = $this->PVM->dbsports->where_in('id',$ids)->update('pending_video',$update_data);
        return $result;
    }
}