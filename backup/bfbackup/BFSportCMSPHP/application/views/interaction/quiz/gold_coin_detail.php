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
    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
<div class="wrapper">
    <div class="content-wrapper" style="margin-left: 0;">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border" style="padding-top: 10px;padding-bottom: 4px;">
                            <div class="clearfix">
                                <h3 class="box-title pull-left" style="line-height: 2;">用户金豆</h3>
                                <a class="pull-right" style="line-height: 2.6;" href="<?= $list_url ?>">返回</a>
                            </div>
                        </div>
                        <div class="box-body">
                            <div style="font-weight: bold;">
                                <div class="pull-left">
                                    <div class="pull-left">用户ID</div>
                                    <div class="pull-left text-info" style="margin-left: 6px;"><?= $user_id ?></div>
                                </div>
                                <div class="pull-left" style="margin-left: 10px;">
                                    <div class="pull-left">用户名</div>
                                    <div class="pull-left text-info" style="margin-left: 6px;"><?= $user_name ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer with-border">
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th style="min-width: 100px;">时间</th>
                                        <th style="width: 20%;">流水ID</th>
                                        <th style="min-width: 86px;">类型</th>
                                        <th style="width: 40%;">说明</th>
                                        <th style="width: 20%;">变动金额</th>
                                        <th style="width: 20%;">金豆余额</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($list as $item): ?>
                                        <tr>
                                            <td><?= $item['created_at'] ?></td>
                                            <td><?= $item['id'] ?></td>
                                            <td><?= $type_title[$item['type']] ?></td>
                                            <td><?= $item['description'] ?></td>

                                            <?php if ($item['type'] == 0): ?>

                                                <td><?= '-' . $item['consume'] ?></td>

                                            <?php else: ?>

                                                <td><?= '+' . $item['consume'] ?></td>

                                            <?php endif; ?>


                                            <td><?= $item['balance'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>

                            </div>
                            <div class="box-footer with-border">
                                <div class="col-sm-4">
                                    <div class="pull-left" style="margin-top: 5px;">
                                        每页<?= $page_limit ?>条,共有<?= $page_total ?>条
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <?= $page ?>
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
<script src="/static/dist/js/bvalidator.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>

<script>

</script>
</body>
</html>
