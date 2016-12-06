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
	<link rel="stylesheet" href="/static/dist/css/bootstrap-switch.min.css">
    <link rel="stylesheet" href="/static/plugins/sweetalert-master/dist/sweetalert.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        .table>tbody>tr>td{vertical-align: middle;}
    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
    <div class="wrapper">
        <div class="content-wrapper" style="margin-left: 0;">
            <section class="content-header">
              <h1>焦点图<small>Slide </small></h1>
              <ol class="breadcrumb">
                <li><a href="/main/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">焦点图</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-body">
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-9 text-right">
                                    <a class="btn btn-success btn-flat" role="button" href="<?=base_url('/box/slide/add')?>"><i class="fa fa-plus"></i> 添加焦点图</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 user-list">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">焦点图列表</h3>
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
                                            <th>图片</th>
                                            <th>标题</th>
                                            <th>内容类型</th>
                                            <th>id</th>
                                            <th>上下线</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $types = array('video'=>'视频', 'collection'=>'合集');?>
                                        <?php 
                                        foreach($slidedata as $key => $slide): ?>
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0)" class="item-text-edit" data-type="text" data-id="<?=$slide['id']?>" data-pk="<?=$slide['priority']?>" data-name="priority" data-title="修改排序">
                                                    <?=$sortOffset+$key+1?>
                                                </a>
                                            </td>
                                            <td><?php if ($slide['image']) { ?><img src="<?=getImageUrl($slide['image'])?>" style="width: 100px;" title="<?=$slide['title'] ?>" /><?php }?></td>
                                            <td style="width:40%;"><?=$slide['title']?></td>
                                            <td><?php if (array_key_exists($slide['type'], $types)) { echo $types[$slide['type']]; } else { echo $slide['type']; } ?></td>
                                            <td>
                                                <?=$slide['ref_id']?>
                                            </td>
                                            <td>
                                                <input data-pk="<?= $slide['id'] ?>" name="visible"
                                                       class="release" type="checkbox"
                                                    <?php if ($slide['visible']): ?>
                                                        checked
                                                    <?php endif; ?> >
                                            </td>
                                            <td>
                                                <!--  <a class="btn btn-flat btn-info btn-xs" role="button" href="#"><i class="fa fa-eye"></i> 预览</a>-->
                                                <a class="btn btn-flat btn-warning btn-xs" role="button" href="<?=base_url('/box/slide/add?id='.$slide['id']).'&redirect='.current_url();?>"><i class="fa fa-edit"></i> 编辑</a>
                                                <a class="btn btn-flat btn-danger btn-xs btn-remove" role="button" href="javascript:void(0);" data-nid="<?=$slide['id']?>"><i class="fa fa-remove"></i> 删除</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <input type="hidden" class="js_update_sort_id" value="">
                                </table>
                            </div>
                        </div>
                        <div class="box box-solid">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        
                                    </div>
                                    <div class="col-md-10 text-right">
                                        <table width="100%">
                                            <tr>
                                                <td><?=$page?></td>
                                                <td width="100" align="right">共 <strong class="text-info"><?=$total?></strong> 条</td>
                                            </tr>
                                        </table>
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
<!-- AdminLTE App -->
<script src="/static/dist/js/app.min.js"></script>
<script src="/static/dist/js/bootstrap-switch.min.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script>
var alertConfig = {
    title: "你确定要删除吗?",
    text: "删除后不可恢复，请谨慎操作!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dd4b39",
    confirmButtonText: "删除",
    cancelButtonText: "取消",
    closeOnConfirm: false
};
$('#batch-remove,.btn-remove').on('click',function(){
    var slideId = $(this).data('nid');
    var tr = $(this).parents('tr');
    var target = this;
    if(this.id == 'batch-remove') slideId = $('#batch-id').val();

    swal(alertConfig,function(){
        $.post('<?=base_url("/box/slide/remove")?>',{id : slideId},function(d){
            if(d == 'success'){
                swal({title : "删除成功!",text : "焦点图已被删除！",type : "success"},function(){
                    if(target.id == 'batch-remove') window.location.reload();
                    tr.fadeOut('fast',function(){$(this).remove();});
                });
            }else{
                swal("删除失败!", "焦点图删除失败，请重试！", "error");
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


$('.item-text-edit').editable({ 
    type:'text'
});

$('.item-text-edit').click(function(){
	$('.js_update_sort_id').val($(this).data('id'));
});

$('.user-list').on('click', '.editable-submit', function(){
	var input_val = $(this).parent().prev().find('input').val(),
	   id = $('.js_update_sort_id').val();
    if (!id) {
        return false;
    }

	$.post("<?=base_url('/box/slide/updatesort') ?>", { 'id':id, 'sort':input_val }, function(json){
	    if (!json.status) {
	        window.location.reload();
	        return false;
	   }
	}, 'json');
});


$("input[name='visible']").bootstrapSwitch({
    size: 'mini',
    onText: '上线',
    offText: '下线',
    onSwitchChange: function (event, state) {
        $.ajax({
            method: "POST",
            url: "<?=base_url('/box/slide/upstatus') ?>",
            data: {name: "visible", value: parseInt(Number(state)), pk: $(this).data('pk')}
        });
    }
});
</script>
</body>
</html>
