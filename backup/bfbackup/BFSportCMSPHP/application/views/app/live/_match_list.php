<link rel="stylesheet" href="/static/dist/css/bootstrap-switch.min.css">
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

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
                            <th style="min-width: 50px;">比赛id</th>
                            <th style="min-width: 86px;">比赛模式</th>
                            <th style="min-width: 86px;">比赛标题</th>
                            <th style="min-width: 86px;">比赛描述</th>
                            <th style="min-width: 45px;">赛事</th>
                            <th style="min-width: 120px;">对阵双方 / 比分</th>
                            <th style="min-width: 86px;">比赛状态</th>
                            <th style="min-width: 45px;">看点</th>
                            <th style="min-width: 86px;">直播地址</th>
                            <th style="min-width: 60px;">上线</th>
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
                                    <span><?= $match_statuses[$match['status']] ?></span>
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
                                    <h6><?=isset($live_types[$match['live_type']])? $live_types[$match['live_type']] : '';?></h6>
                                </td>
                                <td>
                                    <input data-pk="<?= $match['id'] ?>" class="release match-visible" type="checkbox"
                                           <?php if ($match['visible']): ?>checked<?php endif; ?>
                                           disabled>
                                </td>
                                <td>
                                    <a role="button" href="javascript:void(0);"
                                       class="btn btn-xs btn-flat btn-success"
                                       data-id="<?= $match['id'] ?>"><i class="fa fa-share"></i>推荐比赛</a>
                                </td>
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

    $(".btn-success").click(function(){
        var match_id = $(this).data("id");
        $.post('<?php echo base_url("app/live/add");?>', {match_id:match_id}, function(data){
            if(data == 'success') {
                window.location.href = '<?php echo site_url("app/live?event_id=") . $curr_event_id . "&date=" . $curr_date;?>';
            } else if(data == 'already') {
                alert("您已经推荐过！");
            } else {
                alert("推荐失败！");
            }
        });
    });
</script>
