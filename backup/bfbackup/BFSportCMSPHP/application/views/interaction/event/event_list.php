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
              <h1>事件管理</h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">事件管理</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-body">
                                <div class="col-md-9">
                                    <a class="btn btn-info" role="button" href="<?php echo base_url('/interaction/event/eventhd/eventsList/').$type?>"><?php if($sport_id==0):?><i class="fa fa-flag-checkered"></i><?php endif;?> 全部</a>
                                    <?php foreach ($sports_list as $sport_item):?>
                                    <a class="btn btn-info" role="button" href="<?php echo base_url('/interaction/event/eventhd/eventsList/').$type.'?sport_id='.$sport_item['id']?>">
                                    <?php if($sport_id==$sport_item['id']):?>
                                    <i class="fa fa-flag-checkered"></i>
                                    <?php endif;?> <?=$sport_item['name']?></a>
                                    <?php endforeach;?>
                                </div>
                                <div class="col-md-3 text-right">
                                    <a class="btn btn-success btn-flat" role="button" href="<?php echo base_url('/interaction/event/eventhd/eventAdd/').$type?>"><i class="fa fa-plus"></i> 添加事件</a>
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
                                <h3 class="box-title">事件列表</h3>
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
                                            <!-- <th width="40"></th> -->
                                            <th>赛场事件</th>
                                            <th>ID</th>
                                            <th>事件文案</th>
                                            <th>场景动画</th>
                                            <th>音效</th>
                                            <th>动画时间</th>
                                            <th>总时长</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($list as $event):?>
                                        <tr>
                                            <!-- <td><input type="checkbox" value="<?=$event['id']?>" class="more-box" /></td> -->
                                            <td><?=$event['title']?></td>
                                            <td><?=$event['id']?></td>
                                            <td><?=$event['desc']?></td>
                                            <td><img style="width:100px;height:75px;" src="<?php echo $source_path.$event['video']?>" /></td>
                                            <td><audio controls="controls"><source src="<?php echo $source_path.$event['audio']?>" type="audio/mp3"></audio></td>
                                            <td><?=$event['duration']?></td>
                                            <td><?=$event['total_duration']?></td>
                                            <td>
                                                <a href="<?php echo site_url('/interaction/event/eventhd/eventEdit/').$event['id']?>"><button type="button" class="btn btn-info btn-xs" >编辑</button></a>
                                                <a class="btn btn-flat btn-danger btn-xs btn-remove" role="button" href="javascript:void(0);" data-cid="<?=$event['id']?>"><i class="fa fa-remove"></i> 删除</a>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="box box-solid">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <table width="100%">
                                            <tr>
                                                <td><?=$page?></td>
                                                <!-- <td width="100" align="right">共 <strong class="text-info"></strong> 条</td>-->
                                            </tr>
                                        </table>
                                    </div>
                                </div>
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
<script src="/static/dist/js/bootstrap-switch.min.js"></script>
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
    var eventId = $(this).data('cid');
    var tr = $(this).parents('tr');
    var target = this;
    if(this.id == 'batch-remove'){
        toolId = $('#batch-id').val();
        if(collectionId == ''){
            swal("操作失败!", "请先选择要删除的视频合集！", "error");
            return false;
        }
    }

    swal(alertConfig,function(){
        $.post('<?=base_url("/interaction/event/eventhd/eventRemove")?>',{id : eventId},function(d){
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
function setBatchId(){
    var allBox = $('.more-box:checked'), idBox = $('#batch-id'), ids = [];
    allBox.each(function(){ ids.push($(this).val()); });
    idBox.val(ids.join(','));
}

$('#select-all,.more-box').on('change',function(){
    if(this.id == 'select-all'){
        if($(this).prop('checked')){
            $('.more-box').prop('checked','checked');
        }else{
            $('.more-box').removeAttr('checked');
        }
    }
    setBatchId();
});
</script>
</body>
</html>
