<?php
$config = array();
// 节目
$config['api_program']          = 'http://api.sports.baofeng.com/api/v3/android/program/content/list?id=%d';
// 合集
$config['api_collection']       = 'http://api.sports.baofeng.com/api/v3/android/collection/content/list?id=%d';
//比赛热门话题
$config['api_match_thread']     = 'http://api.board.sports.baofeng.com/api/v1/android/board/thread/match/list?id=%d';
//比赛热门帖子
$config['api_match_post']     = 'http://api.board.sports.baofeng.com/api/v1/android/board/post/match/list?id=%d';
//比赛聊天记录
$config['api_history']          = 'http://r.rt.sports.baofeng.com/api/v2/history?id=%d&type=%d';
//球队人气值
$config['api_support']          = 'http://r.rt.sports.baofeng.com/api/stats/v1/party/support?id=%d';
//互动达人
$config['api_finger']           = 'http://r.rt.sports.baofeng.com/api/stats/v1/top/finger?id=%d';
//战报
$config['api_report']           = 'http://api.sports.baofeng.com/api/v3/android/event/match/report/list?id=%d';
//比赛关联视频
$config['api_match_video']      = 'http://api.sports.baofeng.com/api/v3/android/event/match/video/list?id=%d';
//比赛相关资讯
$config['api_match_related']    = 'http://api.sports.baofeng.com/api/v3/android/event/match/related?id=%d';
// 视频相关视频
$config['api_video_related']    = 'http://api.sports.baofeng.com/api/v3/android/video/related?id=%d';

// 话题详情
$config['api_topic_detail']     = 'http://api.board.sports.baofeng.com/api/v1/android/board/thread/content/info?id=%d';
// 话题全部帖子
$config['api_topic_posts']      = 'http://api.board.sports.baofeng.com/api/v1/android/board/thread/content/list?id=%d';
// 话题热门帖子
$config['api_topic_hots']       = 'http://api.board.sports.baofeng.com/api/v1/android/board/thread/content/hot?id=%d';
// 话题达人列表
$config['api_topic_people']     = 'http://api.board.sports.baofeng.com/api/v1/android/board/thread/content/people?id=%d';

// 帖子详情
$config['api_post_detail']      = 'http://api.board.sports.baofeng.com/api/v1/android/board/thread/post/detail?id=%d';
// 帖子评论
$config['api_post_comments']    = 'http://api.board.sports.baofeng.com/api/v1/android/board/thread/comment/list?id=%d';

//专题
$config['api_special_topic']    = 'http://api.sports.baofeng.com/api/v3/android/special/content/list?id=%d';
//专栏
$config['api_column']    = 'http://api.sports.baofeng.com/api/v3/android/column/content/list?id=%d';

//图集
$config['api_gallery_related'] = 'http://api.sports.baofeng.com/api/v3/android/gallery/related?id=%d';

//个性化推荐
if (ENVIRONMENT == 'production') {
    $config['api_sportsrec'] = 'http://192.168.215.215/sportsrec/v1/itemrec?id=%d&type=%s&howmany=%d';
} else {
    $config['api_sportsrec'] = 'http://110.172.215.205:8080/sportsrec/v1/itemrec?id=%d&type=%s&howmany=%d';
}

//推荐测试专用接口
$config['api_sportsrec_test']    = 'http://110.172.215.208:8080/sportsrec/v1/itemrec?id=%d&type=%s&howmany=%d';
