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
                                        <label for="title" class="control-label col-md-2"><strong class="text-danger">*</strong>标题</label>
                                        <div class="col-md-8">
                                            <input class="form-control" id="title" name="title" placeholder="<?=$video['title']?>" type="text" value="<?=$video['title']?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="is_vr" class="control-label col-md-2">是VR</label>
                                        <div class="col-md-5">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" id="is_vr" name="is_vr" <?php if($video['isvr'] == 1):?>checked="checked"<?php endif;?>>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="duration" class="control-label col-md-2">时长</label>
                                        <div class="col-md-5">
                                            <div id="timepicker" class="input-append">
                                                <input data-format="hh:mm:ss" class="form-control" id="duration" name="duration" placeholder="" style="display: inline-block; width: 90%;" type="text" value="<?=$video['duration']?>">
                                            <span class="add-on" style="display: inline-block; margin-left: 5px;">
                                                <i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-calendar"></i>
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="publish_tm" class="control-label col-md-2">发布时间</label>
                                        <div class="col-md-5">
                                            <div id="datetimepicker" class="input-append date">
                                                <input data-format="yyyy-MM-dd hh:mm:ss" class="form-control" style="display: inline-block; width: 90%;" id="publish_tm" name="publish_tm" placeholder="" type="text" value="<?=$video['publish_tm']?>">
                                            <span class="add-on" style="display: inline-block; margin-left: 5px;">
                                                <i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-calendar"></i>
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="match_id" class="control-label col-md-2">关联比赛</label>
                                        <div class="col-md-5">
                                            <input class="form-control" id="match_id" name="match_id" placeholder="若需关联比赛输入比赛ID" type="text" value="<?=$match_id?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="site" class="control-label col-md-2">视频来源</label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="site">
                                                <?php foreach($site_list as $site):?>
                                                    <option value="<?=$site['site']?>" <?php if ($site['site'] == $video['site']):?>selected="selected"<?php endif;?>><?=$site['site']?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="brief" class="control-label col-md-2">摘要</label>
                                        <div class="col-md-8">
                                            <textarea placeholder="请输入摘要" id="brief" name="brief" rows="3" class="form-control"><?=$video['brief']?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="play_url" class="control-label col-md-2">播放地址</label>
                                        <div class="col-md-8">
                                            <input class="form-control" id="play_url" name="play_url" placeholder="如何是第三方视频需要填写" type="text" value="<?=$video['play_url']?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="play_code" class="control-label col-md-2">播放代码</label>
                                        <div class="col-md-8">
                                            <textarea placeholder="用于App端播放" id="play_code" name="play_code" rows="3" class="form-control"><?=$video['play_code']?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="duration" class="control-label col-md-2">封面</label>
                                        <div class="col-md-8">
                                            <div id="image-view" style="padding: 10px 0;">
                                                <img src="<?=$video['image_url']?>" style="width:150px;" />
                                            </div>
                                        <span class="btn btn-warning fileinput-button">
                                            <i class="glyphicon glyphicon-plus"></i>
                                            <span>上传图片</span>
                                            <input id="fileupload" type="file" name="image" multiple data-url="<?=IMG_UPLOAD?>" />
                                        </span>&nbsp;&nbsp;&nbsp;&nbsp;<b>建议尺寸225*150</b>
                                            <input id="cover" type="hidden" name="cover" value="<?=$video['image']?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tags" class="control-label col-md-2">标签</label>
                                        <div class="col-md-8">
                                            <?php $this->load->view('common/tagSelect',array('selected' =>$video_tags)); ?>
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
