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
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
    <div class="wrapper">
        <div class="content-wrapper" style="margin-left: 0;">
            <section class="content-header">
              <h1>角色<small>Role </small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">角色列表</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-8">
                        <?php if($isWrite > 0): ?>
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <h3 class="box-title">添加角色</h3>
                            </div>
                            <div class="box-body">
                                <form class="form-inline" method="post" id="role-form">
                                    <div class="form-group">
                                        <label for="name">角色名称</label>
                                        <input class="form-control" type="text" name="name" placeholder="请输入角色名称" />
                                    </div>
                                    <div class="form-group">
                                        <label for="brief">简介</label>
                                        <input class="form-control" type="text" name="brief" placeholder="请输入简介" />
                                    </div>
                                    <div class="form-group">
                                        <label for="enable">是否启用</label>
                                        <select class="form-control" name="enable">
                                            <option value="1" selected="selected">启用</option>
                                            <option value="0">禁用</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button id="add-btn" class="btn btn-success">添加</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">列表</h3>
                                <p class="text-warning pull-right">
                                    <span style="color: #f90;"><i class="fa fa-info"></i> 提示：</span>点击列中的值，可以编辑。
                                </p>
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>角色名称</th>
                                            <th>简介</th>
                                            <th>是否启用</th>
                                            <th>权限</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($roles as $role): ?>
                                        <tr>
                                            <td><?=$role['id']?></td>
                                            <td><a href="javascript:void(0)" class="item-text-edit" data-type="text" data-pk="<?=$role['id']?>" data-name="name" data-title="修改角色名称"><?=$role['name']?></a></td>
                                            <td><a href="javascript:void(0)" class="item-text-edit" data-type="text" data-pk="<?=$role['id']?>" data-name="brief" data-title="修改简介"><?=$role['brief']?></a></td>
                                            <td>
                                                <a href="javascript:void(0)" class="item-select-edit-enable" data-type="select" data-pk="<?=$role['id']?>" data-name="enable" data-title="是否启用角色">
                                                    <?php if($role['enable'] == 1): ?>
                                                    启用
                                                    <?php else: ?>
                                                    禁用
                                                    <?php endif; ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php if($isWrite > 0): ?>
                                                <?php if($role['total'] > 0): ?>
                                                <button type="button" class="btn btn-xs btn-info btn-review" data-rid="<?=$role['id']?>" data-title="<?=$role['name']?>">查看/修改</button>
                                                <?php else: ?>
                                                <button type="button" class="btn btn-xs btn-success btn-review" data-rid="<?=$role['id']?>" data-title="<?=$role['name']?>">设置权限</button>
                                                <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" id="role-view" style="display: none;">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title" id="power-title"></h3>
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
<!-- AdminLTE App -->
<script src="/static/dist/js/app.min.js"></script>
<script src="/static/dist/js/jstree.min.js"></script>
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script>
//--editable------------------------------------------------------------
$.fn.editable.defaults.disabled = (<?=$isWrite?> > 0 ? false : true);
$.fn.editable.defaults.url = '<?=base_url("/cms/role/update")?>';
$('.item-text-edit').editable();
$('.item-select-edit-enable').editable({
    prepend: "请选择",
	source: [
		{value: 1, text: '启用'},
		{value: 0, text: '禁用'}
    ]
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
$('.btn-review').on('click',function(){
    var rid = $(this).data('rid');
    var name = $(this).data('title');
    var treeBox = $('#tree_' + rid);

    $('#role-view').show();

    $('#power-title').text(name + '权限');
    $('div',treeWrap).hide();
    if(treeBox.size() > 0){
        treeBox.show();
    }else{
        $.get('<?=base_url("/cms/acl/admin/userjson?role_id=")?>' + rid,function(acl){
            var result = typeof acl === 'string' ? JSON.parse(acl) : acl;
            $('<div id="tree_'+ rid +'"></div>').appendTo(treeWrap);
            config.core.data = result;
            $('#tree_' + rid).jstree(config).on('changed.jstree',function(event,node){
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
                        role_id : rid,
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
$('#role-form').on('submit',function(){
    var name = $('input[name="name"]').val(),
        brief = $('input[name="brief"]').val(),
        enable = $('select[name="enable"]').val();

    if(name == ''){
        message('danger','出错啦！','角色名称不能为空！',$(this).parent(),true);
        return false;
    }

});

</script>
</body>
</html>
