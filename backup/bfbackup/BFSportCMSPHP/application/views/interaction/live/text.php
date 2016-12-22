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
                            <h3 class="box-title pull-left" style="line-height: 200%;">已发布的图文消息</h3>
                            <div class="text-warning pull-right">
                             
                            </div>
                        </div>
                        <div class="box-body">
                            
                            <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>图文</th>
                                            <th>发布时间</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($list as $k=>$v) { 
                                                if (!$v['data']['payload']['type']) { 
                                            ?>
                                        <tr>
                                            <td><?=$v['data']['payload']['text'] ?>
                                                <br/>
                                                <?php if ($v['data']['payload']['image']) {?>
                                            <img alt="" style="width: 100px;" src="<?=$v['data']['payload']['image'] ?>">
                                            <?php }?>
                                            </td>
                                            
                                            <td><?php if (isset($v['data']['payload']['date'])) { echo $v['data']['payload']['date']; }?></td>
                                        </tr>
                                        <?php } } ?>
  
                                                                                                                    </tbody>
                                </table>
                            
                        </div>
                    </div>
                </div>
            </div>
            
            <a href="#text_board" name="text_board"></a>
            <?php if ($match['type'] == 'team'):?>
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <table class="table table-hover">
                            <tr>
                                <td>
                                    <h3 class="box-title" style="line-height: 200%;"><?= $match['team_info']['team1_info']['name']?></h3>
                                </td>
                                <td>
                                    <h3 class="box-title" style="line-height: 200%;">
                                    <input type="text" value="<?= $match['team_info']['team1_score'] ?>" maxlength="4" style="width:65px;height:50px;font-size:40px" id="team1_score_input" /> VS <input type="text" value="<?= $match['team_info']['team2_score'] ?>" maxlength="4" style="width:65px;height:50px;font-size:40px" id="team2_score_input" />
                                    </h3>
                                </td>
                                <td>
                                    <h3 class="box-title" style="line-height: 200%;"><?= $match['team_info']['team2_info']['name']?></h3>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <button class="btn bth-xs btn-primary add-score" type="button" data-pk="1" data-tp="team1">+1</button>
                                    <button class="btn bth-xs btn-primary add-score" type="button" data-pk="2" data-tp="team1">+2</button>
                                    <button class="btn bth-xs btn-primary add-score" type="button" data-pk="3" data-tp="team1">+3</button>
                                    <button class="btn bth-xs btn-primary add-score" type="button" data-pk="-1" data-tp="team1">-1</button>
                                </td>
                                <td>
                                    <button id="update-score" class="btn bth-xs btn-success" type="button">提交</button>
                                    <button id="refresh-score" class="btn bth-xs btn-info" type="button">刷新</button>
                                </td>
                                <td>
                                    <button class="btn bth-xs btn-primary add-score" type="button" data-pk="1" data-tp="team2">+1</button>
                                    <button class="btn bth-xs btn-primary add-score" type="button" data-pk="2" data-tp="team2">+2</button>
                                    <button class="btn bth-xs btn-primary add-score" type="button" data-pk="3" data-tp="team2">+3</button>
                                    <button class="btn bth-xs btn-primary add-score" type="button" data-pk="-1" data-tp="team2">-1</button>
                                </td>
                            </tr>
                            
                        </table>
                    </div>
                </div>
            </div>
            <?php endif;?>
            <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title pull-left" style="line-height: 200%;">发布图文</h3>
                        </div>
                        <div class="box-body">
                            <form class="form-horizontal" method="post" id="js_text_form" action="<?=base_url('/interaction/live/text_live/save') ?>">
                                
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="">图片</label>
                                    <div class="col-sm-8">
                                        <div id="image-view" style="padding: 5px 0;"><img style="width:100%; display:none;" src=""></div>
                                        <span class="btn btn-warning fileinput-button">
                                            <i class="glyphicon glyphicon-plus"></i>
                                            <span>上传图片</span>
                                             
                                            <input id="fileupload" type="file" name="image" multiple data-url="<?=IMG_UPLOAD?>" />
                                        </span>
                                        <input id="poster" type="hidden" name="poster" value="" />
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="site">文字</label>
                                    <div class="col-sm-8">
                                        <textarea id="text-content" autofocus class="form-control js_text" name="content" rows="3" placeholder="请输入信息"></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                

                                <div class="form-group text-center">
                                    <input type="hidden" name="match_id" value="<?=$matchId ?>"/>
                                    <input type="hidden" name="host" value="<?=$host ?>"/>
                                    <button id="add-btn" class="btn btn-success" type="button">发送</button>
                                </div>
                            </form>
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
                $('#add-btn').click();
                return false;
            }
        });
    })
    var formJQ = $('#js_text_form');
    //添加表单验证，blur表示input失去焦点就验证
    formJQ.bValidator({validateOn: 'blur'});

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
                alert('上传失败,请重试！');
            }
            else{
                $("#image-view").html('<img style="width:100%;" src="http://image.sports.baofeng.com/' + data.result.data.pid + '">').show();
                $('#poster').val(data.result.data.pid);
            }
        }
    });
    
    
    //接管提交按钮
    $('#add-btn').click(function () {

        var v = formJQ.data("bValidators").bvalidator.isValid();

        if (v == true) {
            var text = $('.js_text').val();
            if (!text.length) {
                swal = window.parent.swal ? window.parent.swal :swal;
            	swal("请填写文字!", "请填写文字!", "error");
                return false;
            }
        	formJQ.submit();
        	$(this).attr('disabled',true);
        }

    });

    // 比分板相关逻辑
    $('.add-score').on('click', function(e) {
        var teamPa = $(this).data('tp');
        var addScore  = $(this).data('pk');
        $.ajax({
            method: "POST",
            url: "<?php echo site_url('/interaction/live/main_live/addScore/').$match['id'] ?>",
            data: {team_pa: teamPa, score: addScore}
        }).done(function (data) {
            var rtn=JSON.parse(data);
            team1_score = rtn.team_info.team1_score;
            team2_score = rtn.team_info.team2_score;
            $('#team1_score_input').val(team1_score);
            $('#team2_score_input').val(team2_score);
        })
    }
    )
    $('#update-score').on('click', function(e) {
        var team1_score_val = $('#team1_score_input').val();
        var team2_score_val = $('#team2_score_input').val();
        $.ajax({
            method: "POST",
            url: "<?php echo site_url('/interaction/live/main_live/updateScore/').$match['id'] ?>",
            data: {team1_score: team1_score_val, team2_score: team2_score_val}
        }).done(function (data) {
            alert('提交成功');
        })
    });
    $('#refresh-score').on('click', function (e) {
        $.ajax({
            method: "POST",
            url: "<?php echo site_url('/interaction/live/main_live/refreshScore/').$match['id'] ?>"
        }).done(function (data) {
            var rtn=JSON.parse(data);
            team1_score = rtn.team_info.team1_score;
            team2_score = rtn.team_info.team2_score;
            $('#team1_score_input').val(team1_score);
            $('#team2_score_input').val(team2_score);
        });
    })
</script>
</body>
</html>
