<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['items'] = array(
    'video', 'news', 'program', 'collection', 'gallery', 'special', 'activity', 'match', 'box_collection',
    'thread',
);

$config['news_type'] = array(
    'news' => '新闻', 
    'video' => '视频', 
    'gallery' => '图集', 
    'program' => '节目', 
    'special' => '专题', 
    'activity' => '活动', 
    'collection' => '合集',
    'thread' => '话题',
    'top' => '置顶'
);

$config['match_types'] = array(
    'team'    => '团队对阵式',
    'player'  => '个人对阵式',
    'various' => '非对阵式',
);

$config['match_statuses'] = array(
    'notstarted'=> '未开始',
    'ongoing'   => '直播中',
    'finished'  => '已结束',
    'postponed' => '比赛推迟',
    'cancelled' => '取消',
);

$config['match_forecast_types'] = array(
    'news'  => '新闻',
    'html'  => 'URL',
    'thread' => '话题',
);

$config['banner_types'] = array(
    'news'  => '新闻',
    'html'  => 'URL',
);

$config['live_types'] = array(
    'none'      => '无直播',
    'message'   => '图文直播',
    'stream'    => '视频直播',
);

$config['notification_types'] = array(
    'video' => '视频',
    'news'  => '新闻',
    'program' => '节目',
    'match' => '比赛',
    'h5'   => 'H5',
);
