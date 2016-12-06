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
              <h1>合集列表</h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">合集列表</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-body">
                                <div class="col-md-12 text-right">
                                    <a class="btn btn-success btn-flat" role="button" href="<?=base_url('/notification/add/').$platf?>"><i class="fa fa-plus"></i> 添加推送</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="pending-video-list">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">推送列表</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>推送id</th>
                                            <?php if ($platf=='android'):?>
                                            <th>推送标题</th>
                                            <?php endif;?>
                                            <th>推送描述</th>
                                            <th>类型</th>
                                            <th>内容id/url</th>
                                            <th>操作</th>
                                            <th>推送至友盟</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($list as $notification):?>
                                        <tr>
                                            <td><?=$notification['id']?></td>
                                            <?php if ($platf=='android'):?>
                                            <td><?=$notification['title']?></td>
                                            <?php endif;?>
                                            <td><?=$notification['desc']?></td>
                                            <td><?=$ntypes[$notification['type']]?></td>
                                            <td>
                                                <?php if ($notification['type'] == 'h5'):?>
                                                <?=$notification['url']?>
                                                <?php else:?>
                                                <?=$notification['ref_id']?>
                                                <?php endif;?>
                                            </td>
                                            <td>
                                                <a href="<?php echo site_url('notification/edit/').$notification['id'].'?redirect='.current_url()?>"><button type="button" class="btn btn-info btn-xs" >编辑</button></a>
                                            </td>
                                            <td>
                                                <?php if ($notification['send_stat'] != 0):?>
                                                <span>已发送</span>
                                                <?php else:?>
                                                <a class="btn btn-flat btn-danger btn-xs btn-send" role="button" href="javascript:void(0);" 
                                                data-cid="<?=$notification['id']?>" data-type="<?=$notification['type']?>" data-ref="<?=$notification['ref_id']?>">发送</a>
                                                <?php endif;?>
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
//--send alert-------------------------------------------------------------
var alertConfig = {
    title: "你确定要发送吗?",
    text: "发送后不可恢复，请谨慎操作!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dd4b39",
    confirmButtonText: "确定发送",
    cancelButtonText: "取消",
    closeOnConfirm: false,
    html: true
};
$('.btn-send').on('click',function(){
    var notificationId = $(this).data('cid');
    var type = $(this).data('type');
    var refId = $(this).data('ref');
    var platf = '<?=$platf?>';

    alertConfig.text = "发送后不可恢复，请谨慎操作! <br />关联内容类型："+type+"<br />关联内容id："+refId;
    $.post('<?=base_url("/notification/getRelInfo")?>',{type : type, ref_id : refId},function(d){
        if (d!='fail') {
            var rtn=JSON.parse(d);
            alertConfig.text = "发送后不可恢复，请谨慎操作! <br />关联内容类型："+type+"<br />关联内容id："+refId+"<br />关联内容标题："+rtn.title;
        }

        swal(alertConfig,function(){
            $.post('<?=base_url("/notification/send")?>',{id : notificationId, platf : platf},function(d){
                if(d == 'success'){
                    swal({title : "发送成功!",text : "推送内容已被发送！",type : "success"},function(){
                        window.location.reload();
                    });
                }else{
                    swal("发送失败!", "发送失败，请重试！", "error");
                }
            });
        });
    });
});
</script>
</body>
</html>
