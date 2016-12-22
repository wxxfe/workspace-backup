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
              <h1>频道热门直播<small>Channel </small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">频道热门直播</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <!-- 
                    <div class="col-md-2" style="display: none;">
                    </div>
                    -->
                    <div class="col-md-12" id="pending-video-list">
                        <div class="box">
                            <div class="box-header with-border">
                              <h3 class="box-title">已推荐</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>排序</th>
                                            <th>开始时间</th>
                                            <th>比赛模式</th>
                                            <th>比赛描述</th>
                                            <th>对阵双方</th>
                                            <th>比赛id</th>
                                            <th>看点</th>
                                            <th>直播地址</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($selected_match as $s_match):?>
                                        <tr>
                                            <td><?=$s_match['priority']?></td>
                                            <td><?=$s_match['start_tm']?></td>
                                            <td>
                                            <?php if ($s_match['type'] == 'team'):?>
                                            团队对阵式
                                            <?php elseif($s_match['type'] == 'player'):?>
                                            个人对阵式
                                            <?php elseif($s_match['type'] == 'various'):?>
                                            非对阵式
                                            <?php endif;?>
                                            </td>
                                            <td><?=$s_match['brief']?></td>
                                            <td>
                                            <?php if ($s_match['type'] == 'team'):?>
                                            <?=$s_match['team_info']['team1_info']['name']?> vs <?=$s_match['team_info']['team2_info']['name']?>
                                            <?php elseif ($s_match['type'] == 'player'):?>
                                            <?=$s_match['player_info']['player1_info']['name']?> vs <?=$s_match['player_info']['player2_info']['name']?>
                                            <?php elseif ($s_match['type'] == 'various'):?>
                                            <?php endif?>
                                            </th>
                                            <th><?=$s_match['id']?></th>
                                            <th>
                                                <?php if (isset($s_match['forecast']) && !empty($s_match['forecast'])):?>
                                                  <div class="btn-group">
                                                        <button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button" aria-expanded="true">
                                                          <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <?php foreach ($s_match['forecast'] as $forecast):?>
                                                            <li><a href="#"><?=$forecast['title']?></a></li>
                                                            <?php endforeach;?>
                                                        </ul>
                                                  </div>
                                                <?php endif;?>
                                            </th>
                                            <th>
                                                <?php if (isset($s_match['live']) && !empty($s_match['live'])):?>
                                                  <div class="btn-group">
                                                        <button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button" aria-expanded="true">
                                                          <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <?php foreach ($s_match['live'] as $live):?>
                                                            <li><a href="#">站点：<?=$live['site']?>;  切流时间：<?=$live['stream_tm']?></a></li>
                                                            <?php endforeach;?>
                                                        </ul>
                                                  </div>
                                                <?php endif;?>
                                            </th>
                                            <td>
                                                <button class="btn btn-xs btn-flat btn-danger btn-cancel" data-rid="<?=$s_match['sm_id']?>"><i class="fa fa-remove"></i> 取消推荐</button>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--  
                    <div class="col-md-2" id="role-view" style="display: none;">
                    </div>
                    -->
                </div>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12" id="channel_detail">
                        <div class="box" style="border: none;">
                            <div class="box-body">
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        <li <?php if ($use_event_id == 'all'):?>class="active"<?php endif;?>><a href="<?php echo site_url('channel/hotlive').'/'.$channel_id.'?use_event_id=all&use_date='.$use_date;?>">全部</a></li>
                                        <?php foreach ($event_list as $event):?>
                                        <li <?php if ($use_event_id == $event['id']):?>class="active"<?php endif;?>><a href="<?php echo site_url('channel/hotlive').'/'.$channel_id.'?use_event_id='.$event['id'].'&use_date='.$use_date;?>"><?=$event['name']?></a></li>
                                        <?php endforeach;?>
                                    </ul>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="date-wrap">
                                            <a href="<?php echo site_url('channel/hotlive').'/'.$channel_id.'?use_date='.$date_slide['week_prev_date'].'&use_event_id='.$use_event_id;?>" class="prev">&lt;</a>
                                            <div class="slide-wrap">
                                                <ul class="matchs">
                                                    <li>
                                                        <?php foreach($date_slide['week_data'] as $day_info):?>
                                                        <a href="<?php echo site_url('channel/hotlive').'/'.$channel_id.'?use_date='.$day_info['date'].'&use_event_id='.$use_event_id;?>" <?php if($day_info['date'] == $use_date):?>class="active"<?php endif;?>>
                                                            <?php echo $day_info['date']?><br />星期<?php echo $day_info['day']?><br />共 <strong class="text-info"><?php echo $day_info['count']?></strong> 场
                                                        </a>
                                                        <?php endforeach;?>
                                                    </li>
                                                </ul>
                                            </div>
                                            <a href="<?php echo site_url('channel/hotlive').'/'.$channel_id.'?use_date='.$date_slide['week_next_date'].'&use_event_id='.$use_event_id;?>" class="next">&gt;</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- 赛事信息 -->
            <?php $this->load->view('common/matchList', array('event_id' => $use_event_id, 'date' => $use_date, 'channel_id' => $channel_id)); ?>
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
<script>
var slider = new Slider({
    wrap : $('.slide-wrap'),
    prev : $('.prev'),
    next : $('.next')
});
$('.btn-pop').popover();
// --remove alert-------------------------------------------------------------
var alertConfig = {
    title: "你确定要取消吗?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dd4b39",
    confirmButtonText: "确定取消",
    cancelButtonText: "关闭",
    closeOnConfirm: false
};
$('.btn-cancel').on('click',function(){
    var rid = $(this).data('rid');
    var tr = $(this).parents('tr');
    var target = this;

    swal(alertConfig,function(){
        $.post('<?php echo site_url("/channel/hotLiveRecommendCancel").'/'.$channel_id?>',{id : rid},function(d){
            if(d == 'success'){
                swal({title : "取消成功!",text : "已成功取消比赛推荐！",type : "success"},function(){
                    tr.fadeOut('fast',function(){$(this).remove();});
                });
            }else{
                swal("取消失败!", "比赛取消推荐失败，请重试！", "error");
            }
        });
    });
});


//--editable------------------------------------------------------------
$.fn.editable.defaults.url = '<?php echo base_url("/match/setvisible")?>';
$('.item-select-edit-enable').editable({
    prepend: "请选择",
    source: [
        {value: 1, text: '启用'},
        {value: 0, text: '禁用'}
    ]
});
</script>
</body>
</html>
