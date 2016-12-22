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
                                <div class="col-md-3">
                                    <form id="search-form" method="get">
                                        <div class="input-group">
                                            <input id="keyword" class="form-control" placeholder="请输入关键词或视频ID" type="text" name="keyword" <?php if (!empty($keyword)):?>value="<?=$keyword?>"<?php endif;?> />
                                            <span class="input-group-btn"><button class="btn bg-purple btn-flat" type="submit"><i class="fa fa-search"></i> 搜索</button></span>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-9 text-right">
                                    <a class="btn btn-default btn-flat" role="button" href="<?php echo base_url('/collection/edit/').$collection_id?>"><i class="fa fa-reply"></i> 返回</a>
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
					<input type="hidden" id="coll_id" value="<?=$collection_id?>">
                                        <?php $i=0;?>
                                        <?php foreach($list as $video):?>
                                        <?php $i++?>
                                        <tr>
					    <td><input type="checkbox" name="btn-success-many" value="<?=$video['id']?>"></td>
                                            <td><?=$i?></td>
                                            <td><?=$video['id']?></td>
                                            <td><?=$video['title']?></td>
                                            <td><img style="width:100px;height:75px;" src="<?=$video['image_url']?>" /></td>
                                            <td><?=$video['tag_str']?></td>
                                            <td><?=$video['visible'] == 1 ? '正常' : '下线'?></td>
                                            <td>
                                                <a class="btn btn-flat btn-success btn-xs" data-vid="<?=$video['id']?>" data-cid="<?=$collection_id?>" href="javascript:void(0);" role="button">
                                                <i class="fa fa-edit"></i>加入合集</a>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>

                                    </tbody>
                                </table>
				 <input type="button" name="checkbox_submit" class="checkbox_submit" value="选中加入合集">
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
    title: "你确定要添加吗?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#00a65a",
    confirmButtonText: "添加",
    cancelButtonText: "关闭",
    closeOnConfirm: false
};
$('.btn-success').on('click',function(){
    var vid = $(this).data('vid');
    var cid = $(this).data('cid');
    swal(alertConfig,function(){
        window.location.href='<?php echo site_url("collection/addVideo/")?>' + cid + '/' + vid;
    });
});
$('.checkbox_submit').on('click',function(){
    var cid = $("#coll_id").val();
    var adIds = "";  
    $("input:checkbox[name=btn-success-many]:checked").each(function(i){  
        if(0==i){  
            adIds = $(this).val();  
        }else{
            adIds += ("_"+$(this).val());  
        }  
     }); 
    if(adIds == null || adIds == "")
    {
	alert("没有选中任何视频！！");
	return false;
    } 
    swal(alertConfig,function(){
        window.location.href='<?php echo site_url("collection/addManyVideo/")?>' + cid + '/' + adIds;
    });
});
</script>
</body>
</html>
