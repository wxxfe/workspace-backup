<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

DBSQL - 2016-10-27 09:47:18 --> log_id:161027094718-179;user_id:6;sql:INSERT INTO `log_user_action_201643` (`id`, `user_id`, `type`, `route`, `data`) VALUES ('161027094718-179', '6', 'user_login', '', '[]')
DBSQL - 2016-10-27 10:18:34 --> log_id:161027101834-268;user_id:6;menu:/gallery;sql:INSERT INTO `gallery` (`title`, `brief`, `image`, `visible`, `origin`, `publish_tm`) VALUES ('tt', 't', '', '1', '暴风体育', '2016-10-27 10:18:07')
DBSQL - 2016-10-27 10:18:34 --> log_id:161027101834-268;user_id:6;menu:/gallery;sql:DELETE FROM `gallery_tag` WHERE `gallery_id` IN('101')
DBSQL - 2016-10-27 10:18:48 --> log_id:161027101848-261;user_id:6;menu:/gallery;sql:INSERT INTO `gallery_image` (`brief`, `gallery_id`, `image`, `title`) VALUES ('','101','93c33e9c6ea47673f0154c46fafab560','拍照调整_05'), ('','101','2d2d2442cd520c13e74c544634d457cc','拍照调整_02')
DBSQL - 2016-10-27 10:18:52 --> log_id:161027101852-371;user_id:6;menu:/gallery;sql:INSERT INTO `gallery_image` (`brief`, `gallery_id`, `image`, `title`) VALUES ('','101','93c33e9c6ea47673f0154c46fafab560','拍照调整_05')
DBSQL - 2016-10-27 10:18:53 --> log_id:161027101853-999;user_id:6;menu:/gallery;sql:UPDATE `gallery` SET `updated_at` = '2016-10-27 10:18:53', `title` = 'tt', `brief` = 'ttt', `image` = '93c33e9c6ea47673f0154c46fafab560', `visible` = '1', `origin` = '暴风体育', `publish_tm` = '2016-10-27 10:18:07' WHERE `id` = '101'
DBSQL - 2016-10-27 10:18:53 --> log_id:161027101853-999;user_id:6;menu:/gallery;sql:DELETE FROM `gallery_tag` WHERE `gallery_id` IN('101')
DBSQL - 2016-10-27 11:31:20 --> log_id:161027113120-889;user_id:6;menu:/news/;sql:UPDATE `news` SET `title` = '孔健添加一条新闻试试', `subtitle` = '22222', `site` = 'sina', `image` = 'c70b71acc65fa0e8bafbda4175ac75eb', `large_image` = '', `content` = '<div class=\"bfsports-topic-box\">参与话题：<span class=\"bfsports-topic\" data-tid=\"74\">加油啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊 啊啊啊啊啊</span></div><p>这条新闻就是测试用的<br/></p>', `visible` = '1', `publish_tm` = '2016-10-26 17:11:57', `updated_at` = '2016-10-27 11:31:20' WHERE `id` = '3417'
DBSQL - 2016-10-27 11:31:20 --> log_id:161027113120-889;user_id:6;menu:/news/;sql:DELETE FROM `news_tag` WHERE `news_id` = '3417'
DBSQL - 2016-10-27 11:31:20 --> log_id:161027113120-889;user_id:6;menu:/news/;sql:INSERT INTO `news_tag` (`news_id`, `tag_id`) VALUES ('3417','')
DBSQL - 2016-10-27 14:42:59 --> log_id:161027144259-763;user_id:6;menu:/SysMessage;sql:INSERT INTO `sys_message` (`user_ids`, `content`, `image`) VALUES ('135601920047939428', 'test', '')
DBSQL - 2016-10-27 14:51:10 --> log_id:161027145110-260;user_id:6;menu:/SysMessage;sql:UPDATE `sys_message` SET `user_ids` = 'all', `content` = 'test', `image` = '' WHERE `id` = '23'
DBSQL - 2016-10-27 14:54:36 --> log_id:161027145436-584;user_id:6;menu:/interaction/quiz/published_quiz/;sql:SET NAMES utf8mb4
DBSQL - 2016-10-27 14:54:36 --> log_id:161027145436-584;user_id:6;menu:/interaction/quiz/published_quiz/;sql:UPDATE `subject` SET `status` = 'wait' WHERE `id` = '304'
DBSQL - 2016-10-27 14:54:36 --> log_id:161027145436-584;user_id:6;menu:/interaction/quiz/published_quiz/;sql:UPDATE `subject` SET `status` = 'wait' WHERE `id` = '303'
DBSQL - 2016-10-27 14:54:36 --> log_id:161027145436-584;user_id:6;menu:/interaction/quiz/published_quiz/;sql:UPDATE `subject` SET `status` = 'wait' WHERE `id` = '302'
DBSQL - 2016-10-27 14:54:44 --> log_id:161027145444-321;user_id:6;menu:/interaction/quiz/published_quiz/;sql:SET NAMES utf8mb4
DBSQL - 2016-10-27 14:54:58 --> log_id:161027145458-73;user_id:6;menu:/interaction/quiz/published_quiz/;sql:SET NAMES utf8mb4
DBSQL - 2016-10-27 14:55:00 --> log_id:161027145500-661;user_id:6;menu:/interaction/quiz/published_quiz/;sql:SET NAMES utf8mb4
DBSQL - 2016-10-27 14:55:03 --> log_id:161027145503-556;user_id:6;menu:/interaction/quiz/published_quiz/;sql:SET NAMES utf8mb4
DBSQL - 2016-10-27 14:55:09 --> log_id:161027145509-923;user_id:6;menu:/interaction/quiz/published_quiz/;sql:SET NAMES utf8mb4
DBSQL - 2016-10-27 14:55:14 --> log_id:161027145513-32;user_id:6;menu:/interaction/quiz/published_quiz/;sql:SET NAMES utf8mb4
DBSQL - 2016-10-27 14:55:20 --> log_id:161027145520-104;user_id:6;menu:/interaction/quiz/published_quiz/;sql:SET NAMES utf8mb4
DBSQL - 2016-10-27 14:55:22 --> log_id:161027145522-706;user_id:6;menu:/interaction/quiz/published_quiz/;sql:SET NAMES utf8mb4
DBSQL - 2016-10-27 14:55:24 --> log_id:161027145524-454;user_id:6;menu:/interaction/quiz/published_quiz/;sql:SET NAMES utf8mb4
DBSQL - 2016-10-27 16:46:21 --> log_id:161027164621-432;user_id:6;sql:INSERT INTO `log_user_action_201643` (`id`, `user_id`, `type`, `route`, `data`) VALUES ('161027164621-432', '6', 'user_login', '', '[]')
DBSQL - 2016-10-27 16:47:26 --> log_id:161027164726-927;user_id:6;menu:/SysMessage;sql:INSERT INTO `sys_message` (`user_ids`, `content`, `image`) VALUES ('135601920096726116', 'skp test', '93c33e9c6ea47673f0154c46fafab560')
DBSQL - 2016-10-27 17:33:02 --> log_id:161027173302-138;user_id:6;sql:INSERT INTO `log_user_action_201643` (`id`, `user_id`, `type`, `route`, `data`) VALUES ('161027173302-138', '6', 'user_login', '', '[]')
DBSQL - 2016-10-27 19:30:45 --> log_id:161027193045-823;user_id:6;sql:INSERT INTO `log_user_action_201643` (`id`, `user_id`, `type`, `route`, `data`) VALUES ('161027193045-823', '6', 'user_login', '', '[]')
DBSQL - 2016-10-27 19:31:17 --> log_id:161027193117-188;user_id:6;menu:/SysMessage;sql:INSERT INTO `sys_message` (`user_ids`, `content`, `image`) VALUES ('135601920047939428,135601920096726116', 'tatty', '')
