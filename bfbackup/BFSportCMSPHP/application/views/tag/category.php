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
              <h1>标签管理<small>Tag</small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">分类管理</li>
              </ol>
            </section>
            <section class="content">
                <?php if ($this->AM->canInsert()): ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-body">
                                <div class="col-md-3">
                                    <form id="add-form" method="post" action="<?=site_url('/tag/category')?>">
                                        <div class="input-group">
                                            <input id="name" class="form-control" placeholder="请输入分类名称" type="text" name="name" />
                                            <span class="input-group-btn"><button class="btn bg-green btn-flat" type="submit"><i class="fa fa-plus"></i> 新增分类</button></span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-md-12" id="">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">分类列表</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>分类</th>
                                            <th width="40%">名称</th>
                                            <th>启用</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($list as $cate): ?>
                                        <tr>
                                            <td><?=$cate['type']?></td>
                                            <td class="cate_name"><span class="<?=($cate['editable']? 'cate_name_text' : '')?>"><?=$cate['name']?></span></td>
                                            <td class="cate_visible">
                                                <input type="checkbox" <?=($cate['visible']? 'checked' : '')?> <?=($cate['editable']? '' : 'disabled="disabled"')?>>
                                            </td>
                                            <td>
                                            <?php if ($cate['editable']): ?>
                                                <input type="hidden" class="cate_id" value="<?=$cate['id']?>">
                                                <?php if ($this->AM->canModify()): ?>
                                                <a class="btn btn-flat btn-warning btn-xs cate_update" role="button"><i class="fa fa-edit"></i> 确认修改</a>
                                                <?php endif; ?>
                                                <?php if ($this->AM->canDelete()): ?>
                                                <a class="btn btn-flat btn-danger btn-xs btn-remove cate_remove" role="button"><i class="fa fa-remove"></i> 删除</a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <hr>
                                            <?php endif; ?>
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
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script src="/static/dist/js/bootstrap-switch.min.js"></script>
<script>
$('.cate_name_text').editable({
    validate: function(value) {
        if ($.trim(value) == '') {
            return '该字段不能为空！';
        }
    }
});

$("#add-form").submit(function() {
    var name = $("#name").val();
    if (!name) {
        swal('请输入名称', '', 'error');
        return false;
    }
    return true;
});

$(".cate_visible").children("input[type='checkbox']").bootstrapSwitch({
    size: 'mini',
    onText: '上线',
    offText: '下线',
    onSwitchChange: function (event, state) {
        $(this).find("input[type='checkbox']").attr("checked", parseInt(Number(state)));
    }
});

$(".cate_update").click(function() {
    var id = parseInt($(this).siblings(".cate_id").val());
    var name = $(this).parent("td").siblings(".cate_name").text();
    var visible = $(this).parent("td").siblings(".cate_visible").find("input[type='checkbox']").is(":checked")? 1 : 0;
    var data = {id : id, name : name, visible : visible};
    $.post(
        "<?=site_url('/tag/category/update') ?>", data,
        function(result) {
            if ($.isNumeric(result)) {
                swal('修改成功', '', 'success');
            } else {
                swal('修改失败', '请重试！', 'error');
            }
        }
    );
});

$(".cate_remove").click(function() {
    var id = parseInt($(this).siblings(".cate_id").val());
    $.post(
        "<?=site_url('/tag/category/remove') ?>", {id : id},
        function(result) {
            if ($.isNumeric(result)) {
                swal('删除成功', '', 'success');
                window.location.href = "<?=site_url('/tag/category') ?>";
            } else {
                swal('删除失败', result, 'error');
            }
        }
    );
});

</script>
</body>
</html>
