<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= HEAD_TITLE ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/static/bootstrap/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/static/bootstrap/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/static/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/static/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="/static/dist/css/themes/default/style.min.css">
    <link rel="stylesheet" href="/static/dist/css/bootstrap-editable.css">
    <link rel="stylesheet" href="/static/dist/css/bootstrap-switch.min.css">
    <link rel="stylesheet" href="/static/plugins/sweetalert-master/dist/sweetalert.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        th, td {
            vertical-align: middle !important;
            text-align: center;
            word-warp: break-word;
            word-break: break-all;
        }

        img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        .box-header .box-title {
            vertical-align: middle;

        }

        td .btn {
            margin-right: 4px;
        }

    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
<div class="wrapper">
    <div class="content-wrapper" style="margin-left: 0;">
        <section class="content-header">

        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <?php if (isset($postgame_post_list)): ?>
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title pull-left" style="line-height: 200%;">赛后热帖</h3>
                                <form method="post"
                                      action="<?= $add_posts_live_url . $match_id . '?redirect=' . current_url() ?>"
                                      class="pull-left form-group" style="margin-bottom: 0; width: 68%;">
                                    <input class="form-control pull-left" style="width: 66%; margin-left: 10px;"
                                           value=""
                                           placeholder="输入帖子ID"
                                           type="text" name="add_ids" required data-bvalidator-msg="请输入帖子ID!"/>

                                    <button class="btn bg-purple btn-flat pull-left" type="submit">加入</button>
                                </form>

                                <a href="<?= current_url() ?>"
                                   role="button" class="btn btn-success btn-flat pull-right"
                                >刷新</a>

                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th style="min-width: 86px;">帖子ID</th>
                                        <th style="min-width: 130px;">帖子主</th>
                                        <th style="width: 100%;">帖子内容</th>
                                        <th style="min-width: 86px;">帖子图</th>
                                        <th style="min-width: 86px;">回复数</th>
                                        <th style="min-width: 86px;">点赞数</th>
                                        <th style="min-width: 86px;">举报数</th>
                                        <th style="min-width: 56px;">排序</th>
                                        <th style="min-width: 86px;">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($postgame_post_list as $item): ?>
                                        <tr>
                                            <td><?= $item['id'] ?></td>
                                            <td><?= $item['user_name'] ?></td>
                                            <td><?= $item['content'] ?></td>
                                            <td>
                                                <?php if ($item['image']): ?>
                                                    <img src="<?= getImageUrl($item['image']) ?>">
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $item['comment_count'] ?></td>
                                            <td><?= $item['likes'] ?></td>
                                            <td><?= $item['reports'] ?></td>
                                            <td>
                                                <a class="editable"
                                                   data-validation="number"
                                                   data-type="text"
                                                   data-url="<?= $in_place_edit_live_url . 'match_post/' . $match_id ?>"
                                                   data-pk="<?= $item['match_post']['id'] ?>"
                                                   data-name="display_order"><?= $item['match_post']['display_order'] ?></a>
                                            </td>
                                            <td>
                                                <a href="<?= $out_live_url . 'match_post/' . $item['match_post']['id'] . '?redirect=' . current_url() ?>"
                                                   class="btn btn-xs btn-danger btn-del">
                                                    <i class="fa fa-times"></i>移出
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($game_box_thread_list)): ?>
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title pull-left" style="line-height: 200%;">赛中话题</h3>
                                <a class="pull-left btn bg-purple btn-flat" style="margin-left: 10px;"
                                   href="<?= $add_url . '?' . 'match_id=' . $match_id . '&host_id=' . $host_id . '&table=matching_thread&redirect=' . current_url() ?>">
                                    新建话题
                                </a>
                                <form method="post"
                                      action="<?= $add_threads_live_url . 'matching_thread/' . $match_id . '/' . $host_id . '?redirect=' . current_url() ?>"
                                      class="pull-left form-group" style="margin-bottom: 0; width: 68%;">
                                    <input class="form-control pull-left" style="width: 66%;" value=""
                                           placeholder="输入话题ID"
                                           type="text" name="add_ids" required data-bvalidator-msg="请输入话题ID!"/>

                                    <button class="btn bg-purple btn-flat pull-left" type="submit">加入</button>
                                </form>

                                <a href="<?= current_url() ?>"
                                   role="button" class="btn btn-success btn-flat pull-right"
                                >刷新</a>

                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th style="min-width: 86px;">话题ID</th>
                                        <th style="min-width: 130px;">话题主</th>
                                        <th style="width: 100%;">话题标题</th>
                                        <th style="min-width: 86px;">话题图</th>
                                        <th style="min-width: 86px;">帖子数</th>
                                        <th style="min-width: 56px;">排序</th>
                                        <th style="min-width: 180px;">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($game_box_thread_list as $item): ?>
                                        <tr>
                                            <td><?= $item['id'] ?></td>
                                            <td><?= $item['user_name'] ?></td>
                                            <td>
                                                <a class="editable"
                                                   data-validation-maxlen="128"
                                                   data-type="text"
                                                   data-url="<?= $in_place_edit_url ?>"
                                                   data-pk="<?= $item['id'] ?>"
                                                   data-name="title"><?= $item['title'] ?></a>
                                            </td>
                                            <td>
                                                <?php if ($item['icon']): ?>
                                                    <img src="<?= getImageUrl($item['icon']) ?>">
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $item['count'] ?></td>
                                            <td>
                                                <a class="editable"
                                                   data-validation="number"
                                                   data-type="text"
                                                   data-url="<?= $in_place_edit_live_url . 'matching_thread/' . $match_id ?>"
                                                   data-pk="<?= $item['match_thread']['id'] ?>"
                                                   data-name="display_order"><?= $item['match_thread']['display_order'] ?></a>
                                            </td>
                                            <td>
                                                <a target="_blank" href="<?= $post_list_url . $item['id'] ?>"
                                                   class="btn btn-info btn-xs">
                                                    <i class="fa fa-external-link"></i>帖子
                                                </a>
                                                <a href="<?= $edit_url . $item['id'] . '?redirect=' . current_url() ?>"
                                                   class="btn btn-primary btn-xs">
                                                    <i class="fa fa-pencil-square-o"></i>编辑
                                                </a>
                                                <a href="<?= $out_live_url . 'matching_thread/' . $item['match_thread']['id'] . '?redirect=' . current_url() ?>"
                                                   class="btn btn-xs btn-danger btn-del">
                                                    <i class="fa fa-times"></i>移出
                                                </a>
                                                <?php if ($item['match_thread']['used'] == 0): ?>
                                                    <a href="<?= $send_message_live_url . $item['id'] . '/' . $match_id . '/' . $host_id . '/' . $item['match_thread']['id'] . '?redirect=' . current_url() ?>"
                                                       class="btn btn-xs btn-danger btn-del">
                                                        <i class="fa fa-times"></i>发送
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title pull-left" style="line-height: 200%;">赛前话题</h3>
                            <a class="pull-left btn bg-purple btn-flat" style="margin-left: 10px;"
                               href="<?= $add_url . '?' . 'match_id=' . $match_id . '&host_id=' . $host_id . '&table=match_thread&redirect=' . current_url() ?>">
                                新建话题
                            </a>
                            <form method="post"
                                  action="<?= $add_threads_live_url . 'match_thread/' . $match_id . '/' . $host_id . '?redirect=' . current_url() ?>"
                                  class="pull-left form-group" style="margin-bottom: 0; width: 68%;">
                                <input class="form-control pull-left" style="width: 66%;" value=""
                                       placeholder="输入话题ID"
                                       type="text" name="add_ids" required data-bvalidator-msg="请输入话题ID!"/>

                                <button class="btn bg-purple btn-flat pull-left" type="submit">加入</button>
                            </form>

                            <a href="<?= current_url() ?>"
                               role="button" class="btn btn-success btn-flat pull-right"
                            >刷新</a>

                        </div>
                        <div class="box-body">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th style="min-width: 86px;">话题ID</th>
                                    <th style="min-width: 130px;">话题主</th>
                                    <th style="width: 100%;">话题标题</th>
                                    <th style="min-width: 86px;">话题图</th>
                                    <th style="min-width: 86px;">帖子数</th>
                                    <th style="min-width: 56px;">排序</th>
                                    <th style="min-width: 180px">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($pregame_thread_list as $item): ?>
                                    <tr>
                                        <td><?= $item['id'] ?></td>
                                        <td><?= $item['user_name'] ?></td>
                                        <td>
                                            <a class="editable"
                                               data-validation-maxlen="128"
                                               data-type="text"
                                               data-url="<?= $in_place_edit_url ?>"
                                               data-pk="<?= $item['id'] ?>"
                                               data-name="title"><?= $item['title'] ?></a>
                                        </td>
                                        <td>
                                            <?php if ($item['icon']): ?>
                                                <img src="<?= getImageUrl($item['icon']) ?>">
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $item['count'] ?></td>
                                        <td>
                                            <a class="editable"
                                               data-validation="number"
                                               data-type="text"
                                               data-url="<?= $in_place_edit_live_url . 'match_thread/' . $match_id ?>"
                                               data-pk="<?= $item['match_thread']['id'] ?>"
                                               data-name="display_order"><?= $item['match_thread']['display_order'] ?></a>
                                        </td>
                                        <td>
                                            <a target="_parent" href="<?= $post_list_url . $item['id'] ?>"
                                               class="btn btn-info btn-xs">
                                                <i class="fa fa-external-link"></i>帖子
                                            </a>
                                            <a href="<?= $edit_url . $item['id'] . '?redirect=' . current_url() ?>"
                                               class="btn btn-primary btn-xs">
                                                <i class="fa fa-pencil-square-o"></i>编辑
                                            </a>
                                            <a href="<?= $out_live_url . 'match_thread/' . $item['match_thread']['id'] . '?redirect=' . current_url() ?>"
                                               class="btn btn-xs btn-danger btn-del">
                                                <i class="fa fa-times"></i>移出
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
</div>
<!-- jQuery 2.1.4 -->
<script src="/static/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="/static/bootstrap/js/bootstrap.min.js"></script>
<!-- Jquery UI -->
<script src="/static/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/static/fileupload/scripts/jquery.ui.widget.js"></script>
<script src="/static/fileupload/scripts/jquery.fileupload.js"></script>
<!-- AdminLTE App -->
<script src="/static/dist/js/app.min.js"></script>
<script src="/static/dist/js/jstree.min.js"></script>
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script src="/static/dist/js/bootstrap-switch.min.js"></script>
<script src="/static/dist/js/bvalidator.js"></script>
<script src="/static/dist/js/bvalidator.bs3form.min.js"></script>
<script src="/static/dist/js/bs3form.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script>
    //验证
    $('form').bValidator({validateOn: 'blur'});

    //就地编辑
    $('.editable').editable({
        emptytext: '点这编辑',
        validate: function (value) {
            //验证是否为空，是空则提示
            if (!bValidator.validators.required(value)) {
                return bValidator.defaultOptions.messages.zh.required;
            }
            var tJQ = $(this);
            //验证是否为数字，不是则提示
            if (tJQ.data('validation') == 'number' && !bValidator.validators.number(value)) {
                return bValidator.defaultOptions.messages.zh.number;
            }
            //验证是否超出最大字符数,超出则提示
            var maxlen = Number(tJQ.data('validation-maxlen'));
            if (maxlen && !bValidator.validators.maxlen(value, maxlen)) {
                return bValidator.defaultOptions.messages.zh.maxlen.replace('{0}', maxlen);
            }
        },
        success: function (response, newValue) {
            //编辑成功后，如果是编辑排序项目，则重新加载页面
            if ($(this).data('name') == 'display_order') {
                location.reload(true);
            }
        }
    });
</script>
</body>
</html>
