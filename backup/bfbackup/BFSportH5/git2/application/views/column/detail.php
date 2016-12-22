<?php $this->load->view('common/header', $header_footer_data) ?>

<?php if ($page_type == 'share'): ?>
    <?php $this->load->view('common/download', $download_data) ?>
<?php endif; ?>

<div class="section section-article <?= $page_type ?>">

    <!-- 大图背景的大字标题 开始 -->
    <div class="img-text">
        <img class="bg" src="<?= getImageUrl($news['large_image']) ?>"/>
        <div class="text <?= $page_type ?>">
            <div class="one clearfix">
                <div class="date">
                    <div class="day"><?= nice_date($news['publish_tm'], 'd') ?></div>
                    <div class="m-y">
                        <div class="m"><?= nice_date($news['publish_tm'], 'M') ?></div>
                        <div class="y"><?= nice_date($news['publish_tm'], 'Y') ?></div>
                    </div>
                </div>
                <h1><?= $news['title'] ?></h1>
            </div>
            <?php if ($news['subtitle']): ?>
                <div class="two clearfix">
                    <img
                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADgAAAAeCAYAAAEOdO2jAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyNpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChNYWNpbnRvc2gpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjk3NTlGRDRCOEVCODExRTY4QjU2OEQwMDYwRUYwRDhFIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjk3NTlGRDRDOEVCODExRTY4QjU2OEQwMDYwRUYwRDhFIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6OTc1OUZENDk4RUI4MTFFNjhCNTY4RDAwNjBFRjBEOEUiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6OTc1OUZENEE4RUI4MTFFNjhCNTY4RDAwNjBFRjBEOEUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz5PZCVGAAAEFUlEQVR42mK8y8/0nwEHYEFi3wNiJSAOg/KVkCUFofRqIAaZxsiERRJmzX+AAGLEZydIJyMQm0D5QlA7w5Q+/IUb6wKl3wPxKijNAJOcBaXToLr3gDgAAQSzkxHJITA2iDZGMrUDiCvAfvzwtxPZmyCF5UB8F8lHQlDNxlDvd0LlUTTCgDJUUyc0MM9CA7Yc6g9ldKcSBYDOBNMAAYQ3XPEBJiTbhKBOmgUVY4QGRgVUHsYWAsUHC5K/3mEx+B6UDkNKCu+RU9Y9HC4CiZ+BhiiKOia0xMQADfoOKPss1IYwaHyuRtfIiESfhfpFCRoFYUip9QwuG/9Dk9Z/qLNANodC/V+BnORB0UFSNMDiESCASE4ApAKYReheZEDKOf+JwESrvSfADMI4LZyJxK5AivJZaJEGi2GyUjgDWk6uIFKvMZa0eQ+pAIKBdHwWGkNzPUo0QIMPPT1jKzWUkfjv0Qo6nBamoWl6j8UQWCYxQXLgTDRfdyLFNSII6Z1KAQLQWkVHCMIwlHoswAp1BDoCjMAKrkBXcAQZQUfoCjICjiArKF5693iGityRL7hrkyZ5eXmb6W2r5XtmxxlOI5LTSEz1ruXbCjePAhZPgGqV/jcCkgcE+Sw1LClewvLexIFm5gdiZ+PyDnjUMrSSQQuD34BzD7SOSyRZUUL5TMIEObDWrn+c7bSAcWifsFdS5uRcgBZw9gMPvqYzipWvvhOtlXK3EPBdiAC+mGZYAEJLQg2BYwTVsf9nyLbWRu5AZXDcZABKRVsirqAggTqiwKAQ/KykvcL+Bpx7cWSV9RWFCT+2Yr+spRvoYQmAyGgkfJS30EunkMFJHqGi1NICXgJQT4FGEn4jnCvFZ7dUUqMAI0uwTerfrdE0u9tLgHKs4LZhGAYGRRZQRlBG0Ar2CM4IzgjtCM4IyQoZwV6hI0QraAT3IwOXK0VJsfMoSsAPI7HEI4/UiW8/D99tHMEn1lPvXmsGOBaECqldC/vMlFoPFagEdq9s8CCHv2nTQJcZS//3Emepy25pBykIKYAhfjBGUIYa6SkGwCkBcoWOtS8C+gSfkhlOAXwoJ/+X0OCPIMB2ILZmpS2iylhrTclEZkectwCADxC/whlPWTMxOEjv01a8TQE803u3Ya10mRo0iayGWMdNfDo6GKsALhG1FbVUapMQwCWzk1BnA1HxSmu1r2SwAYqGjEa1Qq0N5JiUDdyrpxq/KMrdKf2gGCCnnFV/EKJtqW5xrnBTsoDOnwtnGA4yeK8ZlpRaiNTAZyJnW1C4I3Q6HDfiJQHHS32G4sW94UOJEs6tOHtOmG31RNGZ6BSUOdkIzh9ihmeiuU8AlHzMalEjRNHTgVpzQ/YJSSf9Lq3tM9JwmR/80qL/RmzPfxUgim3JfgBSHkaJTbw7DAAAAABJRU5ErkJggg=="/>
                    <p>
                        <?= $news['subtitle'] ?>
                    </p>
                </div>
            <?php endif; ?>
            <div class="portrait clearfix">
                <img class="roundimg" src="<?= getImageUrl($news['column']['image']) ?>"/>
                <div class="title"><?= $news['column']['title'] ?></div>
                <div class="read-time">预计阅读时间<?= intval($news['duration'] / 60) ?>分钟</div>
            </div>
        </div>
        <img class="bf-make"
             src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAH4AAAAiCAYAAAHuaF2SAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyNpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChNYWNpbnRvc2gpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjk3NTlGRDRGOEVCODExRTY4QjU2OEQwMDYwRUYwRDhFIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjk3NTlGRDUwOEVCODExRTY4QjU2OEQwMDYwRUYwRDhFIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6OTc1OUZENEQ4RUI4MTFFNjhCNTY4RDAwNjBFRjBEOEUiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6OTc1OUZENEU4RUI4MTFFNjhCNTY4RDAwNjBFRjBEOEUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6+e5OaAAALZ0lEQVR42mK8y8/E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"/>
    </div>
    <!-- 大图背景的大字标题 结束 -->

    <div class="section-content">
        <!-- 文章 开始 -->
        <article class="article">

            <div class="content"<?php if ($page_type == 'share'): ?> style="padding-bottom: 20px;"<?php endif; ?>>
                <div class="paragraphs">
                    <?= $news['content'] ?>
                </div>
            </div>
        </article>
        <!-- 文章 结束 -->

        <div class="copyright-info">
            版权声明：本文系暴风体育独家原创稿件，未经许可不得转载。版权所有，侵权必究。
        </div>

    </div>
</div>

<?php $this->load->view('common/share_third', array('page_type' => $page_type)) ?>

<?php $this->load->view('common/list_news_action', array('list' => $news['columnRelated'], 'page_type' => $page_type, 'title' => '相关专栏', 'download_data' => $download_data, 'line_show' => true)) ?>


<?php $this->load->view('common/footer', $header_footer_data) ?>
