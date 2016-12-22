<?php $this->load->view('common/header', $header_footer_data) ?>
<?php if ($page_type == 'share'): ?>
    <?php $this->load->view('common/download', $download_data) ?>
<?php endif; ?>

<div class="container">
    <?php if ($videos && $videos[0]): ?>

        <!-- 视频 开始 -->
        <div class="top-video">
            <div class="embed-video">

                <?php
                $t_video_id = $videos[0]['id'];
                if ($videoextra && isset($videoextra[$t_video_id]) && $videoextra[$t_video_id]) {
                    $videos[0]['extra'] = $videoextra[$t_video_id];
                }
                $t_video_json = getVideoPlayJson($videos[0]);
                ?>

                <a href="javascript:void(0)" data-video='<?= $t_video_json ?>'><span class="embed-video-play"></span><img src="<?php echo isset($videos[0]['image']) ? getImageUrl($videos[0]['image']) : '' ?>" alt="<?php echo isset($videos[0]['title']) ? $videos[0]['title'] : '' ?>"/></a>
            </div>
        </div>
        <!-- 视频 结束 -->

        <section class="section">
            <div class="section-content">
                <!-- Swiper -->
                <div class="swiper-container video-list" data-playing="">
                    <div class="swiper-wrapper">

                        <?php foreach ($videos as $index => $val): ?>

                            <?php
                            $t_video_id = $val['id'];
                            if ($videoextra && isset($videoextra[$t_video_id]) && $videoextra[$t_video_id]) {
                                $val['extra'] = $videoextra[$t_video_id];
                            }
                            $t_video_json = getVideoPlayJson($val);
                            ?>

                            <div class="swiper-slide" data-video='<?= $t_video_json ?>' data-chapter="">
                                <div class="slide-video-wrapper <?php echo $index == 0 ? 'playing' : ''; ?>" title="5">
                                    <img src="<?php echo isset($val['image']) ? getImageUrl($val['image']) : '' ?>"/>
                                    <span class="tag tag-flat tag-primary"><?php echo $index == 0 ? '播放中' : ''; ?></span>
                                </div>
                                <p class="title"><?php echo isset($val['title']) ? $val['title'] : '' ?></p>
                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- Swiper -->
            </div>
        </section>

    <?php endif; ?>

    <!-- 比赛条 开始 -->
    <?php if ($match['type'] != 'various'): ?>

        <!-- 分隔线开始 加高的 -->
        <div class="divideline-height"></div>
        <!-- 分隔线结束 -->

        <div class="match-bar">
            <div class="row">
                <div class="col-4 match-footnote">
                    <?php echo $match['show_data']['vs_1']['name'] ?>
                    <img class="badge" src="<?php echo getImageUrl($match['show_data']['vs_1']['badge']); ?>" alt="">
                </div>
                <div class="col-4 match-footnote-score">
                    <?php echo $match['show_data']['vs_1']['extra_score'] ?>
                    <em>:</em><?php echo $match['show_data']['vs_2']['extra_score']; ?>
                </div>
                <div class="col-4 match-footnote">
                    <img class="badge" src="<?php echo getImageUrl($match['show_data']['vs_2']['badge']); ?>" alt="">
                    <?php echo $match['show_data']['vs_2']['name']; ?>
                </div>
            </div>
        </div>

    <?php endif; ?>
    <!-- 比赛条 结束 -->

    <?php if ($report): ?>

        <!-- 分隔线开始 加高的 -->
        <div class="divideline-height"></div>
        <!-- 分隔线结束 -->

        <section class="section">
            <div class="section-title">
                <h2>战报</h2>
            </div>
            <div class="section-content">
                <!-- 战报 开始 -->
                <div class="match-report">
                    <?php foreach ($report as $val): ?>


                        <?php

                        if (!((isset($val['content']) && $val['content']) || (isset($val['gif']) && $val['gif']) || (isset($val['image']) && $val['image']))) {
                            continue;
                        }

                        if (!isset($val['report_tm'])) {
                            continue;
                        }


                        if ($val['report_tm'] < 0) {
                            $report_title = '综述';
                            $report_class = 'overview';
                        } else {
                            $report_title = '';
                            $report_class = '';
                            if (isset($val['report_tm'])) {
                                $f = $m = 0;
                                $f = floor($val['report_tm'] / 60);
                                if ($f >= 1) {
                                    $m = $val['report_tm'] % 60;
                                }
                                if ($f >= 1) {
                                    $report_title .= $f . "’";
                                }
                                if ($m > 0) {
                                    $report_title .= $m . "’’";
                                }
                            }
                        }

                        $url = '';
                        if (isset($val['gif']) && $val['gif']) {
                            $url = getImageUrl($val['gif']);

                        } else if (isset($val['image']) && $val['image']) {
                            $url = getImageUrl($val['image']);
                        }

                        ?>

                        <div class="report-item <?php echo $report_class; ?> row">
                            <div class="timeline col-2">
                                <span><?php echo $report_title; ?></span>
                            </div>
                            <div class="message message-host col-10">
                                <?php if (isset($val['content']) && $val['content']): ?>
                                    <p class="content"><?php echo $val['content']; ?></p>
                                <?php endif; ?>
                                <?php if ($url): ?>
                                    <div class="poster">
                                        <img src="<?php echo $url; ?>" alt="">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
                <!-- 战报 开始 -->
            </div>
        </section>
    <?php endif; ?>

    <?php if ($finger): ?>
        <?php
        $finger_num = array('第一名', '第二名', '第三名');
        $finger_class = array('', 'silver', 'copper');
        ?>
        <section class="section">
            <div class="section-title">
                <h2>互动达人榜</h2>
            </div>
            <div class="section-content">
                <div class="hero-rank row">
                    <?php foreach ($finger as $f_index => $f_value): ?>
                        <div class="hero-rank-item col-4">
                            <div class="hero-img">
                                <?php if (isset($f_value['avatar']) && $f_value['avatar']): ?>
                                <img class="roundimg" src="<?php echo getImageUrl($f_value['avatar']); ?>/>
                                <?php endif; ?>
                                <span class=" button button-round-lg rank-tag <?php echo $finger_class[$f_index]; ?>
                                "><?php echo $finger_num[$f_index]; ?></span>
                            </div>
                            <div class="hero-name">
                                <span><?php echo (isset($f_value['name']) && $f_value['name']) ? $f_value['name'] : ''; ?></span>
                            </div>
                            <div class="hero-info">
                                <p><?php echo (isset($f_value['score']) && $f_value['score']) ? $f_value['score'] : ''; ?>
                                    互动分</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($posts): ?>

        <!-- 分隔线开始 加高的 -->
        <div class="divideline-height"></div>
        <!-- 分隔线结束 -->

        <?php foreach ($posts as $val): ?>
            <section class="section">
                <div class="section-title">
                    <a class="go" href="javascript:void(0)"
                       data-url="<?= base_url('topic/detail/') . $val['thread_id'] ?>"
                       data-info='<?= getShareOrAppJson('topic', array('id' => $val['thread_id'], 'title' => $val['title']), $page_type) ?>'>
                        <h2>热门话题：<?php echo isset($val['title']) ? $val['title'] : ''; ?></h2>
                    </a>
                </div>
                <div class="section-content">
                    <a class="go" href="javascript:void(0)"
                       data-url="<?= base_url('post/detail/') . $val['id'] ?>"
                       data-info='<?= getShareOrAppJson('topic', array('id' => $val['thread_id'], 'title' => $val['title'], 'postId' => $val['id']), $page_type) ?>'>
                        <div class="topic-card">
                            <div class="paragraphs">
                                <?php if (isset($val['content']) && $val['content']): ?>
                                    <p><?php echo $val['content']; ?></p>
                                <?php endif; ?>
                                <?php if (isset($val['image']) && $val['image']): ?>
                                    <p><img src="<?php echo getImageUrl($val['image']); ?>"></p>
                                <?php endif; ?>
                            </div>
                            <div class="post-info">
                                <div class="post-time"><span><?= date('m-d H:i', $val['created_at']) ?></span></div>
                                <!-- 画像 有名字 开始 -->
                                <div class="portrait withname">
                                    <?php if (isset($val['icon']) && $val['icon']): ?>
                                        <div class="img-wrapper">
                                            <img class="roundimg" src="<?php echo $val['icon']; ?>"/>
                                        </div>
                                        <div class="wrapper">
                                            <h2 class="title"><?php echo isset($val['nickname']) ? $val['nickname'] : ''; ?></h2>
                                        </div>
                                    <?php else: ?>
                                        <div class="wrapper" style="margin-left: 0px;">
                                            <h2 class="title"><?php echo isset($val['nickname']) ? $val['nickname'] : ''; ?></h2>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <!-- 画像 有名字 结束 -->
                            </div>
                        </div>
                    </a>
                </div>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php $this->load->view('common/list_news_action', array('list' => $related, 'page_type' => $page_type, 'exclude_type' => array('program', 'activity'), 'title' => '赛事资讯', 'download_data' => $download_data, 'line_show' => true)) ?>

</div>

<?php $this->load->view('common/footer', $header_footer_data) ?>
