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

        .box-header > .box-tools {
            position: relative;
            top: 1px;
        }

        #add-btn {
            margin-left: 10px;
        }

        .box-header .box-title {
            vertical-align: middle;

        }

        td .btn {
            margin-right: 4px;
        }

        .sort_selected a {
            color: #f90;
        }

        .sort_selected a:hover {
            color: #3c8dbc;
        }

    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
<div class="wrapper">
    <div class="content-wrapper" style="margin-left: 0;">
        <section class="content-header">
            <h1><?= $post_main_title ?>
                <small>Post</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/main/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <?php if ($post_type === 'thread'): ?>
                    <li><a href="<?= $list_url ?>"><i class="fa"></i> 话题列表</a></li>
                <?php endif; ?>
                <li class="active"><a href="<?= $post_list_condition_url ?>"><?= $post_main_title ?>列表</a></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <?php if ($post_type === 'check'): ?>
                                <a class="btn btn-warning
                                   <?php if ($post_check == 0): ?>
                                        disabled
                                   <?php endif; ?>
                                   "
                                   href="<?= $post_list_url . '0/' . $sort_selected[0] . '/' . $sort_selected[1] . '/' . $page_offset ?>"><i
                                        class="fa"></i> 未通过</a>
                                <a class="btn btn-success
                                   <?php if ($post_check == 1): ?>
                                        disabled
                                   <?php endif; ?>
                                   "
                                   href="<?= $post_list_url . '1/' . $sort_selected[0] . '/' . $sort_selected[1] . '/' . $page_offset ?>"><i
                                        class="fa"></i> 已通过</a>

                            <?php endif; ?>
                            <?php if (isset($post_title)): ?>
                                <div class="pull-left">
                                    <h3 class="box-title"
                                        style="line-height: 200%;"><?= $post_title ?></h3>
                                </div>
                            <?php endif; ?>
                            <div class="text-warning pull-right">
                                <?php if (isset($post_tip) AND $post_check == 0): ?>
                                    <div class="pull-left" style="margin-right: 10px; line-height: 250%">
                                        <span style="color: #f90;"><i class="fa fa-info"></i> 提示：</span>
                                        <span><?= $post_tip ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if (isset($post_btn)): ?>
                                    <?php if ($post_type === 'check'): ?>
                                        <?php if ($post_check == 0): ?>
                                            <form method="post" class="pull-left">
                                                <input type="hidden" name="post_ids" value="<?= $post_ids ?>"/>
                                                <button formaction="<?= $post_btn_url . '?redirect=' . current_url() ?>"
                                                        class="btn bg-purple btn-flat"
                                                        type="submit"><?= $post_btn ?></button>
                                            </form>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <a href="<?= $post_btn_url ?>"
                                           id="add-btn" class="btn btn-success btn-flat pull-left"><i
                                                class="glyphicon glyphicon-plus"></i><?= $post_btn ?></a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="box-body">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th style="min-width: 86px;">帖子ID</th>
                                    <th style="min-width: 130px;">帖子主</th>
                                    <th style="width: 100%;">帖子内容</th>
                                    <th style="min-width: 86px;">帖子图</th>

                                    <?php if ($sort_selected[0] === 'created_at'): ?>

                                        <th style="min-width: 90px;" class="sort_selected">

                                            <?php if ($sort_selected[1] === 'DESC'): ?>

                                                <a href="<?= $post_list_condition_url . '/created_at/ASC/' . $page_offset ?>"
                                                >创建时间<i class="fa fa-fw fa-sort-desc"></i></a>

                                            <?php else: ?>
                                                <a href="<?= $post_list_condition_url . '/created_at/DESC/' . $page_offset ?>"
                                                >创建时间<i class="fa fa-fw fa-sort-asc"></i></a>

                                            <?php endif; ?>

                                        </th>

                                    <?php else: ?>

                                        <th style="min-width: 90px;">
                                            <a href="<?= $post_list_condition_url . '/created_at/DESC/' . $page_offset ?>"
                                            >创建时间<i class="fa fa-fw fa-sort"></i></a>
                                        </th>

                                    <?php endif; ?>


                                    <?php if ($sort_selected[0] === 'comment_count'): ?>

                                        <th style="min-width: 86px;" class="sort_selected">

                                            <?php if ($sort_selected[1] === 'DESC'): ?>

                                                <a href="<?= $post_list_condition_url . '/comment_count/ASC/' . $page_offset ?>"
                                                >回复数<i class="fa fa-fw fa-sort-desc"></i></a>

                                            <?php else: ?>
                                                <a href="<?= $post_list_condition_url . '/comment_count/DESC/' . $page_offset ?>"
                                                >回复数<i class="fa fa-fw fa-sort-asc"></i></a>

                                            <?php endif; ?>

                                        </th>

                                    <?php else: ?>

                                        <th style="min-width: 86px;">
                                            <a href="<?= $post_list_condition_url . '/comment_count/DESC/' . $page_offset ?>"
                                            >回复数<i class="fa fa-fw fa-sort"></i></a>
                                        </th>

                                    <?php endif; ?>


                                    <?php if ($sort_selected[0] === 'likes'): ?>

                                        <th style="min-width: 86px;" class="sort_selected">

                                            <?php if ($sort_selected[1] === 'DESC'): ?>

                                                <a href="<?= $post_list_condition_url . '/likes/ASC/' . $page_offset ?>"
                                                >点赞数<i class="fa fa-fw fa-sort-desc"></i></a>

                                            <?php else: ?>
                                                <a href="<?= $post_list_condition_url . '/likes/DESC/' . $page_offset ?>"
                                                >点赞数<i class="fa fa-fw fa-sort-asc"></i></a>

                                            <?php endif; ?>

                                        </th>

                                    <?php else: ?>

                                        <th style="min-width: 86px;">
                                            <a href="<?= $post_list_condition_url . '/likes/DESC/' . $page_offset ?>"
                                            >点赞数<i class="fa fa-fw fa-sort"></i></a>
                                        </th>

                                    <?php endif; ?>


                                    <?php if ($sort_selected[0] === 'reports'): ?>

                                        <th style="min-width: 86px;" class="sort_selected">

                                            <?php if ($sort_selected[1] === 'DESC'): ?>

                                                <a href="<?= $post_list_condition_url . '/reports/ASC/' . $page_offset ?>"
                                                >举报数<i class="fa fa-fw fa-sort-desc"></i></a>

                                            <?php else: ?>
                                                <a href="<?= $post_list_condition_url . '/reports/DESC/' . $page_offset ?>"
                                                >举报数<i class="fa fa-fw fa-sort-asc"></i></a>

                                            <?php endif; ?>

                                        </th>

                                    <?php else: ?>

                                        <th style="min-width: 86px;">
                                            <a href="<?= $post_list_condition_url . '/reports/DESC/' . $page_offset ?>"
                                            >举报数<i class="fa fa-fw fa-sort"></i></a>
                                        </th>

                                    <?php endif; ?>


                                    <th style="min-width: 86px">上下线</th>
                                    <th style="min-width: 86px">热帖</th>
                                    <th style="min-width: 160px">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($list as $index => $item): ?>
                                    <tr>
                                        <td><?= $item['id'] ?></td>
                                        <td><?= $item['user_name'] ?></td>
                                        <td><?= $item['content'] ?></td>
                                        <td>
                                            <?php if ($item['image']): ?>
                                                <img src="<?= getImageUrl($item['image']) ?>">
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $item['created_at'] ?></td>
                                        <td><?= $item['comment_count'] ?></td>
                                        <td><?= $item['likes'] ?></td>
                                        <td><?= $item['reports'] ?></td>
                                        <td>
                                            <input data-pk="<?= $item['id'] ?>" name="visible"
                                                   class="release" type="checkbox"
                                                <?php if ($item['visible']): ?>
                                                    checked
                                                <?php endif; ?>
                                            >
                                        </td>
                                        <td>
                                            <input data-pk="<?= $item['id'] ?>" name="featured"
                                                   class="release" type="checkbox"
                                                <?php if ($item['featured']): ?>
                                                    checked
                                                <?php endif; ?>
                                            >
                                        </td>
                                        <td>
                                            <a href="<?= $post_del_url . $item['id'] . '/' . $item['thread_id'] ?>"
                                               class="btn btn-xs btn-danger btn-del">
                                                <i class="fa fa-times"></i>删除
                                            </a>
                                            <a href="" class="btn btn-info btn-xs">
                                                <i class="fa fa-external-link"></i>封禁用户
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="box-footer with-border">
                            <div class="col-sm-5">
                                <div class="page_status" role="status" aria-live="polite">
                                    每页<?= $page_limit ?>条,共有<?= $page_total ?>条
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <?= $page ?>
                            </div>
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
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script>

    //切换开关 上下线
    $("input[name='visible']").bootstrapSwitch({
        size: 'mini',
        onText: '上线',
        offText: '下线',
        onSwitchChange: function (event, state) {
            $.ajax({
                method: "POST",
                url: "<?= $in_place_edit_url ?>",
                data: {name: "visible", value: parseInt(Number(state)), pk: $(this).data('pk')}
            });
        }
    });

    //切换开关 热帖
    $("input[name='featured']").bootstrapSwitch({
        size: 'mini',
        onText: '是',
        offText: '否',
        onSwitchChange: function (event, state) {
            $.ajax({
                method: "POST",
                url: "<?= $in_place_edit_url ?>",
                data: {name: "featured", value: parseInt(Number(state)), pk: $(this).data('pk')}
            });
        }
    });

    //删除操作
    $('.btn-del').on('click', function (e) {
        e.preventDefault();
        var delURL = $(this).prop('href');
        swal(
            {
                title: "你确定要删除吗?",
                text: "删除后不可恢复，请谨慎操作!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dd4b39",
                confirmButtonText: "确定删除",
                cancelButtonText: "取消",
                closeOnConfirm: false
            },
            function () {
                $.ajax({
                    method: "POST",
                    url: delURL
                }).done(function (data) {
                    //删除后重新加载页面，因为条数减少，需要重新计算分页
                    swal(
                        {
                            title: "删除成功!",
                            text: "等待重新加载页面!",
                            animation: false,
                            showConfirmButton: false,
                            allowOutsideClick: true
                        }
                    );
                    location.reload(true);
                });
            }
        );
    });
</script>
</body>
</html>
