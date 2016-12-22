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
              <h1><?=$channelInfo['name']?> - 推荐列表<small>Video List</small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">推荐列表</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-body">
                                <div class="col-md-3">
                                    <form id="search-form" method="get">
                                        <div class="input-group" style="position: relative;">
                                            <div class="input-group-btn">
                                                <button type="button" disabled class="btn btn-default dropdown-toggle btn-flat" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-right: none;"><span id="dropdown_value">视频</span></button>
                                                <input id="search-type" type="hidden" name="stype" value="video" />
                                            </div>
                                            <input id="keyword" class="form-control" placeholder="请输入关键词或ID" value="<?=$keyword?>" type="text" name="keyword" />
                                            <span class="input-group-btn"><button id="search_btn" class="btn bg-purple btn-flat" type="submit"><i class="fa fa-search"></i> 搜索</button></span>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-9 text-right">
                                    <?php if($type == 'search'): ?>
                                    <a href="<?=base_url('/ChannelVideo/channel/'.$channelId.'/top')?>" class="btn btn-default btn-flat pull-right" role="button"><i class="fa fa-reply"></i> 结束搜索</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box" style="border: none;">
                            <div class="box-header" style="padding: 0px 10px;">
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs pull-right">
                                        <?php if($type != 'search'): ?>
                                        <li <?php if($type == 'video'): ?>class="active"<?php endif; ?>><a href="<?=base_url('/ChannelVideo/channel/'.$channelId.'/video')?>">视频</a></li>
                                        <li <?php if($type == 'top'): ?>class="active"<?php endif; ?>><a href="<?=base_url('/ChannelVideo/channel/'.$channelId.'/top')?>">置顶</a></li>
                                        <?php endif; ?>
                                        <li class="pull-left header">
                                            <?php if($type != 'search'): ?>
                                            视频列表 - <?=$this->config->item('news_type')[$type]?>
                                            <?php else: ?>
                                            共为您找到 <strong class="text-danger"><?=$total?></strong> 条，
                                                <?php if(is_numeric($keyword)): ?>
                                                视频ID为<strong class="text-info"><?=$keyword?></strong> 的视频
                                                <?php else: ?>
                                                标题包含 <strong class="text-info"><?=$keyword?></strong> 的视频
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <?php if($type == 'top'): ?>
                                            <th>排序</th>
                                            <?php endif; ?>
                                            <th>标题</th>
                                            <th>封面</th>
                                            <th>类型</th>
                                            <th>视频ID</th>
                                            <th>发布时间</th>
                                            <th>预览</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            function getEditUrl($type,$id){
                                                $editUrl = '';
                                                switch($type){
                                                    case 'news':
                                                        $editUrl = base_url('/news/edit/update/'.$id);
                                                        break;
                                                    case 'video':
                                                        $editUrl = base_url('/video/detailEdit/'.$id);
                                                        break;
                                                    case 'gallery':
                                                        $editUrl = base_url('/gallery/edit/'.$id);
                                                        break;
                                                    case 'activity':
                                                        $editUrl = base_url('/activity/edit/'.$id);
                                                        break;
                                                    case 'program':
                                                        $editUrl = base_url('/activity/edit/'.$id);
                                                        break;
                                                    case 'special':
                                                        $editUrl = base_url('/activity/edit/'.$id);
                                                        break;
                                                    case 'collection':
                                                        $editUrl = base_url('/activity/edit/'.$id);
                                                        break;
                                                }
                                                return $editUrl;
                                            }
                                        ?>
                                        <?php $o = $offset; ?>
                                        <?php if(!empty($news)): ?>

                                            <?php if($type == 'top'): ?>

                                                <?php foreach($news as $key => $item): ?>
                                                <?php
                                                    $o++;
                                                    $editUrl = getEditUrl($item['type'],$item['info']['id']);
                                                ?>
                                                <tr>
                                                    <td>
                                                        <a href="javascript:void(0)" class="item-text-edit" data-type="text" data-nid="<?=$item['tid']?>" data-pk="<?=$item['tid']?>" data-name="priority" data-title="修改排序">
                                                            <?=$o?>
                                                        </a>
                                                    </td>
                                                    <td><?=$item['info']['title']?></td>
                                                    <td><img src="<?=getImageUrl($item['info']['image'])?>" style="width: 100px;" alt="" /></td>
                                                    <td><?=$this->config->item('news_type')[$item['type']]?></td>
                                                    <td><?=$item['info']['id']?></td>
                                                    <td><?=$item['info']['publish_tm']?></td>
                                                    <td>
                                                        <?php if($item['type'] == 'video'): ?>
                                                        <button type="button" class="btn btn-flat bg-purple btn-xs" data-toggle="modal" data-target="#myModal<?=$item['info']['id']?>"><i class="fa fa-eye"></i> 预览</button>
                                                        <?php $this->load->view('common/videoPreview',array('video' => $item['info'])); ?>

                                                        <?php else: ?>
                                                        <!--<a class="btn btn-flat bg-purple btn-xs" role="button" href="#"><i class="fa fa-eye"></i> 预览</a>-->

                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-flat btn-warning btn-xs" role="button" href="<?=$editUrl?>"><i class="fa fa-edit"></i> 编辑</a>
                                                        <a class="btn btn-flat btn-danger btn-xs btn-remove" role="button" href="javascript:void(0);" data-tid="<?=$item['tid']?>" data-nid="<?=$item['info']['id']?>" data-type="<?=$item['type']?>"><i class="fa fa-remove"></i> 移除标签</a>
                                                        <a class="btn btn-flat btn-primary btn-xs btn-cancel-top" role="button" href="javascript:void(0);" data-tid="<?=$item['tid']?>"><i class="fa fa-share"></i> 取消置顶</a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>

                                            <?php else: ?>

                                                <?php foreach($news as $key => $item): ?>
                                                <?php
                                                    $o++;
                                                    $editUrl = getEditUrl($type,$item['id']);
                                                ?>
                                                <tr>
                                                    <td><?=$item['title']?></td>
                                                    <td><img src="<?=getImageUrl($item['image'])?>" style="width: 100px;" alt="" /></td>
                                                    <?php if($type == 'search'): ?>
                                                    <td><?=$this->config->item('news_type')[$stype]?></td>
                                                    <?php else: ?>
                                                    <td><?=$this->config->item('news_type')[$type]?></td>
                                                    <?php endif; ?>
                                                    <td><?=$item['id']?></td>
                                                    <td><?=$item['publish_tm']?></td>
                                                    <td>
                                                        <?php if($type == 'video'): ?>
                                                        <button type="button" class="btn btn-flat bg-purple btn-xs" data-toggle="modal" data-target="#myModal<?=$item['id']?>"><i class="fa fa-eye"></i> 预览</button>
                                                        <?php $this->load->view('common/videoPreview',array('video' => $item)); ?>

                                                        <?php else: ?>
                                                        <!--<a class="btn btn-flat bg-purple btn-xs" role="button" href="#"><i class="fa fa-eye"></i> 预览</a>-->

                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-flat btn-warning btn-xs" role="button" href="<?=$editUrl?>"><i class="fa fa-edit"></i> 编辑</a>
                                                        <?php if($type == 'search'): ?>

                                                            <?php if($item['has_tag']): ?>
                                                            <a class="btn btn-flat btn-danger btn-xs btn-remove" role="button" href="javascript:void(0);" data-tid="" data-nid="<?=$item['id']?>" data-type="<?=$stype?>"><i class="fa fa-remove"></i> 移除标签</a>
                                                            <?php else: ?>
                                                            <a class="btn btn-flat btn-danger btn-xs btn-add-tag" role="button" href="javascript:void(0);" data-tid="" data-nid="<?=$item['id']?>" data-type="<?=$stype?>"><i class="fa fa-plus"></i> 添加标签</a>
                                                            <?php endif; ?>

                                                        <?php else: ?>

                                                        <a class="btn btn-flat btn-danger btn-xs btn-remove" role="button" href="javascript:void(0);" data-tid="" data-nid="<?=$item['id']?>" data-type="<?=$type?>"><i class="fa fa-remove"></i> 移除标签</a>

                                                        <?php endif; ?>

                                                        <?php if($item['is_top']): ?>
                                                        <a class="btn btn-flat btn-primary btn-xs btn-cancel-top" role="button" href="javascript:void(0);" data-rid="<?=$item['id']?>" data-tid="<?=$item['tid']?>"><i class="fa fa-share"></i> 取消置顶</a>
                                                        <?php else: ?>
                                                        <a class="btn btn-flat btn-primary btn-xs btn-set-top" role="button" data-rid="<?=$item['id']?>" data-stype="<?=$stype?>" href="javascript:void(0)"><i class="fa fa-arrow-circle-up"></i> 置顶</a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                        <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">无</td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="box box-solid">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <?php if($type != 'search'): ?>
                                        <span style="color: #f90; line-height: 30px;"><i class="fa fa-info"></i> 提示：</span>
                                        点击列表中的排序可以修改
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-9 text-right">
                                        <table width="100%">
                                            <tr>
                                                <td><?=$page?></td>
                                                <td width="100" align="right">共 <strong class="text-info"><?=$total?></strong> 条</td>
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
<!-- AdminLTE App -->
<script src="/static/dist/js/app.min.js"></script>

