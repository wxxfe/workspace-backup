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
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        .avatar{width: 50px; height: 50px; border-radius: 50%;}
        .table>tbody>tr>td{vertical-align: middle;}
    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
    <div class="wrapper">
        <div class="content-wrapper" style="margin-left: 0;">
            <section class="content-header">
              <h1>频道管理<small>添加快捷入口</small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">频道管理</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12" id="channel_detail">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">添加快捷入口</h3>
                            </div><!-- /.box-header -->
                            <!-- form start -->
                            <form role="form" class="form-horizontal" method="post" id="shortcut_add">
                            <div class="box-body">
                                <div class="form-group">
                                  <label for="name" class="control-label col-md-2">入口名称</label>
                                  <div class="col-md-8">
                                    <input class="form-control" id="name" name="name" placeholder="入口名称" type="text" <?php if(isset($shortcut_id)):?>value="<?=$shortcut['name']?>"<?php endif;?>>
                                  </div>
                                </div>
                                <div class="form-group">
                                    <label for="target" class="control-label col-md-2">类型</label>
                                    <div class="col-md-5">
                                        <select id="type-select" class="form-control" name="target">
                                        <?php foreach($target_list as $target_k => $target_v):?>
                                        <option value="<?=$target_k?>" <?php if(isset($shortcut_id) && $shortcut['target'] == $target_k):?>selected="selected"<?php endif;?>><?=$target_v?></option>
                                        <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                                <div id="schedule_entry_title" class="form-group" style="display: <?=!isset($shortcut_id) || (isset($shortcut['target']) && $shortcut['target'] == 'schedule') ? '':'none';?>">
                                    <label for="name" class="control-label col-md-2">看点推介</label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="entry_title" name="entry_title" placeholder="看点推介" type="text" <?php if(isset($shortcut['entry_title'])):?>value="<?=$shortcut['entry_title']?>"<?php endif;?>>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                        <div class="box-footer text-center">
                            <button type="submit" id="form_submit" class="btn btn-primary">提交</button>
                            &nbsp;&nbsp;
                            <button type="cancel" onclick="window.history.go(-1)" class="btn btn-default">取消</button>
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
<script>
$("#type-select").change(function(){
    var type = $(this).val();
    if(type == 'schedule') {
        $("#schedule_entry_title").show();
    } else {
        $("#schedule_entry_title").hide();
    }
});
$("#form_submit").click(function() {//button的click事件  
    $("#shortcut_add").submit();
});

$('#shortcut_add').on('submit',function(){
    alert('ok');
});
</script>
</body>
</html>