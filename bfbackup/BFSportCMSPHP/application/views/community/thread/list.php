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

        #add-btn {
            margin-left: 10px;
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
            <h1><?= $module_title ?>
                <small><?= $module_title_en ?></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/main/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><a href="<?= $list_url ?>"><?= $module_title ?>列表</a></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <div class="pull-left">
                                <h3 class="box-title" style="line-height: 200%;"><?= $module_title ?>列表</h3>
                            </div>

                            <div class="text-warning pull-right">
                                <div class="pull-left" style="line-height: 250%">
                                    <span style="color: #f90;"><i class="fa fa-info"></i> 提示：</span>
                                    <span>下划虚线项目可点击编辑</span>
                                </div>
                                <a href="<?= $add_url ?>"
                                   id="add-btn" role="button" class="btn btn-success btn-flat pull-left"><i
                                        class="glyphicon glyphicon-plus"></i>添加<?= $module_title ?></a>
                            </div>
                        </div>
                        <div class="box-body">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th style="min-width: 86px;"><?= $module_title ?>ID</th>
                                    <th style="min-width: 130px;"><?= $module_title ?>主</th>
                                    <th style="width: 100%;"><?= $module_title ?>标题</th>
                                    <th style="min-width: 86px;"><?= $module_title ?>图</th>
                                    <th style="min-width: 86px;">帖子数</th>
                                    <th style="min-width: 86px;">创建时间</th>
                                    <th style="min-width: 86px;">上下线</th>
                                    <th style="min-width: 180px;">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($list as $index => $item): ?>
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
                                        <td><?= $item['created_at'] ?></td>
                                        <td>
                                            <input data-pk="<?= $item['id'] ?>" name="visible"
                                                   class="release" type="checkbox"
                                                <?php if ($item['visible']): ?>
                                                    checked
                                                <?php endif; ?>
                                            >
                                        </td>
                                        <td>
                                            <a href="<?= $post_list_url . $item['id'] ?>" class="btn btn-info btn-xs">
                                                <i class="fa fa-external-link"></i>帖子
                                            </a>
                                            <a href="<?= $edit_url . $item['id'] . '?redirect=' . current_url() ?>"
                                               class="btn btn-primary btn-xs">
                                                <i class="fa fa-pencil-square-o"></i>编辑
                                            </a>
                                            <a href="<?= $del_url . $item['id'] ?>"
                                               class="btn btn-xs btn-danger btn-del">
                                                <i class="fa fa-times"></i>删除
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

    //就地编辑
    $('.editable').editable({
        emptytext: '点这编辑',
        validate: function (value) {
            //验证是否为空，是空则提示
            if (!bValidator.validators.required(value)) {
                return bValidator.defaultOptions.messages.zh.required;
            }
            var tJQ = $(this);
            //验证是否超出最大字符数,超出则提示
            var maxlen = Number(tJQ.data('validate-maxlen'));
            if (maxlen && !bValidator.validators.maxlen(value, maxlen)) {
                return bValidator.defaultOptions.messages.zh.maxlen.replace('{0}', maxlen);
            }
        }
    });

    //切换开关
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