<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script>
$.fn.editable.defaults.url = '<?=base_url("/ChannelVideo/updateField/manual_priority")?>';
$('.item-text-edit').editable({
    success : function(respons,newValue){
        if(respons == 'success'){
            window.location.reload();
        }
    }
});
var currentType = '<?=$type?>';
var currentTag = '<?=$tagId?>';
var alertConfig = {
    title: "你确定要移除吗?",
    text: '将从该新闻中删除 <strong class="text-info"><?=$tag?></strong> 标签!',
    type: "warning",
    html: true,
    showCancelButton: true,
    confirmButtonColor: "#dd4b39",
    confirmButtonText: "确定移除",
    cancelButtonText: "取消",
    closeOnConfirm: false
};
$('.table').on('click','.btn-remove',function(){
    var newsId = $(this).data('nid');
    var tr = $(this).parents('tr');
    var type = $(this).data('type');
    var tid = $(this).data('tid');

    swal(alertConfig,function(){
        $.get('<?=base_url("/ChannelNews/removeTag")?>/' + newsId + '/' + type + '/<?=$tagId?>/' + currentType + '/' + tid,function(d){
            if(d == 'success'){
                swal({title : "移除成功!",text : "标签已从该新闻中移除！",type : "success"},function(){
                    if(currentType == 'top' || currentType == 'search'){
                        window.location.reload();
                    }else{
                        tr.fadeOut('fast',function(){$(this).remove();});
                    }
                });
            }else{
                swal("移除失败!", "标签删除失败，请重试！", "error");
            }
        });
    });
});

