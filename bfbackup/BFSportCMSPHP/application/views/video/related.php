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
              <h1>视频相关推荐列表</h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">视频相关推荐列表</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-body">
                                <div class="col-md-3" id="searchBox">
                                </div>
                                <div class="col-md-9 text-right">
                                </div>
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
                                <h3 class="box-title">可用列表</h3>
                                <!-- 
                                <div class="box-tools text-warning pull-right">
                                    <span style="color: #f90; line-height: 30px;"><i class="fa fa-info"></i> 提示：</span>点击列中的值，可以编辑。
                                </div>
                                -->
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>视频id</th>
                                            <th>标题</th>
                                            <th>封面图</th>
                                            <th>标签</th>
                                            <th>上下线</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=0;?>
                                        <?php foreach($list as $video):?>
                                        <?php $i++?>
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0)" class="item-text-edit" data-type="text" data-pk="<?=$video['relate_id']?>" data-name="priority" data-title="修改排序">
                                                    <?=$i?>
                                                </a>
                                            </td>
                                            <td><?=$video['id']?></td>
                                            <td><?=$video['title']?></td>
                                            <td><img style="width:100px;height:75px;" src="<?=$video['image_url']?>" /></td>
                                            <td><?=$video['tag_str']?></td>
                                            <td><?=$video['visible'] == 1 ? '正常' : '下线'?></td>
                                            <td>
                                                <a class="btn btn-flat btn-danger btn-remove btn-xs" data-rid="<?=$video['relate_id']?>" href="javascript:void(0);" role="button">
                                                <i class="fa fa-remove"></i>取消推荐</a>
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
                                    <div class="col-md-12 text-center">
                                        <a class="btn btn-default btn-flat" role="button" href="<?=base_url('/video/detailEdit/'.$video_id)?>"><i class="fa fa-edit"></i> 编辑<?=$video_info['title']?></a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <a class="btn btn-default btn-flat" role="button" href="<?=base_url('/video/editedList')?>"><i class="fa fa-reply"></i> 返回视频列表</a>
                                    </div>
                                </div>
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

<script src="/static/dist/js/search-box.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script>
var videoId = <?=$video_id?>;
var searchBox = new SearchBox({
    wrap : $('#searchBox'),
    route : '<?=base_url("/video/setRelated")?>/' + videoId,
    type : [{type : 'video', name : '视频'}]
});

//--editable------------------------------------------------------------
$.fn.editable.defaults.url = '<?=base_url("/video/setRelatedSort")?>';
$('.item-text-edit').editable({
    params : function(param){
        param.video_id = <?=$video_id?>;
        return param;
    },
    success : function(respons,newValue){
        if(respons == 'success'){
            window.location.reload();
        }
    }
});

var alertConfig = {
    title: "你确定要取消关联吗?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#00a65a",
    confirmButtonText: "确定取消",
    cancelButtonText: "关闭",
    closeOnConfirm: false
};
$('.btn-remove').on('click',function(){
    var rid = $(this).data('rid');
    swal(alertConfig,function(){
        $.get('<?=base_url("/video/cancelRelated")?>/' + rid,function(d){
            if(d == 'success'){
                swal({title : "取消成功!",text : "视频已被取消关联！",type : "success"},function(){
                    window.location.reload();
                });
            }else{
                swal("取消失败!", "视频取消关联失败，请重试！", "error");
            }
        });
    });
});
</script>
</body>
</html>
