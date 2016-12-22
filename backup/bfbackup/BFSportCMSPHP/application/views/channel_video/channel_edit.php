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
                    <div class="col-md-4 col-md-offset-4">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">更新频道</h3>
                            </div>
                            <div class="box-body">
                                <form class="form-horizontal" id="channel-form" method="post">
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">名称</label>
                                        <div class="col-sm-10 bvalidator-bs3form-msg">
                                            <input required maxlength="128" data-bvalidator-msg="请输入名称!" type="text" name="name" value="<?=$channel['name']?>" class="form-control" placeholder="请输入频道名称" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="tag-select"<?php if($channel['tmpl'] != 'single'): ?> style="display: none;"<?php endif; ?>>
                                        <label for="tag" class="col-sm-2 control-label">关联标签</label>
                                        <div class="col-sm-10">
                                            <select id="tag-search" class="form-control"></select>
                                            <input type="hidden" name="tags" value="<?=$channel['tags']?>" id="tag-box" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="visible" class="col-sm-2 control-label">上线?</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="visible">
                                                <option<?php if($channel['visible'] == 0): ?> selected="selected"<?php endif; ?> value="0">下线</option>
                                                <option<?php if($channel['visible'] == 1): ?> selected="selected"<?php endif; ?> value="1">上线</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12 text-center">
                                            <button class="btn btn-flat btn-success" type="submit">修改</button>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <button class="btn btn-flat btn-default" onclick="window.history.go(-1)" type="button">返回</button>
                                        </div>
                                    </div>
                                </form>
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

<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script src="/static/plugins/select2/select2.full.min.js"></script>
<script>
$('#channel-form').bValidator({validateOn: 'blur,change'});

var currentTag = '<?=(!empty($currentTag) ? $currentTag['name'] : '搜索标签')?>';
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
            return currentTag;
        }
    }
}).on('select2:select',function(d){
    var tagInfo = d.params.data;
    return tagInfo.name;
});
</script>
</body>
</html>
