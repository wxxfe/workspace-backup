<?php $this->load->view('common/header', $header_footer_data) ?>
<?php if ($page_type == 'share'): ?>
    <?php $this->load->view('common/download', $download_data) ?>
<?php endif; ?>

<div class="top-video">
    <div class="embed-video">
        <a href="javascript:void(0)" data-video='<?= getVideoPlayJson($top_video) ?>'><span class="embed-video-play"></span><img src="<?= $top_video['image'] ? getImageUrl($top_video['image']) : '' ?>" alt="<?= $top_video['title'] ?>"/></a>
    </div>
</div>

<section class="section section-article">
    <div class="section-content">
        <!-- 文章 开始 -->
        <article class="article">
            <div class="article-headline">
                <div class="wrapper">
                    <h1 class="title"><?= $top_video['title'] ?></h1>
                    <?php if ($top_video['issue_no']): ?>
                        <div class="footnote">
                            <span class="note"><?= $top_video['issue_no'] ?>期</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="content">
                <!-- 节目简介 开始 -->
                <div class="program-profile">
                    <div class="portrait withname">
                        <div class="img-wrapper">
                            <img class="roundimg" src="<?= $program['image'] ? getImageUrl($program['image']) : '' ?>"/>
                        </div>
                        <div class="wrapper">
                            <h2 class="title"><?= $program['title'] ?></h2>
                        </div>
                    </div>

                    <div class="toggle-btn"><i class="fa fa-angle-right fa-lg icon"></i></div>

                    <div class="paragraphs">
                        <p><?= $program['desc'] ?></p>
                    </div>
                </div>
                <!-- 节目简介 结束 -->
            </div>
        </article>
        <!-- 文章 结束 -->
    </div>
</section>


<section class="section">
    <div class="section-title">
        <h2>选集（<?= $video_list['total'] ?>）</h2>
    </div>
    <div class="section-content">
        <!-- Swiper -->
        <div class="swiper-container video-list" data-playing="<?= $top_video['issue_no'] ?>">
            <div class="swiper-wrapper">
                <?php foreach ($video_list['content'] as $video): ?>
                    <div class="swiper-slide"
                         data-video='<?= getVideoPlayJson($video) ?>'
                         data-chapter="<?= $video['issue_no'] ?>">
                        <div class="slide-video-wrapper <?php if ($top_video['id'] == $video['id']): ?>
                    playing <?php endif; ?>" title="<?= $video['title'] ?>">
                            <img src="<?= $video['image'] ?>"/>
                            <span class="tag tag-flat tag-primary">
                        <?php if ($top_video['id'] == $video['id']): ?>
                            播放中
                        <?php else: ?>
                            <?= $video['issue_no'] ?>期
                        <?php endif; ?>
                        </span>
                        </div>
                        <p class="title"><?= $video['title'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Swiper -->
    </div>
</section>
<?php $this->load->view('common/footer', $header_footer_data) ?>
