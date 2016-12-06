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
    <link rel="stylesheet" href="/static/fileupload/css/fileupload.css">
    <link rel="stylesheet" href="/static/fileupload/css/fileupload-ui.css">
    <link rel="stylesheet" href="/static/dist/css/bootstrap-editable.css">
    <link rel="stylesheet" href="/static/dist/css/bootstrap-switch.min.css">
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
                                <div class="col-md-3">
                                    <form id="search-form" method="get">
                                        <div class="input-group">
                                            <input id="keyword" class="form-control" placeholder="请输入关键词或视频ID" type="text" name="keyword" <?php if (!empty($keyword)):?>value="<?=$keyword?>"<?php endif;?> />
                                            <span class="input-group-btn"><button class="btn bg-purple btn-flat" type="submit"><i class="fa fa-search"></i> 搜索</button></span>
                                        </div>
                                    </form>
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
                                            <th>视频id</th>
                                            <th>标题</th>
                                            <th>来源</th>
                                            <th>封面图</th>
                                            <th style="width: 150px;">关联比赛</th>
                                            <th>时长</th>
                                            <th>标签</th>
                                            <th>box_vid</th>
                                            <th>上下线</th>
                                            <th>上传时间</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($list as $video):?>
                                        <tr>
                                            <td><?=$video['id']?></td>
                                            <td><?=$video['title']?></td>
                                            <td><?=$video['origin']?></td>
                                            <td><img style="width:100px;height:75px;" src="<?=$video['image_url']?>" /></td>
                                            <td><?php if(empty($video['match_info'])){ echo "无";}else{echo $video['match_info'];}?></td>
                                            <td><?=$video['duration']?></td>
                                            <td><?=$video['tag_str']?></td>
                                            <td><?=$video['box_vid']?></td>
                                            <td>
                                                <input data-pk="<?= $video['id'] ?>" name="visible"
                                                       class="release" type="checkbox"
                                                    <?php if ($video['visible']): ?>
                                                        checked
                                                    <?php endif; ?>
                                                >
                                            </td>
                                            <td><?=$video['publish_tm']?></td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#myModal<?=$video['id']?>">预览</button>
                                                <?php $this->load->view('common/videoPreview',array('video' => $video)); ?>

                                                <a href="<?php echo site_url('video/detailEdit/'.$video['id']).'?redirect='.current_url()?>"><button type="button" class="btn btn-info btn-xs" >编辑</button></a>
                                                <?php if ($video['need_bfonline_vid'] == 1):?>
                                                <a href="javascript:void(0);" type="button" class="btn btn-warning btn-xs btn-bindvid" data-cid="<?=$video['box_cid']?>" data-vid="<?=$video['id']?>">获取box_vid</a>
                                                <?php endif;?>
                                                <a class="btn btn-flat btn-primary btn-xs" role="button" href="<?=base_url('/video/related/'.$video['id'])?>"><i class="fa fa-link"></i> 相关推荐</a>
                                                <!-- <button type="button" class="btn btn-success btn-xs" >加入合集</button> -->
                                                <!-- <button type="button" class="btn btn-danger btn-xs" >替换未完成</button> -->
                                            </td>
                                        </tr>
                                        <?php endforeach;?>

                                    </tbody>
                                </table>
                                <?=$page?>
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
<script src="/static/dist/js/jstree.min.js"></script>
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script src="/static/dist/js/bootstrap-switch.min.js"></script>
<script src="/static/dist/js/bvalidator.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script>
//--bind vid alert-------------------------------------------------------------
var alertConfig = {
    title: "你确定要获取box_vid吗?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dd4b39",
    confirmButtonText: "确定",
    cancelButtonText: "取消",
    closeOnConfirm: false
};
$('.btn-bindvid').on('click',function(){
    var videoId = $(this).data('vid');
    var videoCid = $(this).data('cid');
    var tr = $(this).parents('tr');

    swal(alertConfig,function(){
        $.post('<?=base_url("/video/bindVid")?>',{id : videoId, cid : videoCid},function(d){
            if(d == 'success'){
                swal({title : "获取box_vid成功!",text : "已经绑定box_vid！",type : "success"},function(){
                    window.location.reload();
                });
            }else{
                swal("获取box_vid失败!", "请重试或联系管理员！", "error");
            }
        });
    });
});
$("input[name='visible']").bootstrapSwitch({
    size: 'mini',
    onText: '上线',
    offText: '下线',
    onSwitchChange: function (event, state) {
        $.ajax({
            method: "POST",
            url: "<?=base_url("/video/update")?>",
            data: {name: "visible", value: parseInt(Number(state)), pk: $(this).data('pk')},
            success:function(){
                window.location.reload();
            }
        });
    }
});



</script>
</body>
</html>
