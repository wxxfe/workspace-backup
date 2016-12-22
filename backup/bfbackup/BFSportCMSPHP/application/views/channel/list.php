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
              <h1>频道<small>Channel </small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">频道列表</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <!-- 
                    <div class="col-md-2" style="display: none;">
                    </div>
                    -->
                    <div class="col-md-12" id="pending-video-list">
                        <div class="box">
                            <div class="box-header with-border">
                              <h3 class="box-title">频道列表</h3>
                              <div class="box-tools text-warning pull-right">
                                <a class="btn btn-success btn-flat" role="button" href="<?=site_url('channel/add')?>"><i class="fa fa-plus"></i> 添加频道</a>
                              </div>
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>排序</th>
                                            <th>频道id</th>
                                            <th>频道名称</th>
                                            <th>关联赛事</th>
                                            <th>默认定制</th>
                                            <th>上线</th>
                                            <th>HOT</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($list as $key => $channel):?>
                                        <tr>
                                          <td>
                                            <a href="javascript:void(0)" class="item-text-edit-sort" data-type="text" data-pk="<?=$channel['id']?>" data-name="priority" data-title="修改排序">
                                                <?=$key+1?>
                                            </a>
                                          </td>
                                          <td><?=$channel['id']?></td>
                                          <td><?=$channel['name']?></td>
                                          <td>
                                          <?php if ($channel['type'] == 'event'):?>
                                          <?=$channel['event_name']?>
                                          <?php endif;?>
                                          </td>
                                          <td>
                                          <!-- 非headline(头条)类型的频道，才可以设置定制 -->
                                          <?php if ($channel['type'] != 'headline'):?>
                                                <a href="javascript:void(0)" class="item-select-edit-visibility" data-type="select" data-pk="<?=$channel['id']?>" data-name="visibility" data-title="默认定制">
                                                    <?php if($channel['visibility'] == 'default'): ?>
                                                    定制
                                                    <?php elseif($channel['visibility'] == 'hidden'): ?>
                                                    隐藏
                                                    <?php endif; ?>
                                                </a>
                                          <?php endif;?>
                                          </td>
                                          <td>
                                              <input data-pk="<?= $channel['id'] ?>" name="visible"
                                                     class="release" type="checkbox"
                                                  <?php if ($channel['visible']): ?>
                                                      checked
                                                  <?php endif; ?>
                                              >
                                          </td>
                                          <td>
                                              <input data-pk="<?= $channel['id'] ?>" name="ishot"
                                                     class="release" type="checkbox"
                                                  <?php if ($channel['ishot']): ?>
                                                      checked
                                                  <?php endif; ?>
                                              >
                                          </td>
                                          <td>
                                            <a href="<?php echo site_url('channel/edit/'.$channel['id'])?>"><button type="button" class="btn btn-info btn-xs" >编辑</button></a>
                                            <a class="btn btn-flat btn-danger btn-xs btn-remove" role="button" href="javascript:void(0);" data-cid="<?=$channel['id']?>"><i class="fa fa-remove"></i> 删除</a>
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
<script src="/static/dist/js/bootstrap-switch.min.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script>
// --remove alert-------------------------------------------------------------
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
    var channelId = $(this).data('cid');
    var tr = $(this).parents('tr');
    var target = this;
    if(this.id == 'batch-remove') newsId = $('#batch-id').val();

    swal(alertConfig,function(){
        $.post('<?=base_url("/channel/remove")?>',{id : channelId},function(d){
            if(d == 'success'){
                swal({title : "删除成功!",text : "频道已被删除！",type : "success"},function(){
                    if(target.id == 'batch-remove') window.location.reload();
                    tr.fadeOut('fast',function(){$(this).remove();});
                });
            }else{
                swal("删除失败!", "频道删除失败，请重试！", "error");
            }
        });
    });
});


//--editable------------------------------------------------------------
$.fn.editable.defaults.url = '<?=base_url("/channel/update")?>';
$('.item-select-edit-visibility').editable({
    prepend: "请选择",
    source: [
        {value: 'default', text: '定制'},
        {value: 'hidden', text: '隐藏'}
    ]
})
//--
$("input[name='visible']").bootstrapSwitch({
    size: 'mini',
    onText: '上线',
    offText: '下线',
    onSwitchChange: function (event, state) {
        $.ajax({
            method: "POST",
            url: "<?=base_url("/channel/update")?>",
            data: {name: "visible", value: parseInt(Number(state)), pk: $(this).data('pk')}
        });
    }
});
$("input[name='ishot']").bootstrapSwitch({
    size: 'mini',
    onText: 'hot',
    offText: 'cold',
    onSwitchChange: function (event, state) {
        $.ajax({
            method: "POST",
            url: "<?=base_url("/channel/update")?>",
            data: {name: "ishot", value: parseInt(Number(state)), pk: $(this).data('pk')}
        });
    }
});
$.fn.editable.defaults.url = '<?=base_url("/channel/updateSort")?>';
$('.item-text-edit-sort').editable({
    success : function(respons,newValue){
        if(respons == 'success'){
            window.location.reload();
        }
    }
});
</script>
</body>
</html>
