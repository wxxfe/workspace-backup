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

        .option .help-block:last-child {
            display: none !important;
        }

        .deadline > * {
            width: 15%;
            float: left;
        }

        .deadline > .help-block {
            width: auto;
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
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title pull-left" style="line-height: 200%;">发布竞猜</h3>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal" method="post" id="form"
                                  action="<?= $add_url . '&redirect=' . current_url() ?>">
                                <div class="form-group">
                                    <label for="question" class="col-sm-2 control-label">竞猜题目</label>
                                    <div class="col-sm-9 bvalidator-bs3form-msg">
                                        <input class="form-control" name="question" placeholder="最多15个字！"
                                               type="text" required maxlength="512"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="option" class="col-sm-2 control-label">答案选项</label>
                                    <div class="col-sm-9 option bvalidator-bs3form-msg">
                                        <input class="form-control pull-left" style="width: 30%; margin-right: 5%;"
                                               name="option[]" placeholder="最多5个字！"
                                               type="text" data-bvalidator="required" data-bvalidator-msg="请至少输入两项答案！"/>
                                        <input class="form-control pull-left" style="width: 30%; margin-right: 5%;"
                                               name="option[]" placeholder="最多5个字！"
                                               type="text" data-bvalidator="required" data-bvalidator-msg="请至少输入两项答案！"/>
                                        <input class="form-control pull-left" style="width: 30%;"
                                               name="option[]" placeholder="最多5个字！" type="text"/>
                                    </div>


                                </div>
                                <div class="form-group">
                                    <label for="deadline" class="col-sm-2 control-label">截止时间</label>
                                    <div class="col-sm-9 deadline bvalidator-bs3form-msg">
                                        <select class="form-control pull-left"
                                                name="time_types"
                                                data-bvalidator="min[0],required"
                                                data-bvalidator-msg="请选择一项!">
                                            <option value="-1">请选择</option>
                                            <?php foreach ($time_types as $t_k => $t_v): ?>
                                                <option value="<?= $t_k ?>"><?= $t_v ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input class="form-control pull-left"
                                               name="deadline" value=""
                                               type="number" required data-bvalidator-msg="请输入多少分钟！"/>
                                        <div class="form-control-static">分钟后截止</div>
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <input type="hidden" name="match_id" value="<?= $match_id ?>"/>
                                    <input type="hidden" name="user_id" value="<?= $host_id?>" />
                                    <button id="add-btn" class="btn btn-success">发布</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title pull-left" style="line-height: 200%;">当前竞猜</h3>
                            <div class="text-warning pull-right">
                                <div class="pull-left" style="line-height: 250%">
                                    <span style="color: #f90;"><i class="fa fa-info"></i> 提示：</span>
                                    <span>下划虚线项目可点击编辑</span>
                                </div>
                                <a href="<?= current_url() ?>"
                                   role="button" class="btn btn-success btn-flat pull-left"
                                   style="margin-left: 10px;">刷新</a>
                            </div>
                        </div>
                        <div class="box-body">
                            <?php foreach ($list as $item): ?>
                                <div class="clearfix" style="border-bottom: 1px solid #f4f4f4; margin-bottom: 10px;">
                                    <div class="col-xs-10">
                                        <div class="col-xs-12" style="margin-bottom: 15px;">
                                            <?php if ($item['status'] === 'end'): ?>

                                                <strong style="display: block; margin-left: 15px;"
                                                ><?= $item['question'] ?></strong>

                                            <?php else: ?>

                                                <a class="editable"
                                                   style="display: block; margin-left: 15px; font-weight: bold;"
                                                   data-validation-maxlen="512"
                                                   data-type="textarea"
                                                   data-url="<?= $in_place_edit_url ?>"
                                                   data-pk="<?= $item['id'] ?>"
                                                   data-name="question"><?= $item['question'] ?></a>

                                            <?php endif; ?>
                                        </div>
                                        <div class="col-xs-12" style="margin-bottom: 15px;">
                                            <?php foreach ($item['option'] as $o_v): ?>
                                                <div class="col-xs-4" style="position: relative;">

                                                    <?php if ($item['status'] === 'end'): ?>

                                                        <?= $o_v['name'] ?>

                                                        <?php if ($o_v['id'] == $item['answer']): ?>
                                                            <strong>(正确答案)</strong>
                                                        <?php endif; ?>

                                                    <?php else: ?>

                                                        <a class="editable" style="display: block;"
                                                           data-validation-maxlen="128"
                                                           data-type="text"
                                                           data-url="<?= $in_place_edit_url ?>"
                                                           data-pk="<?= $item['id'] ?>"
                                                           data-name="<?= 'option' . $o_v['id'] ?>"><?= $o_v['name'] ?></a>
                                                        <button data-answer-id="<?= $o_v['id'] ?>"
                                                                data-pk="<?= $item['id'] ?>"
                                                                class="answer-btn btn btn-success btn-xs">设为答案
                                                        </button>

                                                    <?php endif; ?>

                                                    <div>选择人数 <strong class="text-info"><?= $o_v['num'] ?></strong>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="col-xs-12" style="margin-bottom: 15px;">
                                            <div class="pull-left" style="margin:0 15px;">
                                                竞猜人数 <strong
                                                    class="text-info"><?= $item['statistic_turnouts'] ?></strong></div>
                                            <div class="pull-left">
                                                奖金池 <strong
                                                    class="text-info"><?= $item['statistic_bonus_pool'] ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-2">
                                        <?php if ($item['status'] === 'begin'): ?>

                                            <div><?= $item['remaining_time'] . '分钟后截止<br>' . $status_title[$item['status']] ?></div>

                                        <?php elseif ($item['status'] === 'end'): ?>

                                            <a href="<?= $detail_url . $item['id'] . '?redirect=' . current_url() ?>"><?= $status_title[$item['status']] ?></a>

                                        <?php else: ?>

                                            <div><?= $status_title[$item['status']] ?></div>

                                        <?php endif; ?>

                                    </div>
                                </div>
                            <?php endforeach; ?>
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

    var formJQ = $('#form');

    //添加表单验证，blur表示input失去焦点就验证
    formJQ.bValidator({validateOn: 'blur'});

    //比赛数据
    var match = $.parseJSON('<?= $match_data ?>');

    //把比赛开始时间转换成时间戳，秒
    var match_start_date = new Date(String(match['start_tm']).replace(' ', 'T'));
    var match_start_tm = match_start_date.getTime() / 1000;
    //start_tm:"2016-09-23 02:00:00"
    //接管发布按钮
    $('#add-btn').click(function () {

        var v = formJQ.data("bValidators").bvalidator.isValid();

        //如果基本验证通过，则继续
        if (v == true) {

            //把表单中的多少分钟截止转换为秒
            var deadline = Number($('input[name="deadline"]').val()) * 60;
            //竞猜截止时间的计算类型
            var time_types = Number($('select[name="time_types"]').val());

            //当前时间 秒
            var nowTime = (Date.now() / 1000);

            //array('开赛前', '开赛后', '发布后');
            switch (time_types) {
                case 0:
                    deadline = match_start_tm - deadline;
                    break;
                case 1:
                    deadline = match_start_tm + deadline;
                    break;
                case 2:
                    deadline = nowTime + deadline;
                    break;
            }

            if ((deadline - nowTime) < 0) {
                window.parent.sweetAlert(
                    {
                        title: "当前时间已经超过截止时间\n请重新输入截止时间!",
                        type: "warning",
                        allowOutsideClick: true,
                        animation: false
                    }
                );
            } else {
                var text = '竞猜题目\n' + $.trim($('input[name="question"]').val()) + '\n答案选项';
                $('input[name="option[]"]').each(function () {
                    var ot = $.trim($(this).val());
                    if (ot) {
                        text += '\n' + ot;
                    }
                });
                window.parent.sweetAlert(
                    {
                        title: "你确定发布竞猜吗?",
                        text: text,
                        showCancelButton: true,
                        confirmButtonText: "确定",
                        cancelButtonText: "取消"
                    },
                    function () {
                        formJQ.submit();
                        $('#add-btn').attr('disabled',true);
                    }
                );
            }
            return false;
        }

    });

    //接管答案按钮
    $('.answer-btn').click(function () {
        var tJQ = $(this);
        window.parent.sweetAlert(
            {
                title: "你确定竞猜答案为?",
                text: tJQ.siblings('.editable').text(),
                showCancelButton: true,
                confirmButtonText: "确定",
                cancelButtonText: "取消"
            },
            function () {
                $.ajax({
                    method: "POST",
                    url: "<?= $in_place_edit_url ?>",
                    data: {name: "answer", value: tJQ.data('answer-id'), pk: tJQ.data('pk')}
                }).done(function (data) {
                    window.location.reload(true);
                });
            }
        );
    });

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
            var maxlen = Number(tJQ.data('validation-maxlen'));
            if (maxlen && !bValidator.validators.maxlen(value, maxlen)) {
                return bValidator.defaultOptions.messages.zh.maxlen.replace('{0}', maxlen);
            }
        }
    });

</script>
</body>
</html>
