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
        .table>tbody>tr>td{vertical-align: middle;}
    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
    <div class="wrapper">
        <div class="content-wrapper" style="margin-left: 0;">
            <section class="content-header">
              <h1>标签管理<small>Tag </small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">标签列表</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-body">
                                <div class="col-md-4">
                                    <form id="search-form" method="post">
                                        <div class="input-group">
                                            <input id="keyword" class="form-control" placeholder="请输入标签名称" type="text" name="keyword" value="<?=$keyword?>" />
                                            <span class="input-group-btn"><button class="btn bg-purple btn-flat" type="submit"><i class="fa fa-search"></i> 搜索</button></span>
                                            <span class="input-group-btn"><button id="search-reset" class="btn bg-gray btn-flat" type="reset"><i class="fa fa-recycle"></i> 重置</button></span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="user-list">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">标签列表</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                        <?php
                                            // $btn_colors = array('maroon', 'purple', 'olive', 'gray', 'yellow', 'blue', 'navy', 'red', 'green', 'black');
                                            $btn_colors = array('gray');
                                            $filter_arr = json_decode($filter, true);
                                        ?>
                                        <?php foreach ($categories as $cate): ?>
                                            <th><?=$cate['name']?></th>
                                        <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        <?php foreach ($categories as $cate): ?>
                                            <td>
                                            <?php foreach ($cate_tags[$cate['id']] as $tag): ?>
                                                <?php
                                                    $cindex = hexdec(substr(md5($tag['name']), 3, 3)) % count($btn_colors);
                                                    $editable = false;
                                                    if ($cate['type'] == 'none' && $tag['editable'] && $this->AM->canModify()) {
                                                        $editable = true;
                                                    }
                                                    $clickable = false;
                                                    if (!in_array($cate['type'], array('player', 'none'))) {
                                                        $clickable = true;
                                                    }
                                                    
                                                    $checked = false;
                                                    if (@$filter_arr[$cate['type']] == $tag['id']) {
                                                        $checked = true;
                                                    }
                                                ?>
                                                <a role="button" style="margin:2px" class="btn btn-sm bg-<?=($checked? 'orange' : $btn_colors[$cindex])?>" data-id="<?=$tag['id']?>" data-type="<?=$cate['type']?>" <?=(!$editable && !$clickable)? 'disabled="disabled"' : ''?>>
                                                    <span href="#" data-url="<?=site_url('tag/update')?>" data-pk="<?=$tag['id']?>" data-name="name"
                                                        class="<?=($editable? 'tag_name' : '')?> <?=($clickable? 'tag_clickable' : '')?>" >
                                                        <?=$tag['name'] ?>
                                                    </span>
                                                    <?php if ($editable): ?>
                                                    &nbsp;&nbsp;
                                                    <i class="fa fa-minus-square delete_tag"
                                                        data-type="checklist" data-source="{'1':'&nbsp;'}" data-value="1"
                                                        data-url="<?=site_url('tag/delete')?>"
                                                        data-pk="<?=$tag['id']?>"></i>
                                                    <?php endif; ?>
                                                </a>
                                            <?php endforeach; ?>
                                            <?php if ($cate['type'] == 'none' && empty($keyword)): ?>
                                                <a role="button" class="btn btn-lg new_tag" data-url='<?=site_url("tag/add/{$cate['id']}")?>' data-pk="" data-name="name"> <i class="fa fa-plus"></i> </a>
                                            <?php endif; ?>
                                            </td>
                                        <?php endforeach; ?>
                                        </tr>
                                    </tbody>
                                </table>
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
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script>
$(".tag_name").editable({
    validate: function(value) {
        if ($.trim(value) == '') {
            return '该字段不能为空！';
        }
    }
});
$(".new_tag").editable({
    validate: function(value) {
        if ($.trim(value) == '') {
            return '该字段不能为空！';
        }
    },
    success : function() {
        window.location.href = window.location.href;
    }
});
$(".delete_tag").editable({
    title : "取消选中以删除",
    success : function(response, newValue) {
        console.log($(this).parent().remove());
    }
}).on('shown', function(e, editable) {
    $(this).next().children(".popover-title").css('color', 'red');
    // if (!confirm('确定要删除标签吗？此操作危险，请谨慎！')) {
    //     editable.hide();
    // } else {
    //     $(this).next().children(".popover-title").css('color', 'red');
    // }
});

$(".tag_clickable").click(function() {
    var keyword = "<?=($keyword ?: '-')?>";
    var filter = <?=($filter ?: '{}')?>;
    
    var id = $(this).parent().data("id");
    var type = $(this).parent().data("type");
    filter[type] = id;
    
    if (type == 'sports') {
        delete filter.event;
        delete filter.team;
        delete filter.player;
    }
    if (type == 'event') {
        delete filter.team;
        delete filter.player;
    }
    if (type == 'team') {
        delete filter.player;
    }
    
    window.location.href = '<?=site_url("tag/index")?>/' + keyword + '/' + encodeURIComponent(JSON.stringify(filter));
});

$("#search-form").submit(function() {
    var keyword = $("#keyword").val();
    var filter = <?=($filter ?: '{}')?>;
    if (!keyword) {
        keyword = '-';
    }
    $(this).attr('action', "<?=site_url('tag/index') ?>" + "/" + keyword + '/' + encodeURIComponent(JSON.stringify(filter)));
});

$("#search-reset").click(function() {
    window.location.href = '<?=site_url("tag/index")?>';
});

</script>
</body>
</html>
