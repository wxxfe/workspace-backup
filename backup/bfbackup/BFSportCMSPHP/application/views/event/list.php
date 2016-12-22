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
              <h1>赛事分类<small>Event</small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">赛事分类</li>
              </ol>
            </section>
            <section class="content">
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
                                            <th width="15%">排序</th>
                                            <th width="15%">分类id</th>
                                            <th width="15%">分类名称</th>
                                            <th width="40%">分类图标</th>
                                            <th width="15%">上线</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $events = array_values($events); ?>
                                        <?php foreach($events as $key => $event): ?>
                                        <tr>
                                            <td>
                                                <a class="edit-priority" data-type="text" data-pk="<?=$event['id']?>" data-name="priority" data-title="修改排序">
                                                <?=($key+1)?>
                                                </a>
                                            </td>
                                            <td class="event_id"><?=$event['id']?></td>
                                            <td><?=$event['name']?></td>
                                            <td>
                                                <input type="hidden" class="image" value="<?=$event['image']?>">
                                                <div class="image-view" style="padding: 10px 0;">
                                                <?php if (!empty($event['image'])): ?><img src="<?=getImageUrl($event['image'])?>" style="max-width: 35%;" alt="暂无"><?php endif; ?>
                                                </div>
                                                <span class="btn btn-xs btn-warning fileinput-button">
                                                    <i class="glyphicon glyphicon-plus"></i>
                                                    <span>选择图片</span>
                                                    <input class="fileupload-image" type="file" name="image" multiple data-url="<?=IMG_UPLOAD?>" />
                                                </span>
                                                <span class="btn btn-xs btn-info change-image hidden">
                                                    <i class="fa fa-upload"></i>
                                                    <span>确定上传</span>
                                                </span>
                                                <span class="tip"></span>
                                            </td>
                                            <td>
                                                <input data-pk="<?=$event['id'] ?>" class="event-visible" type="checkbox"
                                                    <?php if ($event['visible']): ?>checked<?php endif; ?>
                                                    <?php if (!$this->AM->canModify()): ?>disabled<?php endif; ?>
                                                >
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
<script src="/static/fileupload/scripts/jquery.ui.widget.js"></script>
<script src="/static/fileupload/scripts/jquery.fileupload.js"></script>
<script>
$.fn.editable.defaults.url = '<?=site_url("/event/setPriority/event")?>';
$('.edit-priority').editable({
    validate: function(value) {
        if($.trim(value) == '' || !($.trim(value) > 0)) {
            return '请输入大于0的整数！';
        }
    },
    success : function(response) {
        response = JSON.parse(response);
        console.log(response);
        if (response.status >= 0) {
            window.location.reload();
        } else {
            swal(response.msg, "", "error");
            return false;
        }
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
            $(e.target).parent().siblings(".image-view").html('<img src="http://image.sports.baofeng.com/' + data.result.data.pid + '" style="max-width:35%;">').show();
            $(e.target).parent().siblings(".image").val(data.result.data.pid);
            $(e.target).parent().siblings(".change-image").removeClass("hidden");
        }
    }
});

$(".change-image").click(function() {
    var $this = $(this);
    var id = $(this).parent().siblings(".event_id").text();
    var image = $(this).siblings(".image").val();
    $.ajax({
        method: "POST",
        url: "<?=site_url("/event/setField/event/image") ?>",
        data: {id: id, image: image},
        success: function() {
            $this.siblings(".tip").html("succ").show().hide(2000);
            $this.addClass("hidden");
        }
    });
});

$(".event-visible").bootstrapSwitch({
    size: 'mini',
    onText: '上线',
    offText: '下线',
    onSwitchChange: function (event, state) {
        $.ajax({
            method: "POST",
            url: "<?=site_url("/event/setvisible/event") ?>",
            data: {name: "visible", value: parseInt(Number(state)), pk: $(this).data('pk')}
        });
    }
});

</script>
</body>
</html>
