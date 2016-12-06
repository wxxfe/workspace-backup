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
            <h1>社区达人管理<small>Community </small></h1>
            <ol class="breadcrumb">
                <li><a href="/main/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">社区达人列表</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12 user-list">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">社区达人管理</h3>
                            <a style="margin-left: 950px;" class="btn btn-success btn-flat" role="button" href="<?=base_url('/CommunityPeople/edit')?>"><i class="fa fa-plus"></i> 添加达人</a>
                        </div>
                        <div class="box-body">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>用户名</th>
                                    <th>达人类型</th>
                                    <th>添加时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $o = $offset; ?>
                                <?php
                                foreach($allCp as $key => $val): $o++; ?>
                                    <tr>
                                        <td><?=$o?></td>
                                        <td><?=$val['name']?></td>
                                        <td><?=$val['type']?></td>
                                        <td><?=$val['ctime']?></td>
                                        <td>
                                            <!--  <a class="btn btn-flat btn-info btn-xs" role="button" href="#"><i class="fa fa-eye"></i> 预览</a>-->
                                            <a class="btn btn-flat btn-warning btn-xs" role="button" href="<?=base_url('/CommunityPeople/edit?cp_id='.$val['id'])?>"><i class="fa fa-edit"></i> 更改达人</a>
                                            <a class="btn btn-flat btn-danger btn-xs btn-remove" role="button" href="javascript:void(0);" data-nid="<?=$val['id']?>"><i class="fa fa-remove"></i> 删除达人</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                                <input type="hidden" class="js_update_sort_id" value="">
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
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script>
    var alertConfig = {
        title: "你确定要删除吗?",
        text: "删除后不可恢复，请谨慎操作!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dd4b39",
        confirmButtonText: "确定删除",
        cancelButtonText: "取消",
        closeOnConfirm: false
    };
    $('#batch-remove,.btn-remove').on('click',function(){
        var cpID = $(this).data('nid');
        var tr = $(this).parents('tr');
        var target = this;
        if(this.id == 'batch-remove') slideId = $('#batch-id').val();

        swal(alertConfig,function(){
            $.post('<?=base_url("/CommunityPeople/remove")?>',{id : cpID},function(d){
                if(d == 'success'){
                    swal({title : "删除成功!",text : "达人已被删除！",type : "success"},function(){
                        if(target.id == 'batch-remove') window.location.reload();
                        tr.fadeOut('fast',function(){$(this).remove();});
                    });
                }else{
                    swal("删除失败!", "达人删除失败，请重试！", "error");
                }
            });
        });
    });

    function setBatchId(){
        var allBox = $('.more-box:checked'), idBox = $('#batch-id'), ids = [];
        allBox.each(function(){ ids.push($(this).val()); });
        idBox.val(ids.join(','));
    }

</script>
</body>
</html>
