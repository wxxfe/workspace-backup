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
        .table > tbody > tr > td {
            vertical-align: middle;
        }

        .user-list th, .user-list td {
            text-align: center;
        }
    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
<div class="wrapper">
    <div class="content-wrapper" style="margin-left: 0;">
        <section class="content-header">
            <h1><?php if (isset($id) && $id) {
                    echo '编辑';
                } else {
                    echo '添加';
                } ?>内容
                <small><?php if (isset($id) && $id) {
                        echo 'Edit';
                    } else {
                        echo 'Create';
                    } ?> broadcast
                </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/main/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><?php if (isset($id) && $id) {
                        echo '编辑';
                    } else {
                        echo '添加';
                    } ?>内容
                </li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12" id="user-list">
                    <div class="box">
                        <div class="box-body">
                            <form class="form-horizontal" method="post" id="news-form" data-toggle="validator"
                                  active="">
                                <input type="hidden" name="id" value="<?php if (isset($id) && $id) {
                                    echo $id;
                                } ?>"/>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type" class="control-label col-sm-2">来源类型</label>
                                            <div class="col-sm-3 bvalidator-bs3form-msg">
                                                <?php $types = array('video' => '视频', 'collection' => '专用合集'); ?>
                                                <select id="type-select" data-bvalidator-msg="请选择播报内容来源类型!" required name="type"
                                                        class="form-control">
                                                    <option value="">请选择</option>
                                                    <?php foreach ($types as $k => $v) { ?>
                                                        <option
                                                            value="<?= $k ?>" <?php if (isset($info) && $info && $info['type'] == $k) {
                                                            echo "selected";
                                                        } ?>><?= $v ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="data">来源ID</label>
                                            <div class="col-sm-8 bvalidator-bs3form-msg">
                                                <div class="clearfix">
                                                    <input data-bvalidator="checkData,required"
                                                           class="check-data-input form-control pull-left"
                                                           style="width: calc(100% - 80px);"
                                                           type="text" name="data"
                                                           value="<?php if (isset($info) && $info) {
                                                               if ($info['type'] == 'html') {
                                                                   echo $info['data'];
                                                               } else {
                                                                   echo $info['ref_id'];
                                                               }
                                                           } ?>" placeholder="请先选择类型，再输入id!"
                                                           data-bvalidator-msg="请先选择类型，再输入id!" maxlength="128"/>
<!--                                                    <button class="check-data-btn btn bg-purple btn-flat pull-right"-->
<!--                                                            style="width: 60px;">确认-->
<!--                                                    </button>-->
                                                </div>
                                                <div class="dataInfo"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="title">标题</label>
                                            <div class="col-sm-8 bvalidator-bs3form-msg">
                                                <input required class="form-control" type="text" name="title"
                                                       value="<?php if (isset($info) && $info) {
                                                           echo $info['title'];
                                                       } ?>" placeholder="请输入标题" maxlength="128"
                                                       data-bvalidator-msg="请输入标题!"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="site">排序</label>
                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" name="priority" placeholder=""
                                                       value="<?php if (isset($info)) {
                                                           echo $info['priority'];
                                                       } ?>"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="visible">上下线</label>
                                            <div class="col-sm-3">
                                                <div class="checkbox">
                                                    <label><input type="checkbox" name="visible"
                                                                  value="1" <?php if (isset($info) && $info['visible']) {
                                                            echo 'checked="checked"';
                                                        } ?>></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
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

<!-- AdminLTE App -->
<script src="/static/dist/js/app.min.js"></script>

<script src="/static/dist/js/bvalidator.js"></script>
<script src="/static/dist/js/bvalidator.bs3form.min.js"></script>
<script src="/static/dist/js/bs3form.js"></script>

<script>
    var $dataInfo = $('.dataInfo');
    bValidator.validators.checkData = function (value) {
        var res = true;
        var type = $("select[name='type'] option:selected").val();
        if(!type) {
            return false;
        }
        var url;
        if (type == 'video') {
            url = '/video/checkVideoId';
        }
        if (type == 'collection') {
            url = '/box/collection/checkCollectionId';
        }
        if (url) {
            $.ajax({
                url: url,
                data: {id: value},
                type: 'post',
                async: false,
                success: function (data) {
                    var prefix = type == 'video' ? '<font color="red">【视频】</font>' : '<font color="red">【合集】</font>';
                    var resultData = $.parseJSON(data);
                    if (resultData.status == 'yes') {
                        $dataInfo.html(prefix+resultData.data);
                    } else {
                        res = resultData.data;
                        $dataInfo.html('');
                    }
                }
            });
        }
        return res;
    };
    $('#news-form').bValidator({validateOn: 'blur', errorValidateOn: 'blur'});

    var $checkDataInput = $(".check-data-input");
    $("#type-select").on('change', function(e){
        $checkDataInput.trigger('blur');
    });
</script>
</body>
</html>
