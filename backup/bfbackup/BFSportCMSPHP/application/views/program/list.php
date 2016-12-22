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
              <h1>节目列表</h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">节目列表</li>
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
                                            <input id="keyword" class="form-control" placeholder="请输入关键词或节目ID" type="text" name="keyword" <?php if (!empty($keyword)):?>value="<?=$keyword?>"<?php endif;?> />
                                            <span class="input-group-btn"><button class="btn bg-purple btn-flat" type="submit"><i class="fa fa-search"></i> 搜索</button></span>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-9 text-right">
                                    <a class="btn btn-success btn-flat" role="button" href="<?=base_url('/program/addstep1')?>"><i class="fa fa-plus"></i> 添加节目</a>
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
                                <h3 class="box-title">节目列表</h3>
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
                                            <th width="40"></th>
                                            <th>节目id</th>
                                            <th>标题</th>
                                            <th>封面图</th>
                                            <th>视频数量</th>
                                            <th>标签</th>
                                            <th>上下线</th>
                                            <th>创建时间</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($list as $program):?>
                                        <tr>
                                            <td><input type="checkbox" value="<?=$program['id']?>" class="more-box" /></td>
                                            <td><?=$program['id']?></td>
                                            <td><?=$program['title']?></td>
                                            <td><img style="width:100px;height:75px;" src="<?=$program['image_url']?>" /></td>
                                            <td><?=$program['videos_count']?></td>
                                            <td><?=$program['tag_str']?></td>
                                            <td>
                                                <input data-pk="<?= $program['id'] ?>" name="visible"
                                                       class="release" type="checkbox"
                                                    <?php if ($program['visible']): ?>
                                                        checked
                                                    <?php endif; ?>
                                                >
                                            </td>
                                            <td><?=$program['created_at']?></td>
                                            <td>
                                                <a href="<?php echo site_url('program/edit/').$program['id']?>" class="btn btn-info btn-xs">编辑</a>
                                                <!-- 
                                                <a class="btn btn-flat btn-danger btn-xs btn-remove" role="button" href="javascript:void(0);" data-cid="<?=$program['id']?>"><i class="fa fa-remove"></i> 删除</a>
                                                -->
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
                                    <!-- 
                                    <div class="col-md-2">
                                        <table>
                                            <tr>
                                                <td width="30" align="center"><input id="select-all" type="checkbox" name="" /></td>
                                                <td>
                                                    <input id="batch-id" type="hidden" value="" />
                                                    <button id="batch-remove" class="btn btn-flat btn-danger btn-xs">批量删除</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    -->
                                    <div class="col-md-10 text-right">
                                        <table width="100%">
                                            <tr>
                                                <td><?=$page?></td>
                                                <!-- <td width="100" align="right">共 <strong class="text-info"></strong> 条</td>-->
                                            </tr>
                                        </table>
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
<script src="/static/dist/js/jstree.min.js"></script>
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script src="/static/dist/js/bootstrap-switch.min.js"></script>
<script src="/static/dist/js/bvalidator.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script>
//--remove alert-------------------------------------------------------------
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
    var programId = $(this).data('cid');
    var tr = $(this).parents('tr');
    var target = this;
    if(this.id == 'batch-remove'){
        programId = $('#batch-id').val();
        if(programId == ''){
            swal("操作失败!", "请先选择要删除的节目！", "error");
            return false;
        }
    }

    swal(alertConfig,function(){
        $.post('<?=base_url("/program/remove")?>',{id : programId},function(d){
            if(d == 'success'){
                swal({title : "删除成功!",text : "节目已被删除！",type : "success"},function(){
                    if(target.id == 'batch-remove') window.location.reload();
                    tr.fadeOut('fast',function(){$(this).remove();});
                });
            }else{
                swal("删除失败!", "节目删除失败，请重试！", "error");
            }
        });
    });
});
function setBatchId(){
    var allBox = $('.more-box:checked'), idBox = $('#batch-id'), ids = [];
    allBox.each(function(){ ids.push($(this).val()); });
    idBox.val(ids.join(','));
}

$('#select-all,.more-box').on('change',function(){
    if(this.id == 'select-all'){
        if($(this).prop('checked')){
            $('.more-box').prop('checked','checked');
        }else{
            $('.more-box').removeAttr('checked');
        }
    }
    setBatchId();
});
//--
$("input[name='visible']").bootstrapSwitch({
    size: 'mini',
    onText: '上线',
    offText: '下线',
    onSwitchChange: function (event, state) {
        $.ajax({
            method: "POST",
            url: "<?=base_url("/program/update")?>",
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
