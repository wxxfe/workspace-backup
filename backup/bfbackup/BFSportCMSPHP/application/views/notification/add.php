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
    <link rel="stylesheet" href="/static/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
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
              <h1>推送管理<small>添加推送</small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">推送列表</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <!-- form start -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">推送管理>>添加推送</h3>
                            </div><!-- /.box-header -->
                            <form role="form" class="form-horizontal" method="post" id="notification">
                            <!-- form start -->
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="target" class="control-label col-md-2">内容类型</label>
                                    <div class="col-md-5">
                                        <select class="form-control" name="type" id="type">
                                        <?php foreach($ntypes as $ntype_v => $ntype_name):?>
                                        <option value="<?=$ntype_v?>" <?php if(isset($nid) && $info['type'] == $ntype_v):?>selected="selected"<?php endif;?>><?=$ntype_name?></option>
                                        <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="content" class="control-label col-md-2"><strong class="text-danger">*</strong>ID/URL</label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="content" name="content" type="text" 
                                            <?php if (isset($nid)):?>
                                            <?php if ($info['type'] == 'h5'):?>
                                            value=<?=$info['url']?>
                                            <?php else:?>
                                            value=<?=$info['ref_id']?>
                                            <?php endif;?>
                                            <?php endif;?>
                                            >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="content_title" class="control-label col-md-2"></label>
                                    <div class="col-md-8">
                                        <a class="btn btn-flat btn-info btn-xs btn-gettitle" role="button" href="javascript:void(0);"> 查看内容标题</a>
                                        <span id="content-title"></span>
                                    </div>
                                </div>
                                <?php if ($platf == 'android'):?>
                                <div class="form-group">
                                    <label for="title" class="control-label col-md-2"><strong class="text-danger">*</strong>推送标题</label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="title" name="title" placeholder="请输入标题" type="text" 
                                        <?php if (isset($nid)):?>value=<?=$info['title']?><?php endif?>>
                                    </div>
                                </div>
                                <?php endif;?>
                                <div class="form-group">
                                    <label for="desc" class="control-label col-md-2"><strong class="text-danger">*</strong>推送描述</label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="desc" name="desc" placeholder="请输入描述" type="text"
                                        <?php if (isset($nid)):?>value=<?=$info['desc']?><?php endif?>>
                                    </div>
                                </div>
                                <input type="hidden" name="platf" value="<?=$platf?>">
                            </div><!-- /.box-body -->
                            <!-- form end -->
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
<script src="/static/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script>

$("#form_submit").click(function() {//button的click事件  
    $("#notification").submit();
});

$('#notification').on('submit',function(){
    alert('ok');
});

$(".btn-gettitle").click(function() {//button的click事件  
    var type  = $("#type").val();
    var refId = $("#content").val();
    $.post('<?=base_url("/notification/getRelInfo")?>',{type : type, ref_id : refId},function(d){
        if (d=='fail') {
            $("#content-title").html('查无此内容');
        } else {
            var rtn=JSON.parse(d);
            $("#content-title").html(rtn.title);
        }
    });
});
</script>
</body>
</html>
