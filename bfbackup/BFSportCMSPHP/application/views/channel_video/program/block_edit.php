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
                <h1>修改内容</h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">修改内容</li>
                </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-4">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">修改内容</h3>
                            </div>
                            <div class="box-body">
                                <div class="input-group" style="position: relative;">
                                    <input id="search-type" type="hidden" value="program" />
                                    <input id="keyword" class="form-control" placeholder="请输入关键词" type="text" name="keyword" />
                                    <span class="input-group-btn"><button id="search_btn" class="btn bg-purple btn-flat" type="button"><i class="fa fa-search"></i> 搜索</button></span>
                                </div>
                                <div id="search-result" style="padding-top: 20px; max-height: 700px; overflow-x: hidden; overflow-y: auto;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="box">
                            <div class="box-header with-border">
                                
                            </div>
                            <div class="box-body">
                                <form class="form-horizontal" method="post" id="add-content">
                                    <div class="form-group">
                                        <label for="ref_id" class="col-sm-2 control-label">节目ID</label>
                                        <div class="col-sm-3">
                                            <input id="ref-id" type="text" class="form-control" value="<?=$content['program_id']?>" name="program_id" placeholder="请输入节目ID" />
                                        </div>
                                        <div class="col-sm-7">
                                            <span style="color: #f90; line-height: 30px;"><i class="fa fa-info"></i> 提示：</span>
                                            如不知道ID，可以通过左侧的搜索得到内容ID
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="title" class="col-sm-2 control-label">内容标题</label>
                                        <div class="col-sm-5">
                                            <input id="title" type="text" class="form-control" value="<?=$content['title']?>" name="title" placeholder="请输入内容标题" />
                                        </div>
                                        <div class="col-sm-5">
                                            <span style="color: #f90; line-height: 30px;"><i class="fa fa-info"></i> 提示：</span>
                                            不填表示使用该视频或合集的标题
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">封面</label>
                                        <div class="col-sm-8">
                                            <?php if(empty($content['image'])): ?>
                                            <div id="image-view" style="padding: 10px 0; display: none;"></div>
                                            <?php else: ?>
                                            <div id="image-view" style="padding: 10px 0;">
                                                <img style="max-width: 200px;" src="<?=getImageUrl($content['image'])?>" alt="">
                                            </div>
                                            <?php endif; ?>
                                            <span class="btn btn-warning fileinput-button">
                                                <i class="glyphicon glyphicon-plus"></i>
                                                <span>上传封面</span>
                                                <input id="fileupload" type="file" name="image" multiple data-url="<?=IMG_UPLOAD?>" />
                                            </span>
                                            <input id="poster" type="hidden" name="poster" value="<?=$content['image']?>" />
                                            <span style="color: #f90; line-height: 30px;"><i class="fa fa-info"></i> 提示：</span>
                                            建议尺寸：335 * 187，不上传将使用视频或合集的封面
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">大图</label>
                                        <div class="col-sm-8">
                                            <?php if(empty($content['large_image'])): ?>
                                            <div id="image-view-big" style="padding: 10px 0; display: none;"></div>
                                            <?php else: ?>
                                            <div id="image-view-big" style="padding: 10px 0;">
                                                <img style="max-width: 300px;" src="<?=getImageUrl($content['large_image'])?>" alt="">
                                            </div>
                                            <?php endif; ?>
                                            <span class="btn btn-warning fileinput-button">
                                                <i class="glyphicon glyphicon-plus"></i>
                                                <span>上传大图</span>
                                                <input id="fileupload-big" type="file" name="image" multiple data-url="<?=IMG_UPLOAD?>" />
                                            </span>
                                            <input id="cover" type="hidden" name="cover" value="<?=$content['large_image']?>" />
                                            <span style="color: #f90; line-height: 30px;"><i class="fa fa-info"></i> 提示：</span>
                                            建议尺寸：692 * 332，不上传将使用视频或合集的封面
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">是否上线</label>
                                        <div class="col-sm-3">
                                            <select class="form-control" name="visible">
                                                <option<?php if($content['visible'] == 1): ?> selected="selected"<?php endif; ?> value="1">上线</option>
                                                <option<?php if($content['visible'] == 0): ?> selected="selected"<?php endif; ?> value="0">下线</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12 text-center">
                                            <button class="btn btn-flat btn-warning btn-lg" type="submit"><i class="fa fa-plus"></i> 更新</button>
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

