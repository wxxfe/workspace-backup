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
            <h1>视频列表<small></small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">视频列表</li>
            </ol>
        </section>
        <div class="row">
            <div class="col-md-12" id="user-list">
                <div class="box">
                    <div class="box-body">
                        <form id="search-form" method="get">
                            <div class="input-group">
                                <div class="col-md-5">
                                    <input id="keyword" class="form-control" placeholder="请输入关键词或视频ID" type="text" name="keyword" <?php if (!empty($keyword)):?>value="<?=$keyword?>"<?php endif;?> />
                                </div>
                                <div class="col-md-5">
                                    <select name="status" class="form-control">
                                        <option value="all" <?php echo $status=='all' ? 'selected':'';?>><?php echo $type == 'box' ? '同步状态':'上传状态';?></option>
                                        <?php $all_status = $type == 'box' ? $this->PVM->box_status_show : $this->PVM->cloud_status_show; foreach($all_status as $key => $val):?>
                                            <option value="<?=$key;?>" <?php echo strval($key)==$status ? 'selected':'';?>><?=$val;?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <span class="input-group-btn"><button class="btn bg-purple btn-flat" type="submit"><i class="fa fa-search"></i> 搜索</button></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="box" style="border: none;">
                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="<?=$type=='editor' ? 'active':'';?>" style="width: 15%; text-align: center;"><a href="<?=site_url('/PendingVideo/index/editor');?>">自上传</a></li>
                            <li role="presentation" class="<?=$type=='spider' ? 'active':'';?>" style="width: 15%; text-align: center;"><a href="<?=site_url('/PendingVideo/index/spider');?>">爬虫</a></li>
                            <li role="presentation" class="<?=$type=='box' ? 'active':'';?>" style="width: 15%; text-align: center;"><a href="<?=site_url('/PendingVideo/index/box');?>">同步至在线</a></li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="pending-video-list">
                            <div class="box">
                                <div class="box-body">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th style="display:<?php echo $type=='box'?'none':'';?>"></th>
                                            <th>标题</th>
                                            <th>大小</th>
                                            <th><?php echo $type == 'box' ? '同步状态':'上传状态';?></th>
                                            <th>上传时间</th>
                                            <?php if($type!= 'box'):?>
                                                <th>操作</th>
                                            <?php endif;?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach($list as $video):?>
                                            <tr>
                                                <td style="display:<?php echo $type=='box'?'none':'';?>"><input type="checkbox" value="<?=$video['id']?>" class="more-box" /></td>
                                                <td><?=$video['title']?></td>
                                                <td><?=round($video['file_size']/(1024*1024),2)."M"?></td>
                                                <td><?php $status_key = $type!= 'box' ? $video['cloud_status'] : $video['box_status']; echo isset($all_status[$status_key]) ? $all_status[$status_key] : '未上传';?></td>
                                                <td><?=$video['publish_tm']?></td>
                                                <?php if($type!= 'box'):?>
                                                    <td>
                                                        <?php if(in_array($video['cloud_status'], array(0,3,10))):?>
                                                            <a data-status="<?=$video['cloud_status'];?>" data-id="<?=$video['id'];?>" data-upload-type="cloud_box" class="btn btn-flat btn-primary btn-xs cloud_upload" role="button" href="javascript:void(0);"><i class="fa fa-cloud-upload"></i>上传</a>
                                                        <?php else:?>
                                                            <a data-status="<?=$video['cloud_status'];?>" data-id="<?=$video['id'];?>" data-upload-type="cloud_box" class="btn btn-flat btn-primary btn-xs cloud_upload" role="button" href="javascript:void(0);" disabled="true"><i class="fa fa-cloud-upload"></i>上传</a>
                                                        <?php endif;?>
                                                        <a class="btn btn-flat btn-info btn-xs" role="button" href="<?=base_url('/PendingVideo/edit/'.$video['id']).'?redirect='.base_url('/PendingVideo/index/'.$type.'/'.$offset);?>"><i class="fa fa-edit"></i>编辑</a>
<!--                                                        <a data-status="--><?//=$video['box_status'];?><!--" data-id="--><?//=$video['id'];?><!--" data-upload-type="box" class="btn btn-flat btn-success btn-xs box_upload" role="button" href="javascript:void(0);"><i class="fa fa-refresh"></i>同步至在线</a>-->
                                                    </td>
                                                <?php endif;?>
                                            </tr>
                                        <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="box box-solid">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-3" style="display:<?php echo $type=='box'?'none':'';?>">
                                            <table>
                                                <tr>
                                                    <td width="30" align="center"><input id="select-all" type="checkbox" name="" /></td>
                                                    <td style="width:165px;">
                                                        <input id="batch-id" type="hidden" value="" />
                                                        <select id="upload-type-select" class="form-control">
                                                            <option value="cloud_box">批量上传</option><!--cloud-->
                                                            <option value="batch_edit">批量编辑</option>
<!--                                                            <option value="box">批量同步至在线</option>-->
<!--                                                            <option value="cloud_box">批量上传且同步到在线</option>-->
                                                        </select>
                                                    </td>
                                                    <td style="padding-left: 10px;">
                                                        <button id="batch-sync" class="btn btn-flat btn-success btn-xs">确定</button>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-9 text-right">
                                            <table width="100%">
                                                <tr>
                                                    <td><?=$page?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
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
    $('#batch-sync,.cloud_upload,.box_upload').on('click',function(){
        var ids,upload_type,status=0;
        if($(this).attr('id') == 'batch-sync'){
            ids = $('#batch-id').val();
            upload_type = $("#upload-type-select").val();
        }else{
            ids = $(this).data('id');
            upload_type = $(this).data('upload-type');
            status = parseInt($(this).data('status'));
        }
        if(!ids){
            alert("未选中视频");
            return;
        }
        if(!(status==0 || status==3 || status==12)) {
            return;
        }
        if(upload_type == 'batch_edit'){//批量编辑
            window.location.href="<?=site_url('/PendingVideo/batchedit?ids=');?>"+ids+'&redirect='+"<?=current_url();?>";
        }else{
            $.post('<?=base_url("/PendingVideo/updatestatus")?>',{id:ids,upload_type:upload_type},function(d){
                if(d == 'success'){
                    swal({title : "成功!",text : "上传或同步成功！",type : "success"},function(){
                        window.location.reload();
                    });
                }else{
                    swal("失败", "上传或同步失败", "error");
                }
            });
        }
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

</script>
</body>
</html>
