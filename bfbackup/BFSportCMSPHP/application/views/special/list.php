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
              <h1>专题管理<small>Special </small></h1>
              <ol class="breadcrumb">
                <li><a href="/main/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><a href="/special"><i class="fa"></i> 专题列表</a></li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-body">
                                <div class="col-md-3">
                                    <form id="search-form" method="post">
                                        <div class="input-group">
                                            <input id="keyword" class="form-control" placeholder="请输入关键词或专辑ID" type="text" name="keyword" value="<?=$keyword ?>" />
                                            <span class="input-group-btn"><button class="btn bg-purple btn-flat" type="submit"><i class="fa fa-search"></i> 搜索</button></span>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-9 text-right">
                                    <a class="btn btn-success btn-flat" role="button" href="<?=base_url('/special/add')?>"><i class="fa fa-plus"></i> 添加专题</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">专题列表</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <!--  <th width="40"></th-->
                                            <th>专题ID</th>
                                            <th>专题名称</th>
                                            <th>图片</th>
                                            <th>导语</th>
                                            <th>标签</th>
                                            <th>下线</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($allSpecial as $key => $special): ?>
                                        <tr>
                                            <!--  <td><input type="checkbox" value="<\?=$special['id']?>" class="more-box" /></td-->
                                            <td><?=$special['id']?></td>
                                            <td style="max-width:400px;"><?=$special['title']?></td>
                                            <td><?php if ($special['image']) { ?><img src="<?=getImageUrl($special['image'])?>" style="width: 100px;" title="<?=$special['title'] ?>" /><?php } ?></td>
                                            <td>
                                                <button type="button" class="btn btn-default" data-toggle="tooltip"  title="<?=$special['brief'] ?>">查看</button>
                                            </td>
                                            <td><?php if ($special['tags']) { echo $special['tags'];} else { echo '无';} ?></td>
                                            <td>
                                                <input data-pk="<?= $special['id'] ?>" name="visible"
                                                       class="release" type="checkbox"
                                                    <?php if ($special['visible']): ?>
                                                        checked
                                                    <?php endif; ?> >
                                            </td>
                                            <td>
                                                <!--  <a class="btn btn-flat btn-info btn-xs" role="button" href="#"><i class="fa fa-eye"></i> 预览</a>-->
                                                <a class="btn btn-flat btn-warning btn-xs" role="button" href="<?=base_url('/special/add?id='.$special['id'])?>"><i class="fa fa-edit"></i> 编辑</a>
                                                <!--  <a class="btn btn-flat btn-danger btn-xs btn-remove" role="button" href="javascript:void(0);" data-nid="<\?=$special['id']?>"><i class="fa fa-remove"></i> 删除</a>-->
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
                                    <div class="col-md-2">
                                        <table style="display:none;">
                                            <tr>
                                                <td width="30" align="center"><input id="select-all" type="checkbox" name="" /></td>
                                                <td>
                                                    <input id="batch-id" type="hidden" value="" />
                                                    <button id="batch-remove" class="btn btn-flat btn-danger btn-xs">批量删除</button>
                                                </td>
                                            </tr>
                                        </table>
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
<script>
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
    var specialId = $(this).data('nid');
    var tr = $(this).parents('tr');
    var target = this;
    if(this.id == 'batch-remove') specialId = $('#batch-id').val();

    swal(alertConfig,function(){
        $.post('<?=base_url("/special/remove")?>',{id : specialId},function(d){
            if(d == 'success'){
                swal({title : "删除成功!",text : "专题已被删除！",type : "success"},function(){
                    if(target.id == 'batch-remove') window.location.reload();
                    tr.fadeOut('fast',function(){$(this).remove();});
                });
            }else{
                swal("删除失败!", "专题删除失败，请重试！", "error");
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

$("input[name='visible']").bootstrapSwitch({
    size: 'mini',
    onText: '上线',
    offText: '下线',
    onSwitchChange: function (event, state) {
        $.ajax({
            method: "POST",
            url: "<?=base_url('/special/upstatus') ?>",
            data: {name: "visible", value: parseInt(Number(state)), pk: $(this).data('pk')}
        });
    }
});
</script>
</body>
</html>
