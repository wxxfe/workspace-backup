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
    <link rel="stylesheet" href="/static/dist/css/bootstrap-switch.min.css">
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
              <h1>专用合集</h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
                <li class="active">专用合集</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-body">
                                <div class="col-md-10 no-padding">
                                    <form id="search-form" method="get">
                                        <div class="input-group">
                                            <input id="keyword" class="form-control" placeholder="请输入合集标题或合集ID" type="text" name="keyword" <?php if (!empty($keyword)):?>value="<?=$keyword?>"<?php endif;?> />
                                            <span class="input-group-btn"><button class="btn bg-purple btn-flat" type="submit"><i class="fa fa-search"></i> 搜索</button></span>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-2">
                                    <a class="btn btn-success btn-flat" role="button" href="<?=base_url('/box/collection/addstep1')?>"><i class="fa fa-plus"></i> 添加合集</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="pending-video-list">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">列表</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>合集id</th>
                                            <th>标题</th>
                                            <th>封面图</th>
                                            <th>视频数量</th>
                                            <th>aid</th>
                                            <th>同步状态</th>
                                            <th>最近同步时间</th>
                                            <th>上下线</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($list as $collection):?>
                                        <tr>
                                            <td><?=$collection['id']?></td>
                                            <td><?=$collection['title']?></td>
                                            <td><img style="width:100px;height:75px;" src="<?=$collection['image_url']?>" /></td>
                                            <td><?=$collection['videos_count']?></td>
                                            <td><?=$collection['box_aid']?></td>
                                            <td><?=$collection['status_sync'] == 0 ? "未同步" : ($collection['status_sync'] == 1 ? "同步成功" : "同步失败")?></td>
                                            <td><?=$collection['status_sync'] > 0 ? $collection['publish_tm'] : ''?></td>
                                            <td>
                                                <input data-pk="<?=$collection['id'] ?>" name="visible"
                                                       class="release" type="checkbox"
                                                    <?php if ($collection['visible']): ?>
                                                        checked
                                                    <?php endif; ?>
                                                >
                                            </td>
                                            <td>
                                                <a class="btn btn-flat btn-success btn-xs btn-rsync" role="button" href="javascript:void(0);" data-cid="<?=$collection['id']?>"><i class="fa fa-refresh"></i> 同步</a>
                                                <a href="<?php echo site_url('box/collection/edit/').$collection['id'].'?redirect='.current_url();?>"><button type="button" class="btn btn-info btn-xs" ><i class="fa fa-edit"></i>修改</button></a>
                                                <a class="btn btn-flat btn-danger btn-xs btn-remove" role="button" href="javascript:void(0);" data-cid="<?=$collection['id']?>"><i class="fa fa-remove"></i> 删除</a>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if($page):?>
                    <div class="box box-solid">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-10 text-right">
                                    <table width="100%">
                                        <tr>
                                            <td><?=$page?></td>
                                        </tr>
                                    </table>
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
<script src="/static/dist/js/app.min.js"></script>
<script src="/static/dist/js/jstree.min.js"></script>
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script src="/static/dist/js/bootstrap-switch.min.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script>
//--remove alert-------------------------------------------------------------
var alertConfig = {
    remove:{
        title: "你确定要删除吗?",
        text: "删除后不可恢复，请谨慎操作!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dd4b39",
        confirmButtonText: "确定删除",
        cancelButtonText: "取消",
        closeOnConfirm: false
    },
    rsync:{
        title: "你确定要同步吗?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "##00a65a",
        confirmButtonText: "确定同步",
        cancelButtonText: "取消",
        closeOnConfirm: false
    }
};
$('#batch-remove,.btn-remove').on('click',function(){
    var collectionId = $(this).data('cid');
    var tr = $(this).parents('tr');
    var target = this;
    if(this.id == 'batch-remove'){
        collectionId = $('#batch-id').val();
        if(collectionId == ''){
            swal("操作失败!", "请先选择要删除的视频合集！", "error");
            return false;
        }
    }

    swal(alertConfig.remove,function(){
        $.post('<?=base_url("/box/collection/remove")?>',{id : collectionId},function(d){
            if(d == 'success'){
                swal({title : "删除成功!",text : "合集已被删除！",type : "success"},function(){
                    if(target.id == 'batch-remove') window.location.reload();
                    tr.fadeOut('fast',function(){$(this).remove();});
                });
            }else{
                swal("删除失败!", "合集删除失败，请重试！", "error");
            }
        });
    });
});

$('.btn-rsync').on('click', function(){
    var collectionId = $(this).data('cid');
    var tr = $(this).parents('tr');
    swal(alertConfig.rsync,function(){
        var status_rsync = $(tr).find("td:eq(5)").text();
        var act = status_rsync == '未同步' ? 'create' : 'update';
        $.post('<?=base_url("/box/collection/rsync")?>',{id : collectionId, act: act},function(d){
            var dt = JSON.parse(d);
            if(dt.errcode == 1){
                swal({title : "同步成功!",text : "合集内容已同步！",type : "success"},function(){
                    $(tr).find("td:eq(5)").text("同步成功");
                    $(tr).find("td:eq(6)").text(dt.publish_tm);
                    if(dt.aid){
                        $(tr).find("td:eq(4)").text(dt.aid);
                    }
                });
            }else{
                swal("同步失败!", "同步失败，请重试！", "error");
            }
        });
    });
});

//--
$("input[name='visible']").bootstrapSwitch({
    size: 'mini',
    onText: '上线',
    offText: '下线',
    onSwitchChange: function (event, state) {
        $.ajax({
            method: "POST",
            url: "<?=base_url("/box/collection/update")?>",
            data: {name: "visible", value: parseInt(Number(state)), pk: $(this).data('pk')}
        });
    }
});
</script>
</body>
</html>
