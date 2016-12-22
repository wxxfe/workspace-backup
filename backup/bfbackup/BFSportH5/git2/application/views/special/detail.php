<?php $this->load->view('common/header', $header_footer_data) ?>
<?php if ($page_type == 'share'): ?>
    <?php $this->load->view('common/download', $download_data) ?>
<?php endif; ?>

<div class="section special-topic top">

    <!-- 大图背景的大字标题 开始 -->
    <div class="img-text">

        <?php if (isset($special['large_image']) && $special['large_image']): ?>
            <img class="bg" src="<?php echo getImageUrl($special['large_image']); ?>"/>
        <?php else: ?>
            <img class="bg" src="<?php echo (isset($special['image']) && $special['image']) ? getImageUrl($special['image']) : ''; ?>"/>
        <?php endif; ?>

        <div class="content">
            <h1><?php echo $special['title']; ?></h1>
            <?php if (isset($special['brief']) && $special['brief']): ?>
                <div class="info">
                    <img
                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADgAAAAeCAYAAAEOdO2jAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyNpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChNYWNpbnRvc2gpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkFDNzBCREE5OUQyRTExRTY5OUM4QkIyMjcwQjJGQUQ4IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkFDNzBCREFBOUQyRTExRTY5OUM4QkIyMjcwQjJGQUQ4Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6REFEMDI0Qzg5RDIzMTFFNjk5QzhCQjIyNzBCMkZBRDgiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6QUM3MEJEQTg5RDJFMTFFNjk5QzhCQjIyNzBCMkZBRDgiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6hc0F6AAAD9UlEQVR42mK8y8/0nwEHYEJi3wNiE1yS6UB8BpekMZIJYSAGQAAxErITZI8y1FhlmITSh7+MIMmzQHwXiN8DcTkuOwWhdCdMACCAYHYyAjGIFoKKz4RaIwg1FQXArEQG76CK06FsBkKBZ4wkdhdJ0z0k8dVQGuxZFijnLNS52AAjmjPBNEAA4Q1XQk5lhOIKpMARwuUCUMAgBw4oHlygflMmFDDIgTMLqnEWNHCUidX4DjlyifUjLIjfI4WiEpQdhuQirBrvIkmC/JsGZXdAo2oWlGZATnIkRQMsHgECCDmt0gTALMKWc8qhGYQYDMtpM6H4LlR8JjSCYOyZ9wSYZ+KysBPJp6uJcLwgNOz3IOWhPUhhvxoqn4bLQhDYjZYJ0WM9DEvJdw+aO9DZ76E0Iy4L06CJnAEp6aGD92gFZge0RC2HJlFkvApJDg5YkNizoMGALxsao7HPQn0Fcmgomtp70GgSRE/edE2lAAFordoaBmEoyA8M1MKQABI2CUNCLYCEIoFJYBKGhCFhlTAkMH68JpfLa9OQ8RJCQ0v7vu6uh+ntqJVnRscR7hDZuEtHwpkBjBl4ilzM7odcYk2zEQk4RfgCvlqCjJUmmWW8AB7XGCd20mm14MdJ91VAuIGxPTmYsmpPq9dSOoBXNWDyQ9rPkd+AZVplXKSYxhLeWH80C+x0FbDzeI3VsKA7haXIOIVNRjp96sBO+aGRCIyk7gVz78waLuhcSbXxINshxYZSh0Q+KaQeLMz1sRr2UmyNsPtI2j1sPoJGTrBujh34oOhQ95zy3clmMwmygf2GlFpM0l2evENt/II432ndU94Bz5ZU49hl6N9cerr9BCjHCmwUhmFgX+oCXaEdAUYII8AI/RFgBDoCGYGs0BWywq/QEf4RciVz2MaBIv3rLSHUtEpysX0+5+318N2G9faGBC4nWjuEWcOyY1pqX0sBvIC4/mlAa0UmBRb4Kxb4g8A3HkuMi1qp73zROkhmE2BQaCXQRgcg2Azc1YDUjvBe48meeTYzduHjo8G9T+XgiW32QAv/Sg/OEqY0B1soDBEKeXI0Vo9sIA/wdMhQH3C8UbSCK0SlPqpiLQsvRtYiOyfAvcIDK+d40vLPAthSeFYAKMNYBF3JxybDszvhAJOz5T5CZI00z1hCMueC+O+V52R8L3l2KzSzHjuhBLUU/uy9htwePUxF76LgYck+idSiMEcinf0h/NbKNdnaEv8SwEgTdoUnOTgBItANA7sllpZaWn4PMh/+5tGatdF3BW+tqe4vjTOE4QQq+5lQxCjrQWF3JXWwFXq7GQi/pv8yNhHo+3FBiccPFDXpJNXBfyO2v/8qQBLbqv0A3HZHuLX38E4AAAAASUVORK5CYII="/>
                    <p><?php echo $special['brief']; ?></p>
                </div>
            <?php endif; ?>
        </div>
        <img class="bf-tag"
             src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFYAAAAiCAYAAAGy4h62AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyNpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChNYWNpbnRvc2gpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkFDNzBCREFEOUQyRTExRTY5OUM4QkIyMjcwQjJGQUQ4IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkFDNzBCREFFOUQyRTExRTY5OUM4QkIyMjcwQjJGQUQ4Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6QUM3MEJEQUI5RDJFMTFFNjk5QzhCQjIyNzBCMkZBRDgiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6QUM3MEJEQUM5RDJFMTFFNjk5QzhCQjIyNzBCMkZBRDgiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4rbmyOAAAGy0lEQVR42mK8y8/E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"/>
    </div>
    <!-- 大图背景的大字标题 结束 -->

