<?php

/**
 * Class Api_model
 * 接口模型类
 */
class Api_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('UrlRequest');
        $this->config->load('sports_api');
    }

    public function url($name, $args = array()) {
        array_unshift($args, $this->config->item("api_{$name}"));
        return call_user_func_array('sprintf', $args);
    }

    public function request($url) {
        return $this->urlrequest->request($url);
    }

    public function requestapi($name, $args = array()) {
        return $this->request($this->url($name, $args));
    }
    /**
     * 获取赛后热门帖子
     * @param $matchid
     */
    public function getMatchPost($matchid) {
        $post = array();
        if (!$matchid) return $post;
        $response = $this->requestapi('match_post', array($matchid));
        $result = @json_decode($response, true);
        if (isset($result['errno']) && isset($result['errno']) == 10000 && isset($result['data']['body'])) {
            foreach ($result['data']['body'] as $row) {
                $post[] = $row;
            }
        }
        return $post;
    }
    /**
     * 获取比赛热门话题
     * @param $matchid
     */
    public function getMatchThread($matchid, $detail = false) {
        $threads = array();
        if (!$matchid) return $threads;
        $response = $this->requestapi('match_thread', array($matchid));
        $result = @json_decode($response, true);
        if (isset($result['errno']) && isset($result['errno']) == 10000 && isset($result['data']['body'])) {
            foreach ($result['data']['body'] as $row) {
                $threads[] = $row;
            }
        }
        if ($detail) {
            $threads = $this->getMatchThreadDetail($threads);
        }
        return $threads;
    }

    /**
     * 获得话题的详细数据
     * @param $threads
     * @return array
     */
    public function getMatchThreadDetail($threads) {
        $data = array();
        foreach ($threads as $val) {
            $resp = @json_decode($this->requestapi('topic_detail', array($val['id'])), true);
            $val['content'] = isset($resp['data']['body']) ? $resp['data']['body'] : array();
            $data[] = $val;
        }
        return $data;
    }

    /**
     * 获取比赛聊天记录
     * @param $matchid
     */
    public function getChatHistory($matchid) {
        $history = array();
        if (!$matchid) return $history;
        $response = $this->requestapi('history', array($matchid,'')); //为空则是所有人，可选值：100 (普通互动消息)、104（主持人消息）
        $result = @json_decode($response, true);
        if (isset($result['data']['history']) && is_array($result['data']['history'])) {
            foreach ($result['data']['history'] as $row) {
                $history[] = $row;
            }
        }
        return $history;
    }

    /**
     * 互动人气（球队人气值）
     * @param $matchid
     */
    public function getSupport($matchid) {
        $supports = array();
        if (!$matchid) return $supports;
        $response = $this->requestapi('support', array($matchid));
        $result = @json_decode($response, true);
        if (isset($result['data']['supports']) && is_array($result['data']['supports'])) {
            foreach ($result['data']['supports'] as $row) {
                $supports[] = $row;
            }
        }
        return $supports;
    }

    /**
     * 互动达人
     * @param $matchid
     */
    public function getFinger($matchid) {
        $topfinger = array();
        if (!$matchid) return $topfinger;
        $response = $this->requestapi('finger', array($matchid));
        $result = @json_decode($response, true);
        if (isset($result['data']['topfinger']) && is_array($result['data']['topfinger'])) {
            foreach ($result['data']['topfinger'] as $row) {
                $topfinger[] = $row;
            }
        }
        return $topfinger;
    }

    /**
     * 战报
     * @param $matchid
     */
    public function getReport($matchid) {
        $report = array();
        if (!$matchid) return $report;
        $response = $this->requestapi('report', array($matchid));
        $result = @json_decode($response, true);
        if (isset($result['data']['report']) && is_array($result['data']['report'])) {
            foreach ($result['data']['report'] as $row) {
                $report[] = $row;
            }
        }
        return $report;
    }

    /**
     * 比赛关联视频
     * @param $matchid
     */
    public function getVideos($matchid) {
        $videos = array();
        if (!$matchid) return $videos;
        $response = $this->requestapi('match_video', array($matchid));
        $result = @json_decode($response, true);
        if (isset($result['data']['video']) && is_array($result['data']['video'])) {
            foreach ($result['data']['video'] as $row) {
                $videos[] = $row;
            }
        }
        return $videos;
    }

    /**
     * 比赛相关资讯
     * @param $matchid
     */
    public function getRelated($matchid) {
        $related = array();
        if (!$matchid) return $related;
        $response = $this->requestapi('match_related', array($matchid));
        $result = @json_decode($response, true);
        if (isset($result['data']['related']) && is_array($result['data']['related'])) {
            foreach ($result['data']['related'] as $row) {
                $related[] = $row;
            }
        }
        return $related;
    }

}

?>