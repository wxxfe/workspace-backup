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
        .avatar{width: 50px; height: 50px; border-radius: 50%;}
        .table>tbody>tr>td{vertical-align: middle;}
    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
    <div class="wrapper">
        <div class="content-wrapper" style="margin-left: 0;">
            <section class="content-header">
              <h1>事件管理<small>添加事件</small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">添加事件</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12" id="pending-video-list">
                        <!-- form start -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">事件管理 - 添加事件</h3>
                            </div><!-- /.box-header -->
                            <form role="form" class="form-horizontal" method="post" id="community_add" enctype="multipart/form-data">
                            <!-- form start -->
                            <div class="box-body">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title" class="control-label col-md-2"><strong class="text-danger"></strong>赛场事件</label>
                                        <div class="col-md-8">
                                            <input class="form-control" id="title" name="title" placeholder="请输入事件名称" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="desc" class="control-label col-md-2">事件文案</label>
                                        <div class="col-md-8">
                                            <input class="form-control" id="desc" name="desc" placeholder="" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="duration" class="control-label col-md-2">动画时间</label>
                                        <div class="col-md-8">
                                            <input class="form-control" id="duration" name="duration" placeholder="" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="total_duration" class="control-label col-md-2">总时间</label>
                                        <div class="col-md-8 bvalidator-bs3form-msg">
                                            <input class="form-control" id="total_duration" name="total_duration" placeholder="" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="type" class="control-label col-md-2">事件类型</label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="type">
                                            <option value="1">全屏事件</option>
                                            <option value="2">普通事件</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- 需要添加赛事信息 -->
                                    <div class="form-group">
                                        <label for="type" class="control-label col-md-2">赛事类型</label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="sport_id">
                                            <?php foreach ($sports_list as $sport_item):?>
                                            <option value="<?=$sport_item['id']?>"><?=$sport_item['name']?></option>
                                            <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="video" class="control-label col-md-2">互动动画</label>
                                        <div class="col-md-8">
                                            <input type="file" name="video" size="20" />
                                            <p class="help-block">上传动画</p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="video_repeat" class="control-label col-md-2">动画重复次数</label>
                                        <div class="col-md-8">
                                            <input class="form-control" id="video_repeat" name="video_repeat" placeholder="填写动画重复次数数字，不填默认为1次" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="audio" class="control-label col-md-2">音效</label>
                                        <div class="col-md-8">
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
<script src="/static/dist/js/bvalidator.js"></script>
<script src="/static/dist/js/bvalidator.bs3form.min.js"></script>
<script src="/static/dist/js/bs3form.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
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

//事件表单
var formJQ = $('#community_add');
//验证插件
formJQ.bValidator({validateOn: 'blur'});

var formBvalidator = formJQ.data("bValidators").bvalidator;

$("#form_submit").click(function() {//button的click事件  
    if (formBvalidator.validate()) {
        // 如果总时长小于道具时长，则提示
        var duration = $('#duration').val();
        var total_duration = $('#total_duration').val();
        if (parseInt(duration) > parseInt(total_duration)) {
            swal(
                {
                    title: "总时间不能小于动画时间!",
                    type: "warning",
                    text: "2秒后自动关闭",
                    timer: 2000,
                    allowOutsideClick: true,
                    animation: false
                }
            );
        } else {
            $("#community_add").submit();
        }
    }
});

$('#community_add').on('submit',function(){
    alert('ok');
});


</script>
</body>
</html>
