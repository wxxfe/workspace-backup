<?php $this->load->view('common/header', $header_footer_data) ?>
<?php if ($page_type == 'share'): ?>
    <?php $this->load->view('common/download', $download_data) ?>
<?php endif; ?>

<div class="share">
    <div class="img-text">
        <div class="main">
            <img src="<?= $topic['thread']['icon'] ?>"/>
            <div class="center">
                <h2 class="title"><span><?= $topic['thread']['title'] ?></span></h2>
                <div class="user">话题主：<?= $topic['author']['nickname'] ?></div>
                <!-- 圆角按钮 开始 -->
                <a href="javascript:void(0);" class="button downloadtoastshow">+ 关注</a>
                <!-- 圆角按钮 结束 -->
            </div>
        </div>
        <?php if ($topic['thread']['descrip']): ?>
            <p class="text"><?= $topic['thread']['descrip'] ?></p>
        <?php endif; ?>
        <?php if ($topic['tags']): ?>
            <div class="tags">
                <?php foreach ($topic['tags'] as $tag): ?>
                    <span class="tag"><?= $tag['community_name'] ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php if ($topic['thread']['descrip'] || $topic['tags']): ?>
    <div class="divideline-height">
    </div>
    <?php endif; ?>
    <!-- tab 开始 全部和热门结构一样，达人是另一种结构 -->
    <div class="tab default">
        <div class="title">
            <a href="#tab-all" class="item-title current">全部</a>
            <a href="#tab-hot" class="item-title">热门</a>
            <a href="#tab-persons" class="item-title">达人</a>
        </div>
        <div class="indicator-wrapper">
            <div class="indicator"></div>
        </div>
        <div class="content clearfix" style="left: 0;">
            <div id="tab-all" class="item-content current">
                <?php if ($posts): ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="tab-content">
                            <div class="portrait withfootnotes">
                                <div class="img-wrapper">
                                    <img class="roundimg" src="<?= $post['icon'] ?>"/>
                                </div>
                                <div class="wrapper">
                                    <h2 class="title"><?= $post['nickname'] ?></h2>
                                    <?php if ($topic['author']['user_id'] == $post['user_id']): ?>
                                        <span class="tag tag-flat tag-primary">话题主</span>
                                    <?php endif; ?>
                                    <div class="footnote">
                                        <span class="note">第<?= $post['seq'] ?>
                                            楼 <?= date('m-d H:i', $post['created_at']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php if ($post['image']): ?>
                                <div class="main-img"><img src="<?= $post['image'] ?>"></div><?php endif; ?>
                            <?php if ($post['content']): ?>
                                <div class="main-txt"><?= $post['content'] ?></div><?php endif; ?>
                            <div class="footer clearfix">
                                <img class="pull-left share-like" src="<?= STATIC_URL ?>/images/share-like-1.jpg"/>
                                <?php
                                $likes_people = '';
                                $likes = @json_decode($post['likes_json'], true);
                                if ($likes) {
                                    $likes_num = count($likes);
                                    $likes = array_slice($likes, 0, 2);
                                    $likes_people = implode('、', array_column($likes, 'nickname'));
                                }
                                ?>
                                <?php if ($likes_people): ?>
                                    <div class="pull-left"><?= $likes_people . ' ' . $likes_num ?>人赞过</div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="divideline-height"></div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <img class="content-empty"
                         src="<?= STATIC_URL ?>/images/content-empty.png"/>
                <?php endif; ?>
            </div>

            <div id="tab-hot" class="item-content">
                <?php if ($hots): ?>
                    <?php foreach ($hots as $post): ?>
                        <div class="tab-content">
                            <div class="portrait withfootnotes">
                                <div class="img-wrapper">
                                    <img class="roundimg" src="<?= $post['icon'] ?>"/>
                                </div>
                                <div class="wrapper">
                                    <h2 class="title"><?= $post['nickname'] ?></h2>
                                    <?php if ($topic['author']['user_id'] == $post['user_id']): ?>
                                        <span class="tag tag-flat tag-primary">话题主</span>
                                    <?php endif; ?>
                                    <div class="footnote">
                                        <span class="note">第<?= $post['seq'] ?>
                                            楼 <?= date('Y-m-d H:i:s', $post['created_at']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php if ($post['image']): ?>
                                <div class="main-img"><img src="<?= $post['image'] ?>"></div><?php endif; ?>
                            <?php if ($post['content']): ?>
                                <div class="main-txt"><?= $post['content'] ?></div><?php endif; ?>
                            <div class="footer clearfix">
                                <img class="pull-left share-like" src="<?= STATIC_URL ?>/images/share-like-1.jpg"/>
                                <?php
                                $likes_people = '';
                                $likes = @json_decode($post['likes_json'], true);
                                if ($likes) {
                                    $likes_num = count($likes);
                                    $likes = array_slice($likes, 0, 2);
                                    $likes_people = implode('、', array_column($likes, 'nickname'));
                                }
                                ?>
                                <?php if ($likes_people): ?>
                                    <div class="pull-left"><?= $likes_people . ' ' . $likes_num ?>人赞过</div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="divideline-height"></div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <img class="content-empty"
                         src="<?= STATIC_URL ?>/images/content-empty.png"/>
                <?php endif; ?>
            </div>

            <div id="tab-persons" class="item-content">
                <?php if ($people): ?>
                    <div class="tab-content">
                        <div class="section-title">
                            <h2>话题主</h2>
                        </div>
                        <div class="portrait withfootnotes">
                            <div class="img-wrapper">
                                <img class="roundimg" src="<?= $people['author']['avatar'] ?>"/>
                            </div>
                            <div class="wrapper">
                                <h2 class="title"><?= $people['author']['nickname'] ?></h2>
                                <span class="tag tag-flat tag-primary">话题主</span>
                                <div class="footnote">
                                    <span class="note">已获得<?= @intval($people['author']['sum_likes']) ?>个赞
                                        <!-- 生产了1个动态 --></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="divideline-height"></div>
                    <div class="tab-content">
                        <div class="section-title">
                            <h2>话题达人</h2>
                        </div>
                        <?php if (isset($people['users'])): ?>
                            <?php foreach ($people['users'] as $user): ?>
                                <div class="portrait withfootnotes">
                                    <div class="img-wrapper">
                                        <img class="roundimg" src="<?= $user['avatar'] ?>"/>
                                    </div>
                                    <div class="wrapper">
                                        <h2 class="title"><?= $user['nickname'] ?></h2>
                                        <?php if ($people['author']['user_id'] == $user['user_id']): ?>
                                            <span class="tag tag-flat tag-primary">话题主</span>
                                        <?php endif; ?>

                                        <div class="footnote">
                                            <span class="note">已获得<?= intval($user['sum_likes']) ?>个赞
                                                <!-- 生产了1个动态 --></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <img class="content-empty"
                         src="<?= STATIC_URL ?>/images/content-empty.png"/>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- tab 结束 -->
    <div class="form-control"><input class="downloadtoastshow" type="text" placeholder="输入你的文字和图片" readonly/></div>


    <?php if ($page_type == 'share'): ?>
        <?php $this->load->view('common/download_toast', $download_data) ?>
    <?php endif; ?>
</div>

<?php $this->load->view('common/footer', $header_footer_data) ?>
