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
    .avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    th, td {
        vertical-align: middle !important;
        text-align: center;
        word-warp: break-word;
        word-break: break-all;
    }

    .date-wrap {
        width: 930px;
        margin: 20px auto;
        overflow: hidden;
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

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">本日赛事</h3>
                </div>
                <div class="box-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th style="min-width: 86px;">开始时间</th>
                            <th style="min-width: 86px;">比赛id</th>
                            <th style="min-width: 86px;">比赛模式</th>
                            <th style="min-width: 86px;">比赛标题</th>
                            <th style="min-width: 86px;">比赛描述</th>
                            <th style="min-width: 86px;">赛事</th>
                            <th style="min-width: 120px;">对阵双方 / 比分</th>
                            <th style="min-width: 86px;">比赛状态</th>
                            <th style="min-width: 86px;">看点</th>
                            <th style="min-width: 86px;">直播地址</th>
                            <th style="min-width: 66px;">上线</th>
                            <?php if ('interaction_live' == $controller) {
                                echo '<th style="min-width: 86px;">主持人</th>';
                            } ?>
                            <th style="min-width: 86px;"><?php if ('interaction_live' == $controller) {
                                    echo '互动';
                                } else {
                                    echo '操作';
                                } ?></th>
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

                                <td>
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
                                    <span class="<?= ($this->AM->canModify() ? 'match-status' : '') ?>"
                                          data-type="select" data-value="<?= $match['status'] ?>"
                                          data-pk="<?= $match['id'] ?>"
                                          data-name="status" data-url="<?= site_url('match/setField/match/status') ?>"
                                    >
                                        <?= $match_statuses[$match['status']] ?>
                                    </span>
                                </td>
                                <td>
                                    <!-- forecast modal start -->
                                    <a href="#" data-toggle="modal" data-target="#modal-forecast-<?= $match['id'] ?>">共
                                        <span
                                            class="forecasts_count"><?= ($match['forecast'] ? count($match['forecast']) : 0) ?></span>
                                        条</a>
                                    <div class="modal false" id="modal-forecast-<?= $match['id'] ?>" role="dialog"
                                         aria-hidden="true">
                                        <div class="modal-dialog" style="width:85%">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-hidden="true"><i class="fa fa-times"></i></button>
                                                    <h4 class="modal-title">看点
                                                        <small> - 比赛：<?= $match['id'] ?></small>
                                                    </h4>
                                                </div>
                                                <div class="modal-body">
                                                    <?php $forecasts = isset($match['forecast']) ? array_values($match['forecast']) : array(); ?>
                                                    <?php if (!$forecasts): ?>暂无看点<?php endif; ?>
                                                    <?php for ($i = 0; $i < count($forecasts); $i++): ?>
                                                        <div class="row">
                                                            <?php $f = isset($forecasts[$i]) ? $forecasts[$i] : array(); ?>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-1"
                                                                           style="text-align:right">看点<?= ($i + 1) ?>
                                                                        ：</label>
                                                                    <div class="col-md-2">
                                                                        <input class="form-control forecast_title"
                                                                               type="text" value="<?= @$f['title'] ?>"
                                                                               readonly>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <select class="form-control forecast_type"
                                                                                disabled>
                                                                            <option value="">选择类型</option>
                                                                            <?php foreach ($forecast_types as $ftype => $fname): ?>
                                                                                <option
                                                                                    value="<?= $ftype ?>" <?= ($ftype == @$f['type'] ? 'selected' : '') ?>><?= $fname ?></option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                    <label class="control-label col-md-1"
                                                                           style="text-align:right">ID/URL：</label>
                                                                    <div class="col-md-4">
                                                                        <input class="form-control forecast_data"
                                                                               value="<?= (@$f['type'] == 'news' ? $f['ref_id'] : (@$f['type'] == 'html' ? $f['data'] : '')) ?>"
                                                                               readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endfor; ?>
                                                </div>
                                                <div class="modal-footer">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- forecast modal end -->
                                </td>
                                <td>
                                    <!-- live modal start -->
                                    <a href="#" data-toggle="modal" data-target="#modal-live-<?= $match['id'] ?>">共
                                        <span
                                            class="lives_count"><?= ($match['live'] ? count($match['live']) : 0) ?></span>
                                        条</a>
                                    <div class="modal false" id="modal-live-<?= $match['id'] ?>" role="dialog"
                                         aria-hidden="true">
                                        <div class="modal-dialog" style="width:90%">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-hidden="true"><i class="fa fa-times"></i></button>
                                                    <h4 class="modal-title">直播流信息
                                                        <small> - 比赛：<?= $match['id'] ?></small>
                                                    </h4>
                                                </div>
                                                <div class="modal-body">
                                                    <?php $lives = isset($match['live']) ? array_values($match['live']) : array(); ?>
                                                    <?php if (!$lives): ?>暂无直播流<?php endif; ?>
                                                    <?php for ($i = 0; $i < count($lives); $i++): ?>
                                                        <div class="row-<?= $i ?>">
                                                            <div class="row">
                                                                <?php $live = isset($lives[$i]) ? $lives[$i] : array(); ?>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label col-md-1"
                                                                               style="text-align:right;">直播源：</label>
                                                                        <div class="col-md-3">
                                                                            <select class="form-control live_site"
                                                                                    disabled>
                                                                                <option value="">选择类型</option>
                                                                                <?php foreach ($live_sites as $site => $name): ?>
                                                                                    <option
                                                                                        value="<?= $site ?>" <?= ($site == @$live['site'] ? 'selected' : '') ?>><?= $name ?></option>
                                                                                <?php endforeach; ?>
                                                                            </select>
                                                                        </div>
                                                                        <label class="control-label col-md-2"
                                                                               for="stream_tm"
                                                                               style="text-align:right;">切流时间：</label>
                                                                        <div class="col-md-4 input-append date">
                                                                            <input class="form-control live_stream_tm"
                                                                                   value="<?= (@$live['stream_tm'] ?: '') ?>"
                                                                                   data-format="yyyy-MM-dd hh:mm:ss"
                                                                                   style="display: inline-block; width: 50%;"
                                                                                   type="text" readonly>
                                                                        </div>
                                                                        <label class="control-label col-md-1"
                                                                               style="text-align:center;">VR模式：</label>
                                                                        <div class="col-md-1">
                                                                            <input class="live_vr"
                                                                                   type="checkbox" <?= (@$live['isvr'] ? 'checked' : '') ?>
                                                                                   disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="control-label col-md-1"
                                                                               style="text-align:right;">直播地址：</label>
                                                                        <div class="col-md-3">
                                                                            <input class="form-control live_play_url"
                                                                                   value="<?= @$live['play_url'] ?>"
                                                                                   readonly>
                                                                        </div>
                                                                        <label class="control-label col-md-2"
                                                                               style="text-align:right;">直播代码：</label>
                                                                        <div class="col-md-3">
                                                                            <input class="form-control live_play_code"
                                                                                   value="<?= @$live['play_code'] ?>"
                                                                                   readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                    <?php endfor; ?>
                                                </div>
                                                <div class="modal-footer">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- live modal end -->
                                    <h6><?= $live_types[$match['live_type']] ?></h6>
                                </td>
                                <td>
                                    <input data-pk="<?= $match['id'] ?>" class="release match-visible" type="checkbox"
                                           <?php if ($match['visible']): ?>checked<?php endif; ?>
                                           <?php if ($controller != 'match' || !$this->AM->canModify()): ?>disabled<?php endif; ?>
                                    >
                                </td>

                                <?php if ('interaction_live' == $controller) { ?>
                                    <td>
                                        <select class="form-control js_zcr_<?= $match['id'] ?>" name="user_id"
                                                required min="1" data-bvalidator-msg="请选择一项!">
                                            <option value="135601920097128682"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920097128682') ?>">
                                                大赢家是托雷斯
                                            </option>
                                            <option value="135601920104775495"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920104775495') ?>">
                                                拼搏
                                            </option>
                                            <option value="135601920104775533"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920104775533') ?>">
                                                无忧无虑
                                            </option>
                                            <option value="135601920104776498"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920104776498') ?>">
                                                微笑
                                            </option>
                                            <option value="135601920104781348"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920104781348') ?>">
                                                晨曦的阳光
                                            </option>
                                            <option value="135601920104782856"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920104782856') ?>">
                                                依然
                                            </option>
                                            <!-- 10-31添加用户 -->
                                            <option value="135601920097940757"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920097940757') ?>">
                                                笛斯坦
                                            </option>
                                            <option value="135601920097940758"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920097940758') ?>">
                                                肥仔
                                            </option>
                                            <option value="135601920097940784"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920097940784') ?>">
                                                花漾
                                            </option>
                                            <option value="135601920097940787"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920097940787') ?>">
                                                来哥
                                            </option>
                                            <option value="135601920097940789"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920097940789') ?>">
                                                林攀
                                            </option>
                                            <option value="135601920097940792"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920097940792') ?>">
                                                若水
                                            </option>
                                            <!-- 2016/11/07 -->
                                            <option value="135601920097941030"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920097941030') ?>">
                                                高兴
                                            </option>
                                            <option value="135601920097940802"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920097940802') ?>">
                                                晶天
                                            </option>
                                            <option value="135601920097940804"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920097940804') ?>">
                                                老狗
                                            </option>
                                            <option value="135601920097940803"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920097940803') ?>">
                                                闹闹
                                            </option>
                                            <option value="135601920097940801"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920097940801') ?>">
                                                念情
                                            </option>
                                            <option value="135601920097941027"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920097941027') ?>">
                                                阮阮
                                            </option>
                                            <option value="135601920097940800"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920097940800') ?>">
                                                小床
                                            </option>
                                            <option value="135601920097940805"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920097940805') ?>">
                                                小辣鸡
                                            </option>
                                            <option value="135601920097941028"
                                                    data-href="<?= base_url('/interaction/live/main_live/index/' . $match['id'] . '/135601920097941028') ?>">
                                                熊熊
                                            </option>
                                            

                                        </select>
                                    </td>
                                <?php } ?>

                                <!-- 内容管理 操作 start -->
                                <?php if ($controller == 'match'): ?>
                                    <td>
                                        <?php $referer = urlencode(base_url($_SERVER['REQUEST_URI'])); ?>
                                        <!-- 编辑 -->
                                        <?php if ($this->AM->canModify()): ?>
                                            <a role="button" class="btn btn-info btn-xs"
                                               href="<?= site_url('match/edit') . '/' . $match['id'] . '?redirect=' . $referer ?>"
                                               ><i class="fa fa-pencil-square-o"></i> 编辑</a>
                                        <?php endif; ?>
                                        <!-- 删除 -->
                                        <!-- 不需要删除功能，屏蔽！ 2016-09-28
                                    <?php if ($this->AM->canModify()): ?>
                                        <a role="button" class="btn btn-danger btn-xs delete-match" href="<?= site_url('match/delete') . '/' . $match['id'] . '?redirect=' . $referer ?>"><i class="fa fa-times"></i> 删除</a>
                                    <?php endif; ?>
                                    -->
                                    </td>
                                <?php endif; ?>
                                <!-- 内容管理操作 end -->

                                <!-- 频道热门直播 操作 start -->
                                <?php if ($controller == 'channel'): ?>
                                    <td>
                                        <a role="button"
                                           href="<?php echo site_url('channel/hotLiveRecommend/') . $channel_id . "?match_id=" . $match['id'] . '&use_date=' . $curr_date . '&use_event_id=' . $curr_event_id; ?>"
                                           class="btn btn-xs btn-flat btn-success"><i class="fa fa-share"></i> 推荐比赛</a>
                                    </td>
                                <?php endif; ?>
                                <!-- 频道热门直播 操作 end -->

                                <!-- 赛程 操作 start --><!-- schedule -->
                                <?php if ($controller == 'main'): ?>
                                    <td>
                                        <?php $referer = urlencode(base_url($_SERVER['REQUEST_URI'])); ?>
                                        <!-- 编辑 -->
                                        <?php if ($this->AM->canModify()): ?>
                                            <a role="button" class="btn btn-info btn-xs"
                                               href="<?= site_url('match/edit') . '/' . $match['id'] . '?redirect=' . $referer ?>"><i
                                                    class="fa fa-pencil-square-o"></i> 编辑</a>
                                        <?php endif; ?>

                                        <!-- 集锦回放 -->
                                        <a role="button" class="btn btn-success btn-xs"
                                           href="<?= site_url('schedule/schedule_video') . '/index/' . $match['id'] . '?redirect=' . $referer ?>"><i
                                                class="fa fa-fire"></i>集锦回放</a>
                                        <!-- 相关资讯 -->
                                        <a role="button" class="btn btn-primary btn-xs"
                                           href="<?= site_url('matchNews') . '/index/' . $match['id'] . '/top?redirect=' . $referer ?>"><i
                                                class="fa fa-link"></i>相关资讯</a>
                                    </td>
                                <?php endif; ?>
                                <!-- 赛程 操作 end -->

                                <!-- 频道热门直播 操作 start -->
                                <?php if ($controller == 'interaction_live'): ?>
                                    <td>
                                        <a role="button" href="javascript:void(0);"
                                           class="btn btn-xs btn-flat btn-success js_go_live"
                                           data-id="<?= $match['id'] ?>"><i class="fa fa-share"></i> 进入</a>
                                    </td>
                                <?php endif; ?>
                                <!-- 频道热门直播 操作 end -->

                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

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
    var match_statuses = <?=json_encode($match_statuses)?>;

    $(".match-status").editable({
        source: match_statuses
    });

    $(".match-visible").bootstrapSwitch({
        size: 'mini',
        onText: '上线',
        offText: '下线',
        onSwitchChange: function (event, state) {
            $.ajax({
                method: "POST",
                url: "<?=site_url("/match/setvisible") ?>",
                data: {name: "visible", value: parseInt(Number(state)), pk: $(this).data('pk')}
            });
        }
    });

    $(".delete-match").click(function () {
        if (confirm("删除后不可恢复，确认删除吗？")) {
            return true;
        }
        return false;
    });

    $('.js_go_live').click(function () {
        var id = parseInt($(this).data('id')),
            url = '';
        if (!id) {
            alert('数据有误,请刷新后再试');
            return false;
        }

        url = $('.js_zcr_' + id).find("option:selected").data('href');
        if (!url.length) {
            alert('数据有误,请刷新后再试');
            return false;
        }

        window.location.href = url;
    });
</script>
