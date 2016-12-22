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
    <link rel="stylesheet" href="/static/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
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
              <h1>Banner管理<small>Banner </small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">比赛Banner</li>
              </ol>
            </section>
            <section class="content" style="min-height:3%;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header" id="add-banner-header" style="margin-left:16px;">
                                <span role="button" class="btn btn-info btn-flat"><i class="fa fa-plus"></i> 添加Banner</span>
                            </div>
                            <div class="box-body hidden" id="add-banner-body">
                                <form id="add-form" method="post">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="title" class="control-label col-sm-2">标题：</label>
                                                <div class="col-sm-8">
                                                    <input class="form-control" placeholder="请输入标题" type="text" name="title" id="title" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="type" class="control-label col-sm-2">类型：</label>
                                                <div class="col-sm-8">
                                                    <select class="form-control" name="type" id="type" required>
                                                        <option value="">请选择类型</option>
                                                        <?php foreach ($banner_types as $type => $name): ?>
                                                            <option value="<?=$type?>"><?=$name?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="publish_tm">日期：</label>
                                                <div class="col-sm-10">
                                                    <div class="input-append date datetimepicker">
                                                        <input value="<?=date('Y-m-d H:i:s')?>" data-format="yyyy-MM-dd hh:mm:ss" class="form-control" style="display: inline-block; width: 80%;" id="publish_tm" name="publish_tm" placeholder="请选择日期" type="text" readonly="readonly" required>
                                                        <span class="add-on" style="display: inline-block; margin-left: 5px;">
                                                            <i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="visible" class="control-label col-sm-2">上线：</label>
                                                <div class="col-sm-8">
                                                    <input type="checkbox" id="visible" name="visible">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="id_data" class="control-label col-sm-2">ID/URL：</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" placeholder="输入新闻ID或URL" type="text" name="id_data" id="id_data" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label col-sm-2">图片：</label>
                                                <div class="col-sm-10">
                                                    <span class="btn btn-warning fileinput-button">
                                                        <i class="glyphicon glyphicon-plus"></i>
                                                        <span>选择图片</span>
                                                        <input class="fileupload-image" type="file" name="image" data-url="<?=IMG_UPLOAD?>" />
                                                    </span>
                                                    <input type="hidden" name="image" class="image">
                                                    <div class="image-view" style="padding: 10px 0;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group" style="text-align:center;">
                                                <input class="btn btn-success" type="submit" value="提交">
                                                <input class="btn bg-gray" type="reset" id="reset" value="取消">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Banner列表</h3>
                                <div class="box-tools pull-right">
                                    <span style="color: #f90; line-height: 30px;"><i class="fa fa-info"></i> 提示：</span>
                                    图片建议尺寸 720*264
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                </div>
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th width="18%">推荐时间</th>
                                            <th>标题</th>
                                            <th width="20%">图片</th>
                                            <th>类型</th>
                                            <th width="25%">新闻ID/URL</th>
                                            <th>上线</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($banners as $banner): ?>
                                        <tr>
                                            <td><?=$banner['id']?></td>
                                            <td>
                                                <div class="input-append date datetimepicker" data-id="<?=$banner['id']?>">
                                                    <input value="<?=$banner['publish_tm']?>" data-format="yyyy-MM-dd hh:mm:ss" class="form-control" style="display: inline-block; width: 75%;" name="publish_tm" placeholder="请选择日期" type="text" readonly>
                                                    <span class="add-on" style="display: inline-block; margin-left: 5px;">
                                                        <i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-calendar"></i>
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="banner_title" data-url="<?=site_url('banner/setField/banner/title')?>" data-pk="<?=$banner['id']?>" data-name="title">
                                                    <?=$banner['title']?>
                                                </span>
                                            </td>
                                            <td>
                                                <input type="hidden" class="image" value="<?=$banner['image']?>">
                                                <div class="image-view" style="padding: 10px 0;">
                                                <?php if (!empty($banner['image'])): ?><img src="<?=getImageUrl($banner['image'])?>" style="max-width: 75%;" alt="暂无"><?php endif; ?>
                                                </div>
                                                <span class="btn btn-xs btn-warning fileinput-button">
                                                    <i class="glyphicon glyphicon-plus"></i>
                                                    <span>选择图片</span>
                                                    <input class="fileupload-image" type="file" name="image" multiple data-url="<?=IMG_UPLOAD?>" />
                                                </span>
                                                <span class="btn btn-xs btn-info change-image hidden" data-id="<?=$banner['id']?>">
                                                    <i class="fa fa-upload"></i>
                                                    <span>确定上传</span>
                                                </span>
                                                <span class="tip"></span>
                                            </td>
                                            
                                            <td><?=$banner_types[$banner['type']]?></td>
                                            <td>
                                                <?php if ($banner['type'] == 'news'): ?>
                                                    <span class="banner_ref" data-url="<?=site_url('banner/setField/banner/ref_id')?>" data-pk="<?=$banner['id']?>" data-name="ref_id"><?=$banner['ref_id']?></span>
                                                <?php else: ?>
                                                    <span class="banner_data" data-url="<?=site_url('banner/setField/banner/data')?>" data-pk="<?=$banner['id']?>" data-name="data"><?=$banner['data']?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <input type="checkbox" class="banner_visible" data-id="<?=$banner['id']?>" <?=($banner['visible']? 'checked' : '')?>>
                                            </td>
                                            <td><span class="remove_banner btn btn-danger btn-xs" data-id="<?=$banner['id']?>"><i class="fa fa-times"></i> 删除</span></td>
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
<script src="/static/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="/static/dist/js/bootstrap-switch.min.js"></script>
<script src="/static/fileupload/scripts/jquery.ui.widget.js"></script>
<script src="/static/fileupload/scripts/jquery.fileupload.js"></script>
<script>
$(".datetimepicker").datetimepicker().on("hide", function() {
    var id = $(this).data("id");
    if (id) { // 编辑
        var publish_tm = $(this).children("input").val();
        $.post(
            "<?=site_url('banner/setField/banner/publish_tm')?>",
            {
                id: id, publish_tm: publish_tm
            },
            function (data) {
                if (data > 0) {
                    swal("更新成功！", "", "success");
                } else {
                    swal('更新失败!','请重试！','error');
                }
            }
        );
    }
});

