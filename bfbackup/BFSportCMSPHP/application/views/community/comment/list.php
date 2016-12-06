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
                <small>Comment</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/main/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <?php if ($post_type === 'thread'): ?>
                    <li><a href="<?= $list_url ?>"><i class="fa"></i> 话题列表</a></li>
                <?php endif; ?>
                <li class="active"><?= $post_main_title ?>列表</li>
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
                                <h3 class="box-title"><?= $post_title ?></h3>
                            <?php endif; ?>
                            <?php if (!$check) {?>
                            <div class="box-tools text-warning pull-right">
                                <?php if (isset($post_tip)): ?>
                                    <span style="color: #f90; line-height: 30px;"><i class="fa fa-info"></i> 提示：</span>
                                    <span><?= $post_tip ?></span>
                                <?php endif; ?>
                                <?php if (isset($post_btn)): ?>
                                    <a id="js_check_ok" role="button" href="javascript:void(0);"
                                       class="btn bg-purple btn-flat pull-right"><i
                                            class="glyphicon glyphicon-plus"></i><?= $post_btn ?></a>
                                <?php endif; ?>
                            </div>
                            <?php }?>
                        </div>
                        <div class="box-body">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th style="min-width: 86px">回复帖子ID</th>
                                    <th style="min-width: 130px">回复用户名</th>
                                    <th style="width: 100%">回复内容</th>

                                    <?php if ($sort_selected[0] === 'created_at'): ?>

                                        <th style="min-width: 90px;" class="sort_selected">

                                            <?php if ($sort_selected[1] === 'DESC'): ?>

                                                <a href="<?= $post_list_condition_url . '/created_at/ASC/' . $page_offset ?>"
                                                >回复时间<i class="fa fa-fw fa-sort-desc"></i></a>

                                            <?php else: ?>
                                                <a href="<?= $post_list_condition_url . '/created_at/DESC/' . $page_offset ?>"
                                                >回复时间<i class="fa fa-fw fa-sort-asc"></i></a>

                                            <?php endif; ?>

                                        </th>

                                    <?php else: ?>

                                        <th style="min-width: 90px;">
                                            <a href="<?= $post_list_condition_url . '/created_at/DESC/' . $page_offset ?>"
                                            >回复时间<i class="fa fa-fw fa-sort"></i></a>
                                        </th>

                                    <?php endif; ?>

                                    <th style="min-width: 160px">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($list as $index => $item): ?>
                                    <tr>
                                        <td><?= $item['id'] ?></td>
                                        <td><?= $item['user_name'] ?></td>
                                        <td><?= $item['content'] ?></td>
                                        <td><?= $item['created_at'] ?></td>
                                        <td>
                                            <a href="<?= $post_del_url . $item['id'] ?>"
                                               class="btn btn-xs btn-danger btn-del">
                                                <i class="fa fa-times"></i>删除
                                            </a>
                                            <a href="javascript:void(0);" class="btn btn-info btn-xs">
                                                <i class="fa fa-external-link"></i>封禁用户
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            <input type="hidden" class="js_ids" value="<?=$ids ?>">
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
    $('#js_check_ok').click(function(){
    	var ids = $('.js_ids').val();
    	if (!ids.length) {
    	    return false;
    	}
    
        swal(
                {
                    title: "你确定要通过吗?",
                    text: "通过后不可批量操作，请谨慎操作!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#dd4b39",
                    confirmButtonText: "确定通过",
                    cancelButtonText: "取消",
                    closeOnConfirm: false
                },
                function () {
                    $.ajax({
                        method: "POST",
                        url: "<?=base_url('/community/comment/checkok/') ?>",
                        data: { 'ids':ids }
                    }).done(function (data) {
                        swal(
                            {
                                title: "操作成功!",
                                animation: false,
                                showConfirmButton: false,
                                allowOutsideClick: true
                            }
                        );
                        window.location.href = "<?= $post_list_condition_url . '/' . $sort_selected[0] . '/' . $sort_selected[1] . '/' . $page_offset ?>";
                    });
                }
            );
    });

                                   
    $('.btn-del').on('click', function (e) {
        e.preventDefault();
        var url = $(this).prop('href');
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
                    url: url
                }).done(function (data) {
                    swal(
                        {
                            title: "删除成功!",
                            animation: false,
                            showConfirmButton: false,
                            allowOutsideClick: true
                        }
                    );
                    window.location.href = "<?= $post_list_condition_url . '/' . $sort_selected[0] . '/' . $sort_selected[1] . '/' . $page_offset ?>";
                });
            }
        );
    });
</script>
</body>
</html>
