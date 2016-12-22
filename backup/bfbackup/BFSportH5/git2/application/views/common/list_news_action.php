<!--

组件需要的数据
array('list' => '', 'page_type' => '','exclude_type'=>array(),'title'=>'','download_data'=>array(),'line_show'=>false);
$list 是必须的
$page_type 默认是 share
$exclude_type 排除不显示的数据类型 类似 array('news','video')
$title 标题，没有或为空则不显示标题
$download_data 下载组件数据，没有则不显示下载组件
$line_show 是否显示分隔线
$parent_view 父模板名字

现在相关资讯有两种数据格式来源，
一种是来着API接口的，具体阅读 接口文档  预备知识
一种是来着直接读数据库的方法，比如News_model.php

两种的区别就是判断资讯数据类型的type放的地方不一样。接口是和其他数据并列一起了

接口返回的时间都是单位为秒的时间戳

-->
<?php if (isset($list) && $list): ?>

    <?php

    //没有页面类型，默认是H5分享页
    if (!(isset($page_type) && $page_type)) {
        $page_type = 'share';
    }

    ?>

    <?php if (isset($line_show) && $line_show): ?>
        <!-- 分隔线开始 -->
        <div class="divideline-height"></div>
        <!-- 分隔线结束 -->
    <?php endif; ?>

    <div class="section">
        <?php if (isset($title) && $title): ?>
            <div class="section-title">
                <h2><?php echo $title; ?></h2>
            </div>
        <?php endif; ?>
        <div class="section-content">

            <div class="list">
                <ul class="news-action">

                    <?php foreach ($list as $item): ?>

                        <?php

                        if ($item) {

                            //处理两种数据格式，统一成一样的
                            if (isset($item['news_extra']) && $item['news_extra']) {
                                $data_type = $item['news_type'];
                                $item = $item['news_extra'];
                            } else {
                                $data_type = $item['type'];
                            }

                            //数据类型是话题的两种名字，统一改成'topic'
                            if ($data_type == 'thread') {
                                $data_type = 'topic';
                            }

                            //排除不显示的数据类型
                            if (isset($exclude_type) && $exclude_type && in_array($data_type, $exclude_type)) {
                                continue;
                            }

                            //如果数据类型是话题，并且有图片，则统一成image键名
                            if ($data_type == 'topic') {
                                if (isset($item['icon']) && $item['icon']) {
                                    $item['image'] = $item['icon'];
                                }
                            }

                            // list_item 附加的class类名
                            $list_item_class = '';

                            $right_wrapper = 'img-wrapper';

                            if (isset($item['image']) && $item['image']) {
                                $list_item_class = 'rightimg';

                                //如果是视频播放页
//                                if (isset($parent_view) && $parent_view == 'video/play') {
//                                    $list_item_class = 'rightvideo';
//                                    $right_wrapper = 'right-video-wrapper';
//                                }
                            }

                            if ($data_type == 'gallery') {
                                $list_item_class = 'imgs';
                            }

                            //非视频播放分享页的视频数据右侧图片上要显示视频播放箭头的样式
                            $tag_video = '';
                            if ($data_type == 'video') {
                                $tag_video = '<span class="tag-video"></span>';
                            }


                            //跳转页面地址
                            if ($data_type == 'video') {
                                $data_url = base_url('/' . $data_type . '/play/' . $item['id']);
                            } else {
                                $data_url = base_url('/' . $data_type . '/detail/' . $item['id']);
                            }

                            //要附加的额外数据，用于生成json
                            $data_extra = array();

                            //分享或APP内用到的json数据
                            $data_info = getShareOrAppJson($data_type, array_merge($item, $data_extra), $page_type);

                            //时间显示文字
                            $time_format = '';
                            if (isset($item['publish_tm']) && $item['publish_tm']) {
                                $time_format = nice_date($item['publish_tm'], 'm-d H:i');
                            } else if (isset($item['created_at']) && $item['created_at']) {
                                $time_format = nice_date($item['created_at'], 'm-d H:i');
                            }

                            //数据类型对应显示的文字名
                            $note_tag = getNewsTypeImg($data_type);

                            if (isset($item['column']) && $item['column']) {
                                $note_tag = getNewsTypeImg('column');
                            }

                            //还没有活动分享页
                            if ($data_type == 'activity') {
                                $data_url = '';
                            }

//
//
//                    switch ($data_type) {
//                        case 'news':
//
//                            break;
//
//                        case 'video':
//
//
//                            break;
//
//                        case 'collection':
//
//
//                            break;
//
//                        case 'gallery':
//
//
//                            break;
//
//                        case 'program':
//
//                            break;
//
//                        case 'match':
//
//
//                            break;
//
//                        case 'topic':
//
//                            break;
//
//                        case 'special':
//
//                            break;
//
//                        case 'activity':
//
//                            break;
//                    }


                        } else {
                            continue;
                        }

                        ?>


                        <li>

                            <div class="list-item <?= $list_item_class ?>">

                                <a href="javascript:void(0)"
                                   data-url="<?= $data_url ?>"
                                   data-info='<?= $data_info ?>'>

                                    <div class="wrapper">
                                        <h2 class="title"><?= $item['title'] ?></h2>
                                        <div class="footnote">
                                            <span class="note"><?= $time_format ?></span>
                                            <span class="note"><img class="tag-img" src="<?= $note_tag ?>"></span>
                                        </div>
                                    </div>
                                    <?php if ($list_item_class): ?>

                                        <?php if ($data_type == 'gallery'): ?>

                                            <div class="<?= $right_wrapper ?>">
                                                <ul class="row">
                                                    <?php
                                                    $gallery_images_count = count($item['images']);
                                                    $gallery_images_end = $gallery_images_count;
                                                    if ($gallery_images_end > 3) $gallery_images_end = 3;
                                                    ?>
                                                    <?php for ($i = 0; $i < $gallery_images_end; $i++): ?>
                                                        <li class="col-4">
                                                            <img class="lazy" data-original="<?php echo isset($item['images'][$i]['image']) ? getImageUrl($item['images'][$i]['image']) : '' ?>"/>
                                                            <?php if ($i == 2): ?>
                                                                <span class="tag tag-flat tag-primary"><?php echo $gallery_images_count ?></span>
                                                            <?php endif; ?>
                                                        </li>
                                                    <?php endfor; ?>
                                                </ul>
                                            </div>

                                        <?php else: ?>

                                            <div class="<?= $right_wrapper ?>">
                                                <img class="lazy" data-original="<?= getImageUrl($item['image']) ?>"/>
                                                <?= $tag_video ?>
                                            </div>

                                        <?php endif; ?>


                                    <?php endif; ?>
                                </a>

                            </div>

                        </li>

                    <?php endforeach; ?>
                </ul>
            </div>

            <?php if ($page_type == 'share' && isset($download_data)): ?>
                <?php
                $download_text_show = true;
                //如果下载文字条数据中有count，并且当前列表数据条数小于等于count，则不显示
                if (isset($download_data['count']) && count($list) <= $download_data['count']) {
                    $download_text_show = false;
                }
                ?>
                <?php if ($download_text_show): ?>
                    <?php $this->load->view('common/download_text', $download_data) ?>
                <?php endif; ?>
            <?php endif; ?>

        </div>

    </div>
<?php endif; ?>
<!-- 列表 结束 -->