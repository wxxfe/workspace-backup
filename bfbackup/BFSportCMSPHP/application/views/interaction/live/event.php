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
            <section class="content">
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title pull-left" style="line-height: 200%;">全屏互动事件</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>赛场事件</th>
                                            <th>ID</th>
                                            <th>事件文案</th>
                                            <th>互动道具</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($full_event as $event):?>
                                        <tr>
                                            <td><?=$event['title']?></td>
                                            <td><?=$event['id']?></td>
                                            <td><?=$event['desc']?></td>
                                            <td class="tool-checkbox">
                                                <?php foreach ($event['tools_info'] as $tool_info):?>
                                                <input type="checkbox" class="tool-item" data-eid="<?=$event['id']?>" data-tid="<?=$tool_info['live_tool_id']?>" /><?=$tool_info['title']?>
                                                <?php endforeach;?>
                                            </td>
                                            <td>
                                                <a class="btn btn-flat btn-danger btn-xs btn-send" role="button" href="javascript:void(0);" data-eid="<?=$event['id']?>"><i class="fa fa-send"></i> 发送</a>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title pull-left" style="line-height: 200%;">普通互动事件</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>赛场事件</th>
                                            <th>ID</th>
                                            <th>事件文案</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($normal_event as $event):?>
                                        <tr>
                                            <td><?=$event['title']?></td>
                                            <td><?=$event['id']?></td>
                                            <td><?=$event['desc']?></td>
                                            <td>
                                                <a class="btn btn-flat btn-danger btn-xs btn-send" role="button" href="javascript:void(0);" data-eid="<?=$event['id']?>"><i class="fa fa-send"></i> 发送</a>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
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
                                <h3 class="box-title">已发送事件列表</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>事件</th>
                                            <th>道具</th>
                                            <th>时间</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($records as $record):?>
                                        <tr>
                                            <td><?=$record['event_title']?></td>
                                            <td><?php echo join(',', $record['tools_name'])?></td>
                                            <td><?=$record['created_at']?></td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="box box-solid">
                            <div class="box-body">
                                <div class="row">
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
    title: "你确定要发送吗?",
    text: "发送事件不可撤销，请谨慎操作!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dd4b39",
    confirmButtonText: "确定发送",
    cancelButtonText: "取消",
    closeOnConfirm: false
};
$('.btn-send').on('click',function(){
    var eventId = $(this).data('eid');
    var hostId = "<?=$host_id?>";
    var tr = $(this).parents('tr');
    var toolIds = [];
    var allTools = tr.children('.tool-checkbox').children('.tool-item');
    allTools.each(function(){
        if ($(this).is(':checked')) {
            toolIds.push($(this).data('tid')); 
        }
    });
    var target = this;

    $(this).attr('disabled',true);
    swal(alertConfig,function(){
        $.post('<?=base_url("/interaction/live/event_live/send/").$match_id?>',{id : eventId, host_id : hostId, tool_id : toolIds},function(d){
            if(d == 'success'){
                swal({title : "发送成功!",text : "互动事件已经发送！",type : "success"},function(){
                    window.location.reload();
                });
            }else{
                swal("发送失败!", "互动事件发送失败，请重试！", "error");
            }
        });
    });
});
</script>
</body>
</html>
