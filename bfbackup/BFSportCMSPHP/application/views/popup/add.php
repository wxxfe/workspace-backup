<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=HEAD_TITLE ?></title>
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
    <link rel="stylesheet" href="/static/fileupload/css/fileupload.css">
    <link rel="stylesheet" href="/static/fileupload/css/fileupload-ui.css">
    <link rel="stylesheet" href="/static/plugins/sweetalert-master/dist/sweetalert.css">
    <link rel="stylesheet" href="/static/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        .table>tbody>tr>td{vertical-align: middle;}

        .user-list th, .user-list td {
            text-align: center;
        }
    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
<div class="wrapper">
    <div class="content-wrapper" style="margin-left: 0;">
        <section class="content-header">
            <h1><?php if (isset($id) && $id) { echo '编辑';} else { echo '添加'; } ?>弹框活动<small><?php if (isset($id) && $id) { echo 'Edit';} else { echo 'Create'; } ?> Popup </small></h1>
            <ol class="breadcrumb">
                <li><a href="/main/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><?php if (isset($id) && $id) { echo '编辑';} else { echo '添加'; } ?>弹框活动</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12" id="user-list">
                    <div class="box">
                        <div class="box-body">
                            <form class="form-horizontal" method="post" id="news-form" data-toggle="validator" active="">
                                <input type="hidden" name="id" value="<?php if (isset($id) && $id) { echo $id; } ?>" />
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="title">活动标题</label>
                                            <div class="col-sm-6">
                                                <input required class="form-control" type="text" name="title" value="<?php if (isset($info) && $info) { echo $info['title'];} ?>" placeholder="请输入活动标题" maxlength="128" data-bvalidator-msg="请输入活动标题!" />
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="">上传图片</label>
                                            <div class="col-sm-7">
                                                <div id="image-view" style="padding: 10px 0; <?php if (isset($info) && !$info['image'] || !isset($id)) { echo 'display: none;';} ?>"><img style="width:100%;" src="<?php if (isset($info) && $info['image']) { echo getImageUrl($info['image']); } ?>"></div>
                                                    <span class="btn btn-warning fileinput-button">
                                                        <i class="glyphicon glyphicon-plus"></i>
                                                        <span>上传图片</span>

                                                        <input id="fileupload" type="file" name="image" multiple data-url="<?=IMG_UPLOAD?>" />
                                                    </span>
                                                <span style="margin-left:30px;">建议尺寸小于700*1000，建议透明PNG图片</span>
                                                <input id="poster" type="hidden" name="poster" value="<?php if (isset($info) && $info['image']) { echo $info['image'];} ?>" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="data">URL</label>
                                            <div class="col-sm-6">
                                                <input required class="form-control" type="text" name="url" value="<?=$info['url']?>" placeholder="请输入url" maxlength="128" data-bvalidator-msg="请输入url!" />
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="type" class="control-label col-sm-3">适用终端</label>
                                            <div class="col-sm-3 bvalidator-bs3form-msg">
                                                <select data-bvalidator-msg="请选择适用终端!" required="" name="platf" class="form-control">
                                                    <option value="">请选择</option>
                                                    <?php foreach ($this->PM->platf_type as $k=>$v) { ?>
                                                        <option value="<?=$k ?>" <?php if (isset($info) && $info && $info['platf'] == $k) { echo "selected"; }?>><?=$v ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="title">适用版本</label>
                                            <div class="col-sm-6">
                                                <input required class="form-control" type="text" name="version" value="<?php if (isset($info) && $info) { echo $info['version'];} ?>" placeholder="请输入适用版本" maxlength="128" data-bvalidator-msg="请输入适用版本!" />
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3" for="title">适用渠道</label>
                                            <div class="col-sm-6">
                                                <input required class="form-control" type="text" name="channel" value="<?php if (isset($info) && $info) { echo $info['channel'];} ?>" placeholder="请输入适用渠道" maxlength="128" data-bvalidator-msg="请输入适用渠道!" />
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="start_tm" class="control-label col-sm-3">开始时间</label>
                                            <div class="col-md-6">
                                                <div id="datetimepicker_start" class="input-append date">
                                                    <input required data-format="yyyy-MM-dd hh:mm:ss" class="form-control pull-left" style="display: inline-block; width: 90%;" id="start_tm" name="start_tm" placeholder="" type="text" value="<?=$info['start_tm']?>" data-bvalidator-msg="请输入开始时间!">
                                                    <span class="add-on" style="display: inline-block; margin-left: 5px;">
                                                        <i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-calendar" style="margin-top: 10px;"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="finish_tm" class="control-label col-sm-3">结束时间</label>
                                            <div class="col-sm-6">
                                                <div id="datetimepicker_finish" class="input-append date">
                                                    <input required data-format="yyyy-MM-dd hh:mm:ss" class="form-control pull-left" style="display: inline-block; width: 90%;" id="finish_tm" name="finish_tm" placeholder="" type="text" value="<?=$info['finish_tm']?>" data-bvalidator-msg="请输入结束时间!">
                                                    <span class="add-on" style="display: inline-block; margin-left: 5px;">
                                                        <i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-calendar" style="margin-top: 10px;"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group text-center">
                                        <button class="btn btn-success" type="submit">保存</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="box box-solid">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-2">
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

<script src="/static/dist/js/bvalidator.js"></script>
<script src="/static/dist/js/bvalidator.bs3form.min.js"></script>
<script src="/static/dist/js/bs3form.js"></script>
<script src="/static/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script>
    $('#news-form').bValidator({validateOn: 'blur,change'});

    $('#fileupload').fileupload({
        add: function (e, data) {
            //data.context = $('<p/>').text('Uploading...').appendTo('#content');
            data.submit();
        },
        done: function (e, data) {
            //$('#cover').val(data);
            var result = data.result.errno;

            if(result !== 10000){
                alert('上传失败,请重试！');
            }
            else{
                $("#image-view").html('<img style="width:100%;" src="http://image.sports.baofeng.com/' + data.result.data.pid + '">').show();
                $('#poster').val(data.result.data.pid);
            }
        }
    });

    $('#datetimepicker_start,#datetimepicker_finish').datetimepicker();
</script>
</body>
</html>
