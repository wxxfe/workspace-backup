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
              <h1>视频列表<small>可用 </small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">视频列表</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-body">
                                <div class="col-md-6 no-padding">
                                    <form id="search-form" method="get">
                                        <div class="input-group">
                                            <input id="keyword" class="form-control" placeholder="请输入关键词或视频ID" type="text" name="keyword" <?php if (!empty($keyword)):?>value="<?=$keyword?>"<?php endif;?> />
                                            <span class="input-group-btn"><button class="btn bg-purple btn-flat" type="submit"><i class="fa fa-search"></i> 搜索</button></span>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a class="btn btn-default btn-flat" role="button" href="<?=base_url('/box/collection/edit/').$collection_id?>"><i class="fa fa-reply"></i> 返回编辑页面</a>
                                    <a class="btn btn-default btn-flat" role="button" href="<?=base_url('/box/collection')?>"><i class="fa fa-reply"></i> 返回合集列表</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="pending-video-list">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">可用列表</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>标题</th>
                                            <th>视频id</th>
                                            <th>封面图</th>
                                            <th>上下线</th>
                                            <th>vid</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=0;?>
                                        <?php foreach($list as $video):?>
                                        <?php $i++?>
                                        <tr>
                                            <td><?=$i?></td>
                                            <td style="max-width: 300px;"><?=$video['title']?></td>
                                            <td><?=$video['id']?></td>
                                            <td><img style="width:100px;height:75px;" src="<?=$video['image_url']?>" /></td>
                                            <td><?=$video['visible'] == 1 ? '正常' : '下线'?></td>
                                            <td><?=$video['box_vid']?></td>
                                            <td>
                                                <?php if(in_array($video['id'], $collection_videos_ids)):?>
                                                    <a class="btn btn-flat btn-warning btn-xs" href="javascript:void(0);" role="button">
                                                        <i class="fa fa-check-square"></i>已加入</a>
                                                <?php else:?>
                                                    <a class="btn btn-flat btn-info btn-xs" data-vid="<?=$video['id']?>" data-cid="<?=$collection_id?>" data-box_vid="<?=$video['box_vid']?>" href="javascript:void(0);" role="button">
                                                        <i class="fa fa-plus"></i>加入</a>
                                                <?php endif;?>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>

                                    </tbody>
                                </table>
                                <?=$page?>
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
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script>
//--editable------------------------------------------------------------
$.fn.editable.defaults.url = '<?=base_url("/video/update")?>';
$('.item-text-edit').editable();
$('.item-select-edit-enable').editable({
    prepend: "请选择",
    source: [
        {value: 1, text: '启用'},
        {value: 0, text: '禁用'}
    ]
});
var alertConfig = {
    add:{
        title: "你确定要添加吗?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#00a65a",
        confirmButtonText: "添加",
        cancelButtonText: "关闭",
        closeOnConfirm: false
    },
    sync:{
        title: "你确定要同步吗?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#00a65a",
        confirmButtonText: "同步",
        cancelButtonText: "关闭",
        closeOnConfirm: false
    }
};

//加入合集
$('.btn-info').on('click',function(){
    var vid = $(this).data('vid');
    var cid = $(this).data('cid');
    $.get('<?php echo site_url("box/collection/addVideo/")?>' + cid + '/' + vid, function(data){
        var jsonData = $.parseJSON(data);
        if(jsonData.status > 0){
            alert(jsonData.errmsg);
        }else{
            window.location.href="<?php echo site_url('box/collection/edit/'.$collection_id);?>";
        }
    });
});
</script>
</body>
</html>
