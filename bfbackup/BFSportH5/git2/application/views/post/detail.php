<?php $this->load->view('common/header', $header_footer_data) ?>
<?php if ($page_type == 'share'): ?>
    <?php $this->load->view('common/download', $download_data) ?>
<?php endif; ?>

<div class="share">
    <div class="share-title">
        <span>话题：</span><span><?= $post['thread_title'] ?></span>
    </div>
    <!-- 分割线 开始 -->
    <div class="divideline">
    </div>
    <!-- 分割线 结束 -->
    <div class="share-content">
        <div class="portrait withfootnotes">
            <div class="img-wrapper">
                <img class="roundimg" src="<?= $post['icon'] ?>"/>
            </div>
            <div class="wrapper">
                <h2 class="title"><?= $post['nickname'] ?></h2>
                <span class="tag tag-flat tag-primary">话题主</span>
                <div class="footnote">
                    <span class="note"><?= date('m-d H:i', $post['created_at']) ?></span>
                </div>
            </div>
        </div>
        <div class="img-text">
            <?php if ($post['image']): ?><img src="<?= $post['image'] ?>"/><?php endif; ?>
            <h2 class="title"><?= $post['content'] ?></h2>
            </a>
        </div>
        <!-- 分割线 开始 -->
        <div class="divideline">
        </div>
        <!-- 分割线 结束 -->
        <div class="share-like">

            <img class="share-like-img" src="<?= STATIC_URL ?>/images/share-like.jpg"/>

            <div class="like-num"><?= $post['likes'] ?>人已赞</div>
            <div class="row portrait">
                <?php $likes = @json_decode($post['likes_json'], true); ?>

                <?php if ($likes): $likes = array_slice($likes, 0, 12); ?>
                    <?php foreach ($likes as $people): ?>
                        <div class="col-2">
                            <img class="roundimg" src="<?= $people['avatar'] ?>" alt="<?= $people['nickname'] ?>">
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($post['comment_count']): ?>
        <!-- 分割线 开始 -->
        <div class="divideline-height">
        </div>
        <!-- 分割线 结束 -->
        <div class="share-content reply">
            <div class="section-title">
                <h2>精彩评论（<?= $post['comment_count'] ?>）</h2>
            </div>
            <?php foreach ($comments as $comment): ?>
                <div class="portrait widthname clearfix">
                    <img class="roundimg pull-left" src="<?= $comment['owner_avatar'] ?>"/>
                    <div class="pull-left">
                        <h2 class="title"><?= $comment['owner_name'] ?></h2>
                        <div class="content">
                            <?= $comment['content'] ?>
                        </div>
                        <div class="footnote">
                            <span class="note"><?= date('m-d H:i', $comment['created_at']) ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <div class="form-control"><input class="downloadtoastshow" type="text" placeholder="输入你的文字和图片" readonly/></div>


    <?php if ($page_type == 'share'): ?>
        <?php $this->load->view('common/download_toast', $download_data) ?>
    <?php endif; ?>
</div>

<?php $this->load->view('common/footer', $header_footer_data) ?>
