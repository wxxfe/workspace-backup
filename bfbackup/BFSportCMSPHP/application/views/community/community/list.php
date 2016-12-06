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
              <h1>全部话题分类</h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">全部话题分类</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-body">
                                <div class="col-md-12 text-right">
                                    <a class="btn btn-success btn-flat" role="button" href="<?=base_url('community/community/add')?>"><i class="fa fa-plus"></i> 添加分类</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- 
                    <div class="col-md-2" style="display: none;">
                    </div>
                    -->
                    <div class="col-md-12" id="pending-video-list">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">全部话题分类</h3>
                                <!-- 
                                <div class="box-tools text-warning pull-right">
                                    <span style="color: #f90; line-height: 30px;"><i class="fa fa-info"></i> 提示：</span>点击列中的值，可以编辑。
                                </div>
                                -->
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>序号</th>
                                            <th>分类id</th>
                                            <th>标题</th>
                                            <th style="width:140px;">关联比赛</th>
                                            <th>分类图片</th>
                                            <th>包含话题数</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=0;?>
                                        <?php foreach($list as $community):?>
                                        <?php $i++?>
                                        <tr>
                                            <td><?=$i?></td>
                                            <td><?=$community['id']?></td>
                                            <td><?=$community['name']?></td>
                                            <td><?php if(empty($community['match_info'])){echo "无";}else{echo $community['match_info'];}?></td>
                                            <td><img style="width:100px;height:75px;" src="<?=$community['icon_url']?>" /></td>
                                            <td><?=$community['thread_count']?></td>
                                            <td>
                                                <a href="<?php echo site_url('community/community/edit/').$community['id']?>"><button type="button" class="btn btn-info btn-xs" >编辑</button></a>
                                                <a class="btn btn-flat btn-danger btn-xs btn-remove" role="button" href="javascript:void(0);" data-cid="<?=$community['id']?>"><i class="fa fa-remove"></i> 删除</a>
                                                <a href="<?php echo site_url('community/community/thread/').$community['id']?>"><button type="button" class="btn btn-warning btn-xs" >查看话题</button></a>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                                <?=$page?>
                            </div>
                        </div>
                    </div>
                    <!--  
                    <div class="col-md-2" id="role-view" style="display: none;">
                    </div>
                    -->
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
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
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
    var communityId = $(this).data('cid');
    var tr = $(this).parents('tr');
    var target = this;
    if(this.id == 'batch-remove') newsId = $('#batch-id').val();

    swal(alertConfig,function(){
        $.post('<?=base_url("/community/community/remove")?>',{id : communityId},function(d){
            if(d == 'success'){
                swal({title : "删除成功!",text : "话题分类已被删除！",type : "success"},function(){
                    if(target.id == 'batch-remove') window.location.reload();
                    tr.fadeOut('fast',function(){$(this).remove();});
                });
            }else{
                swal("删除失败!", "话题分类删除失败，请重试！", "error");
            }
        });
    });
});
//--editable------------------------------------------------------------
$.fn.editable.defaults.url = '<?=base_url("/collection/update")?>';
$('.item-text-edit').editable();
$('.item-select-edit-enable').editable({
    prepend: "请选择",
    source: [
        {value: 1, text: '启用'},
        {value: 0, text: '禁用'}
    ]
});
</script>
</body>
</html>
