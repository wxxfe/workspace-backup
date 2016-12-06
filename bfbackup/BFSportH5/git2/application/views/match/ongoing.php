<?php $this->load->view('common/header', $header_footer_data) ?>
<?php if ($page_type == 'share'): ?>
    <?php $this->load->view('common/download', $download_data) ?>
<?php endif; ?>

<div class="container">
    <!-- 视频 开始 -->
    <?php if ($match['live_stream']): ?>

        <?php $t_live_stream = $match['live_stream'][0]; ?>

        <?php if ($t_live_stream['play_code2']): ?>

            <div class="top-video">
                <div class="embed-video">
                    <?php
                    $t_video_json = json_encode(
                        array(
                            "id" => $t_live_stream['match_id']
                            , "play_code" => array()
                            , "play_code2" => $t_live_stream['play_code2']
                        )
                    )
                    ?>
                    <a href="javascript:void(0)" data-video='<?= $t_video_json ?>'><span class="embed-video-play"></span></a>
                </div>
            </div>

        <?php elseif ($t_live_stream['play_url']): ?>

            <div class="top-video" style="width: 100%; min-height: 5rem;">
                <script>
                    var vodparam = "<?=$t_live_stream['play_url']?>";
                    document.write('<script type="text/javascript" src="http://live.baofengcloud.com/html/script/bfcloud.js?v=2"></' + 'script>');
                </script>
                <script>cloudsdk.initvideo(videoareaname, {
                        "src": vodparam,
                        "id": "cloudsdk",
                        "isautosize": "1"
                    });</script>
            </div>

        <?php endif; ?>
    <?php endif; ?>
    <!-- 视频 结束 -->

    <!-- 比赛条 开始 -->
    <?php if ($match['type'] != 'various'): ?>
        <div class="match-bar">
            <div class="row">
                <div class="col-4 match-footnote">
                    <?php echo $match['show_data']['vs_1']['name'] ?>
                    <img class="badge" src="<?php echo getImageUrl($match['show_data']['vs_1']['badge']) ?>" alt="">
                </div>
                <div class="col-4 match-footnote-score">
                    <?php echo $match['show_data']['vs_1']['extra_score'] ?>
                    <em>:</em><?php echo $match['show_data']['vs_2']['extra_score'] ?>
                </div>
                <div class="col-4 match-footnote">
                    <img class="badge" src="<?php echo getImageUrl($match['show_data']['vs_2']['badge']) ?>" alt="">
                    <?php echo $match['show_data']['vs_2']['name'] ?>
                </div>
            </div>
            <div class="progress">
                <div class="home-team bar" style="<?php echo 'width:' . $supports_p[0] . '%' ?>"></div>
                <div class="visiting-team bar" style="<?php echo 'width:' . $supports_p[1] . '%' ?>"></div>
            </div>
        </div>
    <?php endif; ?>
    <!-- 比赛条 结束 -->


    <?php if ($chathistory): ?>
        <!-- 聊天室 开始 -->
        <div class="chatroom">
            <div class="message-history">
                <ul>
                    <?php foreach ($chathistory as $val): ?>

                        <!-- 右边是自己，现在H5的聊天记录不能判断是否有自己，所以都在左边 -->
                        <?php
                        if (!(isset($val['data']['user']) && $val['data']['user'])) {
                            continue;
                        }
                        $user_name = $val['data']['user']['name'];
                        $user_avatar = $val['data']['user']['avatar'];

                        $content_1 = '';
                        $content_2 = '';

                        //普通文本
                        if (isset($val['data']['text']) && $val['data']['text']) {
                            $content_1 = $val['data']['text'];
                        }
                        //表情
                        if (isset($val['data']['image']) && $val['data']['image']) {
                            if ($content_1) {
                                $content_1 .= '<br>还';
                            }
                            $content_1 .= '发送了一个表情';
                        }

                        //主持人
                        $host_icon = '';
                        if (isset($val['type']) && $val['type'] == 104) {
                            $host_icon = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAYCAYAAAD6S912AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyNpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChNYWNpbnRvc2gpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjdENzhCQjQzODk0NTExRTZBREEzOTI3RTk4OEE4RjBEIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjdENzhCQjQ0ODk0NTExRTZBREEzOTI3RTk4OEE4RjBEIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MEVBODQ2NDI4OTQwMTFFNkFEQTM5MjdFOTg4QThGMEQiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6N0Q3OEJCNDI4OTQ1MTFFNkFEQTM5MjdFOTg4QThGMEQiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4Pr+ujAAACbElEQVR42pyVT0hVQRTGZ64PAyFKKkSCXCloLSJoUcuEJ4lQuQiKDLUIghbtIi0KEjeCO1dhUoSLqFwIhUJQLmoTFNQi++PCgmhTuchKkel33pz73vXqvPtsHt87586c+e45M+ecaz9tiUz54Qr/lp8rSJlxmxB/Ywu/5u2y2JLjIJgE38EflZM6XxyVEvbjwQy+deBHLT4tgFo862CNeXN5I4Tn2DwAJKYboB5sJfR6XjKo4Q4yd6oQfsYZ1uDVPGbb0HvRx/wpFs9StNPot1G+8bwry8NDnsy9Rh8L2NyB6BWow9u2LMIm8QPDp1ZvM/YulQUzGnpzzpYnjJzfnltN4NPElqirVP7OxfkTGLO6Oc9mW0zK1UNuK6/6u8gHEESd7iF0MxxI+z4sm8AXXvosKkeHeW/iRi8i3oPDQF50ANwFA5A61i+gL9m5cNrsxvAtZAsYP1eitfEau8j6ebltOdMoXMHujIY0jmiXlEDOl+5YqsWMgL1CllUp1aDLp4y5pbc5DW4qnZDtBxLmh+TG0C0fwYPtmtAvda4dXMd6BXkyTVQkXN9Be1ZTeFTzrRE5jqyC8AoLj0JHRS2vSe0Gpuek37GyEyLx6AVoYf4+8ngyH0s17SspFzfOxHX0AMnPhxj+ZOIBOmTmDegOJHewH0a6SfaN8ncVHAM/eO5E/srqdVGq2PMa8lewWS4BucL6CfSPlXTiKNU1WvUF0kQnvOr6kFOVfieEcAe4BBrZXK1taFnP8R4Ycus0rfQnzMQfMUpvAoKjCZslIM97WJZKWNzAh0wSW7pIoRb2QfyZuWvgCXhs/mP8E2AAeSy3yznYGbEAAAAASUVORK5CYII=';
                        }
                        $header_footer_data_type = '';
                        $header_footer_data_url = '';
                        $header_footer_data_info = '';
                        $icon = '';
                        $poster = '';
                        $message_class = '';
                        $go_txt = '';

                        if (isset($val['data']['payload']) && $val['data']['payload']) {
                            $payload = $val['data']['payload'];
                            // 0 - 图文 1 - 战报 2 - 互动事件 3 - 竞猜 4 - 话题 5 - 新闻资讯
                            switch ($payload['type']) {
                                case 0:
                                    $content_1 = '[图文] ';
                                    break;
                                case 1:
                                    $content_1 = '[战报] ';
                                    break;
                                case 2:
                                    $content_1 = '[互动事件] ';
                                    break;
                                case 3:
                                    $content_1 = '[竞猜] ';
                                    break;
                                case 4:
                                    $content_1 = '[话题]<br>';
                                    $header_footer_data_type = 'topic';
                                    $header_footer_data_url = base_url('/' . $header_footer_data_type . '/detail/' . $payload['id']);
                                    $header_footer_data_info = getShareOrAppJson($header_footer_data_type, $payload, $page_type);
                                    $message_class = 'message-icon';
                                    $go_txt = '点击参与话题';
                                    $icon = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAF4AAABeCAYAAACq0qNuAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyNpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChNYWNpbnRvc2gpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkVBQTAzRjc4N0IwNDExRTY4QjNCODFDMDhCOTEwNzg5IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkVBQTAzRjc5N0IwNDExRTY4QjNCODFDMDhCOTEwNzg5Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MjcxODQyQUQ3QjAzMTFFNjhCM0I4MUMwOEI5MTA3ODkiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MjcxODQyQUU3QjAzMTFFNjhCM0I4MUMwOEI5MTA3ODkiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz5AMEGpAAAX3ElEQVR42txdCXyU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';
                                    break;
                                case 5:
                                    $content_1 = '[新闻] ';
                                    $header_footer_data_type = 'news';
                                    $header_footer_data_url = base_url('/' . $header_footer_data_type . '/detail/' . $payload['news_id']);
                                    $payload['id'] = $payload['news_id'];
                                    $header_footer_data_info = getShareOrAppJson($header_footer_data_type, $payload, $page_type);
                                    $go_txt = '点击查看新闻';
                                    break;
                            }

                            if (isset($payload['image']) && $payload['image']) {
                                $poster = getImageUrl($payload['image']);
                            }
                            if (isset($payload['text']) && $payload['text']) {
                                $content_1 .= $payload['text'];
                            }
                            if (isset($payload['title']) && $payload['title']) {
                                $content_1 .= $payload['title'];
                            }
                        }

                        ?>

                        <li class="message-item left">

                            <div class="portrait withname right">
                                <div class="img-wrapper">
                                    <img class="roundimg" src="<?php echo $user_avatar; ?>"/>
                                </div>
                                <div class="wrapper">
                                    <h2 class="title"><?php echo $user_name; ?></h2>
                                    <?php if ($host_icon): ?>
                                        <img class="host-icon" src="<?php echo $host_icon; ?>"/>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="message message-host <?php echo $message_class; ?>">
                                <?php if ($poster): ?>
                                    <div class="poster">
                                        <img src="<?php echo $poster; ?>" alt="">
                                    </div>
                                <?php endif; ?>

                                <?php if ($icon): ?>
                                    <div class="content-icon">
                                        <img class="icon" src="<?php echo $icon; ?>">
                                        <p class="content"><?php echo $content_1; ?></p>
                                    </div>
                                <?php else: ?>
                                    <p class="content"><?php echo $content_1; ?></p>
                                <?php endif; ?>

                                <?php if ($go_txt): ?>
                                    <a class="go" data-url='<?php echo $header_footer_data_url; ?>' data-info='<?php echo $header_footer_data_info; ?>' href="javascript:void(0)">
                                        <?php echo $go_txt; ?>
                                    </a>
                                <?php endif; ?>

                            </div>
                        </li>

                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <!-- 聊天室 结束 -->
    <?php else: ?>
        <?php $this->load->view('common/no_data', array('img_class' => 'sofa')) ?>
    <?php endif; ?>
</div>
<?php $this->load->view('common/footer', $header_footer_data) ?>
