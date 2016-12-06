<!DOCTYPE html>
<html>
<?php
if ($date == '-') {
    $date = date('Y-m-d');
}

$CI = &get_instance();
$CI->load->model('Match_model', 'MM');
$CI->config->load('sports');

$curr_date = $date;
$curr_event_id = $event_id;

if (!isset($events) || empty($events)) {
    $CI->load->model('Event_model', 'EM');
    $events = $CI->EM->getEvents();
}

$date_slide = $CI->MM->getMatchCountByWeek($date, $event_id);
$match_list = $CI->MM->getMatch($event_id, $date);
$live_sites = $CI->MM->getSites();

$live_types = $CI->config->item('live_types');
$match_types = $CI->config->item('match_types');
$match_statuses = $CI->config->item('match_statuses');
$forecast_types = $CI->config->item('match_forecast_types');

// 来源控制器
$controller = strtolower(get_class($CI));
?>
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
    <link rel="stylesheet" href="/static/dist/css/bootstrap-switch.min.css">
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

        .prev, .next {
            display: block;
            width: 20px;
            height: 90px;
            border: #ccc 1px solid;
            float: left;
            text-align: center;
            line-height: 90px;
        }

        .prev {
            margin-right: 10px;
        }

        .slide-wrap {
            width: 880px;
            overflow: hidden;
            position: relative;
            height: 90px;
            float: left;
            margin-top: 5px;
        }

        .matchs {
            overflow: hidden;
            position: absolute;
            list-style: none;
            height: 90px;
            left: 0;
            top: 0;
            width: 1000px;
            margin: 0;
            padding: 0;
        }

        .matchs li {
            display: block;
            width: 880px;
            height: 92px;
            float: left;
        }

        .matchs li a {
            display: block;
            width: 100px;
            margin-right: 10px;
            float: left;
            border: #ccc 1px solid;
        }

        .matchs li a {
            text-align: center;
            padding-top: 8px;
            height: 80px;
        }

        .matchs li a.active {
            background: #3c8dbc;
            color: #fff;
        }
    </style>
</head>

<body class="hold-transition skin-red sidebar-mini" style="background: #ecf0f5;">
<div class="box" style="border: none;">
    <div class="box-header">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li <?php if ($curr_event_id == 0): ?>class="active"<?php endif; ?>><a
                        href="<?= site_url('match/matchSelect') . "/0/{$curr_date}" ?>">全部</a></li>
                <?php foreach ($events as $event): ?>
                    <li <?php if ($curr_event_id == $event['id']): ?>class="active"<?php endif; ?>><a
                            href="<?= site_url('match/matchSelect') . "/{$event['id']}/{$curr_date}" ?>"><?= $event['name'] ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="date-wrap">
                    <a href="<?= site_url('match/matchSelect') . "/{$curr_event_id}/{$date_slide['week_prev_date']}" ?>"
                       class="prev">&lt;</a>
                    <div class="slide-wrap">
                        <ul class="matchs">
                            <li>
                                <?php foreach ($date_slide['week_data'] as $day_info): ?>
                                    <a href="<?= site_url('match/matchSelect') . "/{$curr_event_id}/{$day_info['date']}" ?>"
                                       <?php if ($day_info['date'] == $curr_date): ?>class="active"<?php endif; ?>>
                                        <?php echo $day_info['date'] ?><br/>星期<?php echo $day_info['day'] ?><br/>共
                                        <strong class="text-info"><?php echo $day_info['count'] ?></strong> 场
                                    </a>
                                <?php endforeach; ?>
                            </li>
                        </ul>
                    </div>
                    <a href="<?= site_url('match/matchSelect') . "/{$curr_event_id}/{$date_slide['week_next_date']}" ?>"
                       class="next">&gt;</a>
                </div>
            </div>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-hover">
            <thead>
            <tr>
                <th style="min-width: 86px;">开始时间</th>
                <th style="min-width: 86px;">比赛id</th>
                <th style="width: 25%;">比赛模式</th>
                <th style="width: 25%;">比赛标题</th>
                <th style="width: 25%;">比赛描述</th>
                <th style="min-width: 86px;">赛事</th>
                <th style="width: 25%;">对阵双方 / 比分</th>
                <th style="min-width: 86px;">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($match_list as $match): ?>
                <?php
                $event = @$events[$match['event_id']];
                if (!$event) {
                    continue;
                }
                ?>
                <tr>
                    <td><?= $match['start_tm'] ?></td>
                    <td><?= $match['id'] ?></td>
                    <td><?= $match_types[$match['type']] ?></td>
                    <td><?= $match['title'] ?></td>
                    <td><?= $match['brief'] ?></td>
                    <td><?= $event['name'] ?></td>
                    <td style="text-align: center;">
                        <?php if ($match['type'] == 'team'): ?>
                            <?= $match['team_info']['team1_info']['name'] ?> vs <?= $match['team_info']['team2_info']['name'] ?>
                        <?php elseif ($match['type'] == 'player'): ?>
                            <?= $match['player_info']['player1_info']['name'] ?> vs <?= $match['player_info']['player2_info']['name'] ?>
                        <?php elseif ($match['type'] == 'various'): ?>
                            （无）
                        <?php endif ?>
                        <br>
                        <?php if ($match['type'] == 'team'): ?>
                            <?= $match['team_info']['team1_score'] ?> : <?= $match['team_info']['team2_score'] ?>
                        <?php elseif ($match['type'] == 'player'): ?>
                            <?= $match['player_info']['player1_score'] ?> : <?= $match['player_info']['player2_score'] ?>
                        <?php elseif ($match['type'] == 'various'): ?>
                            （无）
                        <?php endif ?>
                    </td>
                    <td>
                        <button data-mid="<?= $match['id'] ?>" class="match-select-btn btn btn-primary btn-xs">关联
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
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
<script src="/static/dist/js/bootstrap-switch.min.js"></script>


<script>
    $('.match-select-btn').click(function (e) {
        e.preventDefault();
        window.parent.postMessage($(this).data('mid'), '*');
    });
</script>
</body>
</html>