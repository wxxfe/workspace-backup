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
    <link rel="stylesheet" href="/static/plugins/select2/select2.min.css">
    <link rel="stylesheet" href="/static/dist/css/bootstrap-switch.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        .table>tbody>tr>td{vertical-align: middle;}
        .select2-container--default .select2-selection--multiple .select2-selection__choice{
            color: #000;
        }
        .select2-container{display: block;}
        .label{display: inline-block;}
        .select2-container .select2-selection--single{height: auto;}
        .select2-container--default .select2-selection--single .select2-selection__arrow{height: 35px;}
    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
    <div class="wrapper">
        <div class="content-wrapper" style="margin-left: 0;">
            <section class="content-header">
              <h1>视频频道管理<small>Channel Video</small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">视频频道管理</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-4">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">添加频道</h3>
                            </div>
                            <div class="box-body">
                                <form class="form-horizontal" id="channel-form" method="post">
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">名称</label>
                                        <div class="col-sm-10 bvalidator-bs3form-msg">
                                            <input required maxlength="128" data-bvalidator-msg="请输入名称!" type="text" name="name" class="form-control" placeholder="请输入频道名称" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tmpl" class="col-sm-2 control-label">模板</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="tmpl" id="tmpl-select">
                                                <option value="block">多视频</option>
                                                <option value="single">单视频</option>
                                                <option value="program">节目</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="tag-select" style="display: none;">
                                        <label for="tag" class="col-sm-2 control-label">关联标签</label>
                                        <div class="col-sm-10">
                                            <select id="tag-search" class="form-control"></select>
                                            <input type="hidden" name="tags" value="" id="tag-box" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="priority" class="col-sm-2 control-label">排序</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="priority" value="" class="form-control" placeholder="请输入频道排序" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="visible" class="col-sm-2 control-label">上线?</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="visible">
                                                <option value="0">下线</option>
                                                <option value="1">上线</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12 text-center">
                                            <button class="btn btn-flat btn-success" type="submit">添加</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">频道列表</h3>
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
                                            <th>频道ID</th>
                                            <th>频道名称</th>
                                            <th>频道模板</th>
                                            <th>标签</th>
                                            <th>上下线</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($channels as $key => $item): ?>
                                        <tr>
                                            <td>
                                                <a href="javascript:void(0)" class="item-text-edit" data-type="text" data-pk="<?=$item['id']?>" data-name="priority" data-title="修改排序">
                                                    <?=$key+1?>
                                                </a>
                                            </td>
                                            <td><?=$item['id']?></td>
                                            <td><?=$item['name']?></td>
                                            <td><?=$tmpls[$item['tmpl']]?></td>
                                            <td>
                                                <?php if(!empty($item['tagsData'])): ?>
                                                    <?php foreach($item['tagsData'] as $tag): ?>
                                                    <span class="label label-info"><?=$tag['name']?></span>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <input data-pk="<?= $item['id'] ?>" name="visible"
                                                       class="release" type="checkbox"
                                                    <?php if ($item['visible']): ?>
                                                        checked
                                                    <?php endif; ?>
                                                >
                                            </td>
                                            <td>
                                                <a class="btn btn-flat btn-success btn-xs" role="button" href="<?=base_url('/ChannelVideo/channelEdit/'.$item['id'])?>"><i class="fa fa-edit"></i> 编辑</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
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

<script src="/static/dist/js/bvalidator.js"></script>
<script src="/static/dist/js/bvalidator.bs3form.min.js"></script>
<script src="/static/dist/js/bs3form.js"></script>

<script src="/static/dist/js/bootstrap-switch.min.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script src="/static/dist/js/bootstrap-editable.min.js"></script>

<script src="/static/plugins/select2/select2.full.min.js"></script>
<script>
$('#channel-form').bValidator({validateOn: 'blur,change'});
$.fn.editable.defaults.url = '<?=base_url("/ChannelVideo/updateField/channel_video")?>';
$('.item-text-edit').editable({
    success : function(respons,newValue){
        console.log(respons);
        if(respons == 'success'){
            window.location.reload();
        }
    }
});
$("input[name='visible']").bootstrapSwitch({
    size: 'mini',
    onText: '上线',
    offText: '下线',
    onSwitchChange: function (event, state) {
        $.ajax({
            method: "POST",
            url: '<?=base_url("/ChannelVideo/updateField/channel_video")?>',
            data: {name: "visible", value: parseInt(Number(state)), pk: $(this).data('pk')}
        });
    }
});

$('#tmpl-select').on('change',function(){
    var tmpl = $(this).val();
    if(tmpl == 'single'){
        $('#tag-select').show();
        $('.select2-container').width('100%');
    }else{
        $('#tag-select').hide();
        $('#tag-box').val('');
    }
});

var _port = window.location.port ? ':' + window.location.port : '';
$('#tag-search').select2({
    placeholder: '搜索标签',
    tag : false,

    ajax: {
        url: 'http://' + document.domain + _port + '/search/query/tag',
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                keyword : params.term,
                page : params.page
            }
        },
        processResults: function (data, params) {
            params.page = params.page || 1;

            return {
                results: data.result,
                pagination: {
                    more: (params.page * 30) < data.total
                }
            };
        },

        cache: true
    },
    escapeMarkup: function (markup) { return markup; },
    minimumInputLength: 1,
    templateResult : function(data){
        var item = data;
        return item.name + '-' + item.type
    },
    templateSelection : function(data){
        if(data.name){
            $('#tag-box').val(data.fake_id);
            return data.name;
        }else{
            return '搜索标签';
        }
    }
}).on('select2:select',function(d){
    var tagInfo = d.params.data;
    return tagInfo.name;
});

</script>
</body>
</html>
