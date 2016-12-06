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
              <h1>用户<small>Users </small></h1>
              <ol class="breadcrumb">
                <li><a href="/main/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">用户列表</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-2">
                        <?php if($isWrite > 0): ?>
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <h3 class="box-title">添加用户</h3>
                            </div>
                            <div class="box-body">
                                <form method="post" id="user-form">
                                    <div class="form-group">
                                        <label for="name">头像</label>
										<div id="image-view" style="padding-top: 10px;"></div>
										<div class="input-group">
											<span class="btn btn-warning fileinput-button">
												<i class="glyphicon glyphicon-plus"></i>
												<span>上传头像</span>
												<input id="fileupload" type="file" name="image" multiple data-url="http://w.image.sports.baofeng.com/save?token=xVFpX0RU" />
											</span>
											<input id="avatar" type="hidden" name="avatar" value="" />
										</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="username">用户名</label>
										<input class="form-control" type="text" name="username" placeholder="请输入用户名" />
                                    </div>
                                    <div class="form-group">
                                        <label for="qq" style="display: block;">密码
                                            <button id="create-passwd" type="button" class="btn btn-xs btn-warning pull-right">自动生成密码</button>
                                        </label>
										<input class="form-control" type="text" name="password" placeholder="请设置账号密码" />
                                    </div>
                                    <div class="form-group">
                                        <label for="nickname">昵称</label>
										<input class="form-control" type="text" name="nickname" placeholder="请输入昵称" />
                                    </div>
                                    <div class="form-group">
                                        <label for="gender">性别</label>
										<select class="form-control" name="gender">
											<option value="1" selected="selected">男性</option>
											<option value="0">女性</option>
										</select>
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile">电话</label>
										<input class="form-control" type="number" name="mobile" placeholder="请输入电话" />
                                    </div>
                                    <div class="form-group">
                                        <label for="email">邮箱</label>
										<input class="form-control" type="text" name="email" placeholder="请输入邮箱地址" />
                                    </div>
                                    <div class="form-group">
                                        <label for="qq">QQ</label>
										<input class="form-control" type="number" name="qq" placeholder="请输入QQ号码" />
                                    </div>
                                    <div class="form-group">
                                        <label for="enable">是否启用</label>
										<select class="form-control" name="enable">
											<option value="1" selected="selected">启用</option>
											<option value="0">禁用</option>
										</select>
                                    </div>
                                    <div class="form-group">
                                        <label for="memo">备注</label>
										<input class="form-control" type="text" name="memo" placeholder="请输入备注" />
                                    </div>
                                    <div class="form-group text-center">
                                        <button id="add-btn" class="btn btn-success">添加</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-10" id="user-list">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">用户列表</h3>
                                <div class="box-tools text-warning pull-right">
                                    <span style="color: #f90; line-height: 30px;"><i class="fa fa-info"></i> 提示：</span>点击列中的值，可以编辑。
                                </div>
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>头像</th>
                                            <th>用户名</th>
                                            <th>昵称</th>
                                            <th>性别</th>
                                            <th>电话</th>
                                            <th>邮箱</th>
                                            <th>QQ</th>
                                            <th>是否启用</th>
                                            <th>备注</th>
                                            <th>密码</th>
                                            <th>角色</th>
                                            <th>权限</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $gender = array('女','男'); ?>
                                        <?php foreach($users as $user): ?>
                                        <tr>
                                            <td><?=$user['id']?></td>
                                            <td>
												<?php if(empty($user['avatar'])): ?>
												<img src="/static/dist/img/avatar_default.png" class="avatar" alt="" />
												<?php else: ?>
												<img src="http://image.sports.baofeng.com/<?=$user['avatar']?>" class="avatar" alt="" />
												<?php endif; ?>
											</td>
                                            <td><?=$user['username']?></td>
                                            <td><?=$user['nickname']?></td>
                                            <td><?=$gender[$user['gender']]?></td>
                                            <td><?=$user['mobile']?></td>
                                            <td><?=$user['email']?></td>
                                            <td><?=$user['qq']?></td>
                                            <td>
                                                <a href="javascript:void(0)" class="item-select-edit-enable" data-type="select" data-pk="<?=$user['id']?>" data-name="enable" data-title="是否启用用户">
                                                    <?php if($user['enable'] == 1): ?>
                                                    启用
                                                    <?php else: ?>
                                                    禁用
                                                    <?php endif; ?>
                                                </a>
                                            </td>
                                            <td><a href="javascript:void(0)" class="item-text-edit" data-type="text" data-pk="<?=$user['id']?>" data-name="memo" data-title="输入备注"><?=$user['memo']?></a></td>
                                            <td><a href="javascript:void(0)" class="item-text-edit" data-type="text" data-pk="<?=$user['id']?>" data-name="password" data-title="重新设置密码">重设密码?</a></td>
                                            <td>
                                                <?php
                                                    $r = '';
                                                    $rid = '';
                                                    foreach($user['roles'] as $key => $role){
                                                        $r .= $role['name'];
                                                        $rid .= $role['id'];
                                                        if($key < count($user['roles']) - 1){
                                                            $r .= ',';
                                                            $rid .= ',';
                                                        }
                                                    }
                                                ?>
                                                <a href="javascript:void(0)" class="item-role-edit" data-nickname="<?=$user['nickname']?>" data-value="<?=$rid?>" data-type="checklist" data-pk="<?=$user['id']?>" data-name="role" data-title="选择角色">
                                                    <?=$r?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php if($isWrite > 0): ?>
                                                    <?php if(count($user['roles']) > 0): ?>
                                                    <button type="button" class="btn btn-xs btn-info btn-review" data-uid="<?=$user['id']?>" data-title="<?=$user['nickname']?>">查看/修改</button>
                                                    <?php else: ?>
                                                    无
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?=$page?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2" id="role-view" style="display: none;">
                        <div class="box" id="role-box">
                            <div class="box-header with-border">
                                <h3 class="box-title" id="power-title"></h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <div class="box-body" id="tree-wrap">
                                <div id="tree"></div>
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
<script>
//--file upload----------------------------------------------------
$('#fileupload').fileupload({
    add: function (e, data) {
        //data.context = $('<p/>').text('Uploading...').appendTo('#content');
        data.submit();
    },
    done: function (e, data) {
        //$('#cover').val(data);
        var result = data.result.errno;

        if(result !== 10000){
            alert('上传失败,请重试！');
        }
        else{
            $("#image-view").html('<img class="avatar" src="http://image.sports.baofeng.com/' + data.result.data.pid + '">')
            $('#image-show').val(data.result.data.pid);
        }
    }
});


