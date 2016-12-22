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
        
        .user-list th, .user-list td {
            text-align: center;
        }
    .user-list .popover-content {
            padding: 9px 24px;
        }
    </style>
 <!-- jQuery 2.1.4 -->
<script src="/static/plugins/jQuery/jQuery-2.1.4.min.js"></script>   
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
    
    function update_mod(id, mid)
    {
    	if (!mid || !id) {
    	    window.location.reload();
    	    return false;
    	}

    	var mod_name = $.trim($('input[name="mod_name_'+mid+'"]').val()),
    	mod_sort = parseInt($('input[name="mod_sort_'+mid+'"]').val()),
    	mod_visible = $('input[name="mod_visible_'+mid+'"]').prop('checked'),
    	visible     = 1;

    	if (!mod_visible) {
    	    visible = 0;
    	}
    	//console.log(mod_name, mod_sort,mod_visible, id, mid);   
     
        $.post('<?=base_url("/special/addmod?id=")?>'+id,{ 'mod_id':mid, 'mod_name':mod_name, 'mod_sort':mod_sort, 'mod_visible':visible, 'is_ajax':1 },function(json){
            if(!json.status){
                swal({title : "修改成功!",text : "版块已被修改！",type : "success"},function(){
                    window.location.reload();return false;
                });
            } else {
                swal("修改失败!", "版块修改失败，请刷新后重试！", "error");
            }
        });
    }


    function del_content(cid){
    	//var cid = parseInt($(this).data('cid'));
    	if (!cid) {
    		window.location.reload();
    	    return false;
    	}
    	//console.log(cid);return false;

        swal(alertConfig,function(){
            $.post('<?=base_url("/special/removecontent")?>',{ 'cid' : cid },function(json){
                if(!json.status){
                    swal({title : "删除成功!",text : "内容已被删除！",type : "success"},function(){
                        window.location.reload();
                        return false;
                    });
                }else{
                    swal("删除失败!", "内容删除失败，请重试！", "error");
                }
            }, 'json');
        });
    }
    </script>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
    <div class="wrapper">
        <div class="content-wrapper" style="margin-left: 0;">
            <section class="content-header">
              <h1><?php if (isset($is_up) && $is_up) { echo '编辑';} else { echo '添加';}?>专题<small><?php if (isset($is_up) && $is_up) { echo 'Edit';} else { echo 'Create';}?> Special </small></h1>
              <ol class="breadcrumb">
                <li><a href="/main/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><?php if (isset($is_up) && $is_up) { echo '编辑';} else { echo '添加';}?>专题</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-body">
                                <form class="form-horizontal" method="post" id="news-form" data-toggle="validator">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="">版块内容</label>
                                                <div class="col-sm-10">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <span class="control-label col-sm-2">版块名称</span>
                                                <div class="col-sm-2">
                                                    <input class="form-control" required type="text" name="mod_name" value="" placeholder="请输入版块名称" maxlength="128" data-bvalidator-msg="请输入版块名称!" />
                                                </div>
                                                <span class="control-label col-sm-1">排序</span>
                                                <div class="col-sm-2">
                                                    <input class="form-control" type="text" name="mod_sort" value="1" placeholder="请输入版块排序" maxlength="128" data-bvalidator-msg="请输入版块排序!" readonly="readonly" />
                                                </div>
                                                
                                                <div class="col-sm-1">
                                                    <div class="checkbox">
                                                        <input name="mod_visible" class="release" type="checkbox" checked value="1"/>
                                                    </div>
                                                </div>
         
                                                <div class="col-sm-2">
                                                    <button type="submit" class="btn btn-success">添加</button>
                                                </div>
                                            </div>
                                            
                                        </div>
                                     </div>
                                     </form>
                                     <?php if ($block_list) {
                                         foreach ($block_list as $k=>$v) {
                                      ?>
                                      <HR align="center" width="100%" color="#987cb9">
                                      <form class="form-horizontal" method="post" class="block-form" data-toggle="validator">
                                     <div class="row"> 
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <span class="control-label col-sm-2">排序</span>
                                                <div class="col-sm-2">
                                                    <input class="form-control" type="text" name="mod_sort_<?=$v['id']?>" value="<?php echo $k+1 ?>" placeholder="请输入版块排序" maxlength="128" data-bvalidator-msg="请输入版块排序!" />
                                                </div>
                                                <span class="control-label col-sm-1">版块名称</span>
                                                <div class="col-sm-2">
                                                    <input class="form-control" required type="text" name="mod_name_<?=$v['id']?>" value="<?=$v['title'] ?>" placeholder="请输入版块名称" maxlength="128" data-bvalidator-msg="请输入版块名称!" />
                                                </div>
                                                
                                                
                                                <div class="col-sm-1">
                                                    <div class="checkbox">
                                                        <input data-pk="<?= $v['id'] ?>" name="mod_visible_<?=$v['id']?>"
                                                           class="release js_change_mod_status" type="checkbox"
                                                        <?php if ($v['visible']): ?>
                                                            checked
                                                        <?php endif; ?> value="1" />
                                                    </div>
                                                </div>
         
                                                <div class="col-sm-2">
                                                    <a class="js_update_mod btn btn-warning" data-id="<?=$id?>" data-mid="<?=$v['id']?>" href="javascript:update_mod(<?=$id?>,<?=$v['id']?>);">修改</a>
                                                </div>
                                                <div class="col-sm-2">
                                                    <a class="btn btn-success" href="<?php $str = base_url('/special/addcontent?id=').$id.'&modid='.$v['id']; if (isset($is_up) && $is_up) { $str.='&is_up='.$is_up; } echo $str; ?>">添加内容</a>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    
                          
                                    <?php if ($v['child']) { 
                                     ?>
                                    <div class="row">
                                        <div class="user-list col-md-12" >
                                            <div>
                                                <div class="box-header with-border">
                                                    <h3 class="box-title"><?=$v['title'] ?> - 相关推荐</h3>
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
                                                                <th>标题</th>
                                                                <th>图片</th>
                                                                <th>内容类型</th>
                                                                <th>内容id</th>
                                                                <th>上下线</th>
                                                                <th>操作</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($v['child'] as $k1=>$v1) {  ?>
                                                            
                                                            <tr>
                                                                <td>
                                                                    <a href="javascript:void(0)" class="item-text-edit" data-type="text" data-cid="<?=$v1['id']?>" data-bid="<?=$v['id'] ?>" data-pk="<?=$v1['priority']?>" data-name="priority" data-title="修改排序">
                                                                        
                                                                        <?php echo $k1+1?>
                                                                    </a>
                                                                </td>
                                                                <td><?php if ($v1['info']) { echo $v1['info'][0]['title']; }?></td>
                                                                <td><?php if ($v1['info'] && $v1['info'][0]['image']) { ?><img alt="" style="width: 100px;" src="<?= getImageUrl($v1['info'][0]['image'])?>"><?php } ?></td>
                                                                <td><?=array('news' => '新闻', 'video' => '>视频', 'gallery' => '图集')[$v1['type']]?></td>
                                                                <td><?=$v1['ref_id']?></td>
                                                                <td>
                                                                    <div class="checkbox">
                                                                        <input data-pk="<?= $v1['ref_id'] ?>" data-field="<?=$v1['type'] ?>" name="content_visible_<?=$v1['ref_id']?>"
                                                                           class="release js_change_content_status" type="checkbox"
                                                                        <?php if ($v1['info'] && $v1['info'][0]['visible']): ?>
                                                                            checked
                                                                        <?php endif; ?> value="1" />
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <!--  <a href="#" role="button" class="btn btn-flat btn-info btn-xs"><i class="fa fa-eye"></i> 预览</a>-->
                                                                    <a href="<?php if ('video' == $v1['type']) { $str = 'video/detailedit/';} elseif ('gallery' == $v1['type']) { $str = 'gallery/edit/'; } else { $str = 'news/edit/update/'; } echo base_url($str.$v1['ref_id']).'?redirect='.urlencode(base_url($_SERVER['REQUEST_URI'])); ?>" role="button" class="btn btn-flat btn-warning btn-xs"><i class="fa fa-edit"></i> 编辑</a>
                                                                    <a  href="javascript:del_content(<?=$v1['id'] ?>);" data-cid="<?=$v1['id'] ?>"  class="btn btn-flat btn-danger btn-xs btn-remove js_remove_content"><i class="fa fa-remove"></i> 删除</a>
                                                                </td>
                                                            </tr>
                                                             <?php }?>                                      
                                                         </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    
                                    <?php } } }?>
                                    <br>
                                    <div class="form-group text-center">
                                        <a href="<?= base_url('/special/add?id='.$id) ?>" class="btn btn-info">上一步</a>
                                        <a href="<?= $jump_url ?>" class="btn btn-success" >保存</a>
                                    </div>
                                    
                                    <input type="hidden" value="" class="js_update_sort_bid" />
                                    <input type="hidden" value="" class="js_update_sort_cid" />
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>

