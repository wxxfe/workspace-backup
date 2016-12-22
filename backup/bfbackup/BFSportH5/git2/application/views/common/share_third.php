<!--组件需要的数据-->
<!--array('page_type' => '');-->

<?php if (isset($page_type) && $page_type == 'app'): ?>

    <!-- 分享到第三方 开始 -->
    <div class="share-third">
        <!-- 中间有信息的分割线 开始 -->
        <div class="divideline-words-middle">
            <span class="words">分享至</span>
        </div>
        <!-- 中间有信息的分割线 结束 -->

        <div class="share-panel">
            <div class="third-img" data-platform="SinaWeibo">
                <img src="<?= STATIC_URL ?>/images/sinaweibo.png"/>
                <p class="third-name">新浪微博</p>
            </div>
            <div class="third-img" data-platform="WechatMoments">
                <img src="<?= STATIC_URL ?>/images/pyq.png"/>
                <p class="third-name">朋友圈</p>
            </div>
            <div class="third-img" data-platform="Wechat">
                <img src="<?= STATIC_URL ?>/images/wxfriend.png"/>
                <p class="third-name">微信好友</p>
            </div>
            <div class="third-img" data-platform="QQ">
                <img src="<?= STATIC_URL ?>/images/qqfriend.png"/>
                <p class="third-name">QQ</p>
            </div>
            <div class="third-img" data-platform="QZone">
                <img src="<?= STATIC_URL ?>/images/qzone.png"/>
                <p class="third-name">QQ空间</p>
            </div>
        </div>
    </div>
    <!-- 分享到第三方 结束 -->

<?php endif; ?>