//--editable------------------------------------------------------------
$.fn.editable.defaults.disabled = (<?=$isWrite?> > 0 ? false : true);
$.fn.editable.defaults.url = '<?=base_url("/cms/user/update")?>';
$('.item-text-edit').editable();
$('.item-select-edit-enable').editable({
    prepend: "请选择",
	source: [
		{value: 1, text: '启用'},
		{value: 0, text: '禁用'}
    ]
});
$('.item-role-edit').editable({
	source: [
        <?php foreach($roles as $role): ?>
            {value: <?=$role['id']?>, text: '<?=$role['name']?>'},
        <?php endforeach; ?>
    ],
    success : function(respons,newValue){
        var nextTd = $(this).parent().next();
        var uid = $(this).attr('data-pk');
        var nickname = $(this).attr('data-nickname');
        if(newValue.length > 0){
            nextTd.html('<button type="button" class="btn btn-xs btn-info btn-review" data-uid="'+ uid +'" data-title="'+ nickname +'">查看/修改</button>');
        }else{
            nextTd.html('无');
        }
    }
});

//--tree------------------------------------------------------------

var config = {
    core : {
        animation : 1,
        check_callback : true,
        themes : {tripes : true},
        data : []
    },
    checkbox : {
      keep_selected_style : false
    },
    plugins : ['checkbox','changed']
};

