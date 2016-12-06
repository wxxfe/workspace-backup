<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

DBSQL - 2016-11-03 16:16:07 --> log_id:161103161607-591;user_id:6;sql:INSERT INTO `log_user_action_201644` (`id`, `user_id`, `type`, `route`, `data`) VALUES ('161103161607-591', '6', 'user_login', '', '[]')
DBSQL - 2016-11-03 16:17:41 --> log_id:161103161741-373;user_id:6;menu:/community/poster;sql:UPDATE `poster` SET `image_small` = '2d2d2442cd520c13e74c544634d457cc', `image_large` = '93c33e9c6ea47673f0154c46fafab560', `name` = 'tt', `content` = 'tt', `visible` = 1, `id` = '6', `type` = 'threads' WHERE `id` = '6'
DBSQL - 2016-11-03 16:17:41 --> log_id:161103161741-373;user_id:6;menu:/community/poster;sql:DELETE FROM `poster_has` WHERE `poster_id` = '6'
DBSQL - 2016-11-03 16:17:41 --> log_id:161103161741-373;user_id:6;menu:/community/poster;sql:INSERT INTO `poster_has` (`thread_id`, `poster_id`, `display_order`) VALUES (72, '6', 0)
