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
                                <h3 class="box-title pull-left" style="line-height: 2;">竞猜明细</h3>
                                <a class="pull-right" style="line-height: 2.6;" href="<?= $list_url ?>">返回</a>
                            </div>
                        </div>
                        <div class="box-body">
                            <div style="font-weight: bold;">
                                <div class="row">
                                    <div class="col-xs-2" style="text-align: right;">比赛ID</div>
                                    <div class="col-xs-10 text-info" style="text-align: left;"><?= $match_id ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-2" style="text-align: right;">竞猜ID</div>
                                    <div class="col-xs-10 text-info" style="text-align: left;"><?= $id ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-2" style="text-align: right;">竞猜状态</div>
                                    <div class="col-xs-10 text-info"
                                         style="text-align: left;"><?= $status_title[$status] ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-2" style="text-align: right;">参与人数</div>
                                    <div class="col-xs-10 text-info"
                                         style="text-align: left;"><?= $statistic_turnouts ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-2" style="text-align: right;">奖金池</div>
                                    <div class="col-xs-10 text-info"
                                         style="text-align: left;"><?= $statistic_bonus_pool ?></div>
                                </div>
                                <?php if ($status === 'end'): ?>
                                    <div class="row">
                                        <div class="col-xs-2" style="text-align: right;">中奖赔率</div>
                                        <div class="col-xs-10 text-info"
                                             style="text-align: left;"><?= $statistic_odds ?></div>
                                    </div>
                                <?php endif; ?>
                                <div class="row">
                                    <div class="col-xs-2" style="text-align: right;">竞猜题目</div>
                                    <div class="col-xs-10 text-info" style="text-align: left;"><?= $question ?></div>
                                </div>

                                <?php foreach ($option as $o_v): ?>
                                    <div class="row">
                                        <div class="col-xs-2" style="text-align: right;">答案选项<?= $o_v['id'] ?></div>
                                        <div class="col-xs-10 clearfix" style="text-align: left;">
                                            <div class="pull-left text-info"><?= $o_v['name'] ?></div>
                                            <div class="pull-left" style="margin-left: 20px;">
                                                选择人数 <span class="text-info"><?= $o_v['num'] ?></span>
                                            </div>
                                            <div class="pull-left" style="margin-left: 20px;">
                                                累计投注 <span class="text-info"><?= $o_v['bet'] ?></span>
                                            </div>
                                            <?php if ($o_v['id'] == $answer): ?>
                                                <div class="pull-left text-info" style="margin-left: 20px;">
                                                    中奖答案选项
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            </div>

                        </div>

                        <div class="box-footer with-border">
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th style="min-width: 120px">用户ID</th>
                                        <th style="width: 50%">用户名</th>
                                        <th style="width: 50%">答案选项</th>
                                        <th style="min-width: 120px">累计投注</th>
                                        <th style="min-width: 120px">中奖金额</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($list as $item): ?>
                                        <tr>
                                            <td><?= $item['user_id'] ?></td>
                                            <td><?= $item['user_name'] ?></td>
                                            <td><?= $item['answer'] . ' ' . $item['answer_name'] ?></td>
                                            <td><?= $item['bet_count'] ?></td>
                                            <td><?= $item['win_count'] ?></td>
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
