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
              <h1>事件管理<small>添加道具</small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">添加道具</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12" id="pending-video-list">
                        <!-- form start -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">事件管理 - 添加道具</h3>
                            </div><!-- /.box-header -->
                            <form role="form" class="form-horizontal" method="post" id="community_add" enctype="multipart/form-data">
                            <!-- form start -->
                            <div class="box-body">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="live_tool_id" class="control-label col-md-2">选择道具</label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="live_tool_id" <?php if($type=='edit'):?>disabled=""<?php endif;?>>
                                            <?php foreach($tool_list as $tool):?>
                                            <option value="<?=$tool['id']?>"
                                            <?php if ($type=='edit' && $et_info['live_tool_id']==$tool['id']):?>selected="selected"<?php endif;?>
                                            ><?=$tool['title']?></option>
                                            <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="action" class="control-label col-md-2">动画效果</label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="action">
                                            <?php foreach ($event_tool_action as $eta_k=>$eta_v):?>
                                            <option value="<?=$eta_k?>"
                                            <?php if($type == 'edit' && $et_info['action'] == $eta_k):?>selected="selected"<?php endif?>
                                            ><?=$eta_v?></option>
                                            <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="duration" class="control-label col-md-2">动画时间</label>
                                        <div class="col-md-8">
                                            <input class="form-control" id="duration" name="duration" placeholder="" type="text"
                                            <?php if($type=='edit'):?>value="<?=$et_info['duration']?>"<?php endif;?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="video" class="control-label col-md-2">互动动画</label>
                                        <div class="col-md-8">
                                            <?php if($type=='edit'):?>
                                            <div id="image-view-2" style="padding: 10px 0;">
                                                <img src="<?php echo $source_path.$et_info['video']?>" style="width:150px;" />
                                            </div>
                                            <?php endif;?>
                                            <input type="file" name="video" size="20" />
                                            <p class="help-block">上传动画</p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="video_repeat" class="control-label col-md-2">动画重复次数</label>
                                        <div class="col-md-8">
                                            <input class="form-control" id="video_repeat" name="video_repeat" placeholder="填写动画重复次数数字，不填默认为1次" type="text" 
                                            <?php if($type=='edit'):?>value="<?=$et_info['video_repeat']?>"<?php endif;?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="audio" class="control-label col-md-2">音效</label>
                                        <div class="col-md-8">
                                            <?php if($type=='edit'):?>
                                            <div>
                                                <audio controls="controls"><source src="<?php echo $source_path.$et_info['audio']?>" type="audio/mp3"></audio>
                                            </div>
                                            <?php endif;?>
                                            <input type="file" name="audio" size="20" />
                                            <p class="help-block">上传音效</p>
                                        </div>
                                    </div>
                                </div>
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
//--file upload----------------------------------------------------
$('#fileupload').fileupload({
    add: function (e, data) {
        //data.context = $('<p/>').text('Uploading...').appendTo('#content');
        data.submit();
    },
    done: function (e, data) {
        //$('#cover').val(data);
        var result = data.result.errno

        if(result !== 10000){
            alert('上传失败,请重试！')
        }
        else{
            $("#image-view-1").html('<img src="http://image.sports.baofeng.com/' + data.result.data.pid + '">')
            $('#icon').val(data.result.data.pid);
        }
    }
});


$("#form_submit").click(function() {//button的click事件  
    $("#community_add").submit();
});

$('#community_add').on('submit',function(){
    alert('ok');
});


</script>
</body>
</html>
