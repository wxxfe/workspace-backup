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
              <h1>专栏管理<small>添加专栏</small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">专栏列表</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">专栏管理>>添加专栏</h3>
                            </div><!-- /.box-header -->
                            <form role="form" class="form-horizontal" method="post" id="column">
                            <!-- form start -->
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="title" class="control-label col-md-2"><strong class="text-danger">*</strong>名称</label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="title" name="title" placeholder="输入名称" type="text" 
                                        <?php if (isset($cid)):?>value=<?=$info['title']?><?php endif?>>
                                    </div>
                                </div>
                                <!-- 
                                <div class="form-group">
                                    <label for="content" class="control-label col-md-2"><strong class="text-danger">*</strong>消息内容</label>
                                    <div class="col-md-8">
                                        <textarea placeholder="请输入摘要" id="content" name="content" rows="3" class="form-control"><?php if (!empty($sid) && !empty($info['content'])):?><?=$info['content']?><?php endif;?></textarea>
                                    </div>
                                </div>
                                -->
                                <div class="form-group">
                                    <label for="image" class="control-label col-md-2">头像</label>
                                    <div class="col-md-8">
                                        <div id="image-view" style="padding: 10px 0;">
                                            <?php if (!empty($cid) && !empty($info['image'])):?>
                                            <img src="<?=getImageUrl($info['image'])?>" style="width:150px;" />
                                            <?php endif;?>
                                        </div>
                                        <span class="btn btn-warning fileinput-button">
                                            <i class="glyphicon glyphicon-plus"></i>
                                            <span>上传图片</span>
                                            <input id="fileupload" type="file" name="image" multiple data-url="http://w.image.sports.baofeng.com/save?token=xVFpX0RU" />
                                        </span>
                                        <input id="cover" type="hidden" name="cover" 
                                        <?php if (!empty($cid) && !empty($info['image'])):?>
                                        value="<?=$info['image']?>"
                                        <?php endif;?>
                                        >
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
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?=$info['title']?> - 添加新闻</h3>
                                <div class="box-tools pull-right">
                                    <span style="color: #f90; line-height: 30px;"><i class="fa fa-info"></i> 提示：</span>
                                    点击列表中的排序可以修改
                                </div>
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>排序</th>
                                            <th>新闻ID</th>
                                            <th>标题</th>
                                            <th>摘要</th>
                                            <th>发布时间</th>
                                            <th>状态</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($news as $key => $item): ?>
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0)" class="item-text-edit-sort" data-type="text" data-nid="<?=$cid?>" data-pk="<?=$item['tid']?>" data-name="priority" data-title="修改排序">
                                                    <?=$key+1?>
                                                </a>
                                            </td>
                                            <td><?=$item['id']?></td>
                                            <td><?=$item['title']?></td>
                                            <td><?=$item['subtitle']?></td>
                                            <td><?=$item['publish_tm']?></td>
                                            <td><?=$item['visible'] == 1 ? '正常' : '下线'?></td>
                                            <td>
                                                <!-- <a class="btn btn-flat btn-warning btn-xs" role="button" href="#"><i class="fa fa-edit"></i> 编辑</a> -->
                                                <a class="btn btn-flat btn-danger btn-xs btn-remove" role="button" href="javascript:void(0);" data-nid="<?=$item['id']?>" data-cid="<?=$cid?>"><i class="fa fa-remove"></i> 删除</a>
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
                                        <a class="btn btn-default btn-flat" role="button" href="<?php echo site_url('/column/newsSearch/').$cid.'/news'?>"><i class="fa fa-edit"></i> 添加新闻</a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <a class="btn btn-default btn-flat" role="button" href="<?=base_url('/column/getlist')?>"><i class="fa fa-reply"></i> 返回列表</a>
                                    </div>
                                </div>
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
<script src="/static/fileupload/scripts/jquery.fileupload.js"></script>

<!-- AdminLTE App -->
<script src="/static/dist/js/app.min.js"></script>
<script src="/static/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
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
    $("#column").submit();
});

$('#column').on('submit',function(){
    alert('ok');
});

$.fn.editable.defaults.url = '<?=base_url("/column/updateSort")?>';
$('.item-text-edit-sort').editable({
    params : function(param){
        param.column_id = <?=$cid?>;
        return param;
    },
    success : function(respons,newValue){
        if(respons == 'success'){
            window.location.reload();
        }
    }
});

var alertConfig = {
    title: "你确定要删除吗?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dd4b39",
    confirmButtonText: "删除",
    cancelButtonText: "关闭",
    closeOnConfirm: false
};
$('.btn-remove').on('click',function(){
    var nid = $(this).data('nid');
    var cid = $(this).data('cid');
    swal(alertConfig,function(){
        $.get('<?=base_url("/column/removeNews")?>/' + cid + '/' + nid,function(d){
            if(d == 'success'){
                swal({title : "删除成功!",text : "新闻已从专栏删除！",type : "success"},function(){
                    window.location.reload();
                });
            }else{
                swal("删除失败!", "新闻从专栏删除失败，请重试！", "error");
            }
        });
    });
});
</script>
</body>
</html>
