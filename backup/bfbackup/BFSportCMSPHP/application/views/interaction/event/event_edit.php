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
    <link rel="stylesheet" href="/static/plugins/sweetalert-master/dist/sweetalert.css">
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
                                <h3 class="box-title">事件管理 - 编辑事件</h3>
                            </div><!-- /.box-header -->
                            <form role="form" class="form-horizontal" method="post" id="community_add" enctype="multipart/form-data">
                            <!-- form start -->
                            <div class="box-body">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title" class="control-label col-md-2"><strong class="text-danger"></strong>赛场事件</label>
                                        <div class="col-md-8">
                                            <input class="form-control" id="title" name="title" placeholder="请输入事件名称" type="text" value=<?=$info['title']?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="desc" class="control-label col-md-2">事件文案</label>
                                        <div class="col-md-8">
                                            <input class="form-control" id="desc" name="desc" placeholder="" type="text" value=<?=$info['desc']?>>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="duration" class="control-label col-md-2">动画时间</label>
                                        <div class="col-md-8">
                                            <input class="form-control" id="duration" name="duration" placeholder="" type="text" value="<?=$info['duration']?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="total_duration" class="control-label col-md-2">总时间</label>
                                        <div class="col-md-8 bvalidator-bs3form-msg">
                                            <input class="form-control" id="total_duration" name="total_duration" placeholder="" type="text" value="<?=$info['total_duration']?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="type" class="control-label col-md-2">事件类型</label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="type" disabled="">
                                            <option value="1" <?php if($info['type']==1):?>selected="selected"<?php endif;?>>全屏事件</option>
                                            <option value="2" <?php if($info['type']==2):?>selected="selected"<?php endif;?>>普通事件</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- 需要添加赛事信息 -->
                                    <div class="form-group">
                                        <label for="type" class="control-label col-md-2">赛事类型</label>
                                        <div class="col-md-5">
                                            <select class="form-control" name="sport_id" disabled="">
                                            <?php foreach ($sports_list as $sport_item):?>
                                            <option value="<?=$sport_item['id']?>" 
                                             <?php if($info['sport_id']==$sport_item['id']):?>selected="selected"<?php endif;?>><?=$sport_item['name']?></option>
                                            <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="video" class="control-label col-md-2">互动动画</label>
                                        <div class="col-md-8">
                                            <div id="image-view-2" style="padding: 10px 0;">
                                                <img src="<?php echo $source_path.$info['video']?>" style="width:150px;" />
                                            </div>
                                            <!-- 
                                            <span class="btn btn-warning fileinput-button">
                                                <i class="glyphicon glyphicon-plus"></i>
                                                <span>上传图片</span>
                                                <input type="file" name="video" size="20" />
                                            </span>
                                            -->
                                            <div>
                                                <input type="file" name="video" size="20" />
                                                <p class="help-block">上传动画</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="video_repeat" class="control-label col-md-2">动画重复次数</label>
                                        <div class="col-md-8">
                                            <input class="form-control" id="video_repeat" name="video_repeat" placeholder="填写动画重复次数数字，不填默认为1次" type="text" value="<?=$info['video_repeat']?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="audio" class="control-label col-md-2">音效</label>
                                        <div class="col-md-8">
                                            <div><audio controls="controls"><source src="<?php echo $source_path.$info['audio']?>" type="audio/mp3"></audio></div>
                                            <div>
                                                <input type="file" name="audio" size="20" />
                                                <p class="help-block">上传音效</p>
                                            </div>
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
                <!-- 写死，只有事件类型是“全屏互动事件”，才可以添加道具 -->
                <?php if($info['type'] == 1):?>
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?=$info['title']?> - 道具列表</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>排序</th>
                                            <th>名称</th>
                                            <th>动画效果</th>
                                            <th>音效</th>
                                            <th>互动动画</th>
                                            <th>时长</th>
                                            <th>重复</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($et_list as $key => $item): ?>
                                        <tr>
                                            <td><?=$key+1?></td>
                                            <td><?=$item['title']?></td>
                                            <td><?=$item['action']?></td>
                                            <td><audio controls="controls"><source src="<?php echo $source_path.$item['audio']?>" type="audio/mp3"></audio></td>
                                            <td><img src="<?php echo $source_path.$item['video']?>" style="width:150px;" /></td>
                                            <td><?=$item['duration']?></td>
                                            <td><?=$item['video_repeat']?></td>
                                            <td>
                                                <a class="btn btn-flat btn-warning btn-xs" role="button" href="<?php echo site_url('interaction/event/Eventhd/eventToolEdit/').$info['id'].'/'.$item['id']?>"><i class="fa fa-edit"></i> 编辑</a>
                                                <a class="btn btn-flat btn-danger btn-xs btn-remove" role="button" href="javascript:void(0);" data-vid="<?=$item['id']?>"><i class="fa fa-remove"></i> 删除</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="box box-solid">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <a class="btn btn-default btn-flat" role="button" href="<?php echo site_url('interaction/event/Eventhd/eventToolAdd/').$info['id']?>"><i class="fa fa-edit"></i> 添加道具</a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <a class="btn btn-default btn-flat" role="button" href="<?=base_url('interaction/event/Eventhd/eventsList')?>"><i class="fa fa-reply"></i> 返回列表</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif;?>
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
//--remove alert-------------------------------------------------------------
var alertConfig = {
    title: "你确定要删除吗?",
    text: "删除后不可恢复，请谨慎操作!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dd4b39",
    confirmButtonText: "确定删除",
    cancelButtonText: "取消",
    closeOnConfirm: false
};
$('#batch-remove,.btn-remove').on('click',function(){
    var etId = $(this).data('vid');
    var tr = $(this).parents('tr');
    var target = this;
    if(this.id == 'batch-remove'){
        collectionId = $('#batch-id').val();
        if(collectionId == ''){
            swal("操作失败!", "请先选择要删除的视频合集！", "error");
            return false;
        }
    }

    swal(alertConfig,function(){
        $.post('<?=site_url('interaction/event/Eventhd/eventToolRemove/')?>',{id : etId},function(d){
            if(d == 'success'){
                swal({title : "删除成功!",text : "事件道具已被删除！",type : "success"},function(){
                    if(target.id == 'batch-remove') window.location.reload();
                    tr.fadeOut('fast',function(){$(this).remove();});
                });
            }else{
                swal("删除失败!", "事件道具删除失败，请重试！", "error");
            }
        });
    });
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
