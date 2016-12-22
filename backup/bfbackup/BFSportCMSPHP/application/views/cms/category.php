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
              <h1>管理项设置 <small>Management items </small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">管理项设置</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title" style="display: block;">分类
                                    <span class="pull-right">
                                        <a class="btn btn-xs btn-info btn-flat" role="button" href="<?=base_url('/cms/acl/admin/list')?>">全部分类</a>
                                        <a class="btn btn-xs btn-info btn-flat" role="button" href="<?=base_url('/cms/acl/admin/list?enable=1')?>">只看启用</a>
                                    </span>
                                </h3>
                            </div>
                            <div class="box-body">
                                <div id="tree">
                                    <ul>
                                        <?php
                                        function iterator_acl($acl) {
                                            foreach ($acl as $row) {
                                                echo "<li data-id='{$row['id']}' data-info='".json_encode($row)."' data-jstree='{\"icon\" : \"".(empty($row['icon']) ? "fa fa-meh-o" : $row['icon'])."\"}'>";
                                                echo $row['name'].($row['enable'] == 0 ? '(已禁用)' : '');
                                                if (isset($row['children'])) {
                                                    echo "<ul>";
                                                    iterator_acl($row['children']);
                                                    echo "</ul>";
                                                }
                                                echo "</li>";
                                            }
                                        }
                                        iterator_acl($acl);
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
<div class="popover right" id="test" data-status="create">
    <div class="arrow" style="top: 42px;"></div>
    <h3 class="popover-title">添加子分类</h3>
    <div class="popover-content">
        <form class="form-horizontal" id="c-form">
            <input type="hidden" id="parent_id" value="" />
            <input type="hidden" id="current_id" value="" />
            <div class="form-group" id="type-box">
                <label for="" class="col-sm-3 control-label">类型</label>
                <div class="col-sm-8">
                    <select id="isManage" class="form-control">
                        <option value="">选择类型</option>
                        <option value="menu">管理项</option>
                        <option value="item">子菜单</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-3 control-label">名称</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="cname" placeholder="请输入分类名称" />
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-3 control-label">图标</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="icon" placeholder="请输入图标类名" />
                </div>
            </div>
            <div class="form-group" id="route-item">
                <label for="" class="col-sm-3 control-label">路由</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="route" placeholder="请输入路由地址" value="#" />
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-3 control-label">排序</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="sort" placeholder="请输入顺序" />
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-3 control-label">启用</label>
                <div class="col-sm-8">
                    <select id="enable" class="form-control">
                        <option value="1" selected="selected">启用</option>
                        <option value="0">不启用</option>
                    </select>
                </div>
            </div>
        </form>
        <div id="disabled" class="disabled" style="display: none;">
            <p style="font-size: 18px;" class="text-danger"><i class="fa fa-info"></i> 真的要禁用吗？</p>
        </div>
        <div class="form-group">
            <div class="col-sm-12 text-center" style="padding-bottom: 15px;">
                <button id="btn-add-c" class="btn btn-success" type="button">添加</button>
                <button id="cancel-add" class="btn btn-default" type="button">取消</button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery 2.1.4 -->
<script src="/static/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="/static/bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="/static/dist/js/app.min.js"></script>
<script src="/static/dist/js/jstree.min.js"></script>
<script>

var popover = $('#test');
var title = $('.popover-title',popover);
var submitBtn = $('#btn-add-c',popover);
var cname = $('#cname'),
    icon = $('#icon'),
    route = $('#route'),
    sort = $('#sort'),
    parentId = $('#parent_id'),
    currentId = $('#current_id'),
    enable = $('#enable'),
    mType = $('#isManage');

mType.on('change',function(){
    if($(this).val() == 'menu'){
        $('#route-item').hide();
    }else{
        $('#route-item').show();
    }
});

function customMenu(node){
    var menus = {
        createItem : {
            label : "创建菜单",
            action : function(_node){
                popup(_node,'create');
            }
        },
        renameItem : {
            label : "修改菜单",
            action : function(_node){
                popup(_node,'update');
            }
        },
        deleteItem : {
            label : "禁用菜单",
            action : function(_node){
                popup(_node,'disabled');
            }
        }
    };
    var menu = $('#' + node.id);
    var info = menu.attr('data-info');
    nodeInfo = typeof info == 'string' ? JSON.parse(info) : info;

    if(nodeInfo.enable == 0){
        delete menus['createItem'];
        delete menus['deleteItem'];
        menus.renameItem.label = '启用菜单';
    }
    if(nodeInfo.type == 'item'){
        delete menus['createItem'];
    }

    return menus;
}

