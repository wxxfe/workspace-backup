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

        </section>
        <section class="content">
       
          <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title pull-left" style="line-height: 200%;">发布战报</h3>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal" method="post" id="js_text_form" action="<?=base_url('/interaction/live/report_live/saveinfo') ?>">
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="">时间线</label>
                                    <input type="text" value="" name="timeline" class="js_timeline" />&nbsp;&nbsp;分钟
                                    
                                    <label class="control-label col-sm-2" for="" style="float:none;">综述</label>
                                    <input type="checkbox" value="1" name="sumup" class="js_sumup" />
                                    
                                </div>
                                
                                
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="">图片</label>
                                    <div class="col-sm-8">
                                        <div id="image-view" style="padding: 5px 0;"><img style="width:100%; display:none;" src=""></div>
                                        <span class="btn btn-warning fileinput-button">
                                            <i class="glyphicon glyphicon-plus"></i>
                                            <span>上传图片</span>
                                             
                                            <input id="fileupload" type="file" name="image" data-url="<?=IMG_UPLOAD?>" />
                                        </span>
                                        <input id="poster" type="hidden" name="poster" value="" />
                                    </div>
                                </div>
                                
                                <div class="form-group js_fm"  style="display:none;">
                                    <label class="control-label col-sm-2" for="">封面图</label>
                                    <div class="col-sm-8">
                                        <div id="image-view1" style="padding: 5px 0;"><img style="width:100%; display:none;" src=""></div>
                                        <span class="btn btn-warning fileinput-button">
                                            <i class="glyphicon glyphicon-plus"></i>
                                            <span>上传封面图</span>
                                             
                                            <input id="fileupload1" type="file" name="image" data-url="<?=IMG_UPLOAD?>" />
                                        </span>
                                        <input id="poster1" type="hidden" name="poster1" value="" />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="site">文字</label>
                                    <div class="col-sm-8">
                                        <textarea id="text-content" class="form-control js_content" name="content" rows="3" placeholder="请输入信息"></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                

                                <div class="form-group text-center">
                                    <input type="hidden" name="host"  value="<?=$host ?>"/>
                                    <input type="hidden" name="size" class="js_size" value="0"/>
                                    <input type="hidden" name="match_id" class="js_match_id" value="<?=$matchId ?>"/>
                                    <button id="js_send" class="btn btn-success">发送</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title pull-left" style="line-height: 200%;">已发布的图文消息</h3>
                            <!--  div class="text-warning pull-right">
                                <a href=""
                                   id="refresh-btn" role="button" class="btn btn-success btn-flat pull-left"
                                   style="margin-left: 10px;">刷新</a>
                            </div-->
                        </div>
                        <div class="box-body">
                            
                            <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>已发战报消息</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                        <?php foreach ($reportlist as $report) {?>
                                        <tr>
                                            <td><span class="report_clcle"><?php if (-1 == $report['report_tm']) { echo '综述'; } else { echo $report['report_tm'].'’'; }?></span><?=$report['content'] ?>
                                                <br/>
                                                <?php if ($report['image']) { ?>
                                                    <img alt="" style="width: 100px;margin-left: 50px;" src="<?php $img = $report['gif'] ? $report['gif'] : $report['image']; echo getImageUrl($img) ?>">
                                                <?php } ?>
                                            </td>
                                            
                                            <td><a href="<?=base_url('/interaction/live/report_live/edit/'.$report['id'].'/'.$matchId.'/'.$host) ?>" role="button" class="btn btn-flat btn-warning btn-xs"><i class="fa fa-edit"></i> 编辑</a>
                                            &nbsp;&nbsp;
                                                                                        关键战报 <input type="checkbox" name="important" class="js_important" value="1" data-id="<?=$report['id'] ?>" <?php if ($report['visible']) { echo 'checked'; } ?>></td>
                                        </tr>
                                        <?php } ?>
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
    $(function($) {
        //直接回复：ctrl + enter 提交
        $("#text-content").unbind('keydown').bind("keydown", function(event){
            var event = event ? event : window.event ;
            if( event.keyCode == 13 && event.ctrlKey){
                $('#js_send').click();
                return false;
            }
        });
    })
    var formJQ = $('#js_text_form'),
    imgsize    = 0, hasimage = 0;
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
    $('#js_send').click(function () {

        var v = formJQ.data("bValidators").bvalidator.isValid();

        if (v == true) {

            var id = parseInt($('.js_match_id').val()),
            timeline = parseInt($('.js_timeline').val()),
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
        	
            if (!id || !content || !sumup && !timeline) {

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
            $('#js_send').attr('disabled',true);
            //console.log(id, timeline, sumup, image, content);exit;

            /*$.post("</?=base_url('/interaction/live/report_live/saveinfo') ?>", { 'id':id, 'timeline':timeline, 'sumup':sumup, 'image':image, 'content':content, 'gif':gif }, function(json){
                console.log(json);exit;
                /if (!json.status) {
                    window.location.reload();
                    return false;
                }
            }, 'json').error(function(e){ console.log(e);});*/

        }

    });

    //重要战报
    $('.js_important').click(function(){
        var id = parseInt($(this).data('id'));
        if (!id) {
            alert('数据有误，刷新后重试');
            return false;
        }

        $.post("<?=base_url('/interaction/live/report_live/setimportant') ?>", { 'id':id }, function(json){
            //console.log(json);
            if (json.status) {
                alert('设置失败');
                return false;
            }
        },'json');
    });
</script>
</body>
</html>
