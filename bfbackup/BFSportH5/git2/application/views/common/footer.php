<?php if (isset($page_type) && $page_type): ?>
    <input id="page-type" type="hidden" value="<?= $page_type ?>"/>
<?php endif; ?>

<?php foreach ($resource['js'] as $js): ?>
    <script src="<?= $js ?>"></script>
<?php endforeach; ?>

</body>

</html>