function setInfo(node){
    var info = $(node).data('info');
    currentId.val(info.id);
    parentId.val(info.parent);
    cname.val(info.name);
    icon.val(info.icon);
    route.val(info.route);
    sort.val(info.sort);
    enable.val(info.enable);
    mType.val(info.type);
    if(mType.val() == 'menu'){
        $('#route-item').hide();
    }
}

function popup(node,type){
    var _target = $(node.reference[0]);
    var _parent = node.reference.prevObject[0];
    var offset = _target.offset();
    var dataInfo = $(_parent).data('info');
    var itemInfo = typeof dataInfo === 'string' ? JSON.parse(dataInfo) : dataInfo;
    var pid = itemInfo.id;
    $('#c-form').show();
    $('#disabled').hide();
    if(type == 'update'){
        popover.attr('data-status','update');
        setInfo(_parent);
        title.text('修改菜单');
        submitBtn.text('提交');
        $('#type-box').hide();
    }else if(type == 'create'){
        parentId.val(pid);
        popover.attr('data-status','create');
        title.text('添加菜单');
        submitBtn.text('添加');
    }else if(type == 'disabled'){
        $('#c-form').hide();
        $('#disabled').show();
        currentId.val($(_parent).data('info').id);
        parentId.val($(_parent).data('info').parent);
        popover.attr('data-status','disabled');
        title.text('提示');
        submitBtn.text('确定');
    }
    popover.css({top : offset.top - 30, left : offset.left + 100}).show();

    $('#btn-add-c').off('click').on('click',{pid : pid,li : _parent},onSubmit);
    $('#cancel-add').on('click',function(){
        popover.hide().find('input').val('');
    });

}

function onSubmit(event){

    var data = event.data;
    var pid = data.pid,
        li = data.li;
    var type = popover.attr('data-status');

    if(cname.val() == '' && type != 'disabled'){
        alert(cname.prop('placeholder'));
        return false;
    }

    if(mType.val() == '' && type != 'disabled'){
        alert('请选择菜单类型！');
        return false;
    }

    var item = {name : cname.val(),icon : icon.val(),route : route.val(),parent : pid,sort : sort.val(),enable : enable.val(),type : mType.val()};

    var rmType = type;

    if(type == 'disabled'){
        type = 'update';
        item = $(li).data('info');
        item.enable = 0;
    }
    if(type == 'update'){
        item.id = currentId.val();
        item.parent = parentId.val();
    }


    var url = type == 'update' ? '<?=base_url("/cms/acl/admin/update")?>' : '<?=base_url("/cms/acl/admin/add")?>';

    $.post(url,item,function(result){

        var r = typeof result === 'string' ? JSON.parse(result) : result;

        if(r.status == 'succ'){


            if(rmType == 'update'){
                var cid = $('#tree').jstree(true).rename_node('#' + li.id,item.name);
                $('#tree').jstree(true).set_icon('#' + li.id,item.icon);
                $('#' + li.id).attr('data-info',JSON.stringify(item));
            }

            if(rmType == 'disabled'){
                var cid = $('#tree').jstree(true).rename_node('#' + li.id,item.name + '(已禁用)');
                $('#' + li.id).attr('data-info',JSON.stringify(item));
            }


            if(rmType == 'create'){
                item.id = r.data;
                var newNode = {
                    text : item.name,
                    icon : item.icon,
                    li_attr : {
                        'data-info' : JSON.stringify(item)
                    }
                };
                var cid = $('#tree').jstree(true).create_node('#' + li.id,newNode,'last',function(){
                    this.open_node(li);
                },true);
            }

            $('#cancel-add').trigger('click');
        }

    });
}

var config = {
    core : {
        animation : 1,
        check_callback : true,
        themes : {tripes : true}
    },
    types : {
        '#' : {
            valid_children : ['测试一下']
        }
    },
    plugins : ['contextmenu','types','state','dnd'],
    contextmenu : {
        items : customMenu
    }
};

$('#tree').jstree(config).on('create_node.jstree',function(node,parent,position){
}).on('rename_node.jstree',function(node,text,old){
    console.log(node,text,old);
});
$('#tree').jstree(true).open_all();


//$('#testc').on('click',function(){
//    $.jstree.defaults.core.data = all;
//    //$('#tree').data('jstree', false).empty().jstree();
//    
//    $('#tree').jstree('refresh',true,true);
//});

</script>
</body>
</html>