$("#add-banner-header").click(function() {
    $("#add-banner-body").toggleClass("hidden");
});

$("#add-form").submit(function() {
    if ($("#type").val() == "news" && !(Number($.trim($("#id_data").val())) > 0)) {
        swal("请输入正确的新闻ID", "", "error");
        return false;
    }
    return true;
});

$("#reset").click(function() {
    $("#add-banner-body").toggleClass("hidden");
    return true;
});

$("#visible").bootstrapSwitch({
    size: 'mini',
    onText: '是',
    offText: '否',
    onSwitchChange: function (event, state) {
        $("#visible").attr("checked", state);
        $("#visible").val(Number(state));
    }
});

$(".fileupload-image").fileupload({
    add: function (e, data) {
        data.submit();
    },
    done: function (e, data) {
        var result = data.result.errno;
        
        if (result !== 10000) {
            alert('上传失败,请重试！');
        } else {
            $(e.target).parent().siblings(".image-view").html('<img style="max-width:75%;" src="http://image.sports.baofeng.com/' + data.result.data.pid + '">').show();
            $(e.target).parent().siblings(".image").val(data.result.data.pid);
            $(e.target).parent().siblings(".change-image").removeClass("hidden");
        }
    }
});

$(".change-image").click(function() {
    var $this = $(this);
    var id = $(this).data("id");
    var image = $(this).siblings(".image").val();
    $.ajax({
        method: "POST",
        url: "<?=site_url("/banner/setField/banner/image") ?>",
        data: {id: id, image: image},
        success: function() {
            $this.siblings(".tip").html("succ").show().hide(2000);
            $this.addClass("hidden");
        }
    });
});

$(".banner_title").editable({
    validate: function(value) {
        if ($.trim(value) == '') {
            return '该字段不能为空！';
        }
    }
});

$(".banner_ref").editable({
    validate: function(value) {
        if (!(Number($.trim(value)) > 0)) {
            return '请填写大于0的整数！';
        }
    }
});

$(".banner_data").editable({
    validate: function(value) {
        if ($.trim(value) == '') {
            return '该字段不能为空！';
        }
    }
});

$(".banner_visible").bootstrapSwitch({
    size: 'mini',
    onText: '是',
    offText: '否',
    onSwitchChange: function (event, state) {
        $.post(
            "<?=site_url('banner/setField/banner/visible')?>",
            {
                id: $(this).data("id"), visible: Number(state)
            },
            function (data) {
                if (data > 0) {
                    swal("更新成功！", "", "success");
                } else {
                    swal('更新失败!','请重试！','error');
                }
            }
        );
    }
});

$(".remove_banner").click(function() {
    $.post(
        "<?=site_url('banner/deleteById/banner')?>",
        {
            id: $(this).data("id")
        },
        function (data) {
            if (data > 0) {
                swal("删除成功！", "", "success");
                window.location.href = window.location.href;
            } else {
                swal('删除失败!','请重试！','error');
            }
        }
    );
});

</script>
</body>
</html>
