<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Match extends MY_Controller {


    public function __construct() {
        parent::__construct();
        $this->load->model('Api_model', 'AM');
        $this->load->model('Match_model', 'MM');
        $this->load->model('Video_model', 'VM');
    }

    public function detail($id, $type = 'share') {

        //获取比赛信息
        $match = $this->MM->getMatchById($id);

        $status = 'other';

        if ($match) {
            //判断比赛状态,预约 进行中，回顾 ... 'notstarted','ongoing','finished','postponed','cancelled'
            $status = $match['status'];
            //如果状态是延期，则显示预约模板
            if ($status == 'postponed') {
                $status = 'notstarted';
            }
            //如果状态是未开始，则进一步根据直播流时间判断，是显示预约模板，还是进行中模板
            if ($status == 'notstarted') {
                //是否处于直播状态，优先看直播流时间，如果当前时间已经超过直播流时间，进入直播状态
                //数组,同一个比赛可以有多个直播流，默认第一个
                $match_live_stream = $match['live_stream'];
                if ($match_live_stream) {
                    $stream_time = strtotime($match_live_stream[0]['stream_tm']);
                    if ($stream_time && time() > $stream_time) {
                        $status = 'ongoing';
                    }
                }
            }
        }

        //渲染对应模板
        if ($match && in_array($status, array('notstarted', 'ongoing', 'finished'))) {
            $this->$status($this->getTemplateBaseData($match, $type, $status));
        } else {
            $this->other($this->getTemplateBaseData($match, $type, 'nodata'));
        }

    }

    /**
     * 获得通用模板基础数据
     * @param $match
     * @param $type
     * @param $template_name
     * @return array
     */
    private function getTemplateBaseData($match, $page_type, $template_name) {
        $data = array();
        $data['match'] = $match;
        $data['template_name'] = $template_name;
        $data['page_type'] = $page_type;
        $data['header_footer_data'] = getHeaderFooterData('match-' . $template_name, $match['show_data']['txt_1'], $page_type);
        $data['download_data'] = array(
                'info' => getShareOrAppJson('match', $match)
        );
        return $data;
    }

    /**
     * 获得互动人气百分比
     * @param $supports
     * @param $match
     * @return array|null
     */
    private function getSupportsPercentage($supports, $match) {
        if ($match['type'] != 'various') {
            $p1 = 50;
            $p2 = 50;
            $vs_1_id = $match['show_data']['vs_1']['id'];
            $vs_2_id = $match['show_data']['vs_2']['id'];
            $suport1 = 0;
            $suport2 = 0;
            if (isset($supports[$vs_1_id]) && $supports[$vs_1_id]) {
                $suport1 = int($supports[$vs_1_id]);
            }
            if (isset($supports[$vs_2_id]) && $supports[$vs_2_id]) {
                $suport2 = int($supports[$vs_2_id]);
            }
            if ($suport1 || $suport2) {
                $p1 = int(($suport1 / ($suport1 + $suport2)) * 100);
                $p2 = 100 - $p1;
            }
            return array($p1, $p2);
        } else {
            return null;
        }
    }

    //预约
    private function notstarted($data) {

        $match_id = $data['match']['id'];

        //热门话题
        $data['threads'] = $this->AM->getMatchThread($match_id);

        //比赛相关资讯
        $data['related'] = $this->AM->getRelated($match_id);


        $this->load->view('match/' . $data['template_name'], $data);
    }

    //直播中
    private function ongoing($data) {
        $match_id = $data['match']['id'];

        //互动人气
        $data['supports'] = $this->AM->getSupport($match_id);

        //人气百分值
        $data['supports_p'] = $this->getSupportsPercentage($data['supports'], $data['match']);

        //聊天记录
        $data['chathistory'] = $this->AM->getChatHistory($match_id);

        $this->load->view('match/' . $data['template_name'], $data);
    }

    //回顾
    private function finished($data) {
        $match_id = $data['match']['id'];

        //相关视频
        $data['videos'] = $this->AM->getVideos($match_id);
        $vids = array();
        foreach ($data['videos'] as $val) {
            $vids[] = $val['id'];
        }
        $videoExtra = $vids ? $this->VM->getVideosExtra($vids) : array();
        $data['videoextra'] = array();
        foreach ($videoExtra as $v) {
            $data['videoextra'][$v['video_id']] = $v;
        }

        //互动达人
        $data['finger'] = $this->AM->getFinger($match_id);
        if ($data['finger'] && count($data['finger']) > 3) {
            $data['finger'] = array_slice($data['finger'], 0, 3);
        }

        //热门帖子
        $data['posts'] = $this->AM->getMatchPost($match_id);

        //战报
        $data['report'] = $this->AM->getReport($match_id);

        //比赛相关资讯
        $data['related'] = $this->AM->getRelated($match_id);


        $this->load->view('match/' . $data['template_name'], $data);
    }

    //其他状态
    private function other($data) {
        $nodata_txt = '';
        if ($data['match'] && $data['match']['status'] == 'cancelled') {
            $nodata_txt = $data['match']['show_data']['txt_1'] . '的赛事已经取消';
        }
        $data['nodata_txt'] = $nodata_txt;
        $this->load->view('errors/' . $data['template_name'], $data);
    }

}
