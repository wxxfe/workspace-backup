<?php $this->load->view('common/header', $header_footer_data) ?>
<?php if ($page_type == 'share'): ?>
    <?php $this->load->view('common/download', $download_data) ?>
<?php endif; ?>

<section class="section section-article" <?php if ($page_type == 'app'): ?>style="margin-top: 0;"<?php endif; ?>>
        <div class="section-content">
            <!-- 文章 开始 -->
            <article class="article">
                <?php if (empty($news['large_image'])): ?>
                    <div class="article-headline">
                        <div class="wrapper">
                            <h1 class="title"><?= $news['title'] ?></h1>
                            <div class="footnote">
                            <span
                                class="note"><?= empty($news['site_name']) ? $news['site'] : $news['site_name'] ?></span>
                                <span class="note"><?= nice_date($news['publish_tm'], 'Y-m-d H:i') ?></span>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- 大图背景的大字标题 开始 -->
                    <div class="article-headline article-headline-img">
                        <div class="imgwrapper">
                            <img class="img" src="<?= getImageUrl($news['large_image']) ?>"/>
                        </div>
                        <div class="wrapper">
                            <h1 class="title"><?= $news['title'] ?></h1>
                            <div class="footnote">
                            <span
                                class="note"><?= empty($news['site_name']) ? $news['site'] : $news['site_name'] ?></span>
                                <span class="note"><?= nice_date($news['publish_tm'], 'Y-m-d H:i') ?></span>
                            </div>
                        </div>
                    </div>
                    <!-- 大图背景的大字标题 结束 -->
                <?php endif; ?>

                <div class="content">
                    <div class="paragraphs">
                        <?= $news['content'] ?>
                    </div>
                </div>

            </article>
            <!-- 文章 结束 -->
        </div>
    </section>


<?php $this->load->view('common/share_third', array('page_type' => $page_type)) ?>


<?php $this->load->view('common/list_news_action', array('list' => $related, 'page_type' => $page_type, 'exclude_type' => array('program', 'activity'), 'title' => '相关新闻', 'download_data' => $download_data, 'line_show' => true)) ?>


<?php $this->load->view('common/footer', $header_footer_data) ?>