<!-- Jquery UI -->
<script src="/static/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/static/fileupload/scripts/jquery.ui.widget.js"></script>
<script src="/static/fileupload/scripts/jquery.fileupload.js"></script>

<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script>
var alertConfig = {
    title: "你确定要删除吗?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dd4b39",
    confirmButtonText: "确定删除",
    cancelButtonText: "取消",
    closeOnConfirm: false
};

$('#add-content').on('submit',function(){
    var refId = $('#ref-id').val();
    if(refId == ''){
        swal('请输入内容ID或从左侧搜索！','','error');
        return false;
    }
});

/*-Search-------------------------*/
$('#select_search li a').on('click',function(){
    var val = $(this).data('type');
    var otext = $(this).text();
    $('#search-type').val(val);
    $('#dropdown_value').text(otext);
});

var _port = window.location.port ? ':' + window.location.port : '';
$('#search_btn').on('click',function(){
    var keyword = $('#keyword');
    var stype = $('#search-type');
    if(keyword.val() == ''){
        swal('请输入关键词！','','error');
        return false;
    }
    if(stype.val() == ''){
        swal('请选择搜索类型！','','error');
        return false;
    }
    var url = 'http://' + document.domain + _port + '/api/search/' + stype.val() + '/' + keyword.val();
    $.get(url,function(result){
        var data = typeof result === 'string' ? JSON.parse(result) : result;
        if(data.total > 0){
            var resultHtml = '<ul style="list-style: none; padding: 0; margin: 0;">';
            for(var i=0; i<data.total; i++){
                resultHtml += '<li>' + templateResult(stype.val(),data.result[i]) + '</li>';
            }
            resultHtml += '</ul>';
            $('#search-result').html(resultHtml);
        }else{
            $('#search-result').html('<p class="text-center">无</p>');
        }
    });

});

$('#search-result').on('click','.btn-add',function(){

    var vid = $(this).data('vid');
    var type = $(this).data('type');
    $('#ref-id').val(vid);
    $('#type').val(type);
    $('#keyword').val('');
    $('#search-result').empty();
});

function templateResult(type,data){
    if(!data) return '';
    var image = data.image.indexOf('http://') > -1 ? data.image : 'http://image.sports.baofeng.com/' + data.image;
    var media = '';
    media += '<div class="media" style="margin-bottom: 10px;">';
    media += '	<div class="media-left">';
    media += '		<a href="javascript:void(0)">';
    media += '			<img width="100" class="media-object" src="'+ image +'" alt="'+ data.title +'">';
    media += '		</a>';
    media += '	</div>';
    media += '	<div class="media-body">';
    media += '		<h4 class="media-heading" style="font-size: 16px;">'+ data.title +'</h4>';
    media += '	</div>';
    media += '	<div class="media-right">';
    media += '		<a href="javascript:void(0)" role="button" class="btn btn-info btn-flat btn-add" data-type="'+ type +'" data-vid="'+ data.id +'"><i class="fa fa-plus"></i> 使用</a>';
    media += '	</div>';
    media += '</div>';
    return media;
}

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
            $("#image-view").html('<img style="max-width: 200px;" src="http://image.sports.baofeng.com/' + data.result.data.pid + '">').show();
            $('#poster').val(data.result.data.pid);
        }
    }
});
$('#fileupload-big').fileupload({
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
            $("#image-view-big").html('<img style="max-width: 300px;" src="http://image.sports.baofeng.com/' + data.result.data.pid + '">').show();
            $('#cover').val(data.result.data.pid);
        }
    }
});

</script>
</body>
</html>
