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
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border" style="padding-top: 10px;padding-bottom: 4px;">
                            <div class="pull-left" style="width: 130px;">
                                <h3 class="box-title" style="line-height: 2;">已发布竞猜列表</h3>
                            </div>

                            <form id="search-form" method="get" class="pull-left"
                                  style="width: 500px; margin-left: 20px;">
                                <label for="match_id" class="pull-left" style="line-height: 2.6;">比赛ID</label>
                                <input class="pull-left form-control" style="width: 100px; margin: 0 10px;"
                                       placeholder="请输入ID" type="text"
                                       id="match_id" name="match_id"
                                       <?php if (isset($match_id)): ?>value="<?= $match_id ?>"<?php endif; ?> />
                                <label class="pull-left" style="line-height: 2.6;margin: 0 10px 0 0;">或</label>
                                <label for="subject_id" class="pull-left" style="line-height: 2.6;">竞猜ID</label>
                                <input class="pull-left form-control" style="width: 100px; margin: 0 10px;"
                                       placeholder="请输入ID" type="text"
                                       id="subject_id" name="subject_id"
                                       <?php if (isset($subject_id)): ?>value="<?= $subject_id ?>"<?php endif; ?> />
                                <button class="pull-left btn bg-purple btn-flat" type="submit"><i
                                        class="fa fa-search"></i> 搜索
                                </button>


                            </form>

                        </div>
                        <div class="box-body">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th style="min-width: 86px">比赛ID</th>
                                    <th style="min-width: 86px">竞猜ID</th>
                                    <th style="width: 50%">题目</th>
                                    <th style="width: 50%">答案选项</th>
                                    <th style="min-width: 86px">状态</th>
                                    <th style="min-width: 86px">奖金池</th>
                                    <th style="min-width: 86px">投注人数</th>
                                    <th style="min-width: 86px">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($list as $item): ?>
                                    <tr>
                                        <?php if (empty($item['user_id'])):?>
                                        <?php $user_id = '135601920097128682'?>
                                        <?php else:?>
                                        <?php $user_id = $item['user_id']?>
                                        <?php endif;?>
                                        <td><a href="<?php echo site_url('interaction/live/main_live/index/').$item['match_id'].'/'.$user_id?>"><?= $item['match_id'] ?></a></td>
                                        <td><?= $item['id'] ?></td>
                                        <td><?= $item['question'] ?></td>
                                        <td>
                                            <?php foreach ($item['option'] as $o_v): ?>
                                                <div>
                                                    <?= $o_v['id'] ?> <?= $o_v['name'] ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </td>
                                        <td><?= $status_title[$item['status']] ?></td>
                                        <td><?= $item['statistic_bonus_pool'] ?></td>
                                        <td><?= $item['statistic_turnouts'] ?></td>
                                        <td>
                                            <a href="<?= $detail_url . $item['id'] . '/' . $page_offset . '/' ?>">明细</a>
                                        </td>
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
