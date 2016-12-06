<!-- 下载toast 开始 -->
<div class="toast downloadtoast hide">
    <div class="header">
        <span class="close"></span>
    </div>
    <div class="content">
        <div class="wrapper">
            <div class="logo">
                <img src="<?= STATIC_URL ?>/images/logo.png"/>
                <h2>暴风体育</h2>
                <p>下载客户端，说出你的想法！</p>
            </div>
            <a class="button download download-button"
               data-url='<?= YYB_APP_URL ?>' data-info='<?php echo (isset($info) && $info) ? $info : ''; ?>'>立即下载</a>
        </div>
    </div>
</div>
<!-- 下载条toast 结束 -->