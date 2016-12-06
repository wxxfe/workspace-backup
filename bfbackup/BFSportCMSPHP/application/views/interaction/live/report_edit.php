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
    <link rel="stylesheet" href="/static/dist/css/bootstrap-switch.min.css">
    <link rel="stylesheet" href="/static/plugins/sweetalert-master/dist/sweetalert.css">
    	<link rel="stylesheet" href="/static/fileupload/css/fileupload.css">
	<link rel="stylesheet" href="/static/fileupload/css/fileupload-ui.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        th, td {
            vertical-align: middle !important;
            text-align: center;
            word-warp: break-word;
            word-break: break-all;
        }

        img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        .option > * {
            width: 30%;
            margin-right: 5%;
            float: left;
        }

        .option > input:nth-child(3) {
            margin-right: 0;
        }

        .deadline > * {
            width: 15%;
            float: left;
        }

        .deadline > .help-block {
            width: auto;
        }

        .report_clcle {
	       width:30px;
	       height:30px;
	       line-height:30px;
           border-radius:19px;
           border:1px solid #ccc;
           display:inline-block;
           text-align: center;
           margin-right:10px;
        }
        
        td:first-child{ text-align:left;width:460px; }
    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
<div class="wrapper">
    <div class="content-wrapper" style="margin-left: 0;">
        <section class="content-header">
              <!--  h1>战报<small>Report </small></h1>
              <ol class="breadcrumb">
                <li><a href="/main/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><a href="</?=base_url('/interaction/live/main_live/index/'.$match_id.'/'.$host) ?>"> 互动直播间</a></li>
              </ol-->
        </section>
        <section class="content">
       
          <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title pull-left" style="line-height: 200%;">编辑战报</h3>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal" method="post" id="js_text_form" action="">
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="">时间线</label>
                                    <input type="text" value="<?php if (-1 != $report_info['report_tm']) { echo $report_info['report_tm']; } ?>" name="timeline" class="js_timeline" />&nbsp;&nbsp;分钟
                                    
                                    <label class="control-label col-sm-2" for="" style="float:none;">综述</label>
                                    <input type="checkbox" value="1" name="sumup" class="js_sumup" <?php if (-1 == $report_info['report_tm']) { echo 'checked'; }?> />
                                    
                                </div>
                                
                                
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="">图片</label>
                                    <div class="col-sm-8">
                                        <div id="image-view" style="padding: 5px 0;"><img style="width:100%; <?php if (!$report_info['image']) {?>display:none; <?php }?>" src="<?php $img = $report_info['gif'] ? $report_info['gif'] : $report_info['image'];  echo getImageUrl($img); ?>"></div>
                                        <span class="btn btn-warning fileinput-button">
                                            <i class="glyphicon glyphicon-plus"></i>
                                            <span>上传图片</span>
                                             
                                            <input id="fileupload" type="file" name="image" multiple data-url="<?=IMG_UPLOAD?>" />
                                        </span>
                                        <input id="poster" type="hidden" name="poster" value="<?php if ($report_info['gif']) { echo $report_info['gif']; } else { echo $report_info['image']; } ?>" />
                                    </div>
                                </div>
                                
                                <div class="form-group js_fm"  style="<?php if (!$report_info['gif']) { ?>display:none;<?php }?>">
                                    <label class="control-label col-sm-2" for="">封面图</label>
                                    <div class="col-sm-8">
                                        <div id="image-view1" style="padding: 5px 0;"><img style="width:100%; <?php if (!$report_info['gif']) {?>display:none; <?php }?>" src="<?=getImageUrl($report_info['image']) ?>"></div>
                                        <span class="btn btn-warning fileinput-button">
                                            <i class="glyphicon glyphicon-plus"></i>
                                            <span>上传封面图</span>
                                             
                                            <input id="fileupload1" type="file" name="image" data-url="<?=IMG_UPLOAD?>" />
                                        </span>
                                        <input id="poster1" type="hidden" name="poster1" value="<?php if ($report_info['gif']) { echo $report_info['image']; } ?>" />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="site">文字</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control js_content" name="content" rows="3" placeholder="请输入信息"><?=$report_info['content'] ?></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="site">关键战报</label>
                                    <div class="col-sm-8">
                                        <input type="checkbox" value="1" name="visible" <?php if ($report_info['visible']) { echo 'checked'; }?> />
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                

                                <div class="form-group text-center">
                                    <input type="hidden" name="id" value=""/>
                                    <input type="hidden" name="size" class="js_size" value="<?php if ($report_info['gif_size']) { echo $report_info['gif_size']; } else { echo 0; }?>"/>
                                    <button type="button" class="btn btn-success js_save">保存</button>
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
<script src="/static/dist/js/bvalidator.bs3form.min.js"></script>
<script src="/static/dist/js/bs3form.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script>
    var formJQ = $('#js_text_form'),
       imgsize = 0,
       hasimage = 0;
    //添加表单验证，blur表示input失去焦点就验证
    formJQ.bValidator({validateOn: 'blur'});
    
    swal = window.parent.swal ? window.parent.swal : swal;
    
    //图片
    $('#fileupload').fileupload({
        add: function (e, data) {
            //data.context = $('<p/>').text('Uploading...').appendTo('#content');
            data.submit();
        },
        done: function (e, data) {
            //$('#cover').val(data);
            var result = data.result.errno;

            if(result !== 10000){
            	swal("上传失败!", "上传失败,请重试!", "error");
            }
            else{
                $("#image-view").html('<img style="width:100%;" src="http://image.sports.baofeng.com/' + data.result.data.pid + '">').show();
                $('#poster').val(data.result.data.pid);

                if (data.files[0].type.indexOf('gif')>-1) {
                    //gif图片需要上传封面图
                    $('.js_fm').css('display', 'block');
                    imgsize = data.result.data.nbytes ? data.result.data.nbytes : data.files[0].size;
                    $('.js_size').val(imgsize);
                    hasimage = 1;
                } else {
                	$('.js_fm').css('display', 'none');
                	$('#poster1').val('');
                	$('.js_size').val(0);
                	hasimage = 0;
                }
            }
        }
    });
    
    //封面图
    $('#fileupload1').fileupload({
        add: function (e, data) {
            //data.context = $('<p/>').text('Uploading...').appendTo('#content');
            data.submit();
        },
        done: function (e, data) {
            
            //$('#cover').val(data);
            var result = data.result.errno;

            if(result !== 10000){
            	swal("上传失败!", "上传失败,请重试!", "error");
            }
            else{
                $("#image-view1").html('<img style="width:100%;" src="http://image.sports.baofeng.com/' + data.result.data.pid + '">').show();
                $('#poster1').val(data.result.data.pid);
            }
        }
    });

    //接管提交按钮
    $('.js_save').click(function () {

        var v = formJQ.data("bValidators").bvalidator.isValid();

        if (v == true) {

            var timeline = parseInt($('.js_timeline').val()),
            sumup    = $('.js_sumup').prop('checked'),
            image    = $('#poster').val(),
            content  = $('.js_content').val(),
            gif      = $('#poster1').val();

            if (sumup) {
                sumup = 1;
            } else {
                sumup = 0;
            }

            var alertConfig = {
            	    title: "缺少必要参数",
            	    text: '缺少必要参数!',
            	    type: "warning",
            	    html: true,
            	    showCancelButton: true,
            	    confirmButtonColor: "#dd4b39",
            	    confirmButtonText: "确定",
            	    cancelButtonText: "取消",
            	    closeOnConfirm: false
            	};

        	if (isNaN(timeline)) {
        	    timeline = 0;
            }
        	
            if (!content || !sumup && !timeline) {

                swal(alertConfig,function(){
                	swal.close();
                });
                return false;
            }

            if (hasimage && !gif) {
            	swal("请上传封面图!", "请上传封面图!", "error");
            	return false;
            }

            formJQ.submit();

        }

    });
</script>
</body>
</html>
