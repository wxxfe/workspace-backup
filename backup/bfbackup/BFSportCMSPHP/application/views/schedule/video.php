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
	<link rel="stylesheet" href="/static/dist/css/bootstrap-switch.min.css">
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
              <h1>赛程<small>Schedule </small></h1>
              <ol class="breadcrumb">
                <li><a href="/main/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">比赛视频列表</li>
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
                                            <input id="keyword" class="form-control" placeholder="请输入关键词或视频ID" type="text" name="keyword" value="<?=$keyword ?>" />
                                            <span class="input-group-btn"><button class="btn bg-purple btn-flat" type="submit"><i class="fa fa-search"></i> 搜索</button></span>
                                        </div>
                                    </form>
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
                                <h3 class="box-title">比赛视频列表</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>标题</th>
                                            <th>封面</th>
                                            <th>视频Id</th>
                                            <th>发布时间</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($list as $key => $video): ?>
                                        <tr>
                                            <td style="width:400px;"><?=$video['title']?></td>
                                            <td><?php if ($video['image']) { ?><img src="<?=getImageUrl($video['image'])?>" style="width: 100px;" title="<?=$video['title'] ?>" /><?php } ?></td>
                                            <td><?=$video['id']?></td>
                                            <td><?=$video['created_at']?></td>
                          
                                            <td>
                                                <!--  <a class="btn btn-flat btn-info btn-xs" role="button" href="#"><i class="fa fa-eye"></i> 预览</a>-->
                                                <a class="btn btn-flat btn-warning btn-xs" role="button" href="<?=base_url('/video/detailEdit/'.$video['id'])?>"><i class="fa fa-edit"></i> 编辑</a>
                                                <?php if ($video['tag']) { ?>
                                                    <a class="btn btn-flat btn-danger btn-xs btn-remove js_remove" role="button" href="javascript:void(0);" data-id="<?=$video['id'] ?>" data-mid="<?=$match_id ?>" data-tag="<?=$video['tag'] ?>"><i class="fa fa-remove"></i> 移除<?php if ('replay' == $video['tag_name']) { echo '回放'; } else { echo '集锦'; }?></a>
                                                <?php } else { foreach ($tag_array as $k1=>$v1) {?>
                                                    <a class="btn btn-success btn-flat btn-xs js_add" role="button" href="javascript:void(0);" data-id="<?=$video['id'] ?>" data-mid="<?=$match_id ?>" data-tag="<?=$k1 ?>" ><i class="glyphicon glyphicon-plus"></i>添加到<?php if ('replay' == $v1) { echo '回放'; } else { echo '集锦'; }?></a>
                                                <?php } } ?>
                                                
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
                                    <div class="col-md-2">
   
                                    </div>
                                    <div class="col-md-10 text-right">
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
<script src="/static/dist/js/bootstrap-switch.min.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script>
var alertConfig = {
    title: "你确定要移除吗?",
    text: "你确定要移除吗?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dd4b39",
    confirmButtonText: "确定移除",
    cancelButtonText: "取消",
    closeOnConfirm: false
};


//添加
$('.js_add').click(function(){
	var id = parseInt($(this).data('id')),
	   mid = parseInt($(this).data('mid')),
	   tag = $(this).data('tag');

	   if (!id || !mid || !tag) {
		    swal("数据有误!", "数据有误，刷新后重试！", "error");
		    return false;
	   }

	   alertConfig = {
			    title: "你确定要添加吗?",
			    text: "你确定要添加吗?",
			    type: "warning",
			    showCancelButton: true,
			    confirmButtonColor: "#dd4b39",
			    confirmButtonText: "确定添加",
			    cancelButtonText: "取消",
			    closeOnConfirm: false
			};
		
	    swal(alertConfig,function(){
	 	   $.post("<?=base_url('/schedule/schedule_video/add') ?>", { 'id':id, 'mid':mid, 'tag':tag }, function(json){
			    if (!json.status) {
			    	swal({title : "添加成功!",text : "添加成功！",type : "success"},function(){
	                    //window.location.reload();
	                });
				} else {
					swal("添加失败!", "添加失败，请刷新后重试！", "error");
		       }
		   }, 'json');
	    });
	
});

//移除
$('.js_remove').click(function(){
	var id = parseInt($(this).data('id')),
	   mid = parseInt($(this).data('mid')),
	   tag = $(this).data('tag');

	   if (!id || !mid || !tag) {
		    swal("数据有误!", "数据有误，刷新后重试！", "error");
		    return false;
	   }

	   alertConfig = {
			    title: "你确定要移除吗?",
			    text: "你确定要移除吗?",
			    type: "warning",
			    showCancelButton: true,
			    confirmButtonColor: "#dd4b39",
			    confirmButtonText: "确定移除",
			    cancelButtonText: "取消",
			    closeOnConfirm: false
			};
		
	    swal(alertConfig,function(){
	 	   $.post("<?=base_url('/schedule/schedule_video/delete') ?>", { 'id':id, 'mid':mid, 'tag':tag }, function(json){
			    if (!json.status) {
			    	swal({title : "移除成功!",text : "移除成功！",type : "success"},function(){
	                    window.location.reload();
	                });
				} else {
					swal("移除失败!", "移除失败，请刷新后重试！", "error");
		       }
		   }, 'json');
	    });
	
});

</script>
</body>
</html>