<!-- Bootstrap 3.3.5 -->
<script src="/static/bootstrap/js/bootstrap.min.js"></script>
<!-- Jquery UI -->
<script src="/static/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/static/fileupload/scripts/jquery.ui.widget.js"></script>
<script src="/static/fileupload/scripts/jquery.fileupload.js"></script>
<!-- AdminLTE App -->
<script src="/static/dist/js/app.min.js"></script>

<script src="/static/dist/js/bvalidator.js"></script>
<script src="/static/dist/js/bvalidator.bs3form.min.js"></script>
<script src="/static/dist/js/bs3form.js"></script>
<script src="/static/dist/js/bootstrap-switch.min.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script src="/static/dist/js/bootstrap-editable.min.js"></script>

<script>
$('#news-form').bValidator({validateOn: 'blur,change'});
$('.block-form').bValidator({validateOn: 'blur,change'});

$('.item-text-edit').editable({ 
    type:'text',
    /*url:"</?=base_url('/special/updatecontentsort') ?>",
    params : function(param){
        param.news_id = 2;
        return param;
    },
    success : function(respons,newValue){
        if(respons == 'success'){
            //window.location.reload();
        }
    }*/
});

$('.item-text-edit').click(function(){
	$('.js_update_sort_cid').val($(this).data('cid'));
	$('.js_update_sort_bid').val($(this).data('bid'));
});
$('.user-list').on('click', '.editable-submit', function () {
	var input_val = $(this).parent().prev().find('input').val(),
	   cid = $('.js_update_sort_cid').val(),
	   bid = $('.js_update_sort_bid').val();
    if (!cid || !bid) {
        return false;
    }

	$.post("<?=base_url('/special/updatecontentsort') ?>", { 'cid':cid, 'bid':bid, 'sort':input_val }, function(json){
	    if (!json.status) {
	        window.location.reload();
	        return false;
	   }
	}, 'json');
});



