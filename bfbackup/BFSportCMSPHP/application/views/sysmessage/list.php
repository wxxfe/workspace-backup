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
    <link rel="stylesheet" href="/static/fileupload/css/fileupload.css">
    <link rel="stylesheet" href="/static/fileupload/css/fileupload-ui.css">
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
    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
<div class="wrapper">
    <div class="content-wrapper" style="margin-left: 0;">
        <section class="content-header">
            <h1>系统消息</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">系统消息</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12" id="user-list">
                    <div class="box">
                        <div class="box-body">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-success btn-flat" role="button"
                                   href="<?= base_url('/SysMessage/add/') ?>"><i class="fa fa-plus"></i> 添加系统消息</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" id="pending-video-list">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">系统消息列表</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th style="min-width: 86px;">消息id</th>
                                    <th style="width: 50%;">消息内容</th>
                                    <th style="min-width: 100px;">消息图</th>
                                    <th style="width: 50%;">发送用户</th>
                                    <th style="min-width: 86px;">发送时间</th>
                                    <th style="min-width: 86px;">状态</th>
                                    <th style="min-width: 150px;">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($list as $sysmsg): ?>
                                    <tr>
                                        <td><?= $sysmsg['id'] ?></td>
                                        <td><?= $sysmsg['content'] ?></td>
                                        <td>
                                            <?php if (!empty($sysmsg['image'])): ?>
                                                <img style="width:100px;" src="<?= getImageUrl($sysmsg['image']) ?>"/>
                                            <?php else: ?>
                                                <span>暂无图片</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $sysmsg['user_ids'] ?></td>
                                        <td><?= $sysmsg['send_at'] ?></td>
                                        <td>
                                            <?php if ($sysmsg['send_status'] == 0): ?>
                                                <span>待发送</span>
                                            <?php else: ?>
                                                <span>已发送</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($sysmsg['send_status'] == 0): ?>
                                                <a class="btn btn-flat btn-danger btn-xs btn-send" role="button"
                                                   href="javascript:void(0);"
                                                   data-cid="<?= $sysmsg['id'] ?>">发送</a>
                                                <a href="<?php echo site_url('SysMessage/edit/') . $sysmsg['id'] . '?redirect=' . current_url() ?>">
                                                    <button type="button" class="btn btn-info btn-xs">编辑</button>
                                                </a>
                                                <a class="btn btn-flat btn-danger btn-xs btn-remove" role="button"
                                                   href="javascript:void(0);" data-cid="<?= $sysmsg['id'] ?>"><i
                                                        class="fa fa-remove"></i> 删除</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box box-solid">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-10 text-right">
                                    <table width="100%">
                                        <tr>
                                            <td><?= $page ?></td>
                                        </tr>
                                    </table>
                                </div>
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
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script>
    //--send alert-------------------------------------------------------------
    var alertConfig = {
        title: "你确定要发送吗?",
        text: "发送后不可恢复，请谨慎操作!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dd4b39",
        confirmButtonText: "确定发送",
        cancelButtonText: "取消",
        closeOnConfirm: false,
        html: true
    };
    $('.btn-send').on('click', function () {
        var sysmsgId = $(this).data('cid');

        swal(alertConfig, function () {
            $.post('<?=base_url("/SysMessage/send")?>', {id: sysmsgId}, function (d) {
                if (d == 'success') {
                    swal({title: "发送成功!", text: "推送内容已被发送！", type: "success"}, function () {
                        window.location.reload();
                    });
                } else {
                    swal("发送失败!", "发送失败，请重试！", "error");
                }
            });
        });
    });

    //--remove alert-------------------------------------------------------------
    var removeAlertConfig = {
        title: "你确定要删除吗?",
        text: "删除后不可恢复，请谨慎操作!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dd4b39",
        confirmButtonText: "确定删除",
        cancelButtonText: "取消",
        closeOnConfirm: false
    };
    $('.btn-remove').on('click', function () {
        var sysmsgId = $(this).data('cid');
        var tr = $(this).parents('tr');
        var target = this;

        swal(removeAlertConfig, function () {
            $.post('<?=base_url("/SysMessage/remove")?>', {id: sysmsgId}, function (d) {
                if (d == 'success') {
                    swal({title: "删除成功!", text: "已被删除！", type: "success"}, function () {
                        tr.fadeOut('fast', function () {
                            $(this).remove();
                        });
                    });
                } else {
                    swal("删除失败!", "删除失败，请重试！", "error");
                }
            });
        });
    });
</script>
</body>
</html>
