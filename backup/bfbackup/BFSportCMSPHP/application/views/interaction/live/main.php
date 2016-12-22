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
              <h1>直播<small>Live</small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">直播</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">
                                    <span style="padding-right:10px; font-size: 14px;">赛事：<strong class="text-info"><?=$match['brief']?></strong></span>
                                    <span style="padding-right:10px; font-size: 14px;">比赛：<strong class="text-info"><?=$match_vs?></strong></span>
                                    <span style="padding-right:10px; font-size: 14px;">比赛时间：<strong class="text-info"><?=$match['start_tm']?></strong></span>
                                    <span style="padding-right:10px; font-size: 14px;">状态：<strong class="text-info">
                                    <a>
                                    <span class="match-status"
                                        data-type="select" data-value="<?=$match['status']?>" data-pk="<?=$match['id']?>"
                                        data-name="status" data-url="<?=site_url('match/setField/match/status')?>"
                                    >
                                        <?=$status[$match['status']]?>
                                    </span></a>
                                    </strong></span>
                                    <span style="padding-right:10px; font-size: 14px;">比赛ID：<strong class="text-info"><?=$match['id']?></strong></span>
                                </h3>
                                <!-- 
                                <h4>
                                    <span style="padding-right:10px; font-size: 14px;">比赛比分：
                                        <button class="btn bth-xs btn-primary add-score" type="button" data-pk="1" data-tp="team1">+1</button>
                                        <button class="btn bth-xs btn-primary add-score" type="button" data-pk="2" data-tp="team1">+2</button>
                                        <button class="btn bth-xs btn-primary add-score" type="button" data-pk="3" data-tp="team1">+3</button>
                                        <button class="btn bth-xs btn-primary add-score" type="button" data-pk="-1" data-tp="team1">-1</button>
                                        <input type="text" value="<?= $match['team_info']['team1_score'] ?>" maxlength="4" style="width:65px;height:50px;font-size:40px" id="team1_score_input" />:
                                        <input type="text" value="<?= $match['team_info']['team2_score'] ?>" maxlength="4" style="width:65px;height:50px;font-size:40px" id="team2_score_input" />
                                        <button class="btn bth-xs btn-primary add-score" type="button" data-pk="1" data-tp="team2">+1</button>
                                        <button class="btn bth-xs btn-primary add-score" type="button" data-pk="2" data-tp="team2">+2</button>
                                        <button class="btn bth-xs btn-primary add-score" type="button" data-pk="3" data-tp="team2">+3</button>
                                        <button class="btn bth-xs btn-primary add-score" type="button" data-pk="-1" data-tp="team2">-1</button>
                                        <button id="update-score" class="btn bth-xs btn-success" type="button">提交</button>
                                        <button id="refresh-score" class="btn bth-xs btn-info" type="button">刷新</button>
                                    </span>
                                </h4>
                                -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box" style="border: none;">
                            <div class="box-header" style="padding: 0px 10px;">
                                <div class="nav-tabs-custom" style="box-shadow: none; margin-bottom: 0;">
                                    <ul class="nav nav-tabs nav-justified">
                                        <li class="active"><a href="<?=base_url('/interaction/live/text_live/index/'.$matchId.'/'.$host.'/#text_board')?>" target="inter">图文</a></li>
                                        <li><a href="<?=base_url('/interaction/live/report_live/index/'.$matchId.'/'.$host)?>" target="inter">战报</a></li>
                                        <li><a href="<?=base_url('/interaction/live/event_live/index/'.$matchId.'/'.$host)?>" target="inter">互动事件</a></li>
                                        <li><a href="<?=base_url('/interaction/live/quiz_live/index/'.$matchId.'/'.$host)?>" target="inter">竞猜</a></li>
                                        <li><a href="<?=base_url('/interaction/live/thread_live/index/'.$matchId.'/'.$host)?>" target="inter">话题</a></li>
                                        <li><a href="<?=base_url('/interaction/live/news_live/index/'.$matchId.'/'.$host)?>" target="inter">新闻资讯</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="box-body">
                                <iframe id="inter" src="<?=base_url('/interaction/live/text_live/index/'.$matchId.'/'.$host.'/#text_board')?>" name="inter" width="100%" height="100%" frameborder="0"></iframe>
                                <script>
                                    var view = document.getElementById('inter');
                                    var wh = document.documentElement.clientHeight - 200;
                                    view.height = wh;
                                </script>
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
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script src="/static/dist/js/bvalidator.js"></script>
<script>
var match_statuses = <?=json_encode($status)?>;

$(".match-status").editable({
    source: match_statuses
});

$('.nav-tabs').on('click','li',function(){
    $(this).siblings().removeClass();
    $(this).addClass('active');
});
function sweetalert(config,callback) {
    swal(config,callback);
}
//就地编辑
$('.editable').editable({
    emptytext: '点这编辑',
    validate: function (value) {
        //验证是否为空，是空则提示
        if (!bValidator.validators.required(value)) {
            return bValidator.defaultOptions.messages.zh.required;
        }
        var tJQ = $(this);
        //验证是否超出最大字符数,超出则提示
        var maxlen = Number(tJQ.data('validate-maxlen'));
        if (maxlen && !bValidator.validators.maxlen(value, maxlen)) {
            return bValidator.defaultOptions.messages.zh.maxlen.replace('{0}', maxlen);
        }
    }
});
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

        // window.parent.document.getElementById('frmright').src=window.parent.document.getElementById('frmrightsrc').value;
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
