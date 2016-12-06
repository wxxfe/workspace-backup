<?php

$header_footer_data = getHeaderFooterData('not-found', (isset($title) && $title) ? $title : 'not found! 没有找到页面!', $page_type);

?>

<?php $this->load->view('common/header', $header_footer_data) ?>

<div class="not-found">
    <div class="center">
        <img class="icon" src="<?= STATIC_URL ?>/images/not-found.png">
        <p>抱歉！你访问的页面失联啦...</p>
    </div>
</div>

<?php if ($page_type == 'share'): ?>
    <a class="go-app download-button" data-url='<?= YYB_APP_URL ?>' data-info=''>
        <span>进入暴风体育，看更多内容</span>
    </a>
<?php endif; ?>

<?php $this->load->view('common/footer', $header_footer_data) ?>
