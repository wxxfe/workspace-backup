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
            <h1><?=$this->page_name;?><small><?=$this->router->class;?> </small></h1>
            <ol class="breadcrumb">
                <li><a href="/main/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><?=$this->page_name;?></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12" id="user-list">
                    <div class="box">
                        <div class="box-body">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-9 text-right">
                                <a class="btn btn-success btn-flat" role="button" href="<?=base_url('/app/lists/add')?>"><i class="fa fa-plus"></i> 添加</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 user-list">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">列表</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>排序</th>
                                    <th>ID</th>
                                    <th>标题</th>
                                    <th>推荐语</th>
                                    <th>图片</th>
                                    <th>内容类型</th>
                                    <th>ID/URL</th>
                                    <th>上线</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $types = $this->EX_LM->types;?>
                                <?php
                                foreach($list as $key => $row): ?>
                                    <tr>
                                        <td>
                                            <a href="javascript:void(0)" class="item-text-edit" data-type="text" data-id="<?=$row['id']?>" data-pk="<?=$row['priority']?>" data-name="priority" data-title="修改排序">
                                                <?=$offset+$key+1?>
                                            </a>
                                        </td>
                                        <td><?=$row['id']?></td>
                                        <td style="width:15%;"><?=$row['title']?></td>
                                        <td style="width:15%;"><?=$row['brief']?></td>
                                        <td><?php if ($row['image']) { ?><img src="<?=getImageUrl($row['image'])?>" style="width: 100px;" title="<?=$row['title'] ?>" /><?php }?></td>
                                        <td><?=$types[$row['type']];?></td>
                                        <td style="width:15%;">
                                            <?=$row['type'] == 'html' ? $row['url'] : $row['ref_id']?>
                                        </td>
                                        <td>
                                            <input data-pk="<?= $row['id'] ?>" name="visible" class="release" type="checkbox"
                                                <?php if($row['visible']){echo 'checked';}?>>
                                        </td>
                                        <td>
                                            <a class="btn btn-flat btn-warning btn-xs" role="button" href="<?=base_url('/app/lists/add?id='.$row['id']).'&redirect='.current_url();?>"><i class="fa fa-edit"></i> 编辑</a>
                                            <a class="btn btn-flat btn-danger btn-xs btn-remove" role="button" href="javascript:void(0);" data-nid="<?=$row['id']?>"><i class="fa fa-remove"></i> 删除</a>
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
        confirmButtonText: "删除",
        cancelButtonText: "取消",
        closeOnConfirm: false
    };
    $('#batch-remove,.btn-remove').on('click',function(){
        var slideId = $(this).data('nid');
        var tr = $(this).parents('tr');
        var target = this;
        if(this.id == 'batch-remove') slideId = $('#batch-id').val();

        swal(alertConfig,function(){
            $.post('<?=base_url("/app/lists/remove")?>',{id : slideId},function(d){
                if(d == 'success'){
                    swal({title : "删除成功!",text : "已删除！",type : "success"},function(){
                        if(target.id == 'batch-remove') window.location.reload();
                        tr.fadeOut('fast',function(){$(this).remove();});
                    });
                }else{
                    swal("删除失败!", "删除失败，请重试！", "error");
                }
            });
        });
    });

    $('.item-text-edit').editable({
        type:'text'
    });

    $('.item-text-edit').click(function(){
        $('.js_update_sort_id').val($(this).data('id'));
    });

    $('.user-list').on('click', '.editable-submit', function(){
        var input_val = $(this).parent().prev().find('input').val(),
            id = $('.js_update_sort_id').val();
        if (!id) {
            return false;
        }

        $.post("<?=base_url('/app/lists/updatesort') ?>", { 'id':id, 'sort':input_val }, function(json){
            console.log(json);
            if (!json.status) {
                window.location.reload();
                return false;
            }
        }, 'json');
    });

    $("input[name='visible']").bootstrapSwitch({
        size: 'mini',
        onText: '上线',
        offText: '下线',
        onSwitchChange: function (event, state) {
            $.ajax({
                method: "POST",
                url: "<?=base_url('/app/lists/upstatus') ?>",
                data: {name: "visible", value: parseInt(Number(state)), pk: $(this).data('pk')}
            });
        }
    });
</script>
</body>
</html>
