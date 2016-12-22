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
    <link rel="stylesheet" href="/static/dist/css/bootstrap-editable.css">
	<link rel="stylesheet" href="/static/fileupload/css/fileupload.css">
	<link rel="stylesheet" href="/static/fileupload/css/fileupload-ui.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
        .nav-tabs-custom>.nav-tabs>li.active{
            border-top-color: #605ca8;
        }
        .input-group.upload-btn{
            display: inline-block;
            margin: 10px 0;
            display: none;
        }
    </style>
</head>
<body class="hold-transition skin-purple sidebar-mini" style="background: #ecf0f5;">
    <div class="wrapper">
        <div class="content-wrapper" style="margin-left: 0;">
            <section class="content-header">
              <h1>Dashboard <small>Control panel</small></h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-3">
                        <div class="box box-purple">
                            <div class="box-body box-profile">
                                <div class="avatar-box" style="text-align: center;">
                                    <?php if(empty($user['avatar'])): ?>
                                    <img class="profile-user-img img-responsive img-circle" src="/static/dist/img/avatar_default.png" alt="">
                                    <?php else: ?>
                                    <img class="profile-user-img img-responsive img-circle" src="http://image.sports.baofeng.com/<?=$user['avatar']?>" alt="">
                                    <?php endif; ?>

                                    <div class="input-group upload-btn" id="upload-btn">
                                        <span class="btn btn-warning fileinput-button">
                                            <i class="glyphicon glyphicon-plus"></i>
                                            <span>修改头像</span>
                                            <input id="fileupload" type="file" name="image" multiple data-url="http://w.image.sports.baofeng.com/save?token=xVFpX0RU" />
                                        </span>
                                    </div>
                                </div>
                                <h3 class="profile-username text-center"><a href="javascript:void(0)" class="item-text-edit" data-type="text" data-pk="<?=$user['id']?>" data-name="nickname" data-title="修改昵称"><?=$user['nickname']?></a></h3>
                                <p class="text-muted text-center"></p>

                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item">
                                        <b>用户名</b> <span class="pull-right"><?=$user['username']?></span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>性别</b> <a href="javascript:void(0)" class="item-select-edit pull-right" data-value="<?=$user['gender']?>" data-type="select" data-pk="<?=$user['id']?>" data-name="gender" data-title="修改性别"><?=$user['gender'] == 1 ? '男' : '女'?></a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>电话</b> <a href="javascript:void(0)" class="item-text-edit pull-right" data-type="text" data-pk="<?=$user['id']?>" data-name="mobile" data-title="修改电话"><?=$user['mobile']?></a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>邮箱</b> <a href="javascript:void(0)" class="item-text-edit pull-right" data-type="text" data-pk="<?=$user['id']?>" data-name="email" data-title="修改邮箱地址"><?=$user['email']?></a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>QQ</b> <a href="javascript:void(0)" class="item-text-edit pull-right" data-type="text" data-pk="<?=$user['id']?>" data-name="qq" data-title="修改QQ号码"><?=$user['qq']?></a>
                                    </li>
                                </ul>

                                <a href="#" id="update-info" class="btn bg-purple btn-block"><b>修改个人信息</b></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="javascript:void(0)" data-toggle="tab" aria-expanded="true">操作日志</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="timeline">
                                    <ul class="timeline timeline-inverse">
                                    
                                        <?php foreach ($log as $item):?>
                                        <?php if (empty($last_item_date) || $last_item_date != substr($item['created_at'], 0, 10)):?>
                                        <li class="time-label">
                                            <span class="bg-red"><?php echo substr($item['created_at'], 0, 10)?></span>
                                        </li>
                                        <?php endif;?>
                                        <li>
                                            <i class="<?=$item['iclass']?>"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="fa fa-clock-o"></i> <?php echo nice_date($item['created_at'],'H:i:s');?></span>
                                                <ol class="timeline-header breadcrumb">
                                                    <strong><?php echo $user['nickname'];?></strong> <?php echo $item['desc'];?>
                                                </ol>
                                                <?php if (!empty($item['data'])):?>
                                                <div class="timeline-body">
                                                    <div class="media">
                                                        <div class="media-left">
                                                            <?php if (isset($item['data']['image_url']) && !empty($item['data']['image_url'])):?>
                                                            <img class="media-object" src="<?=$item['data']['image_url']?>" width="150" alt="...">
                                                            <?php endif;?>
                                                            <!--
                                                            <img class="media-object" src="http://image.sports.baofeng.com/21ee30e857cccdd7ca66e2b95f62f6d4" width="150" alt="...">
                                                            -->
                                                        </div>
                                                        <div class="media-body">
                                                            <h4 class="media-heading">
                                                            <?php if (isset($item['data']['id']) && !empty($item['data']['id'])):?>
                                                            ID:<?=$item['data']['id']?>
                                                            <?php endif;?>
                                                            
                                                            <?php if (isset($item['data']['title']) && !empty($item['data']['title'])):?>
                                                            <?=$item['data']['title']?>
                                                            <?php endif;?>
                                                            </h4>
                                                            <?php if (isset($item['data']['brief']) && !empty($item['data']['brief'])):?>
                                                            <?=$item['data']['brief']?>
                                                            <?php endif;?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endif;?>
                                            </div>
                                        </li>
                                        <?php $last_item_date = substr($item['created_at'], 0, 10);?>
                                        <?php endforeach;?>
                                        
                                        
                                        
                                        
                                        
                                        <!--
                                        <li class="time-label">
                                            <span class="bg-red">2016-08-23</span>
                                        </li>
                                        <li>
                                            <i class="fa fa-newspaper-o bg-green"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>
                                                <ol class="timeline-header breadcrumb">
                                                    <li><a href="#">奥运新闻</a></li>
                                                    <li class="active"><strong class="text-green">添加</strong>新闻</li>
                                                </ol>
                                                <div class="timeline-body">
                                                    <div class="media">
                                                        <div class="media-left">
                                                            <img class="media-object" src="http://image.sports.baofeng.com/21ee30e857cccdd7ca66e2b95f62f6d4" width="150" alt="...">
                                                        </div>
                                                        <div class="media-body">
                                                            <h4 class="media-heading">传奇落幕！杜丽宣布退役 4战奥运斩2金1银1铜</h4>
                                                            “我想我以后可能会放弃射击训练了。”里约当地时间8月11日，在奥运会女子50米步枪三姿比赛结束之后，获得一枚获铜的杜丽用一句平静地话告诉所有人：“将为自己的射击生涯画上一个句号。”4届奥运会过后，杜丽完美谢幕。
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="timeline-footer text-right">
                                                    <a class="btn btn-success btn-xs" src="#" role="button">修改</a>
                                                    <a class="btn btn-danger btn-xs" src="#" role="button">删除</a>
                                                </div>
                                            </div>
                                        </li>

                                        <li>
                                            <i class="fa fa-check bg-blue"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>
                                                <ol class="timeline-header breadcrumb">
                                                    <li><a href="#">奥运新闻</a></li>
                                                    <li class="active"><strong class="text-info">更新</strong>新闻</li>
                                                </ol>
                                                <div class="timeline-body">
                                                    <div class="media">
                                                        <div class="media-left">
                                                            <img class="media-object" src="http://w.image.sports.baofeng.com/ba766134fdde0460f43e21f6d51b8445" width="150" alt="...">
                                                        </div>
                                                        <div class="media-body">
                                                            <h4 class="media-heading">8月13日里约奥运会看点 竞走蹦床有望添两金</h4>
                                                            四年之前的伦敦奥运会，陈定在男子20公里竞走比赛中一鸣惊人夺得冠军，这也是中国田径选手在伦敦得到的唯一一块金牌。本届里约奥运会，由卫冕冠军陈定、去年世锦赛亚军王镇和上届奥运会第四名蔡泽林组成的中国队依然是该项目金牌最有利的争夺者。
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="timeline-footer text-right">
                                                    <a class="btn btn-success btn-xs" src="#" role="button">修改</a>
                                                    <a class="btn btn-danger btn-xs" src="#" role="button">删除</a>
                                                </div>
                                            </div>
                                        </li>

                                        <li>
                                            <i class="fa fa-remove bg-red"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="fa fa-clock-o"></i> 11:43</span>
                                                <ol class="timeline-header breadcrumb no-border">
                                                    <li class="active"><strong class="text-danger">删除</strong>新闻</li>
                                                    <li class="active">伊布头球绝杀，曼联2-1莱斯特城夺得社区盾杯</li>
                                                </ol>
                                            </div>
                                        </li>

                                        <li>
                                            <i class="fa fa-user bg-aqua"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="fa fa-clock-o"></i> 11:23</span>
                                                <h3 class="timeline-header no-border"><strong class="text-info">小明</strong> 登录系统</h3>
                                            </div>
                                        </li>

                                        <li class="time-label">
                                            <span class="bg-red">2016-08-22</span>
                                        </li>

                                        <li>
                                            <i class="fa fa-newspaper-o bg-green"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>
                                                <ol class="timeline-header breadcrumb">
                                                    <li><a href="#">奥运视频</a></li>
                                                    <li class="active"><strong class="text-green">添加</strong>视频</li>
                                                </ol>
                                                <div class="timeline-body">
                                                    <div class="media">
                                                        <div class="media-left">
                                                            <img class="media-object" src="http://image.sports.baofeng.com/21ee30e857cccdd7ca66e2b95f62f6d4" width="150" alt="...">
                                                        </div>
                                                        <div class="media-body">
                                                            <h4 class="media-heading">传奇落幕！杜丽宣布退役 4战奥运斩2金1银1铜</h4>
                                                            “我想我以后可能会放弃射击训练了。”里约当地时间8月11日，在奥运会女子50米步枪三姿比赛结束之后，获得一枚获铜的杜丽用一句平静地话告诉所有人：“将为自己的射击生涯画上一个句号。”4届奥运会过后，杜丽完美谢幕。
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="timeline-footer text-right">
                                                    <a class="btn btn-success btn-xs" src="#" role="button">修改</a>
                                                    <a class="btn btn-danger btn-xs" src="#" role="button">删除</a>
                                                </div>
                                            </div>
                                        </li>

                                        <li>
                                            <i class="fa fa-check bg-blue"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>
                                                <ol class="timeline-header breadcrumb">
                                                    <li><a href="#">奥运新闻</a></li>
                                                    <li class="active"><strong class="text-info">更新</strong>新闻</li>
                                                </ol>
                                                <div class="timeline-body">
                                                    <div class="media">
                                                        <div class="media-left">
                                                            <img class="media-object" src="http://w.image.sports.baofeng.com/ba766134fdde0460f43e21f6d51b8445" width="150" alt="...">
                                                        </div>
                                                        <div class="media-body">
                                                            <h4 class="media-heading">8月13日里约奥运会看点 竞走蹦床有望添两金</h4>
                                                            四年之前的伦敦奥运会，陈定在男子20公里竞走比赛中一鸣惊人夺得冠军，这也是中国田径选手在伦敦得到的唯一一块金牌。本届里约奥运会，由卫冕冠军陈定、去年世锦赛亚军王镇和上届奥运会第四名蔡泽林组成的中国队依然是该项目金牌最有利的争夺者。
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="timeline-footer text-right">
                                                    <a class="btn btn-success btn-xs" src="#" role="button">修改</a>
                                                    <a class="btn btn-danger btn-xs" src="#" role="button">删除</a>
                                                </div>
                                            </div>
                                        </li>

                                        <li>
                                            <i class="fa fa-remove bg-red"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="fa fa-clock-o"></i> 11:43</span>
                                                <ol class="timeline-header breadcrumb no-border">
                                                    <li class="active"><strong class="text-danger">删除</strong>新闻</li>
                                                    <li class="active">伊布头球绝杀，曼联2-1莱斯特城夺得社区盾杯</li>
                                                </ol>
                                            </div>
                                        </li>

                                        <li>
                                            <i class="fa fa-user bg-aqua"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="fa fa-clock-o"></i> 11:23</span>
                                                <h3 class="timeline-header no-border"><strong class="text-info">小明</strong> 登录系统</h3>
                                            </div>
                                        </li>

                                    -->
                                    </ul>
                                </div>
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
<script src="/static/dist/js/bootstrap-editable.min.js"></script>
<script>
$.fn.editable.defaults.disabled = true;
$.fn.editable.defaults.url = '<?=base_url("/cms/user/updateUserInfo")?>';
$('.item-text-edit').editable();
$('.item-select-edit').editable({
	source: [
		{value: 1, text: '男'},
		{value: 0, text: '女'}
    ]
});

var userId = <?=$user['id']?>;

$('#update-info').on('click',function(){
    var isDisabled = $('.editable-disabled').length > 0;
    $('.box-profile .editable').editable('toggleDisabled');
    if(isDisabled){
        $('#upload-btn').show();
        $(this).text('确定').removeClass('bg-purple').addClass('btn-info');
    }else{
        $('#upload-btn').hide();
        $(this).text('修改个人信息').removeClass('btn-info').addClass('bg-purple');;
    }
});

//--file upload----------------------------------------------------
$('#fileupload').fileupload({
    add: function (e, data) {
        //data.context = $('<p/>').text('Uploading...').appendTo('#content');
        data.submit();
    },
    done: function (e, data) {
        //$('#cover').val(data);
        console.log(data.result)
        var result = data.result.errno

        if(result !== 10000){
            alert('上传失败,请重试！')
        }else{
            $.post('<?=base_url("/cms/user/updateUserInfo")?>',{pk : userId, name : 'avatar',value : data.result.data.pid},function(d){
                $(".profile-user-img").attr('src','http://image.sports.baofeng.com/' + data.result.data.pid);
            });
        }
    }
});
</script>
</body>
</html>
