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
              <h1>新闻管理 - 相关推荐<small>Related News</small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="<?=base_url('/news/index')?>"><i class="fa fa-dashboard"></i> 新闻列表</a></li>
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
                                <h3 class="box-title"><?=$newsInfo['title']?> - 相关推荐</h3>
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
                                            <th>资讯ID</th>
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
                                        <?php foreach($news as $key => $item): ?>
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0)" class="item-text-edit" data-type="text" data-nid="<?=$newsId?>" data-pk="<?=$item['rid']?>" data-name="priority" data-title="修改排序">
                                                    <?=$key+1?>
                                                </a>
                                            </td>
                                            <td><?=$item['info']['id']?></td>
                                            <td><?=array('news' => '新闻', 'video' => '视频', 'gallery' => '图集', 'collection'=>'合集', 'program'=>'节目', 'activity'=>'活动', 'special'=>'专题', 'thread'=>'话题')[$item['type']]?></td>
                                            <td><?=$item['info']['title']?></td>
                                            <td><img width="120" src="<?=getImageUrl($item['info']['image'])?>" alt="" /></td>
                                            <td>
                                                <?php foreach($item['info']['tags'] as $tag): ?>
                                                <span class="label label-info"><?=$tag['name']?></span>
                                                <?php endforeach; ?>
                                            </td>
                                            <td><?=$item['info']['visible'] == 1 ? '正常' : '下线'?></td>
                                            <td><?=$item['info']['publish_tm']?></td>
                                            <td>
                                                <!--<a class="btn btn-flat btn-info btn-xs" role="button" href="#"><i class="fa fa-eye"></i> 预览</a>-->
                                                <?php if($item['type'] == 'news'): ?>
                                                <a class="btn btn-flat btn-warning btn-xs" role="button" href="<?=base_url('/news/edit/update/'.$item['info']['id']).'?redirect='.current_url()?>"><i class="fa fa-edit"></i> 编辑</a>
                                                <?php elseif($item['type'] == 'video'): ?>
                                                <a class="btn btn-flat btn-warning btn-xs" role="button" href="<?=base_url('/video/detailEdit/'.$item['info']['id']).'?redirect='.current_url()?>"><i class="fa fa-edit"></i> 编辑</a>
                                                <?php elseif($item['type'] == 'gallery'): ?>
                                                <a class="btn btn-flat btn-warning btn-xs" role="button" href="<?=base_url('/gallery/edit/'.$item['info']['id']).'?redirect='.current_url()?>"><i class="fa fa-edit"></i> 编辑</a>
                                                <?php elseif($item['type'] == 'collection'):?>
                                                <a class="btn btn-flat btn-warning btn-xs" role="button" href="<?=base_url('/collection/edit/'.$item['info']['id']).'?redirect='.current_url()?>"><i class="fa fa-edit"></i> 编辑</a>
                                                <?php elseif($item['type'] == 'program'):?>
                                                <a class="btn btn-flat btn-warning btn-xs" role="button" href="<?=base_url('/program/edit/'.$item['info']['id']).'?redirect='.current_url()?>"><i class="fa fa-edit"></i> 编辑</a>
                                                <?php elseif($item['type'] == 'activity'):?>
                                                <a class="btn btn-flat btn-warning btn-xs" role="button" href="<?=base_url('/activity/edit/'.$item['info']['id']).'?redirect='.current_url()?>"><i class="fa fa-edit"></i> 编辑</a>
                                                <?php elseif($item['type'] == 'special'):?>
                                                <a class="btn btn-flat btn-warning btn-xs" role="button" href="<?=base_url('/special/add/?id='.$item['info']['id'])?>"><i class="fa fa-edit"></i> 编辑</a>
                                                <?php elseif($item['type'] == 'thread'):?>
                                                <a class="btn btn-flat btn-warning btn-xs" role="button" href="<?=base_url('/community/thread/edit/'.$item['info']['id']).'?redirect='.current_url()?>"><i class="fa fa-edit"></i> 编辑</a>
                                                <?php endif; ?>
                                                <a class="btn btn-flat btn-danger btn-xs btn-remove" role="button" href="javascript:void(0);" data-nid="<?=$item['rid']?>"><i class="fa fa-remove"></i> 取消推荐</a>
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
                                        <a class="btn btn-default btn-flat" role="button" href="<?=base_url('/news/edit/update/'.$newsId)?>"><i class="fa fa-edit"></i> 编辑<?=$newsInfo['title']?></a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <a class="btn btn-default btn-flat" role="button" href="<?=base_url('/news/index')?>"><i class="fa fa-reply"></i> 返回新闻列表</a>
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
$.fn.editable.defaults.url = '<?=base_url("/news/updateSort")?>';
$('.item-text-edit').editable({
    params : function(param){
        param.news_id = <?=$newsId?>;
        return param;
    },
    success : function(respons,newValue){
        if(respons == 'success'){
            window.location.reload();
        }
    }
});

var newsId = <?=$newsId?>;
var searchBox = new SearchBox({
    wrap : $('#searchBox'),
    route : '<?=base_url("/news/setRelated")?>/' + newsId,
    type : [
            {type : 'news', name : '新闻'},
            {type : 'video', name : '视频'},
            {type : 'gallery', name : '图集'},
            {type : 'collection', name : '合集'},
            {type : 'program', name : '节目'},
            {type : 'activity', name : '活动'},
            {type : 'special', name : '专题'},
            {type : 'thread', name : '话题'}
    ]
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
        $.get('<?=base_url("/news/cancelRelated")?>/' + rid,function(d){
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
