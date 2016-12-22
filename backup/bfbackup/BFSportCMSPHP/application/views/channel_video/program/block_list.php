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
    <link rel="stylesheet" href="/static/dist/css/bootstrap-switch.min.css">
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
                <h1><?=$channelInfo['name']?> - 板块管理</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"><?=$channelInfo['name']?></li>
                </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <form class="form-inline" method="post" id="block-form">
                                        <div class="form-group">
                                            <label for="name">板块名称</label>
                                            <input type="text" id="title" class="form-control" name="title" placeholder="请输入板块名称" />
                                        </div>
                                        <div class="form-group">
                                            <label for="name">排序</label>
                                            <input type="text" class="form-control" name="priority" placeholder="请输入顺序" />
                                        </div>
                                        <div class="form-group">
                                            <label for="name">是否上线</label>
                                            <select class="form-control" name="visible">
                                                <option value="0">下线</option>
                                                <option value="1">上线</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-flat btn-success"><i class="fa fa-plus"></i> 添加</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?=$channelInfo['name']?>板块</h3>
                                <div class="box-tools pull-right">
                                    <span style="color: #f90; line-height: 30px;"><i class="fa fa-info"></i> 提示：</span>
                                    点击列表中带下划线的项可以修改
                                </div>
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>排序</th>
                                            <th>板块ID</th>
                                            <th>板块名称</th>
                                            <th>创建时间</th>
                                            <th>上下线</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($blocks as $key => $item): ?>
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0)" class="item-text-sort-edit" data-type="text" data-pk="<?=$item['id']?>" data-name="priority" data-title="修改排序">
                                                    <?=$key+1?>
                                                </a>
                                            </td>
                                            <td><?=$item['id']?></td>
                                            <td>
                                                <a href="javascript:void(0)" class="item-text-title-edit" data-type="text" data-pk="<?=$item['id']?>" data-name="title" data-title="修改板块名称">
                                                    <?=$item['title']?>
                                                </a>
                                            </td>
                                            <td><?=$item['created_at']?></td>
                                            <td>
                                                <input data-pk="<?= $item['id'] ?>" name="visible"
                                                       class="release" type="checkbox"
                                                    <?php if ($item['visible']): ?>
                                                        checked
                                                    <?php endif; ?>
                                                >
                                            </td>
                                            <td>
                                                <a class="btn btn-flat btn-info btn-xs" role="button" href="<?=base_url('/ChannelVideo/program/'.$item['id'])?>"><i class="fa fa-edit"></i> 编辑资讯</a>
                                                <a class="btn btn-flat btn-danger btn-xs btn-remove" role="button" href="javascript:void(0);" data-bid="<?=$item['id']?>"><i class="fa fa-remove"></i> 删除</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
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

<script src="/static/dist/js/bvalidator.js"></script>
<script src="/static/dist/js/bvalidator.bs3form.min.js"></script>
<script src="/static/dist/js/bs3form.js"></script>

<script src="/static/dist/js/bootstrap-switch.min.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script>
$('#block-form').on('submit',function(){
    var title = $('#title');
    if(title.val() == ''){
        swal("请填写板块名称!", "名称不能为空，请填写！", "error");
        return false;
    }
});

$.fn.editable.defaults.url = '<?=base_url("/ChannelVideo/updateField/tmpl_program")?>';
$('.item-text-sort-edit').editable({
    validate: function(value) {
        if($.trim(value) == '') {
            return '请输入序号！';
        }
    },
    success : function(respons,newValue){
        if(respons == 'success'){
            window.location.reload();
        }
    }
});
$('.item-text-title-edit').editable({
    validate: function(value) {
        if($.trim(value) == '') {
            return '该项不能为空，请填写！';
        }
    },
    success : function(respons,newValue){
        if(respons == 'success'){
            //window.location.reload();
        }
    }
});

$("input[name='visible']").bootstrapSwitch({
    size: 'mini',
    onText: '上线',
    offText: '下线',
    onSwitchChange: function (event, state) {
        $.ajax({
            method: "POST",
            url: '<?=base_url("/ChannelVideo/updateField/tmpl_program")?>',
            data: {name: "visible", value: parseInt(Number(state)), pk: $(this).data('pk')}
        });
    }
});

var alertConfig = {
    title: "你确定要删除吗?",
    text: '删除后，将同时删除资讯和该板块的关联关系！',
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dd4b39",
    confirmButtonText: "确定删除",
    cancelButtonText: "取消",
    closeOnConfirm: false
};
$('.btn-remove').on('click',function(){
    var bid = $(this).data('bid');
    swal(alertConfig,function(){
        $.get('<?=base_url("/ChannelVideo/remove")?>/tmpl_program/' + bid,function(d){
            if(d == 'success'){
                swal({title : "删除成功!",text : "板块已删除，该板块下的资讯关联已删除！",type : "success"},function(){
                    window.location.reload();
                });
            }else{
                swal("删除失败!", "板块删除失败，请重试！", "error");
            }
        });
    });
});

</script>
</body>
</html>
