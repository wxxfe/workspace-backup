<?php

$header_footer_data = getHeaderFooterData('nodata', (isset($nodata_txt) && $nodata_txt) ? $nodata_txt : '没有数据', $page_type);

?>

<?php $this->load->view('common/header', $header_footer_data) ?>

<?php if ($page_type == 'share'): ?>
    <?php $this->load->view('common/download') ?>
<?php endif; ?>

<?php $this->load->view('common/no_data', array('nodata_txt' => (isset($nodata_txt) && $nodata_txt) ? $nodata_txt : '')) ?>

<?php $this->load->view('common/footer', $header_footer_data) ?>