</div>

<!-- if 有比赛数据 -->
<div class="tab tab-slide">
    <div class="indicator-wrapper">
        <div class="indicator current"></div>
        <div class="indicator"></div>
        <div class="indicator"></div>
    </div>
    <div class="content clearfix" style="left: 0;">
        <div class="item-content current">
            <div class="tab-content">
                <div class="match-info">
                    <div class="time">
                        明天 19:30
                    </div>
                    <div class="both-sides">
                        <div class="side side1">
                            <img src="http://image.sports.baofeng.com/07500e1b0d33521f24697a3535e1e47c"/>
                            <span class="name">AC米兰</span>
                        </div>
                        <span class="vs">VS</span>
                        <div class="side side2">
                            <img src="http://image.sports.baofeng.com/07500e1b0d33521f24697a3535e1e47c"/>
                            <span class="name">尤文图斯</span>
                        </div>
                    </div>
                </div>
                <div class="match-info">
                    <div class="time">
                        明天 19:30
                    </div>
                    <div class="both-sides">
                        <div class="side side1">
                            <img src="http://image.sports.baofeng.com/07500e1b0d33521f24697a3535e1e47c"/>
                            <span class="name">AC米兰</span>
                        </div>
                        <span class="vs">VS</span>
                        <div class="side side2">
                            <img src="http://image.sports.baofeng.com/07500e1b0d33521f24697a3535e1e47c"/>
                            <span class="name">尤文图斯</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="item-content">
            <div class="tab-content">
                <div class="match-info">
                    <div class="time">
                        明天 19:30
                    </div>
                    <div class="both-sides">
                        <div class="side side1">
                            <img src="http://image.sports.baofeng.com/07500e1b0d33521f24697a3535e1e47c"/>
                            <span class="name">AC米兰</span>
                        </div>
                        <span class="vs">VS</span>
                        <div class="side side2">
                            <img src="http://image.sports.baofeng.com/07500e1b0d33521f24697a3535e1e47c"/>
                            <span class="name">尤文图斯</span>
                        </div>
                    </div>
                </div>
                <div class="match-info">
                    <div class="time">
                        明天 19:30
                    </div>
                    <div class="both-sides">
                        <div class="side side1">
                            <img src="http://image.sports.baofeng.com/07500e1b0d33521f24697a3535e1e47c"/>
                            <span class="name">AC米兰</span>
                        </div>
                        <span class="vs">VS</span>
                        <div class="side side2">
                            <img src="http://image.sports.baofeng.com/07500e1b0d33521f24697a3535e1e47c"/>
                            <span class="name">尤文图斯</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="item-content">
            <div class="tab-content">
                <div class="match-info">
                    <div class="time">
                        明天 19:30
                    </div>
                    <div class="both-sides">
                        <div class="side side1">
                            <img src="http://image.sports.baofeng.com/07500e1b0d33521f24697a3535e1e47c"/>
                            <span class="name">AC米兰</span>
                        </div>
                        <span class="vs">VS</span>
                        <div class="side side2">
                            <img src="http://image.sports.baofeng.com/07500e1b0d33521f24697a3535e1e47c"/>
                            <span class="name">尤文图斯</span>
                        </div>
                    </div>
                </div>
                <div class="match-info">
                    <div class="time">
                        明天 19:30
                    </div>
                    <div class="both-sides">
                        <div class="side side1">
                            <img src="http://image.sports.baofeng.com/07500e1b0d33521f24697a3535e1e47c"/>
                            <span class="name">AC米兰</span>
                        </div>
                        <span class="vs">VS</span>
                        <div class="side side2">
                            <img src="http://image.sports.baofeng.com/07500e1b0d33521f24697a3535e1e47c"/>
                            <span class="name">尤文图斯</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="divideline-height"></div>
<!-- 比赛if end -->

<?php if (isset($special['content']) && $special['content']): ?>

    <?php foreach ($special['content'] as $val): ?>
        <!-- 一个专题开始 -->

        <?php $this->load->view('common/list_news_action', array('list' => $val['data'], 'page_type' => $page_type, 'title' => $val['title'], 'download_data' => null)) ?>

        <!-- 一个专题结束 -->
    <?php endforeach ?>

    <div class="section">
        <div class="section-content">
            <?php $this->load->view('common/download_text', $download_data) ?>
        </div>
    </div>

<?php endif; ?>


<?php $this->load->view('common/footer', $header_footer_data) ?>
