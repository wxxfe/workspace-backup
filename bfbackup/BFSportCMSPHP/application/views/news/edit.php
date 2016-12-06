<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= HEAD_TITLE ?></title>
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
    <link rel="stylesheet" href="/static/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="/static/plugins/ueditor1_4_3_3-utf8/previewer/previewer.css" />
    <link rel="stylesheet" href="/static/plugins/ueditor1_4_3_3-utf8/bfeditor/bfeditor.css" />
    <style type="text/css">
        .table > tbody > tr > td {
            vertical-align: middle;
        }

        .ui-tooltip {
            left: 20px !important;
            z-index: 1000000;
        }

        #image-view img, #image-view-big img {
            margin: 10px 0;
            max-height: 200px;
        }
        .title-count-tip {
        		padding-top: 7px;
    			margin-bottom: 0;
        }
    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
<div class="wrapper">
    <div class="content-wrapper" style="margin-left: 0;">
        <section class="content-header">
            <h1>编辑新闻
                <small>Edit News</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">编辑新闻</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12" id="user-list">
                    <div class="box">
                        <form class="form-horizontal" method="post" id="news-form">
                            <div class="box-body" style="padding-top: 20px;">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="title">标题</label>
                                            <div class="col-sm-8 bvalidator-bs3form-msg">
                                                <input id="news-form-title" required maxlength="128" data-bvalidator-msg="请输入标题!"
                                                       class="form-control" type="text" name="title"
                                                       value="<?php echo htmlspecialchars($news['title'])?>" placeholder="请输入新闻标题"/>
                                            </div>
                                            
                                            <div id="title-count-tip" class="title-count-tip">
                                            		<span class="count">0</span><span>&nbsp;&nbsp;个字</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="site">来源</label>
                                            <div class="col-sm-8 bvalidator-bs3form-msg">
                                                <select class="form-control" name="site" required
                                                        data-bvalidator-msg="请选择新闻来源!">
                                                    <option value="">请选择</option>
                                                    <?php foreach ($sites as $site): ?>
                                                        <option<?php if ($site['site'] == $news['site']): ?> selected="selected"<?php endif; ?>
                                                            value="<?= $site['site'] ?>"><?= $site['origin'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="publish_tm">发布时间</label>
                                            <div class="col-sm-8">
                                                <div id="datetimepicker" class="input-append date">
                                                    <input value="<?= $news['publish_tm'] ?>"
                                                           data-format="yyyy-MM-dd hh:mm:ss" class="form-control"
                                                           style="display: inline-block; width: 80%;" id="publish_tm"
                                                           name="publish_tm" placeholder="" type="text">
                                                    <span class="add-on"
                                                          style="display: inline-block; margin-left: 5px;">
                                                            <i data-time-icon="fa fa-clock-o"
                                                               data-date-icon="fa fa-calendar"></i>
                                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="match_id">关联比赛</label>
                                            <div class="col-sm-8">
                                                <?php $this->load->view('common/linkMatch', array('current' => $news['match_id'])); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="visible">上下线</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" name="visible">
                                                    <option<?php if ($news['visible'] == 1): ?> selected="selected"<?php endif; ?>
                                                        value="1">上线
                                                    </option>
                                                    <option<?php if ($news['visible'] == 0): ?> selected="selected"<?php endif; ?>
                                                        value="0">下线
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="subtitle">摘要</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" name="subtitle" rows="3"
                                                          placeholder="请输入新闻摘要"><?= $news['subtitle'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="duration">阅读时长</label>
                                            <div class="col-sm-3">
                                                <input maxlength="5" 
                                                       class="form-control" type="text" name="duration" value="<?php echo intval($news['duration']/60) ?>"
                                                       placeholder="阅读时长"/>
                                            </div>
                                            <label class="control-label col-sm-2">单位：分钟</label>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="">封面</label>
                                            <div class="col-sm-8">

                                                <span class="btn btn-warning fileinput-button">
                                                        <i class="glyphicon glyphicon-plus"></i>
                                                        <span>上传封面</span>
                                                        <input id="fileupload" type="file" name="image" multiple
                                                               data-url="<?= IMG_UPLOAD ?>"/>
                                                    </span>&nbsp;&nbsp;&nbsp;&nbsp;<b>建议尺寸225*150</b>
                                                <input id="poster" type="hidden" name="poster"
                                                       value="<?= $news['image'] ?>"/>
                                                <div id="image-view">
                                                    <?php if ($news['image']): ?>
                                                        <img src="<?= getImageUrl($news['image']) ?>" alt=""/>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="">大图</label>
                                            <div class="col-sm-8">

                                                <span class="btn btn-warning fileinput-button">
                                                        <i class="glyphicon glyphicon-plus"></i>
                                                        <span>上传大图</span>
                                                        <input id="fileupload-big" type="file" name="image" multiple
                                                               data-url="<?= IMG_UPLOAD ?>"/>
                                                </span>
                                                <a id="large-image-remove"
                                                   class="btn btn-danger"
                                                    <?php if (!$news['large_image']): ?>
                                                        style="display: none;"
                                                    <?php endif; ?>
                                                   role="button"
                                                   href="javascript:void(0);"><i class="fa fa-remove"></i> 删除大图 </a>&nbsp;&nbsp;&nbsp;&nbsp;<b>建议尺寸750*600</b>
                                                <input id="cover" type="hidden" name="cover"
                                                       value="<?= $news['large_image'] ?>"/>
                                                <div id="image-view-big">
                                                    <?php if ($news['large_image']): ?>
                                                        <img src="<?= getImageUrl($news['large_image']) ?>" alt=""/>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="">标签</label>
                                            <div class="col-sm-8">
                                                <?php $this->load->view('common/tagSelect', array('selected' => $news['tags'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-sm-1" for="">内容</label>
                                            <div class="col-sm-10">
                                                <script id="container" name="content"
                                                        type="text/plain"><?= $news['content'] ?></script>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    		<div class="col-sm-offset-2 col-sm-8">
                                    			<div class="phone">
	                                            <iframe frameborder="0" scrolling="no"></iframe>
	                                            <div class="statusbar"></div>
	                                        </div>
                                    		</div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button class="btn btn-success btn-lg" type="submit">更新</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </section>
    </div>
</div>
<div class="bfeditor-toolbar" style="display: none;">
	<span data-op="delete">删除</span>
	<span data-op="insertBefore">前空行</span>
	<span data-op="insertAfter">后空行</span>
	<span data-op="up">上移</span>
	<span data-op="down">下移</span>
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

<script src="/static/dist/js/bvalidator.js"></script>
<script src="/static/dist/js/bvalidator.bs3form.min.js"></script>
<script src="/static/dist/js/bs3form.js"></script>

<script src="/static/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script src="/static/plugins/ueditor1_4_3_3-utf8/ueditor.config.js"></script>
<script src="/static/plugins/ueditor1_4_3_3-utf8/ueditor.all.js"></script>
<script src="/static/plugins/ueditor1_4_3_3-utf8/bfImage.js"></script>
<script src="/static/plugins/ueditor1_4_3_3-utf8/bfVideo.js"></script>
<script src="/static/plugins/ueditor1_4_3_3-utf8/bfTopic.js"></script>
<script src="/static/plugins/ueditor1_4_3_3-utf8/bfGallery.js"></script>
<script type="text/javascript" src="/static/plugins/ueditor1_4_3_3-utf8/previewer/previewer.js"></script>
<script type="text/javascript" src="/static/plugins/ueditor1_4_3_3-utf8/bfeditor/bfeditor.js" ></script>

<script>

    var ue = UE.getEditor('container');
    ue.sportsbaofengcomdata = {IMG_DOMAIN:"<?= IMG_DOMAIN ?>",IMG_UPLOAD:"<?= IMG_UPLOAD ?>"};

    $('#datetimepicker').datetimepicker();

    $('#news-form').bValidator({validateOn: 'blur'});
    $('#news-form').on('submit', function () {
        var poster = $('#poster').val();
        if (poster == '') {
            swal("请上传封面图!", "", "error");
            return false;
        }
        if (!ue.hasContents()) {
            swal("请输入新闻内容!", "", "error");
            return false;
        }
    });
    $('input,textarea,button,form').on('keypress', function (event) {
        if (event.keyCode == 13) return false;
    });



    //--file upload----------------------------------------------------
    $('#fileupload').fileupload({
        add: function (e, data) {
            //data.context = $('<p/>').text('Uploading...').appendTo('#content');
            data.submit();
        },
        done: function (e, data) {
            //$('#cover').val(data);
            var result = data.result.errno;

            if (result !== 10000) {
                alert('上传失败,请重试！');
            }
            else {
                $("#image-view").html('<img src="<?= IMG_DOMAIN ?>' + data.result.data.pid + '">').show();
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

            if (result !== 10000) {
                alert('上传失败,请重试！');
            }
            else {
                $("#image-view-big").html('<img src="<?= IMG_DOMAIN ?>' + data.result.data.pid + '">').show();
                $('#cover').val(data.result.data.pid);
                $("#large-image-remove").show();
            }
        }
    });
    $('#large-image-remove').on('click', function () {
        $("#image-view-big").html('').hide();
        $('#cover').val('');
        $("#large-image-remove").hide();
    });
    
    $('#news-form-title').on('keyup', setTileNum);
    
    function setTileNum() {
    		var wordNum = getWordsCount($('#news-form-title').val());
    		if(wordNum > 30) {
    			$('#title-count-tip .count').css('color', 'red');
    		} else {
    			$('#title-count-tip .count').css('color', 'inherit');
    		}
    		$('#title-count-tip .count').text(wordNum);
    }
    setTileNum();
    
    // 汉字为1，英文、数字、符号为0.5
	function getWordsCount(str) {
		var lenE = str.length;
		var lenC = 0;
		var CJK = str.match(/[^\x00-\xff]/g);
		var enter = str.match(/\r\n/g);
		if (CJK != null) lenC += CJK.length;
		if (enter != null) lenC -= enter.length;
        return (lenE + lenC)/2;
	}
</script>
</body>
</html>
