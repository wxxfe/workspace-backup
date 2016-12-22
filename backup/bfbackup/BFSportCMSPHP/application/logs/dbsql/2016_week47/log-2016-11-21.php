<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

DBSQL - 2016-11-21 10:25:31 --> log_id:161121102531-995;user_id:6;sql:INSERT INTO `log_user_action_201647` (`id`, `user_id`, `type`, `route`, `data`) VALUES ('161121102531-995', '6', 'user_login', '', '[]')
DBSQL - 2016-11-21 10:26:17 --> log_id:161121102617-197;user_id:6;menu:/gallery;sql:UPDATE `gallery` SET `updated_at` = '2016-11-21 10:26:17', `title` = '精彩\"图\"集', `brief` = '精\"彩\"图集', `image` = 'cd1e8ce6e532061977392ad333c93a04', `visible` = '0', `origin` = '暴风体育', `publish_tm` = '2016-11-11 16:56:33' WHERE `id` = '1005'
DBSQL - 2016-11-21 10:26:17 --> log_id:161121102617-197;user_id:6;menu:/gallery;sql:DELETE FROM `gallery_tag` WHERE `gallery_id` IN('1005')
DBSQL - 2016-11-21 10:26:17 --> log_id:161121102617-197;user_id:6;menu:/gallery;sql:INSERT INTO `gallery_tag` (`gallery_id`, `tag_id`) VALUES ('1005', '2000001')
DBSQL - 2016-11-21 10:26:17 --> log_id:161121102617-197;user_id:6;menu:/gallery;sql:DELETE FROM `gallery_related` WHERE `gallery_id` = 1005
DBSQL - 2016-11-21 10:26:17 --> log_id:161121102617-197;user_id:6;menu:/gallery;sql:DELETE FROM `match_related` WHERE `type` = 'gallery' AND `ref_id` = 1005
DBSQL - 2016-11-21 10:26:48 --> log_id:161121102647-768;user_id:6;menu:/gallery;sql:UPDATE `gallery` SET `updated_at` = '2016-11-21 10:26:47', `title` = '精彩\"图\"集', `brief` = '精\"彩\"图集', `image` = 'cd1e8ce6e532061977392ad333c93a04', `visible` = '0', `origin` = '暴风体育', `publish_tm` = '2016-11-11 16:56:33' WHERE `id` = '1005'
DBSQL - 2016-11-21 10:26:48 --> log_id:161121102647-768;user_id:6;menu:/gallery;sql:DELETE FROM `gallery_tag` WHERE `gallery_id` IN('1005')
DBSQL - 2016-11-21 10:26:48 --> log_id:161121102647-768;user_id:6;menu:/gallery;sql:DELETE FROM `gallery_related` WHERE `gallery_id` = 1005
DBSQL - 2016-11-21 10:26:48 --> log_id:161121102647-768;user_id:6;menu:/gallery;sql:DELETE FROM `match_related` WHERE `type` = 'gallery' AND `ref_id` = 1005
