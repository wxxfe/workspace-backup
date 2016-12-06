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
                                <h3 class="box-title">全部分类 - 添加分类</h3>
                            </div><!-- /.box-header -->
                            <form role="form" class="form-horizontal" method="post" id="community_edit">
                            <!-- form start -->
                            <div class="box-body">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title" class="control-label col-md-2"><strong class="text-danger"></strong>分类名称</label>
                                        <div class="col-md-8">
                                            <input class="form-control" id="name" name="name" type="text" value="<?=$info['name']?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="match_id">关联比赛</label>
                                        <div class="col-sm-8">
                                            <?php $this->load->view('common/linkMatch', array('current' => $info['match_id'])); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="icon" class="control-label col-md-2">icon</label>
                                        <div class="col-md-8">
                                            <div id="image-view-1" style="padding: 10px 0;">
                                                <img src="<?=$info['icon_url']?>" style="width:150px;" />
                                            </div>
                                            <span class="btn btn-warning fileinput-button">
                                                <i class="glyphicon glyphicon-plus"></i>
                                                <span>上传图片</span>
                                                <input id="fileupload" type="file" name="image" multiple data-url="http://w.image.sports.baofeng.com/save?token=xVFpX0RU" />
                                            </span> 80*80
                                            <input id="icon" type="hidden" name="icon" >
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
    $("#community_edit").submit();
});

$('#community_edit').on('submit',function(){
    alert('ok');
});


</script>
</body>
</html>
