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
            <h1>视频列表<small>可用视频-编辑 </small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">视频列表</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12" id="detail-video">
                    <!-- form start -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">编辑视频</h3>
                        </div><!-- /.box-header -->
                        <form role="form" class="form-horizontal" method="post" id="detail_edit">
                            <div class="box-body">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="match_id" class="control-label col-md-2">关联比赛</label>
                                        <div class="col-md-5">
                                            <input class="form-control" id="match_id" name="match_id" placeholder="若需关联比赛输入比赛ID" type="text" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="is_vr" class="control-label col-md-2">是VR</label>
                                        <div class="col-md-5">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" id="is_vr" name="is_vr">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tags" class="control-label col-md-2">标签</label>
                                        <div class="col-md-8">
                                            <?php $this->load->view('common/tagSelect',array('selected' =>'')); ?>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
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
                $("#image-view").html('<img src="http://image.sports.baofeng.com/' + data.result.data.pid + '">')
                $('#cover').val(data.result.data.pid);
            }
        }
    });

    $("#form_submit").click(function() {//button的click事件
        $("#detail_edit").submit();
    });

    $('#detail_edit').on('submit',function(){
        alert('ok');
    });

    $('#datetimepicker').datetimepicker();
    $('#timepicker').datetimepicker({
        pickDate: false
    });

</script>
</body>
</html>
