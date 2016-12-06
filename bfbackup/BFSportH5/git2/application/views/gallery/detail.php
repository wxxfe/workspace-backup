<?php $this->load->view('common/header', $header_footer_data) ?>
<?php if ($page_type == 'share'): ?>
    <?php $this->load->view('common/download', $download_data) ?>
<?php endif; ?>

<article class="article">
    <div class="article-headline">
        <div class="wrapper">
            <h1 class="title"><?= isset($gallery['title']) ? $gallery['title'] : ''; ?></h1>
            <div class="footnote">
                <span class="note"><?= isset($gallery['origin']) ? $gallery['origin'] : ''; ?></span>
                <span class="note"><?= isset($gallery['publish_tm']) ? date('Y-m-d H:i', strtotime($gallery['publish_tm'])) : ''; ?></span>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="paragraphs">
            <?php if (is_array($gallery_content)): ?>
                <?php $gallery_image_count = count($gallery_content);
                for ($i = 0; $i < $gallery_image_count; $i++): ?>
                    <dl class="image">
                        <dt>
                            <img class="lazy" data-original="<?php echo isset($gallery_content[$i]['image']) ? $gallery_content[$i]['image'] : ''; ?>" alt="<?php echo isset($gallery_content[$i]['title']) ? $gallery_content[$i]['title'] : ''; ?>">
                        </dt>
                        <dd class="row">
                            <div class="col-2 text-center"><?php echo ($i + 1) . "/" . $gallery_image_count; ?></div>
                            <div class="col-10"><?php echo isset($gallery_content[$i]['brief']) ? $gallery_content[$i]['brief'] : ''; ?></div>
                        </dd>
                    </dl>
                <?php endfor; ?>
            <?php endif; ?>
        </div>
    </div>
</article>

<?php $this->load->view('common/list_news_action', array('list' => $related, 'page_type' => $page_type, 'title' => '相关图集', 'download_data' => $download_data, 'line_show' => true)) ?>

<?php $this->load->view('common/footer', $header_footer_data) ?>
