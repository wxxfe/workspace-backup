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
                </div>
            </div>

        </article>
        <!-- 文章 结束 -->
        <?php if ($page_type == 'share'): ?>
            <?php $this->load->view('common/download_text', $download_data) ?>
        <?php endif; ?>

    </div>
</section>

<?php $this->load->view('common/list_news_action', array('list' => $related_video['video'], 'page_type' => $page_type, 'title' => '相关视频', 'download_data' => $download_data, 'line_show' => true)) ?>

<?php $this->load->view('common/footer', $header_footer_data) ?>