$('.table').on('click','.btn-set-top',function(){
    var tid = $(this).data('tid');
    var rid = $(this).data('rid');
    var stype = $(this).data('stype');
    var btn = $(this);
    if(currentType == 'search') currentType = stype;
    swal({
        title: "你确定置顶吗?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dd4b39",
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        closeOnConfirm: false
    },function(){
        $.get('<?=base_url("/ChannelNews/setTop")?>/' + currentType + '/' + rid + '/' + currentTag,function(d){
            if(d == 'success'){
                swal({title : "置顶成功!",text : "已推荐到置顶列表中！",type : "success"},function(){
                    btn.html('<i class="fa fa-share"></i> 取消置顶').removeClass('btn-set-top').addClass('btn-cancel-top');
                });
            }else{
                swal("置顶失败!", "资讯置顶失败，请重试！", "error");
            }
        });
    });
});

$('.table').on('click','.btn-cancel-top',function(){
    var tid = $(this).data('tid');
    var btn = $(this);
    swal({
        title: "你确定要取消吗?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dd4b39",
        confirmButtonText: "确定",
        cancelButtonText: "关闭",
        closeOnConfirm: false
    },function(){
        $.get('<?=base_url("/ChannelNews/cancelTop")?>/' + tid,function(d){
            if(d == 'success'){
                swal({title : "取消成功!",text : "已从推荐列表中删除！",type : "success"},function(){
                    if(currentType == 'top'){
                        window.location.reload();
                    }else{
                        btn.html('<i class="fa fa-arrow-circle-up"></i> 置顶').removeClass('btn-cancel-top').addClass('btn-set-top');
                    }
                });
            }else{
                swal("取消失败!", "推荐取消失败，请重试！", "error");
            }
        });
    });
});

/*-Search-------------------------*/
$('#select_search li a').on('click',function(){
    var val = $(this).data('type');
    var otext = $(this).text();
    $('#search-type').val(val);
    $('#dropdown_value').text(otext);
});

$('#search-form').on('submit',function(){
    var keyword = $('#keyword');
    var stype = $('#stype');
    if(keyword.val() == ''){
        swal('请输入关键词或ID！','','error');
        return false;
    }
    if(stype.val() == ''){
        swal('请选择搜索类型！','','error');
        return false;
    }
});

$('.table').on('click','.btn-add-tag',function(){
    var type = $(this).data('type');
    var nid = $(this).data('nid');
    var btn = $(this);
    $.get('<?=base_url("/ChannelNews/setTag")?>/' + currentTag + '/' + type + '/' + nid,function(d){
        if(d == 'success'){
            btn.html('<i class="fa fa-remove"></i> 移除标签').removeClass('btn-add-tag').addClass('btn-remove');
        }else{
            swal("添加失败!", "资讯置顶失败，请重试！", "error");
        }
    });
});

</script>
</body>
</html>