/*$('.box-body').on('click', '.js_update_mod',function(){
	var id = parseInt($(this).data('id')),
	   mid = parseInt($(this).data('mid'));
	   console.log(id, mid);
	if (!mid || !id) {
	    window.location.reload();
	    return false;
	}

	var mod_name = $.trim($('input[name="mod_name_'+mid+'"]').val()),
	mod_sort = parseInt($('input[name="mod_sort_'+mid+'"]').val()),
	mod_visible = $('input[name="mod_visible_'+mid+'"]').prop('checked'),
	visible     = 1;

	if (!mod_visible) {
	    visible = 0;
	}
	console.log(title, mod_sort,mod_visible, id, mid);    
 
    $.post('</?=base_url("/special/addmod?id=")?>'+id,{ 'mod_id':mid, 'mod_name':mod_name, 'mod_sort':mod_sort, 'mod_visible':visible, 'is_ajax':1 },function(json){
        if(!json.status){
            swal({title : "修改成功!",text : "版块已被修改！",type : "success"},function(){
                window.location.reload();return false;
            });
        } else {
            swal("修改失败!", "版块修改失败，请刷新后重试！", "error");
        }
    });

});*/

/*$('.js_remove_content').click(function(){
	var cid = parseInt($(this).data('cid'));
	if (!cid) {
		window.location.reload();
	    return false;
	}

    swal(alertConfig,function(){
        $.post('</?=base_url("/special/removecontent")?>',{ 'cid' : cid },function(json){
            if(!json.status){
                swal({title : "删除成功!",text : "内容已被删除！",type : "success"},function(){
                    window.location.reload();
                    return false;
                });
            }else{
                swal("删除失败!", "内容删除失败，请重试！", "error");
            }
        }, 'json');
    });
});*/

$("input[name='mod_visible']").bootstrapSwitch({
    size: 'mini',
    onText: '上线',
    offText: '下线'
});

//版块上下线
$(".js_change_mod_status").bootstrapSwitch({
    size: 'mini',
    onText: '上线',
    offText: '下线',
    onSwitchChange: function (event, state) {
        $.ajax({
            method: "POST",
            url: "<?=base_url('/special/upblockstatus') ?>",
            data: {name: "visible", value: parseInt(Number(state)), pk: $(this).data('pk')}
        });
    }
});

//内容上下线
$(".js_change_content_status").bootstrapSwitch({
    size: 'mini',
    onText: '上线',
    offText: '下线',
    onSwitchChange: function (event, state) {
        var field = $(this).data('field'),
        pk = $(this).data('pk');
        if (!field || !pk) {
  	      return false;
        }
      //console.log(field, pk, state);
        $.ajax({
            method: "POST",
            url: "<?=base_url('/special/upcontentstatus') ?>",
            data: { 'field': field, 'value': parseInt(Number(state)), 'pk': pk }
        });
    }
});
</script>
</body>
</html>
