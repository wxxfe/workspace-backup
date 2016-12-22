<?php $this->load->view('common/header', $header_footer_data) ?>
<?php if ($page_type == 'share'): ?>
    <?php $this->load->view('common/download', $download_data) ?>
<?php endif; ?>

<!-- 比赛信息 开始 -->
<div class="match-info <?php echo ($match['type'] == 'various') ? 'match-info-various' : ''; ?>" style="background: url('<?= STATIC_URL ?>/images/top-bg-1.jpg') no-repeat 50% 50% / cover;">
    <h1 class="title"><?php echo $match['show_data']['txt_2'] ?></h1>
    <div class="time">
        <?php echo getDateFormat($match['start_tm']) . '开始'; ?>
    </div>
    <?php if ($match['type'] != 'various'): ?>
        <div class="both-sides">
            <div class="side side1">
                <img src="<?php echo getImageUrl($match['show_data']['vs_1']['badge']) ?>"/>
                <span class="name"><?php echo $match['show_data']['vs_1']['name'] ?></span>
            </div>
            <span class="vs">VS</span>
            <div class="side side2">
                <img src="<?php echo getImageUrl($match['show_data']['vs_2']['badge']) ?>"/>
                <span class="name"><?php echo $match['show_data']['vs_2']['name'] ?></span>
            </div>
        </div>
    <?php endif; ?>
    <div class="operation">
        <a class="button">预约</a>
    </div>
</div>
<!-- 比赛信息 结束 -->

<?php if ($threads): ?>

    <!-- 分隔线开始 加高的 -->
    <div class="divideline-height"></div>
    <!-- 分隔线结束 -->

    <section class="section">
        <div class="section-title">
            <h2>热门话题</h2>
        </div>
        <div class="section-content">
            <!-- 热门话题 开始 -->
            <ul class="topic-list news-action last-no-border">
                <?php foreach ($threads as $val): ?>
                    <li>
                        <a href="javascript:void(0)"
                           data-url="<?= base_url('topic/detail/') . $val['id'] ?>"
                           data-info='<?= getShareOrAppJson('topic', $val, $page_type) ?>'>
                            <img class="badge" src="<?php echo isset($val['icon']) ? $val['icon'] : ''; ?>" alt="">
                            <h4><?php echo isset($val['title']) ? $val['title'] : ''; ?>
                                <small>已盖到<?php echo isset($val['count']) ? $val['count'] : ''; ?>楼</small>
                            </h4>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <!-- 热门话题 结束 -->
        </div>
    </section>

<?php endif; ?>

<?php $this->load->view('common/list_news_action', array('list' => $related, 'page_type' => $page_type, 'exclude_type' => array('program', 'activity'), 'title' => '赛事资讯', 'download_data' => $download_data, 'line_show' => true)) ?>

<?php if (!$threads && !$related): ?>
    <div class="no-data">
        <div class="center">
            <img class="content-empty" src="<?= STATIC_URL ?>/images/content-empty.png">
            <p>没有数据<br>请尝试刷新页面再看看</p>
        </div>
    </div>
<?php endif; ?>

<?php if ($page_type == 'share'): ?>
    <?php $this->load->view('common/download_toast', $download_data) ?>
<?php endif; ?>

<?php $this->load->view('common/footer', $header_footer_data) ?>
