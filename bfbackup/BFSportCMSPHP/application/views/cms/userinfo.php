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
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap-switch.min.css">
    <!-- jQuery UI -->
    <link rel="stylesheet" href="/static/plugins/jQueryUI/jquery-ui.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="/static/plugins/datatables/dataTables.bootstrap.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/static/bootstrap/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/static/bootstrap/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/static/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/static/dist/css/skins/_all-skins.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .table tbody tr td{vertical-align: middle;}
        .pics{height: 500px; overflow-y: auto; overflow-x: hidden; list-style: none; margin: 0; padding: 0;}
        .pics li{float: left; width: 270px; margin: 5px; padding: 0;}
        .pics li img{width: 100%;}
        .power-list{padding: 0; margin: 0; list-style: none;}
        .power-list-edit{padding: 0 0 0 24px; margin: 0; list-style: none;}
        .checkbox{
            font-weight: normal;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini" style="background: #ecf0f5;">
    <div class="wrapper">
        <div class="content-wrapper" style="margin-left: 0;">
            <section class="content-header">
                <h1><i class="fa fa-user"></i> 个人信息修改</h1>
              <ol class="breadcrumb">
                <li><a href="/main/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">个人资料修改</li>
              </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-info">
                            <div class="box-header with-border">
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-horizontal">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">昵称:</label>
                                                <p class="col-sm-8 form-control-static"><?=!empty($user['nickname']) ? $user['nickname'] : '未设置'?></p>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">常用邮箱:</label>
                                                <p class="col-sm-8 form-control-static"><?=!empty($user['email']) ? $user['email'] : '未设置'?></p>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">手机号码:</label>
                                                <p class="col-sm-8 form-control-static"><?=!empty($user['mobile']) ? $user['mobile'] : '未设置'?></p>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">QQ号:</label>
                                                <p class="col-sm-8 form-control-static"><?=!empty($user['qq']) ? $user['qq'] : '未设置'?></p>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-offset-4 col-md-8">
                                                    <button id="update-btn" class="btn btn-success btn-flat" type="button"><i class="fa fa-edit"></i> 修 改</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <form method="post" class="form-horizontal hide" id="update-form">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">昵称:</label>
                                                <div class="col-md-4">
                                                    <input class="form-control" value="<?=$user['nickname']?>" name="nickname" placeholder="请输入昵称" type="text" />
                                                    <span class="help-block hide"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">常用邮箱:</label>
                                                <div class="col-md-4">
                                                    <input class="form-control" value="<?=$user['email']?>" name="email" placeholder="请输入常用邮箱" type="text" />
                                                    <span class="help-block hide"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">手机号码:</label>
                                                <div class="col-md-4">
                                                    <input class="form-control" value="<?=$user['mobile']?>" name="mobile" placeholder="请输入手机号码" type="text" />
                                                    <span class="help-block hide"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">QQ号码:</label>
                                                <div class="col-md-4">
                                                    <input class="form-control" value="<?=$user['qq']?>" name="qq" placeholder="请输入QQ号码" type="text" />
                                                    <span class="help-block hide"></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-offset-4 col-md-4">
                                                    <button id="save-btn" class="btn btn-warning btn-flat" type="submit"><i class="fa fa-save"></i> 更 新</button>
                                                    <button id="cancel-btn" class="btn btn-default btn-flat" type="button"><i class="fa fa-close"></i> 取 消</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
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
<script>
$('#update-btn').on('click',function(){
    $('#update-form').removeClass('hide');
});
$('#cancel-btn').on('click',function(){
    $('#update-form').addClass('hide');
});
function validation(obj){
    var fgroup = obj.parents('.form-group');
    var val = obj.val();
    var emailReg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var telReg = /^((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)$/;
    var qqReg = /[1-9][0-9]{4,}/;
    if(obj.val() == ''){
        fgroup.addClass('has-error');
    }else{
        fgroup.removeClass('has-error');
        obj.next().addClass('hide');
        if(obj.attr('name') == 'email' && !emailReg.test(val)){
            fgroup.addClass('has-error');
            obj.next().text('请输入正确的邮箱地址').removeClass('hide');
        }
        if(obj.attr('name') == 'mobile' && !telReg.test(val)){
            fgroup.addClass('has-error');
            obj.next().text('请输入正确的手机号码').removeClass('hide');
        }
        if(obj.attr('name') == 'qq' && !qqReg.test(val)){
            fgroup.addClass('has-error');
            obj.next().text('请输入正确的QQ号码').removeClass('hide');
        }
    }
}
$('#update-form').on('submit',function(){
    $('input[type="text"]',this).each(function(){
        validation($(this));
    });
    var errorCount = $('.has-error').size();
    if(errorCount > 0) return false;
});
$('input[type="text"]').on('blur',function(){
    validation($(this));
});
</script>
</body>
</html>
