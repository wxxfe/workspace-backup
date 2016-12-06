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
        .table>tbody>tr>td{vertical-align: middle;}
    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
    <div class="wrapper">
        <div class="content-wrapper" style="margin-left: 0;">
            <section class="content-header">
              <h1>图集管理 - 相关推荐<small>Related Gallery</small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="<?=base_url('/gallery/index')?>"><i class="fa fa-dashboard"></i> 图集列表</a></li>
                <li class="active">相关推荐</li>
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
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?=$gallery['title']?> - 相关推荐</h3>
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
                                            <th>相关图集ID</th>
                                            <th>类型</th>
                                            <th>标题</th>
                                            <th>封面</th>
                                            <th>标签</th>
                                            <th>状态</th>
                                            <th>发布时间</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($list as $key => $item): ?>
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0)" class="item-text-edit" data-type="text" data-nid="<?=$gallery['id']?>" data-pk="<?=$item['related_id']?>" data-name="priority" data-title="修改排序">
                                                    <?=$key+1?>
                                                </a>
                                            </td>
                                            <td><?=$item['id']?></td>
                                            <td>图集</td>
                                            <td><?=$item['title']?></td>
                                            <td><img width="120" src="<?php echo getImageUrl($item['image']) ?>" alt="暂无" /></td>
                                            <td>暂无</td>
                                            <td><?=$item['visible'] == 1 ? '正常' : '下线'?></td>
                                            <td><?=$item['publish_tm']?></td>
                                            <td>
                                                <a class="btn btn-flat btn-warning btn-xs" role="button" href="<?=base_url('/gallery/edit/'.$item['id'])?>"><i class="fa fa-edit"></i> 编辑</a>
                                                <a class="btn btn-flat btn-danger btn-xs btn-remove" role="button" href="javascript:void(0);" data-nid="<?=$item['related_id']?>"><i class="fa fa-remove"></i> 取消推荐</a>
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
                                    <div class="col-md-12 text-center">
                                        <a class="btn btn-default btn-flat" role="button" href="<?=base_url('/gallery/edit/update/'.$gallery['id'])?>"><i class="fa fa-edit"></i> 编辑<?=$gallery['title']?></a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <a class="btn btn-default btn-flat" role="button" href="<?=base_url('/gallery/index')?>"><i class="fa fa-reply"></i> 返回图集列表</a>
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

<script src="/static/dist/js/search-box.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script>
$.fn.editable.defaults.url = '<?=base_url("/gallery/updateSort")?>';
$('.item-text-edit').editable({
    params : function(param){
        param.gallery_id = <?=$gallery['id']?>;
        return param;
    },
    success : function(respons,newValue){
        if(respons == 'success'){
            window.location.reload();
        }
    }
});

var galleryId = <?=$gallery['id']?>;
var searchBox = new SearchBox({
    wrap : $('#searchBox'),
    route : '<?=base_url("/gallery/setRelated")?>/' + galleryId,
    type : [{type : 'gallery', name : '图集'}]
});

var alertConfig = {
    title: "你确定要取消吗?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dd4b39",
    confirmButtonText: "确定取消",
    cancelButtonText: "关闭",
    closeOnConfirm: false
};
$('.btn-remove').on('click',function(){
    var rid = $(this).data('nid');
    swal(alertConfig,function(){
        $.get('<?=base_url("/gallery/cancelRelated")?>/' + rid,function(d){
            if(d == 'success'){
                swal({title : "取消成功!",text : "新闻已被取消推荐！",type : "success"},function(){
                    window.location.reload();
                });
            }else{
                swal("取消失败!", "新闻取消推荐失败，请重试！", "error");
            }
        });
    });
});

</script>
</body>
</html>
