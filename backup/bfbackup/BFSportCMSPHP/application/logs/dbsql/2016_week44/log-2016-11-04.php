<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

DBSQL - 2016-11-04 17:47:35 --> log_id:161104174735-445;user_id:6;sql:INSERT INTO `log_user_action_201644` (`id`, `user_id`, `type`, `route`, `data`) VALUES ('161104174735-445', '6', 'user_login', '', '[]')
DBSQL - 2016-11-04 17:50:17 --> log_id:161104175017-191;user_id:6;menu:/gallery;sql:UPDATE `gallery` SET `updated_at` = '2016-11-04 17:50:17', `title` = '1', `brief` = '1', `image` = 'f8c925ed0da76270c8ac263a6759fc65', `visible` = '0', `origin` = '暴风体育', `publish_tm` = '2016-11-04 10:33:02' WHERE `id` = '110'
DBSQL - 2016-11-04 17:50:17 --> log_id:161104175017-191;user_id:6;menu:/gallery;sql:DELETE FROM `gallery_tag` WHERE `gallery_id` IN('110')
DBSQL - 2016-11-04 17:50:17 --> log_id:161104175017-191;user_id:6;menu:/gallery;sql:INSERT INTO `gallery_tag` (`gallery_id`, `tag_id`) VALUES ('110', '2000001')
DBSQL - 2016-11-04 17:50:17 --> log_id:161104175017-191;user_id:6;menu:/gallery;sql:DELETE FROM `gallery_tag` WHERE `gallery_id` = 110
DBSQL - 2016-11-04 17:50:17 --> log_id:161104175017-191;user_id:6;menu:/gallery;sql:DELETE FROM `gallery_related` WHERE `gallery_id` = 110
DBSQL - 2016-11-04 17:50:17 --> log_id:161104175017-191;user_id:6;menu:/gallery;sql:DELETE FROM `match_related` WHERE `type` = 'gallery' AND `ref_id` = 110
DBSQL - 2016-11-04 17:52:55 --> log_id:161104175255-733;user_id:6;menu:/news/;sql:UPDATE `news` SET `title` = '12www1', `subtitle` = '', `site` = 'sina', `image` = '1d0ceff853f5e522b55e7a125b01d3e7', `large_image` = '', `content` = '<p>碴中 苛右</p><p>若虹</p><p>昔叵进基颉查叵地若地若</p><p>项可进菲或百茬勤奋量a</p><p>项可国时叵工嘶基埋藏时或夲</p><p>嘶工喱或百嘞百划进基堪</p><p>苛右堪</p><p>基基基基工工</p>', `visible` = '1', `publish_tm` = '2016-11-02 15:22:22', `updated_at` = '2016-11-04 17:52:55', `duration` = '0', `match_id` = '1583' WHERE `id` = '3442'
DBSQL - 2016-11-04 17:52:55 --> log_id:161104175255-733;user_id:6;menu:/news/;sql:DELETE FROM `news_tag` WHERE `news_id` = '3442'
DBSQL - 2016-11-04 17:52:55 --> log_id:161104175255-733;user_id:6;menu:/news/;sql:INSERT INTO `news_tag` (`news_id`, `tag_id`) VALUES ('3442','2000001')
DBSQL - 2016-11-04 17:52:55 --> log_id:161104175255-733;user_id:6;menu:/news/;sql:UPDATE `match_related` SET `match_id` = '1583' WHERE `ref_id` = '3442' AND `type` = 'news'
DBSQL - 2016-11-04 17:53:22 --> log_id:161104175322-263;user_id:6;menu:/gallery;sql:UPDATE `gallery` SET `updated_at` = '2016-11-04 17:53:22', `title` = '1', `brief` = '1', `image` = 'f8c925ed0da76270c8ac263a6759fc65', `visible` = '0', `origin` = '暴风体育', `publish_tm` = '2016-11-04 10:33:02' WHERE `id` = '110'
DBSQL - 2016-11-04 17:53:22 --> log_id:161104175322-263;user_id:6;menu:/gallery;sql:DELETE FROM `gallery_tag` WHERE `gallery_id` IN('110')
DBSQL - 2016-11-04 17:53:22 --> log_id:161104175322-263;user_id:6;menu:/gallery;sql:INSERT INTO `gallery_tag` (`gallery_id`, `tag_id`) VALUES ('110', '2000001')
DBSQL - 2016-11-04 17:53:22 --> log_id:161104175322-263;user_id:6;menu:/gallery;sql:DELETE FROM `gallery_tag` WHERE `gallery_id` = 110
DBSQL - 2016-11-04 17:53:22 --> log_id:161104175322-263;user_id:6;menu:/gallery;sql:DELETE FROM `gallery_related` WHERE `gallery_id` = 110
DBSQL - 2016-11-04 17:53:22 --> log_id:161104175322-263;user_id:6;menu:/gallery;sql:DELETE FROM `match_related` WHERE `type` = 'gallery' AND `ref_id` = 110
DBSQL - 2016-11-04 17:53:30 --> log_id:161104175330-375;user_id:6;menu:/gallery;sql:UPDATE `gallery` SET `updated_at` = '2016-11-04 17:53:30', `title` = '1', `brief` = '1', `image` = 'f8c925ed0da76270c8ac263a6759fc65', `visible` = '0', `origin` = '暴风体育', `publish_tm` = '2016-11-04 10:33:02' WHERE `id` = '110'
DBSQL - 2016-11-04 17:53:30 --> log_id:161104175330-375;user_id:6;menu:/gallery;sql:DELETE FROM `gallery_tag` WHERE `gallery_id` IN('110')
DBSQL - 2016-11-04 17:53:30 --> log_id:161104175330-375;user_id:6;menu:/gallery;sql:DELETE FROM `gallery_tag` WHERE `gallery_id` = 110
DBSQL - 2016-11-04 17:53:30 --> log_id:161104175330-375;user_id:6;menu:/gallery;sql:DELETE FROM `gallery_related` WHERE `gallery_id` = 110
DBSQL - 2016-11-04 17:53:30 --> log_id:161104175330-375;user_id:6;menu:/gallery;sql:DELETE FROM `match_related` WHERE `type` = 'gallery' AND `ref_id` = 110
