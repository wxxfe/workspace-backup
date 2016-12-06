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
              <h1>节目管理<small>添加节目</small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">节目列表</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12" id="pending-video-list">
                        <!-- form start -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">节目管理>>添加节目-step1</h3>
                            </div><!-- /.box-header -->
                            <form role="form" class="form-horizontal" method="post" id="program_add_step1">
                            <!-- form start -->
                            <div class="box-body">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title" class="control-label col-md-2"><strong class="text-danger">*</strong>标题</label>
                                        <div class="col-md-8">
                                            <input class="form-control" id="title" name="title" placeholder="请输入标题" type="text">
                                        </div>
                                    </div>
                                    <!-- 
                                    <div class="form-group">
                                        <label for="icon" class="control-label col-md-2">icon</label>
                                        <div class="col-md-8">
                                            <div id="image-view-1" style="padding: 10px 0;">
                                                <img src="" style="width:150px;" />
                                            </div>
                                            <span class="btn btn-warning fileinput-button">
                                                <i class="glyphicon glyphicon-plus"></i>
                                                <span>上传图片</span>
                                                <input id="fileupload1" type="file" name="image" multiple data-url="http://w.image.sports.baofeng.com/save?token=xVFpX0RU" />
                                            </span>
                                            <input id="icon" type="hidden" name="icon" >
                                        </div>
                                    </div>
                                    -->
                                    <div class="form-group">
                                        <label for="cover_image" class="control-label col-md-2">icon</label>
                                        <div class="col-md-8">
                                            <div id="image-view-2" style="padding: 10px 0;">
                                                <img src="" style="width:150px;" />
                                            </div>
                                            <span class="btn btn-warning fileinput-button">
                                                <i class="glyphicon glyphicon-plus"></i>
                                                <span>上传图片</span>
                                                <input id="fileupload2" type="file" name="image" multiple data-url="http://w.image.sports.baofeng.com/save?token=xVFpX0RU" />
                                            </span>
                                            <input id="cover_image" type="hidden" name="cover_image" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="large_image" class="control-label col-md-2">封面图</label>
                                        <div class="col-md-8">
                                            <div id="image-view-3" style="padding: 10px 0;">
                                                <img src="" style="width:150px;" />
                                            </div>
                                            <span class="btn btn-warning fileinput-button">
                                                <i class="glyphicon glyphicon-plus"></i>
                                                <span>上传图片</span>
                                                <input id="fileupload3" type="file" name="image" multiple data-url="http://w.image.sports.baofeng.com/save?token=xVFpX0RU" />
                                            </span>&nbsp;&nbsp;&nbsp;&nbsp;<b>建议尺寸225*150</b>
                                            <input id="large_image" type="hidden" name="large_image" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="brief" class="control-label col-md-2">摘要</label>
                                        <div class="col-md-8">
                                            <textarea placeholder="请输入摘要" id="brief" name="brief" rows="3" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="visible" class="control-label col-md-2">上下线</label>
                                        <div class="col-md-5">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" id="visible" name="visible">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tags" class="control-label col-md-2">标签</label>
                                        <div class="col-md-8">
                                            <?php $this->load->view('common/tagSelect',array('selected' => '')); ?>
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
$('#fileupload2').fileupload({
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
            $("#image-view-2").html('<img src="http://image.sports.baofeng.com/' + data.result.data.pid + '">')
            $('#cover_image').val(data.result.data.pid);
        }
    }
});
$('#fileupload3').fileupload({
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
            $("#image-view-3").html('<img src="http://image.sports.baofeng.com/' + data.result.data.pid + '">')
            $('#large_image').val(data.result.data.pid);
        }
    }
});

$("#form_submit").click(function() {//button的click事件  
    $("#program_add_step1").submit();
});

$('#program_add_step1').on('submit',function(){
    alert('ok');
});

$('#datetimepicker').datetimepicker();
$('#timepicker').datetimepicker({
    pickDate: false
});

</script>
</body>
</html>
