<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$_USERLOG_INDEX = array(
    'user_login' => array(
            'desc' => '登录系统',
            'iclass' => 'fa fa-user bg-aqua',
            'data_format'=>array(),
            'display_format'=> array()
    ),
    'pending_video_edit' => array(
            'desc' => '待编辑视频添加为可用视频',
            'data_format' => array('id', 'title', 'brief', 'image'),
            'iclass' => 'fa fa-newspaper-o bg-green',
            'link' => array(site_url('video/detailEdit').'/{$id}')
            // 'display_format' => array('image','title','brief','link'),
    ),
    'video_edit' => array(
            'desc' => '对可用视频编辑',
            'data_format' => array('id', 'title', 'brief', 'image'),
            'iclass' => 'fa fa-newspaper-o bg-green',
            'link' => array(site_url('video/detailEdit').'/{$id}')
            // 'display_format' => array('image','title','brief','link'),
    ),
    'video_enabled' => array(
            'desc'  => '视频上线',
            'data_format' => array('id', 'title', 'brief', 'image'),
            'iclass' => 'fa fa-check bg-blue',
            'link' => array(site_url('video/detailEdit').'/{$id}')
    ),
    'video_disabled' => array(
            'desc'  => '视频下线',
            'data_format' => array('id', 'title', 'brief', 'image'),
            'iclass' => 'fa fa-remove bg-red',
            'link' => array(site_url('video/detailEdit').'/{$id}')
    ),
    'news_add' => array('desc' => '新闻添加', 'data_format' => array('id'), 'display_format'=>array('image','title','desc')),
    'news_delete' => array('desc' => '新闻删除', 'data_format' => array('id')),
    'news_update' => array('desc' => '新闻更新', 'data_format' => array('id')),
);