var treeWrap = $('#tree-wrap');
$('.table').on('click','.btn-review',function(){
    var uid = $(this).data('uid');
    var name = $(this).data('title');
    var treeBox = $('#tree_' + uid);

    $('#user-list').removeClass().addClass('col-md-8');
    $('#role-view').removeClass().addClass('col-md-2').show();
    $('#role-box').show();

    $('#power-title').text(name + '的权限');
    $('div',treeWrap).hide();
    if(treeBox.size() > 0){
        treeBox.show();
    }else{
        $.get('<?=base_url("/cms/acl/admin/userjson?user_id=")?>' + uid,function(acl){
            var result = typeof acl === 'string' ? JSON.parse(acl) : acl;
            $('<div id="tree_'+ uid +'"></div>').appendTo(treeWrap);
            config.core.data = result;
            $('#tree_' + uid).jstree(config).on('changed.jstree',function(event,node){
                var powers = {};
                
                var node_id = node.node.id;
                if (node_id.indexOf('_') > -1) { // 修改的是管理项
                    node_id = parseInt(node_id);
                    powers[node_id] = 0;
                    for (var i in node.selected) {
                        var ap = node.selected[i].split('_');
                        var acl_id = ap[0];
                        var power  = ap[1];
                        if (acl_id == node_id) {
                            powers[acl_id] |= power;
                        }
                    }
                } else { // 修改的是菜单项
                    // 本身
                    if (node.action == 'select_node') {
                        powers[node_id] = 7;
                    } else {
                        powers[node_id] = 0;
                    }
                    // 子菜单
                    for (var i in node.node.children_d) {
                        var ap = node.node.children_d[i];
                        if (ap.indexOf('_') > -1) {
                            ap = ap.split('_');
                            var acl_id = ap[0];
                            var power  = ap[1];
                            if (node.action == 'select_node') {
                                powers[acl_id] |= power;
                            } else { // deselect_node
                                powers[acl_id] = 0;
                            }
                        }
                    }
                }
                
                if (powers) {
                    var data = {
                        user_id : uid,
                        powers  : JSON.stringify(powers)
                    };
                    $.post('<?=base_url("/cms/acl/admin/changePower")?>', data, function(d) {
                        console.log(d);
                    });
                }
            });
        });
    }
});

$('.btn-box-tool').on('click',function(){
    $('#role-view').hide();
    $('#user-list').removeClass().addClass('col-md-10');
});

//--form submit------------------------------------------------------------
function message(type,title,msg,target,autoHide){
    var icon = {
        danger : '<i class="icon fa fa-ban"></i>',
        warning : '<i class="icon fa fa-warning"></i>',
        info : '<i class="icon fa fa-info"></i>',
        success : '<i class="icon fa fa-check"></i>'
    };
    var _alert = '';
    _alert += '<div class="alert alert-'+ type +' alert-dismissable">';
    _alert += '    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
    _alert += '    <h4>'+ icon[type] +' '+ title +'</h4>';
    _alert += '    ' + msg;
    _alert += '</div>';
    $(_alert).prependTo(target);
    if(autoHide){
        setTimeout(function(){
            $('.alert-' + type).fadeOut();
        },2500);
    }
}
$('#user-form').on('submit',function(){
    var username = $('input[name="username"]').val(),
        password = $('input[name="password"]').val();

    if(username == ''){
        message('danger','出错啦！','角色名称不能为空！',$(this).parent(),true);
        return false;
    }
    if(password == ''){
        message('danger','出错啦！','密码不能为空！',$(this).parent(),true);
        return false;
    }

});

$('#create-passwd').on('click',function(){
    $.get('<?=base_url('cms/user/createPassword')?>',function(p){
        if(p){
            $('input[name="password"]').val(p);
        }
    });
});


</script>
</body>
</html>
