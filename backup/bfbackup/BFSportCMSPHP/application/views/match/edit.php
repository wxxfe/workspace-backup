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
    <link rel="stylesheet" href="/static/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="/static/dist/css/bootstrap-switch.min.css">
    <link rel="stylesheet" href="/static/plugins/select2/select2.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        .avatar{width: 50px; height: 50px; border-radius: 50%;}
        .table>tbody>tr>td{vertical-align: middle;}
        .date-wrap{width: 930px; margin: 20px auto; overflow: hidden;}
        .prev,.next{display: block; width: 20px; height: 90px; border: #ccc 1px solid; float: left; text-align: center; line-height: 90px;}
        .prev{margin-right: 10px;}
        .slide-wrap{width: 880px; overflow: hidden; position: relative; height: 90px; float: left; margin-top: 5px;}
        .matchs{overflow: hidden; position: absolute; list-style: none; height: 90px; left: 0; top: 0; width: 1000px; margin: 0; padding: 0;}
        .matchs li{display: block; width: 880px; height: 92px; float: left;}
        .matchs li a{display: block; width: 100px; margin-right: 10px; float: left; border: #ccc 1px solid;}
        .matchs li a{text-align: center; padding-top: 8px; height: 80px;}
        .matchs li a.active{background: #3c8dbc; color: #fff;}
    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
<div class="wrapper">
    <div class="content-wrapper" style="margin-left: 0;">
        <section class="content-header">
            <h1><a href="<?=site_url('match')?>">直播管理</a> &gt; 
            <?php if ($match): ?>
                更新直播<small>Update Match </small>
            <?php else: ?>
                添加直播<small>Create Match </small>
            <?php endif; ?>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><?=($match? '更新直播' : '添加直播')?></li>
            </ol>
        </section>
        <section class="content">
            <iframe name="match_id_iframe" id="match_id_iframe" style="display:none;"></iframe>
            <div class="row">
                <div class="col-md-12">
                    <div class="box" id="match-info-box">
                        <div class="box-header with-border">
                          <h3 class="box-title">比赛信息</h3>
                        </div>
                        <div class="box-body" style="padding-top: 20px;">
                            <form class="form-horizontal" method="post" id="match-form" target="match_id_iframe">
                                <input type="hidden" name="match_id" id="match_id" value="<?=$match_id?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="start_tm">比赛开始时间</label>
                                            <div class="col-sm-5">
                                                <div class="input-append date datetimepicker">
                                                    <input value="<?=(@$match['start_tm'] ?: date('Y-m-d H:i:s'))?>" data-format="yyyy-MM-dd hh:mm:ss" class="form-control" style="display: inline-block; width: 75%;" id="start_tm" name="start_tm" placeholder="请选择直播开始时间" type="text" readonly="readonly">
                                                    <span class="add-on" style="display: inline-block; margin-left: 5px;">
                                                        <i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="live_type">直播方式</label>
                                            <div class="col-sm-4">
                                                <select class="form-control" name="live_type" id="live_type" required>
                                                    <?php foreach($live_types as $live_type => $live_name): ?>
                                                    <option value="<?=$live_type?>" <?=(@$match['live_type']==$live_type ? 'selected' : '')?>>
                                                        <?=$live_name ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="status">比赛状态</label>
                                            <div class="col-sm-4">
                                                <select class="form-control" name="status" id="status" required>
                                                    <?php foreach($match_statuses as $status => $status_name): ?>
                                                    <option value="<?=$status?>" <?=(@$match['status']==$status ? 'selected' : '')?>>
                                                        <?=$status_name ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="event_id">所属赛事</label>
                                            <div class="col-sm-4">
                                                <select class="form-control" name="event_id" id="event_id" required data-bvalidator-msg="请选择所属赛事！">
                                                    <option value="">请选择</option>
                                                    <?php foreach($events as $event): ?>
                                                    <option value="<?=$event['id']?>" <?=(@$match['event_id']==$event['id'] ? 'selected' : '')?>>
                                                        <?=$event['name']?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="title">比赛标题</label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="title" id="title" value="<?=@htmlspecialchars($match['title'])?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="stage">阶段</label>
                                            <div class="col-sm-4">
                                                <input class="form-control" name="stage" id="stage" value="<?=@htmlspecialchars($match['stage'])?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="highlight">前瞻描述</label>
                                            <div class="col-sm-4">
                                                <textarea class="form-control" name="highlight" id="highlight" rows="3" placeholder="请输入前瞻描述"><?=@$match['extra']['highlight']?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="brief">比赛描述</label>
                                            <div class="col-sm-4">
                                                <textarea class="form-control" name="brief" id="brief" rows="3" placeholder="请输入比赛描述"><?=@$match['brief']?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="brief2">比赛看点</label>
                                            <div class="col-sm-4">
                                                <textarea class="form-control" name="brief2" id="brief2" rows="3" placeholder="请输入比赛看点"><?=@$match['brief2']?></textarea>
                                            </div>
                                        </div>
                                        <input type="hidden" name="extra_id" value="<?=@$match['extra']['id']?>">
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="has_host">主持人</label>
                                            <div class="col-sm-4">
                                                <input type="checkbox" name="has_host" id="has_host" <?=(@$match['extra']['has_host']? 'checked' : '')?>>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="stats_url">统计URL</label>
                                            <div class="col-sm-8">
                                                <input class="form-control" name="stats_url" id="stats_url" value="<?=@$match['extra']['stats_url']?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="">海报图</label>
                                            <div class="col-sm-8">
                                                <span class="btn btn-warning fileinput-button">
                                                    <i class="glyphicon glyphicon-plus"></i>
                                                    <span>选择图片</span>
                                                    <input id="fileupload-bgimage" type="file" name="image" multiple data-url="<?=IMG_UPLOAD?>" />
                                                </span>&nbsp;&nbsp;&nbsp;&nbsp;<b>建议尺寸750*600</b>
                                                <input type="hidden" name="bgimage" id="bgimage" value="<?=@$match['extra']['bgimage']?>">
                                                <div id="bgimage-view" style="padding: 10px 0;">
                                                <?php if (!@empty($match['extra']['bgimage'])): ?><img src="<?=getImageUrl($match['extra']['bgimage'])?>" style=" max-width: 99%;" alt=""><?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="type">比赛模式</label>
                                            <div class="col-sm-4">
                                                <select class="form-control" name="type" id="type" required data-bvalidator-msg="请选择比赛模式！" <?=($match? 'readonly' : '')?>>
                                                    <!-- 已有的比赛不能修改模式 -->
                                                    <?php if (!$match): ?><option value="">请选择</option><?php endif; ?>
                                                    <?php foreach($match_types as $type => $name): ?>
                                                    <?php if ($match && $match['type'] != $type) continue; ?>
                                                    <option value="<?=$type?>"><?=$name?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <?php if (isset($match['team_info']['id'])): ?>
                                            <input type="hidden" name="extra_info_id" value="<?=$match['team_info']['id']?>">
                                        <?php endif; ?>
                                        <?php if (isset($match['player_info']['id'])): ?>
                                            <input type="hidden" name="extra_info_id" value="<?=$match['player_info']['id']?>">
                                        <?php endif; ?>
                                        <?php if (isset($match['various_info']['id'])): ?>
                                            <input type="hidden" name="extra_info_id" value="<?=$match['various_info']['id']?>">
                                        <?php endif; ?>
                                        <!-- 团队对阵 -->
                                        <span id="team_info">
                                            <!-- team 1 -->
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="team1_id">主队</label>
                                                <div class="col-sm-4">
                                                    <!-- <input class="form-control" name="team1_id" id="team1_id" value="<?=@$match['team_info']['team1_id']?>" required data-bvalidator-msg="请选择主队"> -->
                                                    <select class="form-control" name="team1_id" id="team1_id" required data-bvalidator-msg="请选择主队">
                                                        <option value="">请选择</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="team1_score">主队得分</label>
                                                <div class="col-sm-4">
                                                    <input class="form-control" name="team1_score" id="team1_score" value="<?=@$match['team_info']['team1_score'] ?: 0?>">
                                                </div>
                                            </div>
                                            <!-- team 2 -->
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="team2_id">客队</label>
                                                <div class="col-sm-4">
                                                    <!-- <input class="form-control" name="team2_id" id="team2_id" required data-bvalidator-msg="请选择客队" value="<?=@$match['team_info']['team2_id']?>"> -->
                                                    <select class="form-control" name="team2_id" id="team2_id" required data-bvalidator-msg="请选择主队">
                                                        <option value="">请选择</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="team2_score">客队得分</label>
                                                <div class="col-sm-4">
                                                    <input class="form-control" name="team2_score" id="team2_score" value="<?=@$match['team_info']['team2_score'] ?: 0?>">
                                                </div>
                                            </div>
                                        </span>
                                        <!-- 个人对阵 -->
                                        <span id="player_info">
                                            <!-- player 1 -->
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="player1_id">人员A信息</label>
                                                <div class="col-sm-4">
                                                    <!-- <input class="form-control" name="player1_id" id="player1_id" value="<?=@$match['player_info']['player1_id']?>" required data-bvalidator-msg="请选择人员A"> -->
                                                    <select class="form-control select-player" name="player1_id" id="player1_id" required data-bvalidator-msg="请选择人员A">
                                                    <?php if (isset($match['player_info']['player1_id'])): ?>
                                                        <option value="<?=$match['player_info']['player1_id']?>">
                                                        <?="{$match['player_info']['player1_info']['name']} （{$match['player_info']['player1_info']['nationality']}）"?>
                                                        </option>
                                                    <?php endif; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="player1_score">人员A得分</label>
                                                <div class="col-sm-4">
                                                    <input class="form-control" name="player1_score" id="player1_score" value="<?=@$match['player_info']['player1_score'] ?: 0?>">
                                                </div>
                                            </div>
                                            <!-- player 2 -->
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="player2_id">人员B信息</label>
                                                <div class="col-sm-4">
                                                    <!-- <input class="form-control" name="player2_id" id="player2_id" value="<?=@$match['player_info']['player2_id']?>" required data-bvalidator-msg="请选择人员B"> -->
                                                    <select class="form-control select-player" name="player2_id" id="player2_id" required data-bvalidator-msg="请选择人员B">
                                                    <?php if (isset($match['player_info']['player2_id'])): ?>
                                                        <option value="<?=$match['player_info']['player2_id']?>">
                                                        <?="{$match['player_info']['player2_info']['name']} （{$match['player_info']['player2_info']['nationality']}）"?>
                                                        </option>
                                                    <?php endif; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="player2_score">人员B得分</label>
                                                <div class="col-sm-4">
                                                    <input class="form-control" name="player2_score" id="player2_score" value="<?=@$match['player_info']['player2_score'] ?: 0?>">
                                                </div>
                                            </div>
                                        </span>
                                        <!-- 非对阵 -->
                                        <span id="various_info">
                                        
                                        <?php foreach (array('1' => '1st', '2' => '2nd', '3' => '3rd') as $_rankid => $_rank): ?>
                                            <?php $_name = "name_{$_rank}"; $_image = "image_{$_rank}"; ?>
                                            <div class="form-group">
                                                <label class="control-label col-sm-2" for="">第 <?=$_rankid ?>
                                                    <span class="btn btn-xs btn-warning fileinput-button">
                                                        <i class="fa fa-plus"></i><span>头像</span>
                                                        <input class="fileupload-various" type="file" name="image" data-url="<?=IMG_UPLOAD?>" />
                                                    </span>
                                                    <input type="hidden" name="various_<?=$_image?>" class="various_image" value="<?=@$match['various_info'][$_image]?>">
                                                    <div class="image-view" style="padding: 10px 0;">
                                                    <?php if (!@empty($match['various_info'][$_image])): ?><img src="<?=getImageUrl($match['various_info'][$_image])?>" style="max-width: 99%;" alt=""><?php endif; ?>
                                                    </div>
                                                </label>
                                                <div class="col-sm-4">
                                                    <select class="form-control select-various" name="various_<?=$_name?>" id="various_<?=$_name?>">
                                                        <option selected><?=@strval($match['various_info'][$_name]) ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-10 text-center">
                                        <button class="btn btn-success btn-md" type="submit">提交</button>
                                        <button class="btn btn-info btn-md goback" type="button">返回</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- forecast -->
            <div class="box" id="forecast-box" style="border: none;">
                <div class="box-header with-border">
                  <h3 class="box-title">看点</h3>
                </div>
                <div class="box-body" style="padding-top: 20px;">
                    <?php $forecasts = isset($match['forecast'])? array_values($match['forecast']) : array(); ?>
                    <?php for ($i = 0; $i < 2; $i++): ?>
                    <div class="row" style="margin-bottom: 10px;">
                        <?php $f = isset($forecasts[$i])? $forecasts[$i] : array(); ?>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-1" style="text-align:right">看点<?=($i+1)?>：</label>
                                <div class="col-md-2">
                                    <input class="form-control forecast_title" type="text" value="<?=@$f['title']?>" placeholder="看点标题">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control forecast_type">
                                        <option value="">选择类型</option>
                                        <?php foreach ($forecast_types as $ftype => $fname): ?>
                                        <option value="<?=$ftype?>" <?=($ftype==@$f['type']? 'selected' : '')?>><?=$fname?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <label class="control-label col-md-1" style="text-align:right">ID/URL：</label>
                                <div class="col-md-4">
                                    <input class="form-control forecast_data" value="<?=(@in_array($f['type'], array('news', 'thread')) ? $f['ref_id'] : (@$f['type']=='html'? $f['data'] : ''))?>" placeholder="新闻ID/URL地址/话题ID">
                                </div>
                                <div class="col-md-2 <?=(@empty($f)? 'hidden' : '')?>">
                                    <input type="hidden" class="forecast_id" value="<?=@$f['id']?>">
                                    <span role="button" class="btn btn-xs btn-warning update-forecast">
                                        <i class="fa fa-pencil-square-o"></i>修改
                                    </span>
                                    <span role="button" class="btn btn-xs btn-danger delete-forecast">
                                        <i class="fa fa-times"></i>删除
                                    </span>
                                    <?php if (count($forecasts) > 1): ?>
                                    <span role="button" class="btn btn-xs bg-olive sort-forecast" data-sort="<?=($i+1)?>">
                                        <?php if ($i > 0): ?>
                                        <i class="fa fa-arrow-up" data-opt="up"></i> 上移
                                        <?php else: ?>
                                        <i class="fa fa-arrow-down" data-opt="down"></i> 下移
                                        <?php endif; ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-2 <?=(!@empty($f)? 'hidden' : '')?>">
                                    <span role="button" class="btn btn-xs btn-info add-forecast">
                                        <i class="fa fa-plus"></i>保存
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <!-- live -->
            <div class="box" id="live-box" style="border: none;">
                <div class="box-header with-border">
                  <h3 class="box-title">直播流</h3>
                </div>
                <div class="box-body" style="padding-top: 20px;">
                    <?php $lives = isset($match['live'])? array_values($match['live']) : array(); ?>
                    <?php for ($i = 0; $i < 3; $i++): ?>
                    <div class="row-<?=$i?>">
                        <div class="row" style="margin-bottom: 10px;">
                            <?php $live = isset($lives[$i])? $lives[$i] : array(); ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-1" style="text-align:right;">直播源：</label>
                                    <div class="col-md-3">
                                        <select class="form-control live_site">
                                            <option value="">选择直播源</option>
                                            <?php foreach ($live_sites as $site => $name): ?>
                                            <option value="<?=$site?>" <?=($site==@$live['site']? 'selected' : '')?>><?=$name?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <label class="control-label col-md-2" for="stream_tm" style="text-align:right;">切流时间：</label>
                                    <div class="col-md-4 input-append date datetimepicker">
                                        <input class="form-control live_stream_tm" value="<?=(@$live['stream_tm'] ?: '')?>" data-format="yyyy-MM-dd hh:mm:ss" style="display: inline-block; width: 50%;" placeholder="" type="text" readonly="readonly">
                                        <span class="add-on" style="display: inline-block; margin-left: 5px;">
                                            <i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                    <label class="control-label col-md-1" style="text-align:center;">VR模式：</label>
                                    <div class="col-md-1">
                                        <input class="live_vr" type="checkbox" <?=(@$live['isvr']? 'checked' : '')?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <!-- 
                                    <label class="control-label col-md-1" style="text-align:right;">直播地址：</label>
                                    <div class="col-md-2">
                                        <input class="form-control live_play_url" value="<?=@$live['play_url']?>">
                                    </div>
                                    -->
                                    <label class="control-label col-md-1" style="text-align:right;">云直播码：</label>
                                    <div class="col-md-2">
                                        <input class="form-control live_play_code2" value="<?=@$live['play_code2']?>">
                                    </div>
                                    <label class="control-label col-md-2" style="text-align:right;">直播代码：</label>
                                    <div class="col-md-3">
                                        <input class="form-control live_play_code" value="<?=@$live['play_code']?>">
                                    </div>
                                    <input type="hidden" class="row_num" value="<?=$i?>">
                                    <div class="col-md-3 <?=(@empty($live)? 'hidden' : '')?>" style="text-align:center;">
                                        <input type="hidden" class="live_id" value="<?=@$live['id']?>">
                                        <span role="button" class="btn btn-sm btn-warning update-live">
                                            <i class="fa fa-pencil-square-o"></i>修改
                                        </span>
                                        <span role="button" class="btn btn-sm btn-danger delete-live">
                                            <i class="fa fa-times"></i>删除
                                        </span>
                                    </div>
                                    <div class="col-md-3 <?=(!@empty($live)? 'hidden' : '')?>" style="text-align:center;">
                                        <span role="button" class="btn btn-sm btn-info add-live">
                                            <i class="fa fa-plus"></i>保存
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php endfor; ?>
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
<script src="/static/dist/js/slider.js"></script>
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script src="/static/plugins/sweetalert-master/dist/sweetalert.min.js"></script>
<script src="/static/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="/static/dist/js/bootstrap-switch.min.js"></script>
<script src="/static/plugins/select2/select2.full.min.js"></script>
<script>
$(".datetimepicker").datetimepicker();

$("#has_host").bootstrapSwitch({
    size: 'mini',
    onText: '有',
    offText: '无',
    onSwitchChange: function (event, state) {
        $("#has_host").attr("checked", parseInt(Number(state)));
    }
});

$('#fileupload-bgimage').fileupload({
    add: function (e, data) {
        data.submit();
    },
    done: function (e, data) {
        var result = data.result.errno;

        if (result !== 10000) {
            alert('上传失败,请重试！');
        } else {
            $("#bgimage-view").html('<img src="http://image.sports.baofeng.com/' + data.result.data.pid + '" style="max-width:99%;">').show();
            $('#bgimage').val(data.result.data.pid);
        }
    }
});

$(".select-player").width("250px");
$(".select-player").select2({
    ajax: {
        url: "<?=site_url('/search/query/tag')?>",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                keyword : params.term,
                type : 'player',
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
    templateResult : function(data) {
        if (data.id) {
            var photo = "http://image.sports.baofeng.com/" + data.photo;
            return '<img style="max-width:27%;" src="' + photo + '"> ' + data.name + ' （' + (data.nationality || '未知') + '）';
        }
    },
    templateSelection : function(data) {
        if (data.id) {
            var text = data.text;
            if ("name" in data) {
                text = data.name + ' （' + (data.nationality || '未知') + '）';
            }
            $(data.element.parentElement).html('<option value="' + data.id + '" selected>' + text + '</option>');
            return '<span>' + text + '</span>';
        }
    }
});

$(".fileupload-various").fileupload({
    add: function (e, data) {
        data.submit();
    },
    done: function (e, data) {
        var result = data.result.errno;
        
        if (result !== 10000) {
            alert('上传失败,请重试！');
        } else {
            $(e.target).parent().siblings(".various_image").val(data.result.data.pid);
            $(e.target).parent().siblings(".image-view").html('<img style="max-width:100%;" src="http://image.sports.baofeng.com/' + data.result.data.pid + '">').show();
        }
    }
});

$(".select-various").select2({
    ajax: {
        url: "<?=site_url('/search/query/tag')?>",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                keyword : params.term,
                type : 'player',
                page : params.page
            }
        },
        processResults: function (data, params) {
            data.total = data.result.unshift({id: params.term, name: params.term})
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
    templateResult : function(data) {
        if (data.id) {
            if (isNaN(data.id)) {
                return data.name;
            } else {
                var photo = "http://image.sports.baofeng.com/" + data.photo;
                return '<img style="max-width:27%;" src="' + photo + '"> ' + data.name + ' （' + (data.nationality || '未知') + '）';
            }
        }
    },
    templateSelection : function(data) {
        var select_id = (new RegExp("various_name_[^-]+")).exec(data._resultId);
        
        if (select_id && data.id) {
            var text = data.text;
            if ("name" in data) {
                text = data.name;
            }
            $("#"+select_id).html('<option value="' + text + '" selected>' + text + '</option>');
            
            if ("photo" in data) {
                var photo = "http://image.sports.baofeng.com/" + data.photo;
                $("#"+select_id).parent().siblings().find(".various_image").val(data.photo);
                $("#"+select_id).parent().siblings().find(".image-view").html('<img style="max-width:99%;" src="' + photo + '">');
            } else if (!("title" in data)) {
                $("#"+select_id).parent().siblings().find(".various_image").val("");
                $("#"+select_id).parent().siblings().find(".image-view").html("");
            }
            
            return '<span>' + text + '</span>';
        }
    }
});

$("#match_id_iframe").load(function() {
    var match_id = parseInt($(this).contents().text());
    if (match_id > 0) {
        $("#match_id").val(match_id);
        swal('提交成功!','','success');
    } else {
        swal('提交失败!','请重试！','error');
    }
});

function setEventTeams(event_id) {
    if (!event_id) {
        return false;
    }
    
    $.get(
        "<?=site_url('api/getPredefineTags/team')?>/" + event_id,
        function(data) {
            var team_html = '<option value="">请选择</option>';
            
            data = JSON.parse(data);
            for (var i in data) {
                var item = data[i];
                if (item['type'] == 'team') {
                    for (var j in item['data']) {
                        var row = item['data'][j];
                        team_html += '<option value="' + row['id'] + '">' + row['name'] + '</option>';
                    }
                }
            }
            
            var match = <?=!@empty($match)? json_encode($match) : '{}'?>;
            var team1_id = team2_id = "";
            
            try {
                team1_id = match.team_info.team1_id;
                team2_id = match.team_info.team2_id;
            } catch (e) {
            }
            
            $("#team1_id").html(team_html).val(team1_id);
            $("#team2_id").html(team_html).val(team2_id);
        }
    );
}
setEventTeams($("#event_id").val());
$("#event_id").change(function() {
    setEventTeams($(this).val());
});

$("#team_info").parent().children("span").hide().find("input,select").attr("required", false);
$("#" + $("#type").val() + "_info").show().not("#various_info").find("input,select").attr("required", true);
$("#type").change(function() {
    switch ($(this).val()) {
        case 'team':
        case 'player':
        case 'various':
            var element_id = "#" + $(this).val() + "_info";
            $(element_id).show().not("#various_info").find("input,select").attr("required", true);
            $(element_id).siblings("span").hide().find("input,select").attr("required", false);
            break;
        default:
            $("#team_info").hide().siblings("span").hide();
            break;
    }
});

$(".goback").click(function() {
    var referer = "<?=(!@empty($referer)? $referer : site_url('match/index')) ?>";
    if (referer) {
        window.location.href = referer;
    }
    return true;
});

/////////////////////////////////////////////////////////////
/// forecast

$(".update-forecast").click(function() {
    var id_element = $(this).siblings(".forecast_id");
    var title_element = $(this).parent().siblings().children(".forecast_title");
    var type_element = $(this).parent().siblings().children(".forecast_type");
    var data_element = $(this).parent().siblings().children(".forecast_data");
    
    if (!id_element.val()) {
        return false;
    }
    if (title_element.val() == '') {
        swal('请填写标题!','','error');
        return false;
    }
    if (type_element.val() == '') {
        swal('请选择类型!','','error');
        return false;
    }
    if (type_element.val() == 'news' && !(parseInt(data_element.val()) > 0)) {
        data_element.val("");
        swal('请填写正确的新闻ID!','','error');
        return false;
    }
    if (type_element.val() == 'html' && data_element.val() == '') {
        data_element.val("");
        swal('请填写URL地址!','','error');
        return false;
    }
    
    $.post(
        "<?=site_url('match/editforecast')?>",
        {
            id: id_element.val(),
            title: title_element.val(),
            type: type_element.val(),
            data: data_element.val()
        },
        function (data) {
            if (data > 0) {
                swal({title : "更新成功！",type : "success"}, function() {
                    window.location.replace(window.location.href.replace(window.location.hash, "") + "#forecast-box");
                    window.location.reload();
                });
            } else {
                swal('更新失败!','请重试！','error');
            }
        }
    );
});

var alertConfig = {
    title: "确定要删除这条看点吗?",
    text: "删除后不可恢复，请谨慎操作!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dd4b39",
    confirmButtonText: "确定删除",
    cancelButtonText: "取消",
    closeOnConfirm: false
};

$(".delete-forecast").click(function() {
    var this_element = $(this);
    var id_element = $(this).siblings(".forecast_id");
    var title_element = $(this).parent().siblings().children(".forecast_title");
    var type_element = $(this).parent().siblings().children(".forecast_type");
    var data_element = $(this).parent().siblings().children(".forecast_data");
    
    if (!id_element.val()) {
        return false;
    }
    swal(alertConfig,function(){
        $.post(
            "<?=site_url('match/deleteforecast')?>",
            {
                id: id_element.val()
            },
            function (data) {
                if (data > 0) {
                    id_element.val("");
                    title_element.val("");
                    type_element.val("");
                    data_element.val("");
                    this_element.parent().siblings("div.hidden").removeClass("hidden");
                    this_element.parent().addClass("hidden");
                    swal({title : "删除成功！",type : "success"}, function() {
                        window.location.replace(window.location.href.replace(window.location.hash, "") + "#forecast-box");
                        window.location.reload();
                    });
                } else {
                    swal('删除失败！','','error');
                }
            }
        );
    });
});

$(".add-forecast").click(function() {
    var this_element = $(this);
    var id_element = $(this).parent().siblings().children(".forecast_id");
    var title_element = $(this).parent().siblings().children(".forecast_title");
    var type_element = $(this).parent().siblings().children(".forecast_type");
    var data_element = $(this).parent().siblings().children(".forecast_data");
    
    var match_id = parseInt($("#match_id").val());
    
    if (!(match_id > 0)) {
        swal('请先添加比赛，然后再操作！','','error');
        return false;
    }
    if (title_element.val() == '') {
        swal('请填写标题！','','error');
        return false;
    }
    if (type_element.val() == '') {
        swal('请选择类型！','','error');
        return false;
    }
    if (type_element.val() == 'news' && !(parseInt(data_element.val()) > 0)) {
        data_element.val("");
        swal('请填写正确的新闻ID！','','error');
        return false;
    }
    if (type_element.val() == 'html' && data_element.val() == '') {
        data_element.val("");
        swal('请填写URL地址！','','error');
        return false;
    }
    
    $.post(
        "<?=site_url('match/addforecast')?>",
        {
            match_id: match_id,
            title: title_element.val(),
            type: type_element.val(),
            data: data_element.val()
        },
        function (data) {
            if (data > 0) {
                id_element.val(data);
                this_element.parent().siblings("div.hidden").removeClass("hidden");
                this_element.parent().addClass("hidden");
                swal({title : "添加成功！",type : "success"}, function() {
                    window.location.replace(window.location.href.replace(window.location.hash, "") + "#forecast-box");
                    window.location.reload();
                });
            } else {
                swal('添加失败！','','error');
            }
        }
    );
});

$(".sort-forecast").click(function() {
    var id = $(this).siblings(".forecast_id").val();
    var match_id = parseInt($("#match_id").val());
    
    var opt = $(this).children("i").data("opt");
    var sort = $(this).data("sort");
    if (opt == "up") sort -= 1;
    if (opt == "down") sort += 1;
    
    $.post(
        "<?=site_url("/match/setForecastSort")?>",
        {id: id, match_id: match_id, sort: sort},
        function (data) {
            if (data > 0) {
                window.location.replace(window.location.href.replace(window.location.hash, "") + "#forecast-box");
                window.location.reload();
            } else {
                swal('修改失败！','','error');
            }
        }
    );
});

/////////////////////////////////////////////////////////////
/// live

$(".update-live").click(function() {
    var row_num = $(this).parent().siblings(".row_num").val();
    
    var row_element = $(this).closest("div.row-" + row_num);
    var id_element = row_element.find(".live_id");
    var vr_element = row_element.find(".live_vr");
    var site_element = row_element.find(".live_site");
    // var play_url_element = row_element.find(".live_play_url");
    var play_code_element = row_element.find(".live_play_code");
    var play_code2_element = row_element.find(".live_play_code2");
    var stream_tm_element = row_element.find(".live_stream_tm");
    
    var match_id = parseInt($("#match_id").val());
    
    if (!id_element.val()) {
        return false;
    }
    if (!(match_id > 0)) {
        swal('请先添加比赛，然后再操作！','','error');
        return false;
    }
    if (site_element.val() == '') {
        swal('请选择直播源！','','error');
        return false;
    }
    if (play_code2_element.val() == '' && play_code_element.val() == '') {
        swal('云直播码和直播代码请至少填写一项！','','error');
        return false;
    }
    
    $.post(
        "<?=site_url('match/editlive')?>",
        {
            id: id_element.val(),
            isvr: vr_element.is(":checked")? 1 : 0,
            site: site_element.val(),
            // play_url: play_url_element.val(),
            play_code: play_code_element.val(),
            play_code2: play_code2_element.val(),
            stream_tm: stream_tm_element.val()
        },
        function (data) {
            if (data > 0) {
                swal({title : "修改成功！",type : "success"}, function() {
                    window.location.replace(window.location.href.replace(window.location.hash, "") + "#live-box");
                    window.location.reload();
                });
            } else {
                swal('修改失败！','','error');
            }
        }
    );
});

$(".delete-live").click(function() {
    
    var row_num = $(this).parent().siblings(".row_num").val();
    
    var this_element = $(this);
    var row_element = $(this).closest("div.row-" + row_num);
    var id_element = row_element.find(".live_id");
    var vr_element = row_element.find(".live_vr");
    var site_element = row_element.find(".live_site");
    var play_url_element = row_element.find(".live_play_url");
    var play_code_element = row_element.find(".live_play_code");
    var stream_tm_element = row_element.find(".live_stream_tm");
    
    if (!id_element.val()) {
        return false;
    }

    alertConfig.title = '确定要删除吗？';
    
    swal(alertConfig,function(){
        $.post(
            "<?=site_url('match/deletelive')?>",
            {
                id: id_element.val()
            },
            function (data) {
                if (data > 0) {
                    id_element.val("");
                    vr_element.attr("checked", false);
                    site_element.val("");
                    play_url_element.val("");
                    play_code_element.val("");
                    this_element.parent().siblings("div.hidden").removeClass("hidden");
                    this_element.parent().addClass("hidden");
                    swal({title : "删除成功!",type : "success"}, function() {
                        window.location.replace(window.location.href.replace(window.location.hash, "") + "#live-box");
                        window.location.reload();
                    });
                } else {
                    swal('删除失败！','','error');
                }
            }
        );
    });
    
});

$(".add-live").click(function() {
    var row_num = $(this).parent().siblings(".row_num").val();
    
    var this_element = $(this);
    var row_element = $(this).closest("div.row-" + row_num);
    var id_element = row_element.find(".live_id");
    var vr_element = row_element.find(".live_vr");
    var site_element = row_element.find(".live_site");
    // var play_url_element = row_element.find(".live_play_url");
    var play_code_element = row_element.find(".live_play_code");
    var play_code2_element = row_element.find(".live_play_code2");
    var stream_tm_element = row_element.find(".live_stream_tm");
    
    var match_id = parseInt($("#match_id").val());
    
    if (!(match_id > 0)) {
        swal('请先添加比赛，然后再操作！','','error');
        return false;
    }
    if (site_element.val() == '') {
        swal('请选择直播源！','','error');
        return false;
    }
    if (stream_tm_element.val() == '') {
        swal('请选择切流时间！','','error');
        return false;
    }
    if (play_code2_element.val() == '' && play_code_element.val() == '') {
        swal('云直播码和直播代码请至少填写一项！','','error');
        return false;
    }
    
    $.post(
        "<?=site_url('match/addlive')?>",
        {
            match_id: match_id,
            isvr: vr_element.is(":checked")? 1 : 0,
            site: site_element.val(),
            // play_url: play_url_element.val(),
            play_code: play_code_element.val(),
            play_code2: play_code2_element.val(),
            stream_tm: stream_tm_element.val()
        },
        function (data) {
            if (data > 0) {
                id_element.val(data);
                this_element.parent().siblings("div.hidden").removeClass("hidden");
                this_element.parent().addClass("hidden");
                swal({title : "添加成功！",type : "success"}, function() {
                    window.location.replace(window.location.href.replace(window.location.hash, "") + "#live-box");
                    window.location.reload();
                });
            } else {
                swal('添加失败！','','error');
            }
        }
    );
    
});

</script>
</body>
</html>
